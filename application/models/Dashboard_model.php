<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    /**
     * Helper internal untuk standarisasi filter.
     * Logika Opsi B: Jika tanggal kosong, tampilkan semua data (selama belum Selesai/Ditolak).
     */
    private function _apply_filter($filters)
    {
        if (!empty($filters['start']) && !empty($filters['end'])) {
            // Jika user filter tanggal, tampilkan performa di range tersebut
            $this->db->where('tr.received_date >=', $filters['start']);
            $this->db->where('tr.received_date <=', $filters['end']);
        } else {
            // DEFAULT (Opsi B): Tampilkan total selamanya untuk data yang belum tuntas
            $this->db->where_not_in('tr.status', ['completed', 'rejected']);
        }
        
        if (!empty($filters['store_id'])) {
            $this->db->where('tr.store_id', $filters['store_id']);
        }
        
        if (!empty($filters['platform_id'])) {
            $this->db->where('tr.platform_id', $filters['platform_id']);
        }
    }

    public function count_by_status($status, $filters)
    {
        $this->db->from('tr_returns tr');
        $this->db->where('tr.status', $status);
        $this->_apply_filter($filters);
        return $this->db->count_all_results();
    }

    public function get_avg_sla($filters)
    {
        $this->db->select('received_date, updated_at');
        $this->db->from('tr_returns');
        $this->db->where('status', 'completed');
        
        if (!empty($filters['start'])) $this->db->where('received_date >=', $filters['start']);
        if (!empty($filters['end'])) $this->db->where('received_date <=', $filters['end']);

        $results = $this->db->get()->result_array();

        if (empty($results)) return 0;

        $total_working_days = 0;
        foreach ($results as $row) {
            // Parameter kedua diisi 'updated_at' karena barang sudah selesai
            $total_working_days += count_working_days($row['received_date'], $row['updated_at']);
        }

        return round($total_working_days / count($results), 1);
    }

    public function get_overdue_30_days($filters)
    {
        // Tarik data yang BELUM selesai
        $this->db->select('tr.*, s.store_name, p.platform_name');
        $this->db->from('tr_returns tr');
        $this->db->join('m_stores s', 's.id = tr.store_id');
        $this->db->join('m_platforms p', 'p.id = tr.platform_id');
        $this->db->where_not_in('tr.status', ['completed', 'rejected']);
        
        if (!empty($filters['store_id'])) $this->db->where('tr.store_id', $filters['store_id']);
        if (!empty($filters['platform_id'])) $this->db->where('tr.platform_id', $filters['platform_id']);

        $results = $this->db->get()->result_array();
        
        $overdue_list = [];
        foreach ($results as $row) {
            // PANGGIL HELPER: Hitung Hari Kerja (Senin-Jumat & Minus Libur Nasional)
            $working_days = count_working_days($row['received_date']);
            
            if ($working_days > 20) {
                $row['masa_tunggu'] = $working_days;
                $overdue_list[] = $row;
            }
        }

        // Urutkan berdasarkan yang paling lama (Descending)
        usort($overdue_list, function($a, $b) {
            return $b['masa_tunggu'] <=> $a['masa_tunggu'];
        });

        return $overdue_list;
    }

    public function get_vendor_performance($filters)
    {
        $this->db->select('
            v.vendor_name, 
            COUNT(tr.id) as total_retur,
            AVG(DATEDIFF(IF(tr.status = "completed", tr.updated_at, NOW()), tr.received_date)) as avg_processing_days
        ');
        $this->db->from('tr_returns tr');
        $this->db->join('tr_return_items tri', 'tri.return_id = tr.id');
        $this->db->join('m_vendors v', 'v.id = tri.vendor_id');
        $this->_apply_filter($filters);
        $this->db->group_by('v.id');
        $this->db->order_by('total_retur', 'DESC');
        $this->db->limit(5);
        return $this->db->get()->result_array();
    }

    public function get_complete_vs_reject($filters)
    {
        $this->db->select('status, COUNT(*) as total');
        $this->db->from('tr_returns tr');
        $this->db->where_in('status', ['completed', 'rejected']);

        // 🔥 JANGAN PANGGIL _apply_filter()
        
        if (!empty($filters['start']) && !empty($filters['end'])) {
            $this->db->where('tr.received_date >=', $filters['start']);
            $this->db->where('tr.received_date <=', $filters['end']);
        }

        if (!empty($filters['store_id'])) {
            $this->db->where('tr.store_id', $filters['store_id']);
        }

        if (!empty($filters['platform_id'])) {
            $this->db->where('tr.platform_id', $filters['platform_id']);
        }

        $this->db->group_by('status');

        return $this->db->get()->result_array();
    }
}