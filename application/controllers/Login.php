<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Login
 * Mengatur autentikasi pengguna (login & logout).
 */
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    /**
     * Halaman login.
     * Jika sudah login, redirect ke dashboard.
     */
    public function index()
    {
        // Jika sudah login, langsung ke dashboard
        if ($this->session->userdata('is_logged_in')) {
            redirect('welcome');
        }
        $this->load->view('login');
    }

    /**
     * Proses autentikasi login (POST).
     * Memverifikasi username & password terhadap database.
     */
    public function authenticate()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        if (empty($username) || empty($password)) {
            $this->session->set_flashdata('error', 'Username dan Password harus diisi!');
            redirect('login');
        }

        // Verifikasi username & password menggunakan Auth_model
        $user = $this->Auth_model->login($username, $password);

        if ($user) {
            // Login berhasil: set session data
            $sessionData = [
                'user_id'       => $user['id'],
                'username'      => $user['username'],
                'nama_lengkap'  => $user['nama_lengkap'],
                'role'          => $user['role'],
                'is_logged_in'  => TRUE
            ];
            $this->session->set_userdata($sessionData);

            $this->session->set_flashdata('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
            redirect('welcome');
        } else {
            // Login gagal
            $this->session->set_flashdata('error', 'Username atau Password salah, atau akun tidak aktif!');
            redirect('login');
        }
    }

    /**
     * Proses logout: hapus session dan redirect ke login.
     */
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    /**
     * Seeder: Buat/reset akun superadmin.
     * Akses: /login/seed (hanya untuk setup awal)
     */
    public function seed()
    {
        $this->Auth_model->seed_superadmin();
        echo '<div style="font-family:Arial;padding:50px;text-align:center;">';
        echo '<h2 style="color:#28a745;">✅ Superadmin berhasil dibuat/direset!</h2>';
        echo '<p><strong>Username:</strong> superadmin<br><strong>Password:</strong> admin123</p>';
        echo '<a href="' . base_url('login') . '" style="color:#007bff;">→ Kembali ke Login</a>';
        echo '</div>';
    }
}