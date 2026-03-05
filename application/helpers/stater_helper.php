<?php
defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * =============================================================
 * HELPER MANAJEMEN RETUR & OPERASIONAL
 * =============================================================
 */

/**
 * Fungsi menghitung selisih hari kerja (Senin-Jumat)
 * Mengecualikan Sabtu, Minggu, dan hari libur nasional di tabel m_holidays
 */
if (!function_exists('count_working_days')) {
    function count_working_days($start_date) {
        $ci = get_instance();
        $start = new DateTime($start_date);
        $end = new DateTime(); // Sampai hari ini
        
        // Ambil daftar libur dari database
        $holidays = $ci->db->select('holiday_date')->get('m_holidays')->result_array();
        $holiday_list = array_column($holidays, 'holiday_date');

        $working_days = 0;
        
        // Iterasi hari demi hari
        while ($start <= $end) {
            $day_of_week = $start->format('N'); // 1 (Senin) s/d 7 (Minggu)
            $current_date = $start->format('Y-m-d');

            // Syarat: Bukan Sabtu (6), Bukan Minggu (7), dan tidak terdaftar di m_holidays
            if ($day_of_week < 6 && !in_array($current_date, $holiday_list)) {
                $working_days++;
            }
            $start->modify('+1 day');
        }
        return $working_days;
    }
}
/**
 * Helper untuk mengambil menu sidebar berdasarkan hak akses dan urutan
 */
function get_sidebar_menu() {
    $ci = get_instance();
    $role_id = $ci->session->userdata('role_id');

    if ($role_id == 1) {
        // Super Admin: Ambil SEMUA menu yang aktif tanpa cek tabel akses
        $ci->db->where('is_active', 1);
        $ci->db->order_by('sort_order', 'ASC');
        $menus = $ci->db->get('user_menu')->result_array();

        foreach ($menus as $key => $m) {
            $ci->db->where('menu_id', $m['id']);
            $ci->db->where('is_active', 1);
            $ci->db->order_by('sort_order', 'ASC');
            $menus[$key]['sub_menu'] = $ci->db->get('user_sub_menu')->result_array();
            
            // Hapus menu utama jika tidak punya submenu sama sekali
            if (empty($menus[$key]['sub_menu'])) unset($menus[$key]);
        }
    }else {
        // USER BIASA: Tetap gunakan Join ke tabel akses
        // Ambil Menu Utama yang didalamnya terdapat submenu yang BOLEH dilihat (can_view = 1)
        $ci->db->select('user_menu.id, user_menu.menu, user_menu.sort_order');
        $ci->db->from('user_menu');
        // Join ke submenu lalu ke access control
        $ci->db->join('user_sub_menu', 'user_menu.id = user_sub_menu.menu_id');
        $ci->db->join('user_access_control', 'user_sub_menu.id = user_access_control.submenu_id');
        $ci->db->where('user_access_control.role_id', $role_id);
        $ci->db->where('user_access_control.can_view', 1);
        $ci->db->group_by('user_menu.id'); // Agar menu tidak duplikat
        $ci->db->order_by('CAST(user_menu.sort_order AS UNSIGNED)', 'ASC'); 
        $menus = $ci->db->get()->result_array();

        foreach ($menus as $key => $m) {
            $ci->db->select('user_sub_menu.*');
            $ci->db->from('user_sub_menu');
            $ci->db->join('user_access_control', 'user_sub_menu.id = user_access_control.submenu_id');
            $ci->db->where([
                'user_sub_menu.menu_id' => $m['id'],
                'user_sub_menu.is_active' => 1,
                'user_access_control.role_id' => $role_id,
                'user_access_control.can_view' => 1
            ]);
            $ci->db->order_by('user_sub_menu.sort_order', 'ASC');
            $menus[$key]['sub_menu'] = $ci->db->get()->result_array();
            if (empty($menus[$key]['sub_menu'])) unset($menus[$key]);
        }
    }
    return $menus;
}

/**
 * Helper Keamanan: Proteksi URL dan Hak Akses CRUD secara otomatis
 */
