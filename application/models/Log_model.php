<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Log_model extends CI_Model {
    public function getLogs() {
        $this->db->select('user_log.*, user.username');
        $this->db->from('user_log');
        $this->db->join('user', 'user.id = user_log.user_id', 'left');
        $this->db->order_by('user_log.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function create_log($action, $table, $data_id, $before = null, $after = null) {
        $ci = get_instance();
        $ci->load->library('user_agent'); // Muat library User Agent

        // Menentukan jenis device
        if ($ci->agent->is_mobile()) {
            $device = 'Mobile: ' . $ci->agent->mobile();
        } elseif ($ci->agent->is_browser()) {
            $device = 'Desktop: ' . $ci->agent->browser() . ' ' . $ci->agent->version();
        } else {
            $device = 'Unknown Device';
        }

        $log_data = [
            'user_id'     => $ci->session->userdata('user_id'),
            'action'      => strtoupper($action),
            'table_name'  => $table,
            'data_id'     => $data_id,
            'data_before' => $before ? json_encode($before) : null,
            'data_after'  => $after ? json_encode($after) : null,
            'ip_address'  => $ci->input->ip_address(),
            'user_agent'  => $device . ' (' . $ci->agent->platform() . ')', // Contoh: Desktop: Chrome 120.0 (Windows 11)
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $ci->db->insert('user_log', $log_data);
    }
    // Mengambil 5 IP yang paling banyak melakukan aktivitas
    public function get_top_ips() {
        $this->db->select('ip_address, COUNT(*) as total');
        $this->db->group_by('ip_address');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(5);
        return $this->db->get('user_log')->result_array();
    }

    // Mendeteksi jika 1 user login dari IP yang berbeda dalam waktu bersamaan (24 jam terakhir)
    public function detect_multi_login() {
        $query = "SELECT user_id, COUNT(DISTINCT ip_address) as ip_count, 
                GROUP_CONCAT(DISTINCT ip_address SEPARATOR ', ') as ips,
                u.username
                FROM user_log l
                JOIN user u ON u.id = l.user_id
                WHERE l.created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
                GROUP BY user_id
                HAVING ip_count > 1";
        return $this->db->query($query)->result_array();
    }
}