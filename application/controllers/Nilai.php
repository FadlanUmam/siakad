<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Nilai
 */
class Nilai extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Siakad_model');
        $this->load->model('Auth_model');

        // Cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        // Hanya admin yang boleh akses
        if ($this->session->userdata('role') === 'mahasiswa') {
            $this->session->set_flashdata('error', 'Akses ditolak!');
            redirect('welcome');
        }
    }

    // Tampilkan daftar nilai
    public function index() {
        $data['nilai'] = $this->Siakad_model->get_all_nilai();
        $this->load->view('nilai/index', $data);
    }

    // Tambah data nilai
    public function tambah() {
        if ($this->input->post()) {
            $mahasiswa_id  = $this->input->post('mahasiswa_id', TRUE);
            $matakuliah_id = $this->input->post('matakuliah_id', TRUE);
            $nilai_angka   = $this->input->post('nilai_angka', TRUE);

            // Cek duplikasi nilai
            $existing = $this->db->get_where('nilai', [
                'mahasiswa_id' => $mahasiswa_id,
                'matakuliah_id' => $matakuliah_id
            ])->row_array();

            if ($existing) {
                $this->session->set_flashdata('error', 'Nilai mahasiswa pada mata kuliah ini sudah pernah diinputkan! Silakan edit data nilai yang sudah ada.');
                redirect('nilai/tambah');
            }

            // Konversi nilai huruf
            $nilai_huruf = $this->_hitung_nilai_huruf($nilai_angka);

            $saveData = [
                'mahasiswa_id'  => $mahasiswa_id,
                'matakuliah_id' => $matakuliah_id,
                'nilai_angka'   => $nilai_angka,
                'nilai_huruf'   => $nilai_huruf
            ];

            if ($this->Siakad_model->insert_nilai($saveData)) {
                $nilai_id = $this->db->insert_id();

                $mahasiswa = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);
                $matakuliah = $this->Siakad_model->get_matakuliah_by_id($matakuliah_id);

                // Catat log
                $this->Auth_model->insert_nilai_log([
                    'nilai_id'          => $nilai_id,
                    'user_id'           => $this->session->userdata('user_id'),
                    'aksi'              => 'insert',
                    'nilai_angka_lama'  => NULL,
                    'nilai_huruf_lama'  => NULL,
                    'nilai_angka_baru'  => $nilai_angka,
                    'nilai_huruf_baru'  => $nilai_huruf,
                    'mahasiswa_nama'    => $mahasiswa ? $mahasiswa['nama'] : '-',
                    'matakuliah_nama'   => $matakuliah ? $matakuliah['nama_mk'] : '-',
                    'keterangan'        => 'Input nilai baru: ' . $nilai_angka . ' (' . $nilai_huruf . ')'
                ]);

                $this->session->set_flashdata('success', 'Nilai mahasiswa berhasil diinput.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data nilai.');
            }

            redirect('nilai');
        }

        $data['mahasiswa']  = $this->Siakad_model->get_all_mahasiswa();
        $data['matakuliah'] = $this->Siakad_model->get_all_matakuliah();
        $this->load->view('nilai/tambah', $data);
    }

    // Edit data nilai
    public function edit($id) {
        $data['nilai'] = $this->Siakad_model->get_nilai_by_id($id);

        if (!$data['nilai']) {
            $this->session->set_flashdata('error', 'Data nilai tidak ditemukan.');
            redirect('nilai');
        }

        if ($this->input->post()) {
            $nilai_lama = $data['nilai'];

            $mahasiswa_id  = $this->input->post('mahasiswa_id', TRUE);
            $matakuliah_id = $this->input->post('matakuliah_id', TRUE);
            $nilai_angka   = $this->input->post('nilai_angka', TRUE);

            // Cek duplikasi data
            $existing = $this->db->get_where('nilai', [
                'mahasiswa_id' => $mahasiswa_id,
                'matakuliah_id' => $matakuliah_id,
                'id !=' => $id
            ])->row_array();

            if ($existing) {
                $this->session->set_flashdata('error', 'Kombinasi mahasiswa dan mata kuliah tersebut sudah memiliki data nilai! Silakan edit data tersebut.');
                redirect('nilai/edit/' . $id);
            }

            $nilai_huruf = $this->_hitung_nilai_huruf($nilai_angka);

            $updateData = [
                'mahasiswa_id'  => $mahasiswa_id,
                'matakuliah_id' => $matakuliah_id,
                'nilai_angka'   => $nilai_angka,
                'nilai_huruf'   => $nilai_huruf
            ];

            if ($this->Siakad_model->update_nilai($id, $updateData)) {
                $mahasiswa = $this->Siakad_model->get_mahasiswa_by_id($mahasiswa_id);
                $matakuliah = $this->Siakad_model->get_matakuliah_by_id($matakuliah_id);

                // Catat log
                $this->Auth_model->insert_nilai_log([
                    'nilai_id'          => $id,
                    'user_id'           => $this->session->userdata('user_id'),
                    'aksi'              => 'update',
                    'nilai_angka_lama'  => $nilai_lama['nilai_angka'],
                    'nilai_huruf_lama'  => $nilai_lama['nilai_huruf'],
                    'nilai_angka_baru'  => $nilai_angka,
                    'nilai_huruf_baru'  => $nilai_huruf,
                    'mahasiswa_nama'    => $mahasiswa ? $mahasiswa['nama'] : '-',
                    'matakuliah_nama'   => $matakuliah ? $matakuliah['nama_mk'] : '-',
                    'keterangan'        => 'Update nilai dari ' . $nilai_lama['nilai_angka'] . ' (' . $nilai_lama['nilai_huruf'] . ') menjadi ' . $nilai_angka . ' (' . $nilai_huruf . ')'
                ]);

                $this->session->set_flashdata('success', 'Data Nilai berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data nilai.');
            }

            redirect('nilai');
        }

        $data['mahasiswa']  = $this->Siakad_model->get_all_mahasiswa();
        $data['matakuliah'] = $this->Siakad_model->get_all_matakuliah();
        $this->load->view('nilai/edit', $data);
    }

    // Hapus data nilai
    /*
    // Fungsi Hapus Nilai dinonaktifkan demi keamanan data nilai mahasiswa
    public function hapus($id) {
        $nilai = $this->Siakad_model->get_nilai_by_id($id);

        if ($this->Siakad_model->delete_nilai($id)) {
            if ($nilai) {
                $mahasiswa = $this->Siakad_model->get_mahasiswa_by_id($nilai['mahasiswa_id']);
                $matakuliah = $this->Siakad_model->get_matakuliah_by_id($nilai['matakuliah_id']);

                // Catat log
                $this->Auth_model->insert_nilai_log([
                    'nilai_id'          => $id,
                    'user_id'           => $this->session->userdata('user_id'),
                    'aksi'              => 'delete',
                    'nilai_angka_lama'  => $nilai['nilai_angka'],
                    'nilai_huruf_lama'  => $nilai['nilai_huruf'],
                    'nilai_angka_baru'  => NULL,
                    'nilai_huruf_baru'  => NULL,
                    'mahasiswa_nama'    => $mahasiswa ? $mahasiswa['nama'] : '-',
                    'matakuliah_nama'   => $matakuliah ? $matakuliah['nama_mk'] : '-',
                    'keterangan'        => 'Hapus nilai: ' . $nilai['nilai_angka'] . ' (' . $nilai['nilai_huruf'] . ')'
                ]);
            }

            $this->session->set_flashdata('success', 'Data Nilai berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }

        redirect('nilai');
    }
    */

    // Sinkronisasi nilai huruf
    public function sync() {
        $all_nilai = $this->db->get('nilai')->result_array();
        $updated_count = 0;

        foreach ($all_nilai as $n) {
            $nilai_huruf_baru = $this->_hitung_nilai_huruf($n['nilai_angka']);
            
            if ($n['nilai_huruf'] !== $nilai_huruf_baru) {
                $this->db->where('id', $n['id'])->update('nilai', ['nilai_huruf' => $nilai_huruf_baru]);
                $updated_count++;
            }
        }

        if ($updated_count > 0) {
            $this->session->set_flashdata('success', 'Sinkronisasi berhasil! Sebanyak ' . $updated_count . ' data nilai telah diperbarui.');
        } else {
            $this->session->set_flashdata('success', 'Semua data nilai sudah sesuai.');
        }
        redirect('nilai');
    }

    // Hitung konversi nilai angka ke huruf
    private function _hitung_nilai_huruf($nilai_angka) {
        if ($nilai_angka >= 90) {
            return 'A';
        } elseif ($nilai_angka >= 80) {
            return 'B';
        } elseif ($nilai_angka >= 70) {
            return 'C';
        } elseif ($nilai_angka >= 60) {
            return 'D';
        } else {
            return 'E';
        }
    }
}
