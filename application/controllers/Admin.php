<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Admin
 * Mengatur data pengguna/admin.
 */
class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Siakad_model');
        
        // Cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        // Hanya superadmin yang boleh akses
        if ($this->session->userdata('role') !== 'superadmin') {
            $this->session->set_flashdata('error', 'Akses ditolak! Hanya Superadmin yang bisa mengelola admin.');
            redirect('welcome');
        }
    }

    // Tampilkan daftar admin
    public function index() {
        $data['users'] = $this->Auth_model->get_all_users();
        $this->load->view('admin/index', $data);
    }

    // Tambah admin baru
    public function tambah() {
        if ($this->input->post()) {
            $username      = $this->input->post('username', TRUE);
            $password      = $this->input->post('password', TRUE);
            $nama_lengkap  = $this->input->post('nama_lengkap', TRUE);
            $role          = $this->input->post('role', TRUE);

            // Validasi input
            if (empty($username) || empty($password) || empty($nama_lengkap)) {
                $this->session->set_flashdata('error', 'Semua field harus diisi!');
                redirect('admin/tambah');
            }

            // Cek username duplikat
            if ($this->Auth_model->get_user_by_username($username)) {
                $this->session->set_flashdata('error', 'Username "' . $username . '" sudah digunakan!');
                redirect('admin/tambah');
            }

            // Validasi role
            if (!in_array($role, ['admin', 'superadmin'])) {
                $role = 'admin';
            }

            $userData = [
                'username'      => $username,
                'password'      => $password,
                'nama_lengkap'  => $nama_lengkap,
                'role'          => $role
            ];

            if ($this->Auth_model->insert_user($userData)) {
                $this->session->set_flashdata('success', 'Admin baru "' . $username . '" berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan admin.');
            }

            redirect('admin');
        }

        $this->load->view('admin/tambah');
    }

    // Edit data admin
    public function edit($id) {
        $data['user'] = $this->Auth_model->get_user_by_id($id);

        if (!$data['user']) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin');
        }

        if ($this->input->post()) {
            $nama_lengkap = $this->input->post('nama_lengkap', TRUE);
            $role         = $this->input->post('role', TRUE);
            $password     = $this->input->post('password', TRUE);

            // Validasi role
            if (!in_array($role, ['admin', 'superadmin'])) {
                $role = 'admin';
            }

            // Tidak boleh mengubah role superadmin utama
            if ($data['user']['username'] === 'superadmin' && $role !== 'superadmin') {
                $this->session->set_flashdata('error', 'Tidak dapat mengubah role Superadmin utama!');
                redirect('admin/edit/' . $id);
            }

            $updateData = [
                'nama_lengkap' => $nama_lengkap,
                'role'         => $role,
                'password'     => $password
            ];

            if ($this->Auth_model->update_user($id, $updateData)) {
                $this->session->set_flashdata('success', 'Data admin berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data admin.');
            }

            redirect('admin');
        }

        $this->load->view('admin/edit', $data);
    }

    // Hapus akun admin
    public function hapus($id) {
        $user = $this->Auth_model->get_user_by_id($id);

        // Proteksi superadmin utama
        if ($user && $user['username'] === 'superadmin') {
            $this->session->set_flashdata('error', 'Superadmin utama tidak dapat dihapus!');
            redirect('admin');
        }

        // Proteksi hapus diri sendiri
        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak bisa menghapus akun sendiri!');
            redirect('admin');
        }

        if ($this->Auth_model->delete_user($id)) {
            $this->session->set_flashdata('success', 'Akun admin berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus akun admin.');
        }

        redirect('admin');
    }

    // Toggle status aktif/nonaktif admin
    public function toggle($id) {
        $user = $this->Auth_model->get_user_by_id($id);

        // Proteksi superadmin utama
        if ($user && $user['username'] === 'superadmin') {
            $this->session->set_flashdata('error', 'Superadmin utama tidak bisa dinonaktifkan!');
            redirect('admin');
        }

        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan.');
            redirect('admin');
        }

        $newStatus = $user['is_active'] ? 0 : 1;
        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        if ($this->Auth_model->toggle_user_status($id, $newStatus)) {
            $this->session->set_flashdata('success', 'Akun "' . $user['username'] . '" berhasil ' . $statusText . '.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengubah status akun.');
        }

        redirect('admin');
    }
}
