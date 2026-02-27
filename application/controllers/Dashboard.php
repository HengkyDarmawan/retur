<?php
class Dashboard extends CI_Controller {
    public function __construct() {
        parent::__construct();
        // Cek apakah sudah login
        if (!$this->session->userdata('username')) {
            redirect('auth');
        }
    }

    public function index() {
        $role_id = $this->session->userdata('role_id');
        $user_id = $this->session->userdata('user_id');

        $data['user'] = $this->db->get_where('user', ['username' => $this->session->userdata('username')])->row_array();
        $data['title'] = 'Dashboard';

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('dashboard/index', $data);
        $this->load->view('templates/footer');
    }
}