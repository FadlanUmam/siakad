<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<!-- Bagian judul halaman dan tombol tambah mahasiswa baru -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Mahasiswa</h1>
    <!-- Tombol tambah mahasiswa, hanya tampil di layar sm ke atas (d-none d-sm-inline-block) -->
    <a href="<?php echo base_url('mahasiswa/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Mahasiswa
    </a>
</div>

<!-- Alert Notifikasi Flashdata -->
<!-- Menampilkan pesan sukses jika ada flashdata 'success' dari controller -->
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> <?php echo $this->session->flashdata('success'); ?>
        <!-- Tombol close untuk menutup alert secara manual -->
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Menampilkan pesan error jika ada flashdata 'error' dari controller -->
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
        <!-- Tombol close untuk menutup alert secara manual -->
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Tabel Daftar Mahasiswa menggunakan DataTables -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Mahasiswa Aktif</h6>
    </div>
    <div class="card-body">
        <!-- Wrapper responsif agar tabel bisa di-scroll horizontal di layar kecil -->
        <div class="table-responsive">
            <!-- id="dataTable" digunakan oleh plugin DataTables untuk inisialisasi otomatis -->
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Cek apakah array mahasiswa tidak kosong sebelum melakukan looping -->
                    <?php if (!empty($mahasiswa)) : ?>
                        <!-- Loop setiap data mahasiswa, $no sebagai nomor urut baris -->
                        <?php $no = 1; foreach ($mahasiswa as $mhs) : ?>
                            <tr>
                                <!-- Nomor urut, auto increment setiap iterasi -->
                                <td><?php echo $no++; ?></td>
                                <td class="text-center">
                                    <?php 
                                        // Cek apakah file foto mahasiswa tersedia di server
                                        // Jika tidak ada atau kosong, gunakan gambar default placeholder
                                        $fotoPath = './uploads/mahasiswa/' . $mhs['foto'];
                                        $fotoUrl = (file_exists($fotoPath) && !empty($mhs['foto'])) ? base_url('uploads/mahasiswa/' . $mhs['foto']) : base_url('assets/start/img/undraw_profile.svg');
                                    ?>
                                    <!-- onerror sebagai fallback jika gambar gagal dimuat di browser -->
                                    <img src="<?php echo $fotoUrl; ?>" alt="Foto" class="mahasiswa-foto" onerror="this.src='<?php echo base_url('assets/start/img/undraw_profile.svg'); ?>'">
                                </td>
                                <!-- htmlspecialchars untuk mencegah XSS pada output data -->
                                <td><?php echo htmlspecialchars($mhs['nim']); ?></td>
                                <td><?php echo htmlspecialchars($mhs['nama']); ?></td>
                                <td><?php echo htmlspecialchars($mhs['jurusan']); ?></td>
                                <td>
                                    <!-- Tombol edit, mengarahkan ke halaman edit berdasarkan ID mahasiswa -->
                                    <a href="<?php echo base_url('mahasiswa/edit/' . $mhs['id']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- Tombol hapus dengan konfirmasi dialog sebelum eksekusi -->
                                    <a href="<?php echo base_url('mahasiswa/hapus/' . $mhs['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <!-- Tampilkan pesan jika belum ada data mahasiswa sama sekali -->
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data mahasiswa.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
