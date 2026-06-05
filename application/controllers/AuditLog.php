<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller AuditLog
 * Digunakan untuk mencatat log aktivitas perubahan nilai.
 */
class AuditLog extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Siakad_model');

        // Cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        // Hanya admin/superadmin yang boleh akses
        if ($this->session->userdata('role') === 'mahasiswa') {
            $this->session->set_flashdata('error', 'Akses ditolak! Mahasiswa tidak diizinkan melihat log perubahan nilai.');
            redirect('welcome');
        }
    }

    /**
     * Menampilkan seluruh history perubahan nilai.
     * Endpoint: /auditlog atau /auditlog/index
     */
    public function index() {
        $data['logs'] = $this->Auth_model->get_all_nilai_log();
        $this->load->view('auditlog/index', $data);
    }

    /**
     * Menghapus seluruh log riwayat aktivitas nilai jika ingin menghapus semua history.
     */
    public function clear() {
        $this->db->truncate('nilai_log');
        $this->session->set_flashdata('success', 'Seluruh riwayat perubahan nilai berhasil dihapus.');
        redirect('auditlog');
    }

    /**
     * Menampilkan history perubahan untuk satu nilai tertentu.
     * Endpoint: /auditlog/detail/{nilai_id}
     */
    public function detail($nilai_id) {
        $data['logs'] = $this->Auth_model->get_log_by_nilai_id($nilai_id);
        $data['nilai'] = $this->Siakad_model->get_nilai_by_id($nilai_id);
        $this->load->view('auditlog/detail', $data);
    }
}
