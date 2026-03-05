<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $user;

    public function __construct() {
        parent::__construct();
        
        $this->load->helper('stater');

        $username = $this->session->userdata('username');
        if ($username) {
            $this->user = $this->db->get_where('user', ['username' => $username])->row_array();
            
            if ($this->user && !$this->session->userdata('user_id')) {
                $this->session->set_userdata('user_id', $this->user['id']);
            }

            // --- INI KUNCINYA ---
            // Mengirim variabel 'user' ke seluruh View secara global
            $this->load->vars(['user' => $this->user]);
        }

        check_access();
    }
    // Fungsi untuk mengambil data filter dari input atau session
    protected function get_global_filters() {
        $filters = [
            'start_date'  => $this->input->get('start_date') ?: date('Y-m-01'),
            'end_date'    => $this->input->get('end_date') ?: date('Y-m-t'),
            'store_id'    => $this->input->get('store_id'),
            'platform_id' => $this->input->get('platform_id')
        ];
        return $filters;
    }

    // Render otomatis, sekarang lebih ringkas
    protected function _render($view, $data = [])
    {
        // Kita tidak perlu lagi memasukkan $data['user'] di sini 
        // karena sudah di-handle oleh $this->load->vars() di atas.
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view($view, $data);
        $this->load->view('templates/footer');
    }
}