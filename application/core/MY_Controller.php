<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $user;

    public function __construct() {
        parent::__construct();
        
        // 1. Load Helper wajib
        $this->load->helper('stater');

        // 2. CEK BLOKIR IP (Paling Prioritas)
        // Jika IP terdaftar di tabel blocked_ips, aplikasi langsung berhenti di sini.
        // check_ip_ban();

        // 3. Ambil data user berdasarkan session
        $username = $this->session->userdata('username');
        if ($username) {
            $this->user = $this->db->get_where('user', ['username' => $username])->row_array();
            
            // Sinkronisasi user_id ke session jika belum ada (berguna untuk activity log)
            if ($this->user && !$this->session->userdata('user_id')) {
                $this->session->set_userdata('user_id', $this->user['id']);
            }
        }

        // 4. PROTEKSI AKSES URL & CRUD
        // Dipanggil terakhir karena fungsi ini butuh data user (role_id) yang sudah login.
        check_access();
    }
    // Taruh di sini supaya bisa dipakai SEMUA Controller
    protected function _render($view, $data)
    {
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}