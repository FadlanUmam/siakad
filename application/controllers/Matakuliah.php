<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Matakuliah
 * Mengelola data mata kuliah (tampil, tambah, edit, hapus).
 */
class Matakuliah extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Siakad_model');

        // Cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        // Hanya admin/superadmin yang boleh akses
        if ($this->session->userdata('role') === 'mahasiswa') {
            $this->session->set_flashdata('error', 'Akses ditolak! Mahasiswa tidak diizinkan mengelola data mata kuliah.');
            redirect('welcome');
        }
    }

    /**
     * Menampilkan daftar semua mata kuliah.
     * Endpoint: /matakuliah atau /matakuliah/index
     */
    public function index() {
        // Ambil data mata kuliah dari database
        $data['matakuliah'] = $this->Siakad_model->get_all_matakuliah();
        
        // Merender view daftar mata kuliah
        $this->load->view('matakuliah/index', $data);
    }

    /**
     * Menambah data mata kuliah baru.
     * Endpoint: /matakuliah/tambah
     */
    public function tambah() {
        // Memeriksa jika ada pengiriman form (POST)
        if ($this->input->post()) {
            
            // Ambil input form
            $kode_mk  = $this->input->post('kode_mk', TRUE);
            $nama_mk  = $this->input->post('nama_mk', TRUE);
            $sks      = $this->input->post('sks', TRUE);
            $semester = $this->input->post('semester', TRUE);

            // Susun data ke dalam array asosiatif
            $saveData = [
                'kode_mk'  => $kode_mk,
                'nama_mk'  => $nama_mk,
                'sks'      => $sks,
                'semester' => $semester
            ];

            // Panggil model untuk memasukkan data baru ke database
            if ($this->Siakad_model->insert_matakuliah($saveData)) {
                $this->session->set_flashdata('success', 'Mata Kuliah berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan mata kuliah. Periksa apakah Kode MK sudah terpakai.');
            }

            redirect('matakuliah');
        }

        // Render form tambah mata kuliah
        $this->load->view('matakuliah/tambah');
    }

    /**
     * Mengedit data mata kuliah yang sudah ada.
     * Endpoint: /matakuliah/edit/{id}
     */
    public function edit($id) {
        // Ambil data mata kuliah berdasarkan ID
        $data['matakuliah'] = $this->Siakad_model->get_matakuliah_by_id($id);

        // Jika data tidak ditemukan
        if (!$data['matakuliah']) {
            $this->session->set_flashdata('error', 'Mata kuliah tidak ditemukan.');
            redirect('matakuliah');
        }

        // Memeriksa jika ada data disubmit (POST)
        if ($this->input->post()) {
            
            // Ambil data input
            $kode_mk  = $this->input->post('kode_mk', TRUE);
            $nama_mk  = $this->input->post('nama_mk', TRUE);
            $sks      = $this->input->post('sks', TRUE);
            $semester = $this->input->post('semester', TRUE);

            $updateData = [
                'kode_mk'  => $kode_mk,
                'nama_mk'  => $nama_mk,
                'sks'      => $sks,
                'semester' => $semester
            ];

            // Panggil model untuk memperbarui database
            if ($this->Siakad_model->update_matakuliah($id, $updateData)) {
                $this->session->set_flashdata('success', 'Mata Kuliah berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data.');
            }

            redirect('matakuliah');
        }

        // Render form edit
        $this->load->view('matakuliah/edit', $data);
    }

    /**
     * Menghapus mata kuliah berdasarkan ID.
     * Endpoint: /matakuliah/hapus/{id}
     */
    public function hapus($id) {
        // Hapus data dari database
        if ($this->Siakad_model->delete_matakuliah($id)) {
            $this->session->set_flashdata('success', 'Mata Kuliah berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }

        redirect('matakuliah');
    }
}
