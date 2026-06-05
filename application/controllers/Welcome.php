<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load database & model
        $this->load->model('Siakad_model');

        // Cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $role = $this->session->userdata('role');
        if ($role === 'mahasiswa') {
            $nim = $this->session->userdata('username');
            $mhs = $this->Siakad_model->get_mahasiswa_by_nim($nim);
            
            if ($mhs) {
                $mahasiswa_id = $mhs['id'];
                
                // Hitung IPK mahasiswa
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
                
                $data['ipk'] = ($total_sks_kumulatif > 0) ? number_format($total_bobot_sks_kumulatif / $total_sks_kumulatif, 2) : '0.00';
                $data['total_sks_mhs'] = $total_sks_kumulatif;
                $data['mahasiswa_data'] = $mhs;
            } else {
                $data['ipk'] = '0.00';
                $data['total_sks_mhs'] = 0;
                $data['mahasiswa_data'] = null;
            }
        }

        // Hitung statistik masing-masing tabel
        $data['total_mahasiswa']  = $this->db->count_all('mahasiswa');
        $data['total_matakuliah'] = $this->db->count_all('matakuliah');
        $data['total_nilai']      = $this->db->count_all('nilai');

        // Mengirimkan statistik ke dashboard view
        $this->load->view('dashboard', $data);
    }

    // Fungsi pembantu untuk konversi nilai huruf ke bobot angka
    private function _konversi_huruf_ke_bobot($huruf) {
        $huruf = strtoupper($huruf);
        switch ($huruf) {
            case 'A': return 4;
            case 'B': return 3;
            case 'C': return 2;
            case 'D': return 1;
            case 'E': return 0;
            default: return 0;
        }
    }
}
