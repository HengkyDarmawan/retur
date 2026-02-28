<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends MY_Controller {

   public function index() {
        $data['title'] = 'My Profile';
        $data['user'] = $this->user; // Mengambil data user dari MY_Controller

        // Cek jika ada flashdata untuk SweetAlert2
        // Contoh: $this->session->set_flashdata('swal_icon', 'success');
        // Contoh: $this->session->set_flashdata('swal_title', 'Profil Berhasil Diubah!');

        $this->_render('user/index', $data);
    }
    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->user; // Data user dari MY_Controller

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('user/edit', $data);
        } else {
            $name = $this->input->post('name');
            $user_id = $data['user']['id']; 

            // 1. Cek Upload Gambar
            $upload_image = $_FILES['image']['name'];
            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = '2048';
                $config['upload_path']   = './assets/img/profile/';
                $config['file_name']     = 'pro' . time();

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $upload_data = $this->upload->data();
                    $file_path = $upload_data['full_path'];
                    
                    // Resize
                    $this->load->library('image_lib');
                    $conf_res['image_library'] = 'gd2';
                    $conf_res['source_image'] = $file_path;
                    $conf_res['maintain_ratio'] = TRUE;
                    $conf_res['width'] = 500;
                    $this->image_lib->initialize($conf_res);
                    $this->image_lib->resize();

                    // Convert WebP (Fungsi helper yang kita buat sebelumnya)
                    $new_webp = convert_to_webp($file_path);

                    // Hapus Foto Lama (Kecuali default)
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg' && file_exists(FCPATH . 'assets/img/profile/' . $old_image)) {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    $this->db->set('image', $new_webp);
                }
            }

            // 2. Eksekusi Update
            $this->db->set('name', $name);
            $this->db->where('id', $user_id);
            
            $old_data = $data['user'];
            $this->db->update('user');

            // 3. Log Aktivitas
            $new_data = $this->db->get_where('user', ['id' => $user_id])->row_array();
            create_log('UPDATE', 'user', $user_id, $old_data, $new_data);

            // SINKRONISASI DENGAN FOOTER ANDA (Gunakan 'type' dan 'message')
            $this->session->set_flashdata('type', 'success');
            $this->session->set_flashdata('message', 'Profil Anda berhasil diperbarui.');
            
            redirect('user');
        }
    }
    
    public function changepassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->user;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->_render('user/changepassword', $data);
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');

            // 1. Cek password lama
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('type', 'error');
                $this->session->set_flashdata('message', 'Password lama tidak sesuai!');
                redirect('user/changepassword');
            } else {
                // 2. Cek agar tidak sama dengan yang lama
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('type', 'warning');
                    $this->session->set_flashdata('message', 'Password baru tidak boleh sama dengan yang lama.');
                    redirect('user/changepassword');
                } else {
                    // 3. Update Password
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('id', $data['user']['id']);
                    $this->db->update('user');

                    // 4. Log Aktivitas (Sembunyikan password di log)
                    create_log('UPDATE', 'user', $data['user']['id'], ['password' => '[HIDDEN]'], ['password' => '[CHANGED]']);

                    $this->session->set_flashdata('type', 'success');
                    $this->session->set_flashdata('message', 'Password Anda telah berhasil diperbarui.');
                    redirect('user');
                }
            }
        }
    }
}