<?php 
// Memanggil template header (berisi tag HTML pembuka, CSS, navbar, dan sidebar)
$this->load->view('templates/header'); 
?>

<!-- Bagian Header Halaman: Judul Halaman dan Tombol Aksi -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Nilai Akhir</h1>
    <div>
        <!-- Tombol Sinkronkan Nilai: Mengarah ke controller Nilai method sync() untuk memperbarui semua nilai huruf di database -->
        <a href="<?php echo base_url('nilai/sync'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2" 
           onclick="return confirm('Apakah Anda yakin ingin menyinkronkan ulang seluruh nilai huruf mahasiswa berdasarkan regulasi terbaru?')">
            <i class="fas fa-sync-alt fa-sm text-white-50"></i> Sinkronkan Nilai
        </a>
        <!-- Tombol Input Nilai: Mengarah ke form input nilai baru -->
        <a href="<?php echo base_url('nilai/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Input Nilai Baru
        </a>
    </div>
</div>

<!-- Menampilkan Alert Notifikasi Sukses jika ada data flashdata 'success' dari Controller -->
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Menampilkan Alert Notifikasi Error/Gagal jika ada data flashdata 'error' dari Controller -->
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Card Container untuk Tabel Daftar Nilai -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Nilai Akhir Mahasiswa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <!-- Tabel Bootstrap yang dihubungkan dengan DataTables (id="dataTable") -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa (NIM)</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Nilai Angka</th>
                        <th>Nilai Huruf</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Memeriksa apakah data nilai dikirimkan dari Controller dan tidak kosong -->
                    <?php if (!empty($nilai)) : ?>
                        <?php 
                        $no = 1; 
                        // Melakukan perulangan untuk menampilkan setiap baris data nilai mahasiswa
                        foreach ($nilai as $nl) : 
                        ?>
                            <tr>
                                <!-- Kolom Nomor Urut -->
                                <td><?php echo $no++; ?></td>
                                
                                <!-- Kolom Data Mahasiswa (Nama dan NIM) -->
                                <td>
                                    <strong><?php echo htmlspecialchars($nl['nama_mahasiswa']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($nl['nim']); ?></small>
                                </td>
                                
                                <!-- Kolom Mata Kuliah (Nama dan Kode) -->
                                <td>
                                    <strong><?php echo htmlspecialchars($nl['nama_mk']); ?></strong><br>
                                    <small class="badge badge-secondary"><?php echo htmlspecialchars($nl['kode_mk']); ?></small>
                                </td>
                                
                                <!-- Kolom SKS Mata Kuliah -->
                                <td><?php echo (int) $nl['sks']; ?> SKS</td>
                                
                                <!-- Kolom Nilai Angka -->
                                <td><?php echo (float) $nl['nilai_angka']; ?></td>
                                
                                <!-- Kolom Nilai Huruf dengan warna badge dinamis sesuai nilai hurufnya -->
                                <td>
                                    <?php 
                                        $badgeClass = 'badge-danger'; // Default warna merah (untuk nilai E atau tidak dikenal)
                                        if ($nl['nilai_huruf'] == 'A') $badgeClass = 'badge-success'; // Hijau jika A
                                        elseif ($nl['nilai_huruf'] == 'B') $badgeClass = 'badge-primary'; // Biru jika B
                                        elseif ($nl['nilai_huruf'] == 'C') $badgeClass = 'badge-info'; // Biru muda jika C
                                        elseif ($nl['nilai_huruf'] == 'D') $badgeClass = 'badge-warning'; // Kuning jika D
                                    ?>
                                    <!-- Menampilkan badge nilai huruf -->
                                    <span class="badge <?php echo $badgeClass; ?> px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($nl['nilai_huruf']); ?>
                                    </span>
                                </td>
                                
                                <!-- Kolom Aksi Edit -->
                                <td>
                                    <!-- Tombol Edit: mengarah ke halaman edit nilai berdasarkan id nilai yang dipilih -->
                                    <a href="<?php echo base_url('nilai/edit/' . $nl['id']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- Tombol Hapus (Dinonaktifkan / Dikomentari demi keamanan integritas data):
                                    <a href="<?php echo base_url('nilai/hapus/' . $nl['id']); ?>" class="btn btn-danger btn-sm ml-1" onclick="return confirm('Apakah Anda yakin ingin menghapus data nilai ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                    -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Tampilan jika tabel database 'nilai' masih kosong -->
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data nilai yang diinput.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Memanggil template footer (berisi tag HTML penutup, copyright, dan scripts Javascript)
$this->load->view('templates/footer'); 
?>
