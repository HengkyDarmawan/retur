<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->library('form_validation');
    }

    public function index() {
        // Proteksi: Jika sudah login, dilarang kembali ke halaman login
        if ($this->session->userdata('username')) redirect('dashboard');

        // Aturan validasi yang ketat
        $this->form_validation->set_rules('username', 'Username', 'required|trim|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('auth/header');
            $this->load->view('auth/login');
            $this->load->view('auth/footer');
        } else {
            $this->_login();
        }
    }

    private function _login() {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        $user = $this->Auth_model->getUser($username);

        // 1. Cek User ada atau tidak
        if ($user) {
            // 2. Cek User Aktif
            if ($user['is_active'] == 1) {
                // 3. Verifikasi Password
                if (password_verify($password, $user['password'])) {
                    $sessionData = [
                        'user_id'  => $user['id'],
                        'username' => $user['username'],
                        'role_id'  => $user['role_id'],
                        'logged_in'=> true
                    ];
                    $this->session->set_userdata($sessionData);
                    
                    // Berikan notifikasi sukses (opsional)
                    $this->session->set_flashdata('message', 'Selamat datang kembali!');
                    $this->session->set_flashdata('type', 'success');
                    redirect('dashboard');
                } else {
                    $this->_setError('Kombinasi Username & Password salah.');
                }
            } else {
                $this->_setError('Akun Anda telah dinonaktifkan.');
            }
        } else {
            // Gunakan pesan yang sama dengan password salah agar hacker tidak tahu 
            // apakah username tersebut ada di database atau tidak
            $this->_setError('Kombinasi Username & Password salah.');
        }
    }

    private function _setError($msg) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">'.$msg.'</div>');
        redirect('auth');
    }

    public function logout() 
    {
        // Ambil data session yang spesifik saja untuk dihapus
        $data = ['user_id', 'username', 'role_id', 'logged_in'];
        $this->session->unset_userdata($data);

        // Set flashdata SETELAH unset, dan SEBELUM redirect
        $this->session->set_flashdata('message', 'Kamu telah berhasil keluar.');
        $this->session->set_flashdata('type', 'success'); // Tipe icon SweetAlert
            
        redirect('auth');
    }
}