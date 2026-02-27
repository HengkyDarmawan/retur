<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug extends CI_Controller {

    public function hash($password) {
        // Gunakan ini untuk generate hash baru jika ingin manual input ke DB
        echo "Password Asli: " . $password . "<br>";
        echo "Hash: " . password_hash($password, PASSWORD_DEFAULT);
    }

    public function test_login() {
        // Masukkan username & password yang ingin kamu tes
        $username_input = 'admin'; 
        $password_input = 'admin123';

        // Ambil data dari database
        $user = $this->db->get_where('user', ['username' => $username_input])->row_array();

        if ($user) {
            echo "User ditemukan: " . $user['username'] . "<br>";
            echo "Hash di DB: " . $user['password'] . "<br>";
            
            if (password_verify($password_input, $user['password'])) {
                echo "<strong>STATUS: COCOK!</strong> Login harusnya berhasil.";
            } else {
                echo "<strong>STATUS: GAGAL.</strong> Password tidak cocok dengan Hash.";
            }
        } else {
            echo "User tidak ditemukan di database.";
        }
    }
}