<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cek extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Return_model', 'm_retur');
    }

    public function index() {
        $no_retur       = $this->input->get('nomor', TRUE);
        $data['nomor']  = $no_retur;
        $data['result'] = null;
        $data['last_note'] = null;

        if ($no_retur) {
            $raw = $this->m_retur->get_return_by_number($no_retur);

            if ($raw) {
                $holidays = $this->m_retur->get_holidays_array();
                $raw['aging'] = $this->m_retur->count_working_days(
                    $raw['received_date'],
                    date('Y-m-d'),
                    $holidays
                );

                // ── SENSOR data sensitif ──
                // Nama customer → inisial saja (misal "Budi Santoso" → "B**** S******")
                $raw['customer_display'] = $this->_mask_name($raw['customer_name']);
                $raw['customer_product'] = $this->_mask_name($raw['product_name']);
                $raw['customer_store'] = $this->_mask_name($raw['store_name']);
                $raw['customer_platform'] = $this->_mask_name($raw['platform_name']);
                $raw['customer_recipt'] = $this->_mask_name($raw['receipt_number']);

                // Status internal → label publik
                $raw['status_display'] = $this->_public_status($raw['status']);

                $data['result'] = $raw;

                // Ambil 1 komentar/keterangan terbaru dari history
                // yang keterangannya tidak kosong
                $history = $this->m_retur->get_return_history($raw['id']);
                foreach ($history as $h) {
                    if (!empty(trim($h['keterangan']))) {
                        $data['last_note'] = $h['keterangan'];
                        $data['last_note_date'] = $h['created_at'];
                        break;
                    }
                }
            }
        }

        $this->load->view('returns/cek_status', $data);
    }

    // ── Ubah nama jadi inisial: "Budi Santoso" → "B*** S******" ──
    private function _mask_name($name) {
        $words = explode(' ', trim($name));
        $masked = [];
        foreach ($words as $word) {
            if (strlen($word) <= 1) {
                $masked[] = $word;
            } else {
                $masked[] = substr($word, 0, 1) . str_repeat('*', strlen($word) - 1);
            }
        }
        return implode(' ', $masked);
    }

    // ── Mapping status internal → label yang aman untuk customer ──
    private function _public_status($status) {
        $map = [
            'received'       => ['label' => 'Barang Diterima',    'icon' => 'fa-box-open',       'color' => 'primary'],
            'checking'       => ['label' => 'Sedang Diperiksa',   'icon' => 'fa-search',          'color' => 'warning'],
            'to_vendor'      => ['label' => 'Sedang Diproses',    'icon' => 'fa-cogs',            'color' => 'info'],
            'processing'     => ['label' => 'Sedang Diproses',    'icon' => 'fa-cogs',            'color' => 'info'],
            'from_vendor'    => ['label' => 'Sedang Diproses',    'icon' => 'fa-cogs',            'color' => 'info'],
            'ready'          => ['label' => 'Siap Dikirim Balik', 'icon' => 'fa-truck',           'color' => 'success'],
            'shipped'        => ['label' => 'Dalam Pengiriman',   'icon' => 'fa-shipping-fast',   'color' => 'success'],
            'completed'      => ['label' => 'Selesai',            'icon' => 'fa-check-circle',    'color' => 'success'],
            'rejected'       => ['label' => 'Tidak Dapat Diproses','icon'=> 'fa-times-circle',    'color' => 'danger'],
            'user complain'  => ['label' => 'Dalam Peninjauan',   'icon' => 'fa-clipboard-list',  'color' => 'warning'],
            'aju banding'    => ['label' => 'Dalam Peninjauan',   'icon' => 'fa-clipboard-list',  'color' => 'warning'],
            'menang banding' => ['label' => 'Klaim Disetujui',    'icon' => 'fa-check-circle',    'color' => 'success'],
            'kalah banding'  => ['label' => 'Klaim Ditolak',      'icon' => 'fa-times-circle',    'color' => 'danger'],
        ];

        return $map[$status] ?? ['label' => 'Diproses', 'icon' => 'fa-spinner', 'color' => 'secondary'];
    }
}