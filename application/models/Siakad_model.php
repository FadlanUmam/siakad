<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siakad_model extends CI_Model {

    // Mendapatkan seluruh data mahasiswa
    public function get_all_mahasiswa() {
        return $this->db->order_by('id', 'DESC')->get('mahasiswa')->result_array();
    }

    // Mendapatkan data mahasiswa berdasarkan ID
    public function get_mahasiswa_by_id($id) {
        return $this->db->get_where('mahasiswa', ['id' => $id])->row_array();
    }

    // Mendapatkan data mahasiswa berdasarkan NIM
    public function get_mahasiswa_by_nim($nim) {
        return $this->db->get_where('mahasiswa', ['nim' => $nim])->row_array();
    }

    // Menyimpan data mahasiswa baru
    public function insert_mahasiswa($data) {
        return $this->db->insert('mahasiswa', $data);
    }

    // Memperbarui data mahasiswa
    public function update_mahasiswa($id, $data) {
        return $this->db->where('id', $id)->update('mahasiswa', $data);
    }

    // Menghapus data mahasiswa
    public function delete_mahasiswa($id) {
        return $this->db->where('id', $id)->delete('mahasiswa');
    }

    // Mendapatkan seluruh data mata kuliah
    public function get_all_matakuliah() {
        return $this->db->order_by('semester', 'ASC')->order_by('kode_mk', 'ASC')->get('matakuliah')->result_array();
    }

    // Mendapatkan data mata kuliah berdasarkan ID
    public function get_matakuliah_by_id($id) {
        return $this->db->get_where('matakuliah', ['id' => $id])->row_array();
    }

    // Menyimpan data mata kuliah baru
    public function insert_matakuliah($data) {
        return $this->db->insert('matakuliah', $data);
    }

    // Memperbarui data mata kuliah
    public function update_matakuliah($id, $data) {
        return $this->db->where('id', $id)->update('matakuliah', $data);
    }

    // Menghapus data mata kuliah
    public function delete_matakuliah($id) {
        return $this->db->where('id', $id)->delete('matakuliah');
    }

    // Mendapatkan seluruh data nilai akhir beserta info mahasiswa dan mata kuliah
    public function get_all_nilai() {
        $this->db->select('nilai.*, mahasiswa.nama as nama_mahasiswa, mahasiswa.nim, matakuliah.nama_mk, matakuliah.kode_mk, matakuliah.sks');
        $this->db->from('nilai');
        $this->db->join('mahasiswa', 'nilai.mahasiswa_id = mahasiswa.id');
        $this->db->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id');
        $this->db->order_by('nilai.id', 'DESC');
        return $this->db->get()->result_array();
    }

    // Mendapatkan data nilai berdasarkan ID
    public function get_nilai_by_id($id) {
        return $this->db->get_where('nilai', ['id' => $id])->row_array();
    }

    // Menyimpan data nilai baru
    public function insert_nilai($data) {
        return $this->db->insert('nilai', $data);
    }

    // Memperbarui data nilai
    public function update_nilai($id, $data) {
        return $this->db->where('id', $id)->update('nilai', $data);
    }

    // Menghapus data nilai
    public function delete_nilai($id) {
        return $this->db->where('id', $id)->delete('nilai');
    }

    // Mendapatkan data KHS mahasiswa berdasarkan semester tertentu
    public function get_khs_mahasiswa($mahasiswa_id, $semester) {
        $this->db->select('nilai.*, matakuliah.kode_mk, matakuliah.nama_mk, matakuliah.sks, matakuliah.semester');
        $this->db->from('nilai');
        $this->db->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id');
        $this->db->where('nilai.mahasiswa_id', $mahasiswa_id);
        $this->db->where('matakuliah.semester', $semester);
        return $this->db->get()->result_array();
    }

    // Mendapatkan daftar semester yang diikuti mahasiswa
    public function get_semesters_by_mahasiswa($mahasiswa_id) {
        $this->db->select('DISTINCT(matakuliah.semester) as semester');
        $this->db->from('nilai');
        $this->db->join('matakuliah', 'nilai.matakuliah_id = matakuliah.id');
        $this->db->where('nilai.mahasiswa_id', $mahasiswa_id);
        $this->db->order_by('semester', 'ASC');
        return $this->db->get()->result_array();
    }
}
