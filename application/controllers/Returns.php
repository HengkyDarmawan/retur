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
        // 1. Cek Hak Akses (Tetap sama)
        if (!user_can('view', 'returns')) {
            $this->session->set_flashdata(['message' => 'Akses ditolak!', 'type' => 'error']);
            redirect('dashboard');
        }

        // Ambil daftar hari libur dari model
        $holidays = $this->m_retur->get_holidays_array();

        // 2. Tangkap Filter
        $filter = [
            'start_date' => $this->input->get('start_date', TRUE),
            'end_date'   => $this->input->get('end_date', TRUE),
            'status'     => $this->input->get('status', TRUE),
            'duration'   => $this->input->get('duration', TRUE)
        ];

        // Ambil data dasar dari database
        $raw_returns = $this->m_retur->get_all_returns(); // atau get_filtered_returns
        
        $processed_returns = [];
        $today = date('Y-m-d');

        foreach ($raw_returns as $row) {
            // Hitung umur hari kerja
            $age = $this->m_retur->count_working_days($row['received_date'], $today, $holidays);
            $row['working_day_age'] = $age;

            // --- LOGIKA FILTER DURATION ---
            // Karena DATEDIFF SQL tidak akurat (hitung weekend), kita filter ulang di sini
            if (!empty($filter['duration'])) {
                if ($age < (int)$filter['duration']) {
                    continue; // Skip jika tidak memenuhi kriteria filter hari kerja
                }
            }

            // --- LOGIKA FILTER STATUS (Jika ada) ---
            if (!empty($filter['status']) && $row['status'] !== $filter['status']) {
                continue;
            }

            $processed_returns[] = $row;
        }

        $data['returns'] = $processed_returns;
        $data['title']   = "Manajemen Retur & Klaim";
        $data['filter']  = $filter;
        $data['couriers'] = $this->db->get('m_expeditions')->result_array();

        // Load View (Tetap sama)
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
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $keterangan_user = $this->input->post('keterangan');

        if (!$id) {
            $this->session->set_flashdata('message', 'ID tidak valid!');
            $this->session->set_flashdata('type', 'error');
            redirect('returns');
            return;
        }

        $data_update = ['status' => $status, 'updated_at' => date('Y-m-d H:i:s')];
        $history_msg = $keterangan_user;

        // --- LOGIKA UPLOAD & KOMPRESI ---
        $uploaded_files = [];
        if (!empty($_FILES['evidence_files']['name'][0])) {
            $path = './assets/uploads/evidence/';
            if (!is_dir($path)) mkdir($path, 0777, TRUE);

            $filesCount = count($_FILES['evidence_files']['name']);
            for ($i = 0; $i < $filesCount; $i++) {
                $_FILES['file']['name']     = $_FILES['evidence_files']['name'][$i];
                $_FILES['file']['type']     = $_FILES['evidence_files']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['evidence_files']['tmp_name'][$i];
                $_FILES['file']['error']    = $_FILES['evidence_files']['error'][$i];
                $_FILES['file']['size']     = $_FILES['evidence_files']['size'][$i];

                $config['upload_path']   = $path;
                $config['allowed_types'] = 'jpg|jpeg|png|webp|mp4|mov|avi';
                $config['encrypt_name']  = TRUE;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $filePath = $uploadData['full_path'];
                    $ext = strtolower($uploadData['file_ext']);
                    $final_file_name = $uploadData['file_name'];

                    // 1. JIKA GAMBAR -> KONVERSI KE WEBP
                    if (in_array($ext, ['.jpg', '.jpeg', '.png'])) {
                        $webp_name = $uploadData['raw_name'] . '.webp';
                        $webp_path = $path . $webp_name;

                        if ($ext == '.jpg' || $ext == '.jpeg') $img = imagecreatefromjpeg($filePath);
                        elseif ($ext == '.png') {
                            $img = imagecreatefrompng($filePath);
                            imagepalettetotruecolor($img); // Jaga transparansi
                        }

                        if ($img) {
                            imagewebp($img, $webp_path, 80); // Kualitas 80%
                            imagedestroy($img);
                            unlink($filePath); // Hapus file asli (JPG/PNG)
                            $final_file_name = $webp_name;
                        }
                    }
                    
                    // 2. JIKA VIDEO -> KOMPRES MENGGUNAKAN FFMPEG
                    // Catatan: Ini butuh FFmpeg terinstall di OS server
                    if (in_array($ext, ['.mp4', '.mov', '.avi'])) {
                        $compressed_name = 'compress_' . $uploadData['raw_name'] . '.mp4';
                        $compressed_path = $path . $compressed_name;
                        
                        // Command FFmpeg: Kompres ke bitratre rendah (crf 28) agar file kecil
                        $shell_cmd = "ffmpeg -i $filePath -vcodec libx264 -crf 28 -preset faster -y $compressed_path 2>&1";
                        exec($shell_cmd, $output, $return_var);

                        if ($return_var === 0) {
                            unlink($filePath); // Hapus video asli yang berat
                            $final_file_name = $compressed_name;
                        }
                    }

                    $uploaded_files[] = $final_file_name;
                }
            }
        }
        
        $files_json = !empty($uploaded_files) ? json_encode($uploaded_files) : null;

        // --- LOGIKA STATUS (READY/SHIPPED) ---
        // (Tetap sama seperti kode sebelumnya)
        if ($status == 'ready') {
            $alamat = $this->input->post('shipping_address');
            if (!empty($alamat)) $data_update['shipping_address'] = $alamat;
            if ($this->input->post('is_different_receiver')) {
                $data_update['receiver_info'] = "Penerima: ".$this->input->post('receiver_name')." (".$this->input->post('receiver_phone').")";
            }
        } 

        if ($status == 'shipped') {
            $data_update['courier_id'] = $this->input->post('courier_id');
            $data_update['receipt_number'] = $this->input->post('receipt_number');
            if (empty($data_update['courier_id']) || empty($data_update['receipt_number'])) {
                $this->session->set_flashdata('message', 'Ekspedisi & Resi wajib diisi!');
                redirect('returns'); return;
            }
        }

        $simpan = $this->m_retur->update_status_with_history($id, $data_update, $history_msg, $files_json);
        
        $this->session->set_flashdata('message', $simpan ? 'Berhasil update!' : 'Gagal update!');
        $this->session->set_flashdata('type', $simpan ? 'success' : 'error');
        redirect('returns');
    }
}