<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Master_model', 'm_master');
        // Model return_model diasumsikan memiliki fungsi general crud
        $this->load->model('Return_model', 'm_retur');
    }

    // --- MASTER PLATFORMS ---
    public function platforms()
    {
        $data['title'] = 'Master Platforms';
        $data['type']  = 'platforms';
        $data['list']  = $this->db->get('m_platforms')->result_array();

        $this->form_validation->set_rules('name', 'Platform Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('master/index', $data);
        } else {
            $insertData = ['platform_name' => $this->input->post('name', true)];
            $this->db->insert('m_platforms', $insertData);
            create_log('CREATE', 'm_platforms', $this->db->insert_id(), null, $insertData);
            
            $this->session->set_flashdata(['message' => 'Platform berhasil ditambahkan!', 'type' => 'success']);
            redirect('master/platforms');
        }
    }

    // --- MASTER STORES ---
    public function stores()
    {
        $data['title'] = 'Master Stores';
        $data['type']  = 'stores';
        $data['list']  = $this->db->get('m_stores')->result_array();

        $this->form_validation->set_rules('name', 'Store Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('master/index', $data);
        } else {
            $insertData = ['store_name' => $this->input->post('name', true)];
            $this->db->insert('m_stores', $insertData);
            create_log('CREATE', 'm_stores', $this->db->insert_id(), null, $insertData);
            
            $this->session->set_flashdata(['message' => 'Store berhasil ditambahkan!', 'type' => 'success']);
            redirect('master/stores');
        }
    }

    // --- MASTER EXPEDITIONS ---
    public function expeditions()
    {
        $data['title'] = 'Master Expeditions';
        $data['type']  = 'expeditions';
        $data['list']  = $this->db->get('m_expeditions')->result_array();

        $this->form_validation->set_rules('name', 'Expedition Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('master/index', $data);
        } else {
            $insertData = ['expedition_name' => $this->input->post('name', true)];
            $this->db->insert('m_expeditions', $insertData);
            create_log('CREATE', 'm_expeditions', $this->db->insert_id(), null, $insertData);
            
            $this->session->set_flashdata(['message' => 'Ekspedisi berhasil ditambahkan!', 'type' => 'success']);
            redirect('master/expeditions');
        }
    }

    // --- MASTER VENDORS ---
    public function vendors()
    {
        $data['title'] = 'Master Vendors';
        $data['type']  = 'vendors';
        $data['list']  = $this->db->get('m_vendors')->result_array();

        $this->form_validation->set_rules('name', 'Vendor Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('master/index', $data);
        } else {
            $insertData = ['vendor_name' => $this->input->post('name', true)];
            $this->db->insert('m_vendors', $insertData);
            create_log('CREATE', 'm_vendors', $this->db->insert_id(), null, $insertData);
            
            $this->session->set_flashdata(['message' => 'Vendor berhasil ditambahkan!', 'type' => 'success']);
            redirect('master/vendors');
        }
    }
    // --- MASTER RETURN TYPES ---
    public function return_types()
    {
        $data['title'] = 'Master Return Types';
        $data['type']  = 'return_types';
        $data['list']  = $this->m_master->get_master_data('m_return_types');

        $this->form_validation->set_rules('name', 'Return Type Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('master/index', $data);
        } else {
            $insertData = [
                'type_name' => $this->input->post('name', true),
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->m_master->insert_master('m_return_types', $insertData);
            
            $this->session->set_flashdata(['message' => 'Tipe Retur berhasil ditambahkan!', 'type' => 'success']);
            redirect('master/return_types');
        }
    }

    // --- MASTER HOLIDAYS ---
    public function holidays()
    {
        $data['title'] = 'Master Holidays';
        $data['type']  = 'holidays';
        $data['list']  = $this->m_master->get_master_data('m_holidays');

        $this->form_validation->set_rules('name', 'Description', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->_render('master/index', $data);
        } else {
            $start_date = $this->input->post('start_date');
            $end_date   = $this->input->post('end_date');
            $description = $this->input->post('name', true);

            // Jika hanya isi satu tanggal (start_date saja)
            if (empty($end_date)) {
                $this->m_master->insert_master('m_holidays', [
                    'holiday_date' => $start_date,
                    'description'  => $description
                ]);
            } else {
                // LOGIKA BULK INSERT: Memecah rentang tanggal jadi baris tunggal
                $begin = new DateTime($start_date);
                $end   = new DateTime($end_date);
                $end->modify('+1 day'); 

                $interval = new DateInterval('P1D');
                $daterange = new DatePeriod($begin, $interval ,$end);

                foreach($daterange as $date){
                    $this->m_master->insert_master('m_holidays', [
                        'holiday_date' => $date->format("Y-m-d"),
                        'description'  => $description
                    ]);
                }
            }
            
            $this->session->set_flashdata(['message' => 'Data Libur berhasil diproses!', 'type' => 'success']);
            redirect('master/holidays');
        }
    }

    // --- GLOBAL ACTIONS (EDIT & DELETE) ---

    public function edit($type)
    {
        $id = $this->input->post('id');
        $table = 'm_' . $type;
        $field = $this->_get_field_name($type);

        $before = $this->db->get_where($table, ['id' => $id])->row_array();
        $updateData = [$field => $this->input->post('name', true)];

        $this->db->where('id', $id)->update($table, $updateData);
        create_log('UPDATE', $table, $id, $before, $updateData);

        $this->session->set_flashdata(['message' => 'Data berhasil diubah!', 'type' => 'success']);
        redirect('master/' . $type);
    }

    public function delete($type, $id)
    {
        $table = 'm_' . $type;
        $before = $this->db->get_where($table, ['id' => $id])->row_array();

        if ($this->db->delete($table, ['id' => $id])) {
            create_log('DELETE', $table, $id, $before, null);
            $this->session->set_flashdata(['message' => 'Data berhasil dihapus!', 'type' => 'success']);
        }
        redirect('master/' . $type);
    }

    private function _get_field_name($type)
    {
        switch ($type) {
            case 'platforms': return 'platform_name';
            case 'stores': return 'store_name';
            case 'vendors': return 'vendor_name';
            case 'expeditions': return 'expedition_name';
            case 'return_types': return 'type_name';
            case 'holidays': return 'description';
            default: return 'name';
        }
    }
}