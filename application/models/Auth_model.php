<?php
class Auth_model extends CI_Model {
    public function getUser($username) {
        // Query Builder aman dari SQL Injection karena menggunakan binding parameter otomatis
        return $this->db->get_where('user', ['username' => $username])->row_array();
    }
}
?>