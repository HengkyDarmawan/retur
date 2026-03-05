<?php
class Auth_model extends CI_Model {
    public function getUser($username) {
        // Query Builder aman dari SQL Injection karena menggunakan binding parameter otomatis
        // pastikan tabel 'user' mempunyai kolom login_attempts, last_login_attempt
        return $this->db->get_where('user', ['username' => $username])->row_array();
    }

    public function increaseLoginAttempts($userId) {
        $this->db->set('login_attempts', 'login_attempts+1', FALSE)
                 ->set('last_login_attempt', 'NOW()', FALSE)
                 ->where('id', $userId)
                 ->update('user');
    }

    public function resetLoginAttempts($userId) {
        $this->db->where('id', $userId)
                 ->update('user', ['login_attempts' => 0, 'last_login_attempt' => NULL]);
    }

    public function updatePassword($userId, $hash) {
        $this->db->where('id', $userId)
                 ->update('user', ['password' => $hash]);
    }
}
?>