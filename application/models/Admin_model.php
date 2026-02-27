<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function getRole() {
        return $this->db->get('user_role')->result_array();
    }

    public function insertRole($data) {
        return $this->db->insert('user_role', $data);
    }

    public function updateRole($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('user_role', $data);
    }

    public function deleteRole($id) {
        $this->db->where('id', $id);
        return $this->db->delete('user_role');
    }

    // user management
    public function getUserWithRole()
    {
        $this->db->select('user.*, user_role.role');
        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.id');
        return $this->db->get()->result_array();
    }
    public function getUserById($id)
    {
        return $this->db->get_where('user', ['id' => $id])->row_array();
    }

    public function updateUser($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('user', $data);
    }
    // access
    public function getMenu() {
        return $this->db->get('user_menu')->result_array();
    }

    // GANTI fungsi check_access lama dengan ini:
    public function check_access($role_id, $submenu_id, $type) {
        return $this->db->get_where('user_access_control', [
            'role_id' => $role_id,
            'submenu_id' => $submenu_id,
            $type => 1
        ])->num_rows();
    }

    // TAMBAHKAN fungsi ini untuk proses simpan otomatis:
    public function update_access($role_id, $submenu_id, $type) {
        $where = [
            'role_id' => $role_id, 
            'submenu_id' => $submenu_id
        ];
        
        $check = $this->db->get_where('user_access_control', $where)->row_array();

        if (!$check) {
            // Jika belum ada, insert dengan kolom type = 1
            $data = $where;
            $data[$type] = 1;
            $this->db->insert('user_access_control', $data);
        } else {
            // Jika sudah ada, ambil nilai lama dan balikkan (0 jadi 1, 1 jadi 0)
            $new_value = ($check[$type] == 1) ? 0 : 1;
            
            $this->db->where('id', $check['id']);
            $this->db->update('user_access_control', [$type => $new_value]);
        }
    }
}