if (!function_exists('check_access')) {
    function check_access() {
        $ci = get_instance();
        
        // 1. Cek Login
        if (!$ci->session->userdata('username')) {
            redirect('auth');
        }

        $role_id = $ci->session->userdata('role_id');
        if ($role_id == 1) return; // Super Admin bebas

        // 2. Identifikasi URL Submenu
        $segment1 = $ci->uri->segment(1);
        $segment2 = $ci->uri->segment(2);
        
        // Bersihkan segment2 dari ID (biasanya angka di akhir URL)
        $clean_seg2 = preg_replace('/[0-9]+/', '', $segment2);
        
        $url = $segment1;
        if ($segment2) $url .= '/' . $segment2;

        // Cari di database
        $submenu = $ci->db->get_where('user_sub_menu', ['url' => $url])->row_array();

        if ($submenu) {
            $access = $ci->db->get_where('user_access_control', [
                'role_id'    => $role_id,
                'submenu_id' => $submenu['id']
            ])->row_array();

            if (!$access || $access['can_view'] == 0) redirect('auth/blocked');

            // 3. Cek CRUD (Add/Edit/Delete) secara dinamis
            if (preg_match('/(add|create|insert)/i', $segment2) && $access['can_add'] == 0) redirect('auth/blocked');
            if (preg_match('/(edit|update)/i', $segment2) && $access['can_edit'] == 0) redirect('auth/blocked');
            if (preg_match('/(delete|remove|hapus)/i', $segment2) && $access['can_delete'] == 0) redirect('auth/blocked');
        }
    }
}

/**
 * Helper Audit Trail: Mencatat aktivitas user secara mendetail
 */
if (!function_exists('create_log')) {
    function create_log($action, $table, $data_id, $before = null, $after = null) {
        $ci = get_instance();
        
        $log_data = [
            'user_id'     => $ci->session->userdata('user_id'),
            'action'      => strtoupper($action), // CREATE, UPDATE, DELETE
            'table_name'  => $table,
            'data_id'     => $data_id,
            'data_before' => $before ? json_encode($before) : null,
            'data_after'  => $after ? json_encode($after) : null,
            'ip_address'  => $ci->input->ip_address(),
            'user_agent'  => $ci->input->user_agent(),
            'created_at'  => date('Y-m-d H:i:s')
        ];

        $ci->db->insert('user_log', $log_data);
    }
}

// Fungsi untuk membatasi akses fitur secara ketat
function has_permission($type) {
    $ci = get_instance();
    $role_id = $ci->session->userdata('role_id');
    
    // Ambil URL saat ini (misal: admin/usermanagement)
    $url = $ci->uri->segment(1) . '/' . $ci->uri->segment(2);
    $submenu = $ci->db->get_where('user_sub_menu', ['url' => $url])->row_array();

    if ($submenu) {
        $access = $ci->db->get_where('user_access_control', [
            'role_id' => $role_id,
            'submenu_id' => $submenu['id'],
            $type => 1
        ]);

        // Jika tidak punya izin, tendang ke halaman blocked
        if ($access->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}

/**
 * Fungsi sakti untuk cek izin di View secara simpel
 * Contoh pakai: if(user_can('add')) { ... tombol ... }
 */
function user_can($type, $url = null) {
    $ci = get_instance();
    $role_id = $ci->session->userdata('role_id');

    // Superadmin (Role ID 1) biasanya bypass semua pengecekan
    if ($role_id == 1) return true;

    // Jika URL tidak ditentukan, ambil dari URL halaman saat ini
    if (!$url) {
        $s1 = $ci->uri->segment(1);
        $s2 = $ci->uri->segment(2);
        $url = $s2 ? "$s1/$s2" : $s1;
    }

    // Pastikan nama kolom sesuai (can_view, can_add, can_password, dll)
    // Jika input hanya 'view', maka jadi 'can_view'
    $column = (strpos($type, 'can_') === 0) ? $type : "can_$type";

    $ci->db->select("uac.$column as permission");
    $ci->db->from('user_access_control uac');
    $ci->db->join('user_sub_menu usm', 'usm.id = uac.submenu_id');
    $ci->db->where([
        'uac.role_id' => $role_id,
        'usm.url'     => $url
    ]);
    
    $result = $ci->db->get()->row_array();
    return ($result && $result['permission'] == 1);
}

// convert gambar ke webp untuk optimasi performa (opsional, bisa dipanggil di controller saat upload)
function convert_to_webp($source_path, $quality = 80) {
    // Ambil info file
    $info = getimagesize($source_path);
    $extension = $info['mime'];

    // Buat resource gambar berdasarkan tipe
    if ($extension == 'image/jpeg' || $extension == 'image/jpg') {
        $image = imagecreatefromjpeg($source_path);
    } elseif ($extension == 'image/png') {
        $image = imagecreatefrompng($source_path);
        // Pertahankan transparansi PNG
        imagepalettetobruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
    } else {
        return $source_path; // Jika bukan jpg/png, biarkan apa adanya
    }

    // Tentukan nama file baru (ganti ekstensi jadi .webp)
    $output_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source_path);

    // Simpan ke format WebP
    imagewebp($image, $output_path, $quality);

    // Hapus resource memory
    imagedestroy($image);

    // Hapus file asli (JPG/PNG) untuk menghemat ruang server
    unlink($source_path);

    return basename($output_path);
}
