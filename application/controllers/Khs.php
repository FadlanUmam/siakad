<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller untuk mengelola Kartu Hasil Studi (KHS)
class Khs extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Load model utama untuk akses database
        $this->load->model('Siakad_model');

        // Redirect ke login jika belum terautentikasi
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }
    }

    // Halaman daftar mahasiswa (admin/dosen)
    // Mahasiswa otomatis diarahkan ke KHS miliknya sendiri
    public function index() {
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

        // Kirim seluruh data mahasiswa ke view
        $data['mahasiswa'] = $this->Siakad_model->get_all_mahasiswa();
        $this->load->view('khs/index', $data);
    }

    // Menampilkan detail KHS per semester untuk mahasiswa tertentu
    public function detail($mahasiswa_id) {
        // Mahasiswa hanya boleh mengakses KHS miliknya sendiri
        if ($this->session->userdata('role') === 'mahasiswa') {
            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);
            if (!$mhs || $mhs['id'] != $mahasiswa_id) {
                $this->session->set_flashdata('error', 'Akses ditolak! Anda hanya dapat melihat KHS Anda sendiri.');
                redirect('khs/detail/' . ($mhs ? $mhs['id'] : ''));
            }
        }

        // Ambil data mahasiswa, redirect jika tidak ditemukan
        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);
        if (!$data['mahasiswa']) {
            $this->session->set_flashdata('error', 'Mahasiswa tidak ditemukan.');
            redirect('khs');
        }

        // Ambil daftar semester yang pernah ditempuh mahasiswa
        $data['list_semester'] = $this->Siakad_model->get_semesters_by_mahasiswa($mahasiswa_id);

        // Gunakan semester dari query string, atau default ke semester pertama
        $selected_semester = $this->input->get('semester', TRUE);
        if (empty($selected_semester) && !empty($data['list_semester'])) {
            $selected_semester = $data['list_semester'][0]['semester'];
        }

        $data['selected_semester'] = $selected_semester;

        // Nilai default sebelum data dihitung
        $data['khs_list']  = [];
        $data['ip']        = '0.00';
        $data['total_sks'] = 0;

        if (!empty($selected_semester)) {
            // Ambil data nilai KHS pada semester yang dipilih
            $khs_raw       = $this->Siakad_model->get_khs_mahasiswa($mahasiswa_id, $selected_semester);
            $total_bobot_sks = 0;
            $total_sks       = 0;
            $processed_khs   = [];

            foreach ($khs_raw as $item) {
                $bobot              = $this->_konversi_huruf_ke_bobot($item['nilai_huruf']);
                $sks                = (int) $item['sks'];
                $item['bobot']      = $bobot;
                $item['nilai_mutu'] = $sks * $bobot;

                $total_bobot_sks += $item['nilai_mutu'];
                $total_sks       += $sks;
                $processed_khs[]  = $item;
            }

            $data['khs_list']  = $processed_khs;
            $data['total_sks'] = $total_sks;

            // Hitung IP Semester = total nilai mutu / total SKS
            if ($total_sks > 0) {
                $data['ip'] = number_format($total_bobot_sks / $total_sks, 2);
            } else {
                $data['ip'] = '0.00';
            }
        }

        // Hitung IPK kumulatif dari seluruh semester yang sudah ditempuh
        $all_nilai = $this->db->select('nilai.nilai_huruf, matakuliah.sks')
                              ->from('nilai')
                              ->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id')
                              ->where('nilai.mahasiswa_id', $mahasiswa_id)
                              ->get()->result_array();

        $total_bobot_sks_kumulatif = 0;
        $total_sks_kumulatif       = 0;

        foreach ($all_nilai as $nilai_item) {
            $bobot                      = $this->_konversi_huruf_ke_bobot($nilai_item['nilai_huruf']);
            $sks                        = (int) $nilai_item['sks'];
            $total_bobot_sks_kumulatif += $sks * $bobot;
            $total_sks_kumulatif       += $sks;
        }

        $data['total_sks_kumulatif'] = $total_sks_kumulatif;

        // IPK = total nilai mutu semua semester / total SKS semua semester
        if ($total_sks_kumulatif > 0) {
            $data['ipk'] = number_format($total_bobot_sks_kumulatif / $total_sks_kumulatif, 2);
        } else {
            $data['ipk'] = '0.00';
        }

        $this->load->view('khs/detail', $data);
    }

    // Menampilkan halaman cetak KHS (tampilan print-friendly)
    public function cetak($mahasiswa_id, $semester) {
        // Mahasiswa hanya boleh mencetak KHS miliknya sendiri
        if ($this->session->userdata('role') === 'mahasiswa') {
            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);
            if (!$mhs || $mhs['id'] != $mahasiswa_id) {
                $this->session->set_flashdata('error', 'Akses ditolak! Anda hanya dapat mencetak KHS Anda sendiri.');
                redirect('khs/detail/' . ($mhs ? $mhs['id'] : ''));
            }
        }

        // Tampilkan 404 jika data mahasiswa tidak ditemukan
        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);
        if (!$data['mahasiswa']) {
            show_404();
        }

        $data['selected_semester'] = $semester;

        // Ambil dan proses nilai KHS semester yang dicetak
        $khs_raw         = $this->Siakad_model->get_khs_mahasiswa($mahasiswa_id, $semester);
        $total_bobot_sks = 0;
        $total_sks       = 0;
        $processed_khs   = [];

        foreach ($khs_raw as $item) {
            $bobot              = $this->_konversi_huruf_ke_bobot($item['nilai_huruf']);
            $sks                = (int) $item['sks'];
            $item['bobot']      = $bobot;
            $item['nilai_mutu'] = $sks * $bobot;

            $total_bobot_sks += $item['nilai_mutu'];
            $total_sks       += $sks;
            $processed_khs[]  = $item;
        }

        $data['khs_list']  = $processed_khs;
        $data['total_sks'] = $total_sks;

        // Hitung IP Semester
        if ($total_sks > 0) {
            $data['ip'] = number_format($total_bobot_sks / $total_sks, 2);
        } else {
            $data['ip'] = '0.00';
        }

        // Hitung IPK kumulatif untuk ditampilkan di halaman cetak
        $all_nilai = $this->db->select('nilai.nilai_huruf, matakuliah.sks')
                              ->from('nilai')
                              ->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id')
                              ->where('nilai.mahasiswa_id', $mahasiswa_id)
                              ->get()->result_array();

        $total_bobot_sks_kumulatif = 0;
        $total_sks_kumulatif       = 0;

        foreach ($all_nilai as $nilai_item) {
            $bobot                      = $this->_konversi_huruf_ke_bobot($nilai_item['nilai_huruf']);
            $sks                        = (int) $nilai_item['sks'];
            $total_bobot_sks_kumulatif += $sks * $bobot;
            $total_sks_kumulatif       += $sks;
        }

        $data['total_sks_kumulatif'] = $total_sks_kumulatif;

        // Hitung IPK
        if ($total_sks_kumulatif > 0) {
            $data['ipk'] = number_format($total_bobot_sks_kumulatif / $total_sks_kumulatif, 2);
        } else {
            $data['ipk'] = '0.00';
        }

        $this->load->view('khs/cetak', $data);
    }

    // Menangani upload dan pembaruan foto profil mahasiswa
    public function upload_foto() {
        // Fitur ini hanya untuk role mahasiswa
        if ($this->session->userdata('role') !== 'mahasiswa') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('welcome');
        }

        $nim = $this->session->userdata('username');
        $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

        if (!$mhs) {
            $this->session->set_flashdata('error', 'Data mahasiswa tidak ditemukan.');
            redirect('welcome');
        }

        if (!empty($_FILES['foto']['name'])) {
            // Konfigurasi upload: tipe file, ukuran maksimal, dan nama file
            $config['upload_path']   = './uploads/mahasiswa/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 2048; // 2MB
            $config['file_name']     = 'mhs_' . $mhs['nim'] . '_' . time();

            // Buat folder upload jika belum ada
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('foto')) {
                // Hapus foto lama jika bukan foto default
                if ($mhs['foto'] != 'default.jpg' && file_exists('./uploads/mahasiswa/' . $mhs['foto'])) {
                    unlink('./uploads/mahasiswa/' . $mhs['foto']);
                }

                // Simpan nama file baru ke database
                $uploadData = $this->upload->data();
                $this->Siakad_model->update_mahasiswa($mhs['id'], ['foto' => $uploadData['file_name']]);
                $this->session->set_flashdata('success', 'Foto profil berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        } else {
            $this->session->set_flashdata('error', 'Pilih file foto terlebih dahulu!');
        }

        redirect('khs/detail/' . $mhs['id']);
    }

    // Mengubah nilai huruf menjadi bobot angka untuk perhitungan IP/IPK
    private function _konversi_huruf_ke_bobot($huruf) {
        switch (strtoupper($huruf)) {
            case 'A': return 4;
            case 'B': return 3;
            case 'C': return 2;
            case 'D': return 1;
            default:  return 0; // Nilai E atau tidak valid
        }
    }
}
