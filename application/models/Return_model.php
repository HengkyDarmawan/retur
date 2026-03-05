<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Return_model extends CI_Model {

    /**
     * Mengambil semua data retur dengan Join Master Data
     * Digunakan oleh Admin
     */
    public function get_all_returns() {
        $this->db->select('tr_returns.*, m_stores.store_name, m_platforms.platform_name, m_expeditions.expedition_name');
        $this->db->from('tr_returns');
        $this->db->join('m_stores', 'm_stores.id = tr_returns.store_id', 'left');
        $this->db->join('m_platforms', 'm_platforms.id = tr_returns.platform_id', 'left');
        $this->db->join('m_expeditions', 'm_expeditions.id = tr_returns.courier_id', 'left');

        // DEFAULT URUTAN: Tanggal Masuk Terbaru di Atas
        $this->db->order_by('tr_returns.received_date', 'DESC');
        $this->db->order_by('tr_returns.id', 'DESC'); 

        return $this->db->get()->result_array();
    }

    public function get_filtered_returns($f) {
        $this->db->select('tr_returns.*, m_stores.store_name, m_platforms.platform_name, m_expeditions.expedition_name');
        $this->db->from('tr_returns');
        $this->db->join('m_stores', 'm_stores.id = tr_returns.store_id', 'left');
        $this->db->join('m_platforms', 'm_platforms.id = tr_returns.platform_id', 'left');
        $this->db->join('m_expeditions', 'm_expeditions.id = tr_returns.courier_id', 'left');

        // --- FILTER TANGGAL (Menggunakan received_date) ---
        if(!empty($f['start_date'])) {
            $this->db->where('DATE(tr_returns.received_date) >=', $f['start_date']);
        }
        if(!empty($f['end_date'])) {
            $this->db->where('DATE(tr_returns.received_date) <=', $f['end_date']);
        }

        // Filter Status
        if(!empty($f['status'])) {
            $this->db->where('tr_returns.status', $f['status']);
        }

        // --- FILTER LAMA DI SISTEM (Menggunakan received_date) ---
        if(!empty($f['duration'])) {
            // Menghitung selisih hari dari hari ini ke tanggal barang diterima
            $this->db->where("DATEDIFF(CURDATE(), DATE(tr_returns.received_date)) >=", (int)$f['duration']);
        }

        // Mengurutkan berdasarkan tanggal diterima terbaru
        $this->db->order_by('tr_returns.received_date', 'DESC');
        
        return $this->db->get()->result_array();
    }
    
    /**
     * Query khusus untuk Dashboard (SLA > 7 Hari)
     * Menampilkan data yang "mandeg" atau overdue
     */
    public function get_overdue_returns() {
        $this->db->select('return_number, customer_name, updated_at, status');
        $this->db->from('tr_returns');
        $this->db->where('status !=', 'completed');
        $this->db->where('status !=', 'rejected');
        // Filter: Selisih hari lebih dari 7 dari update terakhir
        $this->db->where('DATEDIFF(NOW(), updated_at) >', 7);
        $this->db->order_by('updated_at', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil history akumulatif untuk timeline
     */
    public function get_history($return_id) {
        $this->db->select('tr_return_history.*, users.name as admin_name');
        $this->db->from('tr_return_history');
        $this->db->join('users', 'users.id = tr_return_history.created_by', 'left');
        $this->db->where('return_id', $return_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Tracking Publik (Tanpa Login)
     * Validasi: No Retur + No Pesanan/Resi
     */
    public function track_public($return_num, $order_num) {
        $this->db->select('tr_returns.*, m_stores.store_name, m_stores.store_logo, m_return_types.type_name');
        $this->db->from('tr_returns');
        $this->db->join('m_stores', 'm_stores.id = tr_returns.store_id');
        $this->db->join('m_return_types', 'm_return_types.id = tr_returns.type_id');
        $this->db->where('return_number', $return_num);
        $this->db->where('order_number', $order_num);
        return $this->db->get()->row_array();
    }

    /**
     * Logic Generate Nomor Retur (Reset Harian)
     */
    public function generate_return_number() {
        $prefix = "RETUR" . date('Ymd');
        $this->db->like('return_number', $prefix, 'after');
        $this->db->order_by('return_number', 'DESC');
        $last_query = $this->db->get('tr_returns', 1)->row_array();

        if ($last_query) {
            $last_num = substr($last_query['return_number'], -4);
            $next_num = str_pad((int)$last_num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_num = "0001";
        }
        return $prefix . $next_num;
    }

    /**
     * Simpan Transaksi dengan Database Transaction (Safety)
     */
    public function insert_return($header, $items) {
        $this->db->trans_start();
        
        // 1. Insert Header
        $this->db->insert('tr_returns', $header);
        $return_id = $this->db->insert_id();

        // 2. Insert Items (Multi-item)
        foreach ($items as $item) {
            $item['return_id'] = $return_id;
            $this->db->insert('tr_return_items', $item);
        }

        // 3. Insert History Awal
        $this->db->insert('tr_return_history', [
            'return_id' => $return_id,
            'status' => $header['status'],
            'keterangan' => 'Barang diterima dan diinput ke sistem.',
            'created_by' => $header['created_by']
        ]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /**
     * Mengambil data master untuk Dropdown di View
     */
    public function get_master($table) {
        return $this->db->get($table)->result_array();
    }

    public function get_return_by_id($id) {
        $this->db->select('
            tr_returns.*, 
            tr_return_items.product_name, 
            tr_return_items.warranty_expiry, 
            tr_return_items.vendor_id,
            m_stores.store_name, 
            m_platforms.platform_name, 
            m_vendors.vendor_name,
            m_return_types.type_name,
            m_expeditions.expedition_name 
        '); // Hapus alias 'as', langsung panggil kolom 'expedition_name'
        
        $this->db->from('tr_returns');
        $this->db->join('tr_return_items', 'tr_return_items.return_id = tr_returns.id', 'left');
        $this->db->join('m_stores', 'm_stores.id = tr_returns.store_id', 'left');
        $this->db->join('m_platforms', 'm_platforms.id = tr_returns.platform_id', 'left');
        $this->db->join('m_vendors', 'm_vendors.id = tr_return_items.vendor_id', 'left');
        $this->db->join('m_return_types', 'm_return_types.id = tr_returns.type_id', 'left');
        $this->db->join('m_expeditions', 'm_expeditions.id = tr_returns.courier_id', 'left'); 
        
        $this->db->where('tr_returns.id', $id);
        return $this->db->get()->row_array();
    }

    // Fungsi Update Status dengan Otomatis Menyimpan History
    // SESUDAH:
        public function update_status_with_history($id, $data_update, $keterangan, $files_json = null) {
            $this->db->trans_start();

            // 1. UPDATE TABEL UTAMA (tr_returns)
            $this->db->where('id', $id);
            $this->db->update('tr_returns', $data_update);
            
            // 2. INSERT KE tr_return_history (TAMBAHKAN KOLOM evidence_files)
            $history = [
                'return_id'      => $id,
                'status'         => $data_update['status'],
                'keterangan'     => $keterangan,
                'evidence_files' => $files_json, // <--- MASUK KE SINI
                'created_by'     => $this->session->userdata('user_id'),
                'created_at'     => date('Y-m-d H:i:s')
            ];
            $this->db->insert('tr_return_history', $history);

            $this->db->trans_complete();
            return $this->db->trans_status();
        }
    /**
     * Mengambil urutan angka terakhir untuk hari ini
     */
    public function generate_daily_sequence() {
        $today = date('Ymd');
        $this->db->like('return_number', $today, 'both');
        $this->db->order_by('id', 'DESC');
        $last_query = $this->db->get('tr_returns', 1)->row_array();

        if ($last_query) {
            // Ambil 4 digit terakhir dari string return_number
            $last_num = substr($last_query['return_number'], -4);
            $next_num = str_pad((int)$last_num + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $next_num = "0001";
        }
        return $next_num;
    }
    public function get_return_history($return_id) {
        $this->db->select('tr_return_history.*, user.name as admin_name'); // Ganti users jadi user
        $this->db->from('tr_return_history');
        $this->db->join('user', 'user.id = tr_return_history.created_by', 'left'); // Ganti users jadi user
        $this->db->where('return_id', $return_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result_array();
    }
    public function get_holidays_array() {
        $res = $this->db->select('holiday_date')->get('m_holidays')->result_array();
        return array_column($res, 'holiday_date');
    }

    /**
     * Logika perhitungan hari kerja (PHP Side)
     * Menghitung selisih hari kerja antara dua tanggal
     */
    public function count_working_days($start_date, $end_date, $holidays) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->modify('+1 day'); // Termasuk hari terakhir

        $working_days = 0;
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $end);

        foreach ($periods as $period) {
            $day_of_week = $period->format('N'); // 1 (Mon) - 7 (Sun)
            $is_weekend = ($day_of_week >= 6); // 6=Sabtu, 7=Minggu
            $is_holiday = in_array($period->format('Y-m-d'), $holidays);

            if (!$is_weekend && !$is_holiday) {
                $working_days++;
            }
        }
        // Kita return -1 karena hari pertama (received_date) biasanya tidak dihitung sebagai "umur 1 hari" 
        // kecuali kamu ingin menghitung hari input sebagai hari ke-1.
        return ($working_days > 0) ? $working_days - 1 : 0;
    }
} // Penutup Class Return_model