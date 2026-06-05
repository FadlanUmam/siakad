<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller KHS Kartu Hasil Studi
class Khs extends CI_Controller {

    // Constructor dijalankan otomatis saat controller dipanggil
    public function __construct() {
        parent::__construct();

        // Memuat model Siakad_model agar bisa mengakses database
        $this->load->model('Siakad_model');

        // Proteksi halaman, hanya user yang sudah login yang bisa mengakses
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }
    }

    // Menampilkan daftar mahasiswa yang dapat dipilih untuk melihat KHS
    public function index() {

        // Mahasiswa langsung diarahkan ke detail KHS-nya sendiri
        if ($this->session->userdata('role') === 'mahasiswa') {

            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

            if ($mhs) {
                redirect('khs/detail/' . $mhs['id']);
            } else {
                $this->session->set_flashdata('error', 'Data profil mahasiswa Anda tidak ditemukan.');
                redirect('welcome');
            }
        }

        // Mengambil seluruh data mahasiswa dari model
        $data['mahasiswa'] = $this->Siakad_model->get_all_mahasiswa();

        // Menampilkan view daftar mahasiswa
        $this->load->view('khs/index', $data);
    }

    // Menampilkan detail KHS berdasarkan mahasiswa yang dipilih
    public function detail($mahasiswa_id) {

        // Proteksi agar mahasiswa tidak bisa melihat KHS orang lain
        if ($this->session->userdata('role') === 'mahasiswa') {

            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

            if (!$mhs || $mhs['id'] != $mahasiswa_id) {
                $this->session->set_flashdata('error', 'Akses ditolak! Anda hanya dapat melihat KHS Anda sendiri.');
                redirect('khs/detail/' . ($mhs ? $mhs['id'] : ''));
            }
        }

        // Mengambil data mahasiswa berdasarkan ID
        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);

        // Jika mahasiswa tidak ditemukan
        if (!$data['mahasiswa']) {
            $this->session->set_flashdata('error', 'Mahasiswa tidak ditemukan.');
            redirect('khs');
        }

        // Mengambil daftar semester yang pernah diambil mahasiswa
        $data['list_semester'] = $this->Siakad_model->get_semesters_by_mahasiswa($mahasiswa_id);

        // Mengambil semester yang dipilih dari URL
        $selected_semester = $this->input->get('semester', TRUE);

        // Default semester jika belum dipilih
        if (empty($selected_semester) && !empty($data['list_semester'])) {
            $selected_semester = $data['list_semester'][0]['semester'];
        }

        $data['selected_semester'] = $selected_semester;

        // Nilai awal
        $data['khs_list'] = [];
        $data['ip'] = 0.00;
        $data['total_sks'] = 0;

        // Jika semester tersedia
        if (!empty($selected_semester)) {

            // Mengambil data nilai KHS mahasiswa pada semester tertentu
            $khs_raw = $this->Siakad_model->get_khs_mahasiswa(
                $mahasiswa_id,
                $selected_semester
            );

            $total_bobot_sks = 0;
            $total_sks = 0;
            $processed_khs = [];

            // Loop setiap mata kuliah
            foreach ($khs_raw as $item) {

                // Konversi nilai huruf menjadi bobot angka
                $bobot = $this->_konversi_huruf_ke_bobot($item['nilai_huruf']);

                // Ambil jumlah SKS
                $sks = (int) $item['sks'];

                // Hitung nilai mutu
                $nilai_mutu = $sks * $bobot;

                // Tambahkan data ke array
                $item['bobot'] = $bobot;
                $item['nilai_mutu'] = $nilai_mutu;

                // Akumulasi total
                $total_bobot_sks += $nilai_mutu;
                $total_sks += $sks;

                $processed_khs[] = $item;
            }

            // Kirim data ke view
            $data['khs_list'] = $processed_khs;
            $data['total_sks'] = $total_sks;

            // Menghitung IP Semester
            $data['ip'] = ($total_sks > 0)
                ? number_format($total_bobot_sks / $total_sks, 2)
                : '0.00';
        }

        // ==========================
        // PERHITUNGAN IPK KUMULATIF
        // ==========================

        // Mengambil semua nilai mahasiswa dari seluruh semester
        $all_nilai = $this->db->select('nilai.nilai_huruf, matakuliah.sks')
                              ->from('nilai')
                              ->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id')
                              ->where('nilai.mahasiswa_id', $mahasiswa_id)
                              ->get()->result_array();

        $total_bobot_sks_kumulatif = 0;
        $total_sks_kumulatif = 0;

        // Loop seluruh nilai mahasiswa
        foreach ($all_nilai as $nilai_item) {

            // Konversi huruf ke bobot
            $bobot = $this->_konversi_huruf_ke_bobot($nilai_item['nilai_huruf']);

            // Ambil jumlah SKS
            $sks = (int) $nilai_item['sks'];

            // Akumulasi nilai
            $total_bobot_sks_kumulatif += ($sks * $bobot);
            $total_sks_kumulatif += $sks;
        }

        $data['total_sks_kumulatif'] = $total_sks_kumulatif;

        // Menghitung IPK
        $data['ipk'] = ($total_sks_kumulatif > 0)
            ? number_format($total_bobot_sks_kumulatif / $total_sks_kumulatif, 2)
            : '0.00';

        // Menampilkan halaman detail KHS
        $this->load->view('khs/detail', $data);
    }

    // Menampilkan halaman cetak KHS
    public function cetak($mahasiswa_id, $semester) {

        // Ambil data mahasiswa
        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);

        if (!$data['mahasiswa']) {
            show_404();
        }

        $data['selected_semester'] = $semester;

        // Ambil nilai semester
        $khs_raw = $this->Siakad_model->get_khs_mahasiswa($mahasiswa_id, $semester);

        $total_bobot_sks = 0;
        $total_sks = 0;
        $processed_khs = [];

        foreach ($khs_raw as $item) {

            $bobot = $this->_konversi_huruf_ke_bobot($item['nilai_huruf']);
            $sks = (int) $item['sks'];

            $item['bobot'] = $bobot;
            $item['nilai_mutu'] = $sks * $bobot;

            $total_bobot_sks += $item['nilai_mutu'];
            $total_sks += $sks;

            $processed_khs[] = $item;
        }

        $data['khs_list'] = $processed_khs;
        $data['total_sks'] = $total_sks;

        // IP semester
        $data['ip'] = ($total_sks > 0)
            ? number_format($total_bobot_sks / $total_sks, 2)
            : '0.00';

        // Hitung IPK
        $all_nilai = $this->db->select('nilai.nilai_huruf, matakuliah.sks')
                              ->from('nilai')
                              ->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id')
                              ->where('nilai.mahasiswa_id', $mahasiswa_id)
                              ->get()->result_array();

        $total_bobot_sks_kumulatif = 0;
        $total_sks_kumulatif = 0;

        foreach ($all_nilai as $nilai_item) {

            $bobot = $this->_konversi_huruf_ke_bobot($nilai_item['nilai_huruf']);
            $sks = (int) $nilai_item['sks'];

            $total_bobot_sks_kumulatif += ($sks * $bobot);
            $total_sks_kumulatif += $sks;
        }

        $data['ipk'] = ($total_sks_kumulatif > 0)
            ? number_format($total_bobot_sks_kumulatif / $total_sks_kumulatif, 2)
            : '0.00';

        // Tampilkan cetak
        $this->load->view('khs/cetak', $data);
    }

    // Upload foto mahasiswa
    public function upload_foto() {

        // hanya mahasiswa
        if ($this->session->userdata('role') !== 'mahasiswa') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('welcome');
        }

        $nim = $this->session->userdata('username');
        $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

        if (!$mhs) {
            redirect('welcome');
        }

        // jika ada file foto
        if (!empty($_FILES['foto']['name'])) {

            $config['upload_path'] = './uploads/mahasiswa/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 2048;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {

                // hapus foto lama
                if ($mhs['foto'] != 'default.jpg') {
                    unlink('./uploads/mahasiswa/' . $mhs['foto']);
                }

                $uploadData = $this->upload->data();

                // update foto
                $this->Siakad_model->update_mahasiswa(
                    $mhs['id'],
                    ['foto' => $uploadData['file_name']]
                );
            }
        }

        redirect('khs/detail/' . $mhs['id']);
    }

    // konversi nilai huruf ke bobot
    private function _konversi_huruf_ke_bobot($huruf) {

        $huruf = strtoupper($huruf);

        switch ($huruf) {
            case 'A': return 4;
            case 'B': return 3;
            case 'C': return 2;
            case 'D': return 1;
            default: return 0;
        }
    }
}
