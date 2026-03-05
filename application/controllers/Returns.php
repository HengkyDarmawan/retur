<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Returns extends MY_Controller {

    public function __construct() {
        parent::__construct(); // WAJIB untuk menjalankan logic di MY_Controller
        $this->load->model('Return_model', 'm_retur');
        // Pastikan user login
        if (!$this->session->userdata('user_id')) {
            redirect('auth');
        }
    }

    public function index() {
        // 1. Cek Hak Akses
        if (!user_can('view', 'returns')) {
            $this->session->set_flashdata(['message' => 'Akses ditolak!', 'type' => 'error']);
            redirect('dashboard');
        }

        // 2. Ambil data master untuk modal/dropdown
        $data['couriers'] = $this->db->get('m_expeditions')->result_array();

        // 3. Tangkap Filter
        $filter = [
            'start_date' => $this->input->get('start_date', TRUE),
            'end_date'   => $this->input->get('end_date', TRUE),
            'status'     => $this->input->get('status', TRUE),
            'duration'   => $this->input->get('duration', TRUE)
        ];

        /** * PERBAIKAN LOGIKA: 
         * Kita cek apakah ada filter yang benar-benar diisi oleh user.
         * Jika semua filter kosong, kita panggil get_all_returns().
         */
        $is_filtering = false;
        foreach ($filter as $key => $value) {
            if (!empty($value)) {
                $is_filtering = true;
                break;
            }
        }

        if ($is_filtering) {
            $data['returns'] = $this->m_retur->get_filtered_returns($filter);
        } else {
            // Ini akan memanggil query dengan ORDER BY received_date DESC
            $data['returns'] = $this->m_retur->get_all_returns(); 
        }

        $data['title']   = "Manajemen Retur & Klaim";
        $data['overdue'] = $this->m_retur->get_overdue_returns(); 
        $data['filter']  = $filter;

        // 4. Load View
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('returns/index', $data);
        $this->load->view('templates/footer');
    }

    public function add() {
        // Cek Hak Akses Add
        if (!user_can('add', 'returns')) {
            $this->session->set_flashdata(['message' => 'Tidak punya akses tambah!', 'type' => 'error']);
            redirect('returns');
        }

        // Ambil Master Data untuk Dropdown Modal Tambah
        $data['title']     = "Input Retur Baru";
        $data['stores']    = $this->m_retur->get_master('m_stores');
        $data['platforms'] = $this->m_retur->get_master('m_platforms');
        $data['vendors']   = $this->m_retur->get_master('m_vendors');
        $data['types']     = $this->m_retur->get_master('m_return_types');
        $data['auto_code'] = $this->m_retur->generate_return_number();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('returns/add', $data);
        $this->load->view('templates/footer');
    }

    // Lanjut ke fungsi simpan (dengan upload logic)? 
    // Beritahu saya jika struktur di atas sudah sesuai.
    public function store() {
        // 1. Cek Hak Akses
        if (!user_can('add', 'returns')) {
            $this->session->set_flashdata(['message' => 'Akses ditolak!', 'type' => 'error']);
            redirect('returns');
        }

        $this->load->library('upload');
        $user_id = $this->session->userdata('user_id');

        // 2. LOGIC NOMOR RETUR (Ambil 4 digit terakhir Order + Sequence)
        $order_number = $this->input->post('order_number');
        // Bersihkan karakter non-alphanumeric dari nomor order
        $clean_order_ref = preg_replace('/[^a-zA-Z0-9]/', '', $order_number);
        $last_4_order = strtoupper(substr($clean_order_ref, -4));
        
        // Ambil nomor urut harian dari model
        $daily_seq = $this->m_retur->generate_daily_sequence();
        
        // Hasil: RET-20260303-5850-0001
        $return_number = "RET-" . date('Ymd') . "-" . $last_4_order . "-" . $daily_seq;

        // 3. LOGIC WHATSAPP (Input: 0812 / 812 -> Hasil: 62812)
        $raw_wa = $this->input->post('customer_wa');
        $clean_wa = '62' . ltrim($raw_wa, '0'); 

        // 4. LOGIC GARANSI (Tanggal Beli + X Tahun)
        $purchase_date = $this->input->post('purchase_date');
        $duration = $this->input->post('warranty_duration');
        if ($duration > 0) {
            $months = $duration * 12;
            $warranty_expiry = date('Y-m-d', strtotime("+$months months", strtotime($purchase_date)));
        } else {
            $warranty_expiry = $purchase_date;
        }

        // 5. HANDLE MULTIPLE PHOTO UPLOAD (Max 3 & Convert WebP)
        $photo_list = [];
        if (!empty($_FILES['evidence_photos']['name'][0])) {
            $files = $_FILES['evidence_photos'];
            foreach ($files['name'] as $key => $image) {
                if ($key > 2) break; 
                if (!empty($files['name'][$key])) {
                    $_FILES['temp_multi']['name']     = $files['name'][$key];
                    $_FILES['temp_multi']['type']     = $files['type'][$key];
                    $_FILES['temp_multi']['tmp_name'] = $files['tmp_name'][$key];
                    $_FILES['temp_multi']['error']    = $files['error'][$key];
                    $_FILES['temp_multi']['size']     = $files['size'][$key];

                    $config['upload_path']   = './assets/uploads/returns/images/';
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['encrypt_name']  = TRUE;
                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('temp_multi')) {
                        $photo_list[] = $this->_convert_to_webp($this->upload->data());
                    }
                }
            }
        }
        $all_photos = !empty($photo_list) ? implode(',', $photo_list) : null;

        // 6. HANDLE VIDEO UPLOAD
        $video_name = null;
        if (!empty($_FILES['evidence_video']['name'])) {
            $config_vid['upload_path']   = './assets/uploads/returns/videos/';
            $config_vid['allowed_types'] = 'mp4|webm|avi';
            $config_vid['max_size']      = 20480; // 20MB
            $config_vid['encrypt_name']  = TRUE;
            $this->upload->initialize($config_vid);
            if ($this->upload->do_upload('evidence_video')) {
                $video_name = $this->upload->data('file_name');
            }
        }

        // 7. PREPARE DATA HEADER
        $data_header = [
            'return_number'      => $return_number,
            'order_number'       => $order_number,
            'store_id'           => $this->input->post('store_id'),
            'platform_id'        => $this->input->post('platform_id'),
            'type_id'            => $this->input->post('type_id'),
            'customer_name'      => $this->input->post('customer_name'),
            'customer_wa'        => $clean_wa,
            'purchase_date'      => $purchase_date,
            'received_date'      => $this->input->post('received_date'),
            'current_keterangan' => $this->input->post('keterangan'),
            'evidence_photo'     => $all_photos,
            'evidence_video'     => $video_name,
            'status'             => 'received',
            'created_by'         => $user_id
        ];

        // 8. PREPARE DATA ITEM
        $items = [[
            'product_name'    => $this->input->post('product_name'),
            'vendor_id'       => $this->input->post('vendor_id'),
            'warranty_expiry' => $warranty_expiry
        ]];

        // 9. SIMPAN KE DATABASE
        if ($this->m_retur->insert_return($data_header, $items)) {
            $this->session->set_flashdata(['message' => "Retur #$return_number Berhasil Disimpan", 'type' => 'success']);
        } else {
            $this->session->set_flashdata(['message' => "Gagal menyimpan data ke database!", 'type' => 'error']);
        }

        redirect('returns');
    }

    /**
     * Private Function: Konversi Image ke WebP
     */
    private function _convert_to_webp($upload_data) {
        $source_path = $upload_data['full_path'];
        $webp_path   = $upload_data['file_path'] . $upload_data['raw_name'] . '.webp';

        // Gunakan library GD bawaan PHP 7.4+
        if ($upload_data['file_ext'] == '.png') {
            $img = imagecreatefrompng($source_path);
        } else {
            $img = imagecreatefromjpeg($source_path);
        }

        imagepalettetotruecolor($img);
        imagewebp($img, $webp_path, 80); // Quality 80%
        imagedestroy($img);
        
        unlink($source_path); // Hapus file asli (jpg/png)
        return $upload_data['raw_name'] . '.webp';
    }
    public function detail($id)
    {
        // UBAH: Return_model menjadi m_retur (sesuai alias di __construct)
        $data['return'] = $this->m_retur->get_return_by_id($id);

        if (!$data['return']) {
            $this->session->set_flashdata(['message' => 'Data retur tidak ditemukan!', 'type' => 'error']);
            redirect('returns');
        }

        // UBAH: Return_model menjadi m_retur
        // Dan pastikan memanggil get_return_history (bukan get_history agar join user jalan)
        $data['logs'] = $this->m_retur->get_return_history($id);

        $data['title'] = "Detail Retur: " . $data['return']['return_number'];
        
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('returns/detail', $data);
        $this->load->view('templates/footer');
    }

    public function update_status() { 
        // Ambil data dasar
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $keterangan_user = $this->input->post('keterangan');

        if (!$id) {
            $this->session->set_flashdata('message', 'ID tidak valid!');
            $this->session->set_flashdata('type', 'error');
            redirect('returns');
            return;
        }

        $data_update = [
            'status'     => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $history_msg = $keterangan_user;
        
        // --- LOGIKA BERDASARKAN STATUS ---

        // 1. Tangkap data Alamat HANYA jika status 'ready'
        if ($status == 'ready') {
            $alamat = $this->input->post('shipping_address');
            $is_diff = $this->input->post('is_different_receiver');
            
            if (!empty($alamat)) {
                $data_update['shipping_address'] = $alamat;
            }
            
            if ($is_diff) {
                $nama_p = $this->input->post('receiver_name');
                $telp_p = $this->input->post('receiver_phone');
                $data_update['receiver_info'] = "Penerima: $nama_p ($telp_p)";
            }
        } 

        // 2. Tangkap data Pengiriman (WAJIB JIKA STATUS SHIPPED)
        if ($status == 'shipped') {
            $data_update['courier_id'] = $this->input->post('courier_id');
            $data_update['receipt_number'] = $this->input->post('receipt_number');
            $data_update['shipping_date'] = $this->input->post('shipping_date');

            if (empty($data_update['courier_id']) || empty($data_update['receipt_number'])) {
                $this->session->set_flashdata('message', 'Ekspedisi dan No Resi wajib diisi!');
                $this->session->set_flashdata('type', 'error');
                redirect('returns');
                return;
            }
        }

        // Panggil Model
        $simpan = $this->m_retur->update_status_with_history($id, $data_update, $history_msg);

        if ($simpan) {
            $this->session->set_flashdata('message', 'Berhasil update status!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('message', 'Gagal update status!');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('returns');
    }
}