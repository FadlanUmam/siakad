<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    // Verifikasi login pengguna
    public function login($username, $password) {
        $user = $this->db->get_where('users', [
            'username' => $username,
            'is_active' => 1
        ])->row_array();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Mendapatkan semua pengguna/admin
    public function get_all_users() {
        return $this->db->order_by('id', 'ASC')->get('users')->result_array();
    }

    // Mendapatkan pengguna berdasarkan ID
    public function get_user_by_id($id) {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }

    // Mendapatkan pengguna berdasarkan username
    public function get_user_by_username($username) {
        return $this->db->get_where('users', ['username' => $username])->row_array();
    }

    // Menambahkan pengguna baru dengan password terenkripsi
    public function insert_user($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->db->insert('users', $data);
    }

    // Memperbarui data pengguna
    public function update_user($id, $data) {
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }
        return $this->db->where('id', $id)->update('users', $data);
    }

    // Menghapus pengguna berdasarkan ID
    public function delete_user($id) {
        return $this->db->where('id', $id)->delete('users');
    }

    // Mengubah status aktif/nonaktif pengguna
    public function toggle_user_status($id, $status) {
        return $this->db->where('id', $id)->update('users', ['is_active' => $status]);
    }

    // Mencatat log perubahan nilai
    public function insert_nilai_log($logData) {
        return $this->db->insert('nilai_log', $logData);
    }

    // Mendapatkan seluruh catatan perubahan nilai
    public function get_all_nilai_log() {
        $this->db->select('nilai_log.*, users.username, users.nama_lengkap');
        $this->db->from('nilai_log');
        $this->db->join('users', 'nilai_log.user_id = users.id', 'left');
        $this->db->order_by('nilai_log.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // Mendapatkan catatan log berdasarkan ID nilai
    public function get_log_by_nilai_id($nilai_id) {
        $this->db->select('nilai_log.*, users.username, users.nama_lengkap');
        $this->db->from('nilai_log');
        $this->db->join('users', 'nilai_log.user_id = users.id', 'left');
        $this->db->where('nilai_log.nilai_id', $nilai_id);
        $this->db->order_by('nilai_log.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    // Membuat akun superadmin default jika belum ada
    public function seed_superadmin() {
        $this->db->query("ALTER TABLE users MODIFY COLUMN role ENUM('superadmin', 'admin', 'mahasiswa') NOT NULL DEFAULT 'admin'");

        $existing = $this->get_user_by_username('superadmin');
        if (!$existing) {
            $this->insert_user([
                'username'      => 'superadmin',
                'password'      => 'admin123',
                'nama_lengkap'  => 'Super Administrator',
                'role'          => 'superadmin'
            ]);
        } else {
            $this->db->where('username', 'superadmin')->update('users', [
                'password' => password_hash('admin123', PASSWORD_BCRYPT)
            ]);
        }

        $dummy_mhs = [
            ['username' => '220101001', 'nama_lengkap' => 'Ahmad Dani'],
            ['username' => '220101002', 'nama_lengkap' => 'Budi Santoso'],
            ['username' => '220101003', 'nama_lengkap' => 'Citra Lestari']
        ];

        foreach ($dummy_mhs as $mhs) {
            $existing_mhs = $this->get_user_by_username($mhs['username']);
            if (!$existing_mhs) {
                $this->insert_user([
                    'username'      => $mhs['username'],
                    'password'      => 'mahasiswa123',
                    'nama_lengkap'  => $mhs['nama_lengkap'],
                    'role'          => 'mahasiswa'
                ]);
            }
        }
    }
}
