<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model', 'm_dash');
        // Pastikan helper yang berisi count_working_days sudah terload
        $this->load->helper('stater_helper'); 
    }

    public function index()
    {
        $data['title'] = "Dashboard Analitik Retur";

        // Filter: Jika tidak diisi, biarkan NULL agar Model mengambil data 'Selamanya'
        $filters = [
            'start'       => $this->input->get('start'), // Kosong = Total Selamanya
            'end'         => $this->input->get('end'),
            'store_id'    => $this->input->get('store'),
            'platform_id' => $this->input->get('platform')
        ];

        // Data Master Dropdown
        $data['stores']    = $this->db->get('m_stores')->result_array();
        $data['platforms'] = $this->db->get('m_platforms')->result_array();

        // Gabungkan data filter untuk dikirim kembali ke View (Input Filter)
        $data = array_merge($data, $filters);

        // --- STATISTIK ---
        // 'received' (Diterima), 'checking' (Proses), 'ready' (Siap Kirim)
        $data['total_received'] = $this->m_dash->count_by_status('received', $filters);
        $data['total_process']  = $this->m_dash->count_by_status('checking', $filters);
        $data['ready_send']     = $this->m_dash->count_by_status('ready', $filters);
        $data['avg_time']       = $this->m_dash->get_avg_sla($filters);

        // --- ANALISA & CHART ---
        $data['vendor_stats']    = $this->m_dash->get_vendor_performance($filters);
        $data['pie_data']        = $this->m_dash->get_complete_vs_reject($filters);
        
        // --- MONITORING (Strict Working Days > 30) ---
        $data['list_overdue_30'] = $this->m_dash->get_overdue_30_days($filters);

        $this->_render('dashboard/admin_retur', $data);
    }
}