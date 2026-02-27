<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu_model extends CI_Model
{
    // Ambil Menu Utama
    public function getAllMenu()
    {
        return $this->db->order_by('sort_order', 'ASC')->get('user_menu')->result_array();
    }

    // Update Menu Utama
    public function updateMenu($id, $data)
    {
        // Sanitasi input teks
        if (isset($data['menu'])) {
            $data['menu'] = $this->security->xss_clean($data['menu']);
        }
        return $this->db->where('id', $id)->update('user_menu', $data);
    }

    // Hapus Menu & Submenu didalamnya
    public function deleteMenu($id)
    {
        // Transaction start (Opsional agar aman)
        $this->db->trans_start();
        $this->db->delete('user_sub_menu', ['menu_id' => $id]);
        $this->db->delete('user_menu', ['id' => $id]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Ambil Submenu dengan Join
    public function getSubMenu()
    {
        $query = "SELECT `user_sub_menu`.*, `user_menu`.`menu`
                FROM `user_sub_menu` JOIN `user_menu`
                ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                ORDER BY `user_sub_menu`.`menu_id` ASC, `user_sub_menu`.`sort_order` ASC
                ";
        return $this->db->query($query)->result_array();
    }

    // Update Submenu
    public function updateSubMenu($data, $id)
    {
        $data['title'] = $this->security->xss_clean($data['title']);
        $data['url']   = $this->security->xss_clean($data['url']);
        return $this->db->where('id', $id)->update('user_sub_menu', $data);
    }

    // Hapus Submenu Tunggal
    public function deleteSubMenu($id)
    {
        return $this->db->delete('user_sub_menu', ['id' => $id]);
    }
}