<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Mahasiswa
 * Mengelola data mahasiswa (tampil, tambah, edit, hapus).
 */
class Mahasiswa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Siakad_model');

        // Cek login
        if (!$this->session->userdata('is_logged_in')) {
            redirect('login');
        }

        // Hanya admin/superadmin yang boleh akses
        if ($this->session->userdata('role') === 'mahasiswa') {
            $this->session->set_flashdata('error', 'Akses ditolak! Mahasiswa tidak diizinkan mengelola data mahasiswa.');
            redirect('welcome');
        }
    }

    /**
     * Menampilkan daftar semua mahasiswa.
     * Endpoint: /mahasiswa atau /mahasiswa/index
     */
    public function index() {
        // Ambil data mahasiswa
        $data['mahasiswa'] = $this->Siakad_model->get_all_mahasiswa();
        
        // Mengirim data ke view dan merender view
        $this->load->view('mahasiswa/index', $data);
    }

    /**
     * Menambah data mahasiswa baru.
     * Endpoint: /mahasiswa/tambah
     */
    public function tambah() {
        // Memeriksa apakah request bertipe POST (form submit)
        if ($this->input->post()) {
            
            // Ambil data input
            $nim     = $this->input->post('nim', TRUE);
            $nama    = $this->input->post('nama', TRUE);
            $jurusan = $this->input->post('jurusan', TRUE);
            $foto    = 'default.jpg'; // nilai default jika tidak upload foto

            // Konfigurasi Upload File menggunakan Library CodeIgniter
            if (!empty($_FILES['foto']['name'])) {
                // Tentukan lokasi penyimpanan file
                $config['upload_path']   = './uploads/mahasiswa/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 2048; // Batas maksimum 2MB
                $config['file_name']     = 'mhs_' . $nim . '_' . time(); // Rename file unik

                // Pastikan direktori upload tersedia, jika tidak buat foldernya
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);

                // Lakukan proses upload file
                if ($this->upload->do_upload('foto')) {
                    // Jika berhasil, dapatkan info file yang diupload
                    $uploadData = $this->upload->data();
                    $foto = $uploadData['file_name'];
                } else {
                    // Jika gagal upload, simpan pesan error di session flashdata
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('mahasiswa/tambah');
                }
            }

            // Simpan data
            $saveData = [
                'nim'     => $nim,
                'nama'    => $nama,
                'jurusan' => $jurusan,
                'foto'    => $foto
            ];

            // Panggil model untuk insert data ke database
            if ($this->Siakad_model->insert_mahasiswa($saveData)) {
                // Tambahkan akun login di tabel users
                $this->load->model('Auth_model');
                $password = $this->input->post('password', TRUE);
                if (empty($password)) {
                    $password = 'mahasiswa123';
                }
                $this->Auth_model->insert_user([
                    'username'     => $nim,
                    'password'     => $password,
                    'nama_lengkap' => $nama,
                    'role'         => 'mahasiswa'
                ]);

                $this->session->set_flashdata('success', 'Data Mahasiswa dan akun login berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data mahasiswa.');
            }

            // Redirect kembali ke halaman list mahasiswa
            redirect('mahasiswa');
        }

        // Jika request GET biasa (hanya membuka form)
        $this->load->view('mahasiswa/tambah');
    }

    /**
     * Memperbarui/mengedit data mahasiswa.
     * Endpoint: /mahasiswa/edit/{id}
     */
    public function edit($id) {
        // Ambil data mahasiswa lama berdasarkan ID
        $data['mahasiswa'] = $this->Siakad_model->get_mahasiswa_by_id($id);

        // Jika data tidak ditemukan, kembalikan ke list mahasiswa
        if (!$data['mahasiswa']) {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
            redirect('mahasiswa');
        }

        // Memeriksa jika data disubmit (POST)
        if ($this->input->post()) {
            
            // Ambil input data yang diubah
            $nim     = $this->input->post('nim', TRUE);
            $nama    = $this->input->post('nama', TRUE);
            $jurusan = $this->input->post('jurusan', TRUE);
            $foto    = $data['mahasiswa']['foto']; // default ke foto yang sudah ada

            // Cek jika user mengunggah foto baru
            if (!empty($_FILES['foto']['name'])) {
                $config['upload_path']   = './uploads/mahasiswa/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']      = 2048;
                $config['file_name']     = 'mhs_' . $nim . '_' . time();

                // Pastikan direktori upload ada
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, TRUE);
                }

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('foto')) {
                    // Hapus foto lama jika bukan foto default
                    if ($data['mahasiswa']['foto'] != 'default.jpg' && file_exists('./uploads/mahasiswa/' . $data['mahasiswa']['foto'])) {
                        unlink('./uploads/mahasiswa/' . $data['mahasiswa']['foto']);
                    }
                    
                    $uploadData = $this->upload->data();
                    $foto = $uploadData['file_name'];
                } else {
                    $this->session->set_flashdata('error', $this->upload->display_errors());
                    redirect('mahasiswa/edit/' . $id);
                }
            }

            // Array data update
            $updateData = [
                'nim'     => $nim,
                'nama'    => $nama,
                'jurusan' => $jurusan,
                'foto'    => $foto
            ];

            // Panggil model untuk memperbarui database
            if ($this->Siakad_model->update_mahasiswa($id, $updateData)) {
                // Update atau buat akun login di tabel users
                $this->load->model('Auth_model');
                $old_nim = $data['mahasiswa']['nim'];
                $existing_user = $this->Auth_model->get_user_by_username($old_nim);
                
                $userData = [
                    'username'     => $nim,
                    'nama_lengkap' => $nama,
                    'role'         => 'mahasiswa'
                ];
                
                $new_password = $this->input->post('password', TRUE);
                if (!empty($new_password)) {
                    $userData['password'] = $new_password;
                }

                if ($existing_user) {
                    $this->Auth_model->update_user($existing_user['id'], $userData);
                } else {
                    // Jika akun user belum ada, buat baru
                    if (empty($userData['password'])) {
                        $userData['password'] = 'mahasiswa123'; // Default password
                    }
                    $this->Auth_model->insert_user($userData);
                }

                $this->session->set_flashdata('success', 'Data Mahasiswa dan akun login berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data.');
            }

            redirect('mahasiswa');
        }

        // Render view edit dan oper data mahasiswa yang dicari
        $this->load->view('mahasiswa/edit', $data);
    }

    /**
     * Menghapus data mahasiswa.
     * Endpoint: /mahasiswa/hapus/{id}
     */
    public function hapus($id) {
        // Cari data mahasiswa
        $mahasiswa = $this->Siakad_model->get_mahasiswa_by_id($id);

        if ($mahasiswa) {
            // Hapus file foto dari disk jika filenya ada dan bukan file default
            if ($mahasiswa['foto'] != 'default.jpg' && file_exists('./uploads/mahasiswa/' . $mahasiswa['foto'])) {
                unlink('./uploads/mahasiswa/' . $mahasiswa['foto']);
            }

            // Panggil model untuk menghapus row di database
            if ($this->Siakad_model->delete_mahasiswa($id)) {
                // Hapus akun login di tabel users
                $this->load->model('Auth_model');
                $existing_user = $this->Auth_model->get_user_by_username($mahasiswa['nim']);
                if ($existing_user) {
                    $this->Auth_model->delete_user($existing_user['id']);
                }

                $this->session->set_flashdata('success', 'Data Mahasiswa dan akun login berhasil dihapus.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus data.');
            }
        } else {
            $this->session->set_flashdata('error', 'Data tidak ditemukan.');
        }

        redirect('mahasiswa');
    }
}
