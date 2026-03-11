<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Return_model', 'm_retur');
    }

    public function index() {
        $no_retur = $this->input->get('nomor', TRUE);
        $data['title'] = "Sistem Retur"; 
        $data['nomor'] = $no_retur;
        $data['result'] = null;

        if ($no_retur) {
            $this->load->model('Return_model', 'm_retur');
            $data['result'] = $this->m_retur->get_return_by_number($no_retur);
            
            if ($data['result']) {
                $holidays = $this->m_retur->get_holidays_array();
                $data['result']['aging'] = $this->m_retur->count_working_days(
                    $data['result']['received_date'], 
                    date('Y-m-d'), 
                    $holidays
                );
            }
        }
        $this->load->view('returns/cek_status', $data);
    }
}