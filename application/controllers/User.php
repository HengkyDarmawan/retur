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
        $data['user'] = $this->user; // Pastikan ini mengambil data user yang sedang login

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('user/edit', $data);
        } else {
            $name = $this->input->post('name');
            // PENTING: Gunakan ID dari session, jangan ambil dari input post untuk urusan WHERE
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
                    
                    // RESIZE & WEBP (Sesuai optimasi sebelumnya)
                    $file_path = $upload_data['full_path'];
                    
                    // Resize dulu
                    $this->load->library('image_lib');
                    $conf_res['image_library'] = 'gd2';
                    $conf_res['source_image'] = $file_path;
                    $conf_res['maintain_ratio'] = TRUE;
                    $conf_res['width'] = 500;
                    $this->image_lib->initialize($conf_res);
                    $this->image_lib->resize();

                    // Convert WebP
                    $new_webp = convert_to_webp($file_path);

                    // Hapus Foto Lama (Kecuali default)
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg' && file_exists(FCPATH . 'assets/img/profile/' . $old_image)) {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }

                    // Set kolom image untuk diupdate
                    $this->db->set('image', $new_webp);
                }
            }

            // 2. Eksekusi Update dengan WHERE yang SANGAT SPESIFIK
            $this->db->set('name', $name);
            $this->db->where('id', $user_id); // Menggunakan ID Unik, bukan username/inputan
            
            // Simpan data lama untuk Log
            $old_data = $data['user'];
            
            $this->db->update('user');

            // 3. Catat Log Aktivitas
            $new_data = $this->db->get_where('user', ['id' => $user_id])->row_array();
            create_log('UPDATE', 'user', $user_id, $old_data, $new_data);

            // SweetAlert2
            $this->session->set_flashdata('swal_icon', 'success');
            $this->session->set_flashdata('swal_title', 'Berhasil!');
            $this->session->set_flashdata('swal_text', 'Profil Anda saja yang diperbarui.');
            
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

            // 1. Cek apakah password saat ini benar
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('swal_icon', 'error');
                $this->session->set_flashdata('swal_title', 'Password Salah!');
                $this->session->set_flashdata('swal_text', 'Password saat ini tidak sesuai.');
                redirect('user/changepassword');
            } else {
                // 2. Cek agar password baru tidak sama dengan password lama
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('swal_icon', 'warning');
                    $this->session->set_flashdata('swal_title', 'Opps!');
                    $this->session->set_flashdata('swal_text', 'Password baru tidak boleh sama dengan password lama.');
                    redirect('user/changepassword');
                } else {
                    // 3. Proses Update
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    // Catat data sebelum diubah untuk log
                    $old_data = $data['user'];

                    $this->db->set('password', $password_hash);
                    $this->db->where('id', $data['user']['id']);
                    $this->db->update('user');

                    // 4. Activity Log (Sangat penting untuk audit keamanan)
                    $new_data = $this->db->get_where('user', ['id' => $data['user']['id']])->row_array();
                    create_log('UPDATE', 'user', $data['user']['id'], ['password' => '[HIDDEN]'], ['password' => '[CHANGED]']);

                    $this->session->set_flashdata('swal_icon', 'success');
                    $this->session->set_flashdata('swal_title', 'Berhasil!');
                    $this->session->set_flashdata('swal_text', 'Password Anda telah diperbarui.');
                    redirect('user');
                }
            }
        }
    }
}