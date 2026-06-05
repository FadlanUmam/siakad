<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Controller KHS (Kartu Hasil Studi)
class Khs extends CI_Controller {

    // dijalankan setiap controller dipanggil
    public function __construct() {
        parent::__construct();

        // load model untuk ambil data dari database
        $this->load->model('Siakad_model');

        // cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }
    }

    // halaman awal KHS
    public function index() {

        // kalau mahasiswa, langsung ke KHS sendiri
        if ($this->session->userdata('role') === 'mahasiswa') {

            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

            if ($mhs) {
                redirect('khs/detail/' . $mhs['id']);
            } else {
                redirect('welcome');
            }
        }

        // ambil semua mahasiswa (admin)
        $data['mahasiswa'] = $this->Siakad_model->get_all_mahasiswa();

        $this->load->view('khs/index', $data);
    }

    // detail KHS mahasiswa
    public function detail($mahasiswa_id) {

        // supaya mahasiswa tidak bisa lihat data orang lain
        if ($this->session->userdata('role') === 'mahasiswa') {

            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

            if (!$mhs || $mhs['id'] != $mahasiswa_id) {
                redirect('khs/detail/' . $mhs['id']);
            }
        }

        // data mahasiswa
        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);

        if (!$data['mahasiswa']) {
            redirect('khs');
        }

        // daftar semester
        $data['list_semester'] = $this->Siakad_model->get_semesters_by_mahasiswa($mahasiswa_id);

        // ambil semester dari url
        $selected_semester = $this->input->get('semester', TRUE);

        // kalau belum dipilih ambil semester pertama
        if (empty($selected_semester) && !empty($data['list_semester'])) {
            $selected_semester = $data['list_semester'][0]['semester'];
        }

        $data['selected_semester'] = $selected_semester;

        $data['khs_list'] = [];
        $data['ip'] = 0.00;
        $data['total_sks'] = 0;

        // kalau ada semester dipilih
        if (!empty($selected_semester)) {

            $khs_raw = $this->Siakad_model->get_khs_mahasiswa(
                $mahasiswa_id,
                $selected_semester
            );

            $total_bobot = 0;
            $total_sks = 0;
            $processed = [];

            foreach ($khs_raw as $item) {

                // ubah nilai huruf ke angka
                $bobot = $this->_konversi_huruf_ke_bobot($item['nilai_huruf']);
                $sks = (int) $item['sks'];

                $nilai_mutu = $sks * $bobot;

                $item['bobot'] = $bobot;
                $item['nilai_mutu'] = $nilai_mutu;

                $total_bobot += $nilai_mutu;
                $total_sks += $sks;

                $processed[] = $item;
            }

            $data['khs_list'] = $processed;
            $data['total_sks'] = $total_sks;

            // hitung IP semester
            $data['ip'] = ($total_sks > 0)
                ? number_format($total_bobot / $total_sks, 2)
                : '0.00';
        }

        // hitung IPK (semua semester)
        $all_nilai = $this->db->select('nilai.nilai_huruf, matakuliah.sks')
                              ->from('nilai')
                              ->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id')
                              ->where('nilai.mahasiswa_id', $mahasiswa_id)
                              ->get()->result_array();

        $total_bobot_kum = 0;
        $total_sks_kum = 0;

        foreach ($all_nilai as $n) {

            $bobot = $this->_konversi_huruf_ke_bobot($n['nilai_huruf']);
            $sks = (int) $n['sks'];

            $total_bobot_kum += ($sks * $bobot);
            $total_sks_kum += $sks;
        }

        $data['total_sks_kumulatif'] = $total_sks_kum;

        // hasil IPK
        $data['ipk'] = ($total_sks_kum > 0)
            ? number_format($total_bobot_kum / $total_sks_kum, 2)
            : '0.00';

        $this->load->view('khs/detail', $data);
    }

    // cetak KHS
    public function cetak($mahasiswa_id, $semester) {

        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);
        if (!$data['mahasiswa']) show_404();

        $data['selected_semester'] = $semester;

        $khs_raw = $this->Siakad_model->get_khs_mahasiswa($mahasiswa_id, $semester);

        $total_bobot = 0;
        $total_sks = 0;
        $processed = [];

        foreach ($khs_raw as $item) {

            $bobot = $this->_konversi_huruf_ke_bobot($item['nilai_huruf']);
            $sks = (int) $item['sks'];

            $item['bobot'] = $bobot;
            $item['nilai_mutu'] = $sks * $bobot;

            $total_bobot += $item['nilai_mutu'];
            $total_sks += $sks;

            $processed[] = $item;
        }

        $data['khs_list'] = $processed;
        $data['total_sks'] = $total_sks;

        // IP semester
        $data['ip'] = ($total_sks > 0)
            ? number_format($total_bobot / $total_sks, 2)
            : '0.00';

        // hitung IPK
        $all_nilai = $this->db->select('nilai.nilai_huruf, matakuliah.sks')
                              ->from('nilai')
                              ->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id')
                              ->where('nilai.mahasiswa_id', $mahasiswa_id)
                              ->get()->result_array();

        $total_bobot_kum = 0;
        $total_sks_kum = 0;

        foreach ($all_nilai as $n) {

            $bobot = $this->_konversi_huruf_ke_bobot($n['nilai_huruf']);
            $sks = (int) $n['sks'];

            $total_bobot_kum += ($sks * $bobot);
            $total_sks_kum += $sks;
        }

        $data['ipk'] = ($total_sks_kum > 0)
            ? number_format($total_bobot_kum / $total_sks_kum, 2)
            : '0.00';

        $this->load->view('khs/cetak', $data);
    }

    // upload foto mahasiswa
    public function upload_foto() {

        if ($this->session->userdata('role') !== 'mahasiswa') {
            redirect('welcome');
        }

        $nim = $this->session->userdata('username');
        $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);

        if (!$mhs) {
            redirect('welcome');
        }

        if (!empty($_FILES['foto']['name'])) {

            $config['upload_path'] = './uploads/mahasiswa/';
            $config['allowed_types'] = 'jpg|png|jpeg';
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

    // konversi nilai huruf ke angka
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
