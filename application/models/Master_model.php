<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function get_master_data($table) {
        return $this->db->get($table)->result_array();
    }

    public function insert_master($table, $data) {
        // Membersihkan data sebelum masuk DB
        $clean_data = $this->security->xss_clean($data);
        return $this->db->insert($table, $clean_data);
    }

    public function update_master($table, $data, $id) {
        // Membersihkan data sebelum update
        $clean_data = $this->security->xss_clean($data);
        return $this->db->where('id', $id)->update($table, $clean_data);
    }

    public function delete_master($table, $id) {
        return $this->db->where('id', $id)->delete($table);
    }

    public function get_master_by_id($table, $id) {
        return $this->db->get_where($table, ['id' => $id])->row_array();
    }
}