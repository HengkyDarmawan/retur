<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Load Model
        $this->load->model('Admin_model', 'admin');
        // Proteksi login
        // is_logged_in();
    }
    public function index() {
        $this->load->model('Log_model', 'log_m');
        $data['title'] = 'Dashboard';
        $data['user'] = $this->user; // Dari MY_Controller
        
        // Data Security
        $data['top_ips'] = $this->log_m->get_top_ips();
        $data['multi_login'] = $this->log_m->detect_multi_login();

        $this->_render('admin/index', $data);
    }

    // Menampilkan daftar Role (Admin, Member, dll)
    public function role() {
        $data['title'] = 'Role Management';
        $data['user'] = $this->user; // Dari MY_Controller
        
        // Mengambil data dari Model
        $data['role'] = $this->admin->getRole();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('templates/footer');
        } else {
            $this->admin->insertRole(['role' => $this->input->post('role')]);
            
            // SESUAIKAN DENGAN SCRIPT FOOTER KAMU:
            $this->session->set_flashdata('message', 'Role baru berhasil ditambahkan!');
            $this->session->set_flashdata('type', 'success'); // Ini agar iconnya centang hijau
            redirect('admin/role');
        }
    }

    // Proses Edit Role
    public function editrole() {
        $id = $this->input->post('id');
        $this->admin->updateRole($id, ['role' => $this->input->post('role')]);
        
        $this->session->set_flashdata('message', 'Data role berhasil diubah!');
        $this->session->set_flashdata('type', 'success');
        redirect('admin/role');
    }

    // Proses Hapus Role
    public function deleterole($id) {
        $this->admin->deleteRole($id);
        
        $this->session->set_flashdata('message', 'Role telah dihapus!');
        $this->session->set_flashdata('type', 'success');
        redirect('admin/role');
    }


    // USER MANAGEMENT
    public function usermanagement()
    {
        $data['title'] = 'User Management';
        $data['user'] = $this->user; // Dari MY_Controller
        
        $data['all_user'] = $this->admin->getUserWithRole();
        $data['role'] = $this->admin->getRole(); // Mengambil daftar role untuk dropdown modal

        $this->form_validation->set_rules('name', 'Full Name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/user_management', $data);
            $this->load->view('templates/footer');
        } else {
            $saveData = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'username' => htmlspecialchars($this->input->post('username', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'role_id' => $this->input->post('role_id'),
                'is_active' => 1,
                'date_created' => time()
            ];

            $this->db->insert('user', $saveData);
            $this->session->set_flashdata('message', 'User baru berhasil ditambahkan!');
            $this->session->set_flashdata('type', 'success');
            redirect('admin/usermanagement');
        }
    }

    public function deleteuser($id)
    {
        $this->db->delete('user', ['id' => $id]);
        $this->session->set_flashdata('message', 'User berhasil dihapus!');
        $this->session->set_flashdata('type', 'success');
        redirect('admin/usermanagement');
    }
    public function edituser()
    {
        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $updateData = [
            'name' => htmlspecialchars($this->input->post('name', true)),
            'username' => htmlspecialchars($this->input->post('username', true)),
            'email' => htmlspecialchars($this->input->post('email', true)),
            'role_id' => $this->input->post('role_id'),
            'is_active' => $this->input->post('is_active')
        ];

        // Jika password diisi (tidak kosong), maka ganti password lama
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->admin->updateUser($id, $updateData);
        
        $this->session->set_flashdata('message', 'Data user berhasil diperbarui!');
        $this->session->set_flashdata('type', 'success');
        redirect('admin/usermanagement');
    }
    // Menampilkan halaman pengaturan akses untuk role spesifik
    public function roleAccess($role_id) {
        $data['title'] = 'Role Access Management';
        $data['user'] = $this->user;
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        // Ambil semua menu utama
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }

    // Fungsi AJAX untuk mengubah akses (View, Add, Edit, Delete)
    public function changeAccess() {
        $role_id = $this->input->post('roleId');
        $submenu_id = $this->input->post('submenuId');
        $type = $this->input->post('type'); 

        // Debugging: Jika Anda buka Network Tab di Inspect Element, 
        // Anda bisa lihat apakah datanya sampai atau tidak
        if (!$role_id || !$submenu_id || !$type) {
            echo "Data tidak lengkap";
            return;
        }

        $this->admin->update_access($role_id, $submenu_id, $type);
        
        // Pastikan fungsi create_log tidak menyebabkan error jika tabel belum ada
        // create_log('UPDATE', 'access_control', $role_id, null, ['action' => "Toggle $type"]);
        
        echo "Sukses";
    }
    public function activity_logs() {
        check_access(); // Satpam otomatis
        
        $this->load->model('Log_model', 'log_m');
        $data['title'] = 'Activity Logs';
        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['logs'] = $this->log_m->getLogs();

        $this->_render('admin/activity_logs', $data);
    }
}