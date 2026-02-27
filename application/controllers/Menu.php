<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        // MY_Controller biasanya sudah menangani check_access, 
        // pastikan model dimuat dengan benar.
        $this->load->model('Menu_model', 'menu');
    }

    // --- MANAJEMEN MENU UTAMA ---

    public function index()
    {
        $data['title'] = 'Menu Management';
        $data['user'] = $this->user; // Dari MY_Controller
        $data['menu'] = $this->menu->getAllMenu();

        $this->form_validation->set_rules('menu', 'Menu', 'required|trim');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->_render('menu/index', $data);
        } else {
            $insertData = [
                'menu' => $this->input->post('menu', true),
                'sort_order' => $this->input->post('sort_order')
            ];

            $this->db->insert('user_menu', $insertData);
            $new_id = $this->db->insert_id();

            // Audit Log
            create_log('CREATE', 'user_menu', $new_id, null, $insertData);

            $this->session->set_flashdata('message', 'Menu baru berhasil ditambahkan!');
            $this->session->set_flashdata('type', 'success');
            redirect('menu');
        }
    }

    public function edit_menu()
    {
        $id = $this->input->post('id');
        $this->form_validation->set_rules('menu', 'Menu', 'required|trim');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', 'Gagal mengubah menu, periksa inputan!');
            $this->session->set_flashdata('type', 'error');
            redirect('menu');
        } else {
            $before = $this->db->get_where('user_menu', ['id' => $id])->row_array();
            $updateData = [
                'menu' => $this->input->post('menu', true),
                'sort_order' => $this->input->post('sort_order')
            ];

            if ($this->menu->updateMenu($id, $updateData)) {
                create_log('UPDATE', 'user_menu', $id, $before, $updateData);
                $this->session->set_flashdata('message', 'Menu berhasil diubah!');
                $this->session->set_flashdata('type', 'success');
            }
            redirect('menu');
        }
    }

    public function delete_menu($id)
    {
        $before = $this->db->get_where('user_menu', ['id' => $id])->row_array();
        
        // deleteMenu di model sudah menghapus submenu terkait secara otomatis
        if ($this->menu->deleteMenu($id)) {
            create_log('DELETE', 'user_menu', $id, $before, null);
            $this->session->set_flashdata('message', 'Menu & Submenu berhasil dihapus');
            $this->session->set_flashdata('type', 'success');
        }
        redirect('menu');
    }


    // --- MANAJEMEN SUBMENU ---

    public function submenu()
    {
        $data['title'] = 'Submenu Management';
        $data['user'] = $this->user;
        $data['subMenu'] = $this->menu->getSubMenu();
        $data['menu'] = $this->menu->getAllMenu(); // Untuk dropdown pilihan menu induk

        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required|trim');
        $this->form_validation->set_rules('icon', 'Icon', 'required|trim');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->_render('menu/submenu', $data);
        } else {
            $insertData = [
                'title' => $this->input->post('title', true),
                'menu_id' => $this->input->post('menu_id', true),
                'url' => $this->input->post('url', true),
                'icon' => $this->input->post('icon', true),
                'sort_order' => $this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            $this->db->insert('user_sub_menu', $insertData);
            $new_id = $this->db->insert_id();

            create_log('CREATE', 'user_sub_menu', $new_id, null, $insertData);

            $this->session->set_flashdata('message', 'Submenu berhasil ditambahkan!');
            $this->session->set_flashdata('type', 'success');
            redirect('menu/submenu');
        }
    }

    public function edit_submenu()
    {
        $id = $this->input->post('id');
        $this->form_validation->set_rules('title', 'Title', 'required|trim');
        $this->form_validation->set_rules('menu_id', 'Menu', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required|trim');
        $this->form_validation->set_rules('icon', 'Icon', 'required|trim');
        $this->form_validation->set_rules('sort_order', 'Sort Order', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', 'Gagal update, cek inputan.');
            $this->session->set_flashdata('type', 'error');
            redirect('menu/submenu');
        } else {
            $before = $this->db->get_where('user_sub_menu', ['id' => $id])->row_array();
            $updateData = [
                'title' => $this->input->post('title', true),
                'menu_id' => $this->input->post('menu_id', true),
                'url' => $this->input->post('url', true),
                'icon' => $this->input->post('icon', true),
                'sort_order' => $this->input->post('sort_order'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            ];

            if ($this->menu->updateSubMenu($updateData, $id)) {
                create_log('UPDATE', 'user_sub_menu', $id, $before, $updateData);
                $this->session->set_flashdata('message', 'Submenu berhasil diupdate!');
                $this->session->set_flashdata('type', 'success');
            }
            redirect('menu/submenu');
        }
    }

    public function delete_submenu($id)
    {
        $before = $this->db->get_where('user_sub_menu', ['id' => $id])->row_array();
        if ($this->menu->deleteSubMenu($id)) {
            create_log('DELETE', 'user_sub_menu', $id, $before, null);
            $this->session->set_flashdata('message', 'Submenu berhasil dihapus');
            $this->session->set_flashdata('type', 'success');
        }
        redirect('menu/submenu');
    }

}