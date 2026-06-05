<?php
// Memanggil file header (navbar, sidebar, css, dll)
$this->load->view('templates/header');
?>

<!-- Judul Halaman -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        Cetak Kartu Hasil St udi (KHS)
    </h1>
</div>

<!-- Card utama -->
<div class="card shadow mb-4">

    <!-- Header Card -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Pilih Mahasiswa Untuk Cetak KHS
        </h6>
    </div>

    <!-- Body Card -->
    <div class="card-body">

        <!-- Membuat tabel responsive -->
        <div class="table-responsive">

            <!-- Tabel daftar mahasiswa -->
            <table class="table table-bordered"
                   id="dataTable"
                   width="100%"
                   cellspacing="0">

                <!-- Header tabel -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Jurusan</th>
                        <th>Aksi KHS</th>
                    </tr>
                </thead>

                <tbody>

                    <!-- Cek apakah data mahasiswa tersedia -->
                    <?php if (!empty($mahasiswa)) : ?>

                        <?php
                        // Nomor urut tabel
                        $no = 1;

                        // Perulangan data mahasiswa
                        foreach ($mahasiswa as $mhs) :
                        ?>

                            <tr>

                                <!-- Nomor -->
                                <td>
                                    <?php echo $no++; ?>
                                </td>

                                <!-- Foto Mahasiswa -->
                                <td class="text-center">

                                    <?php
                                    // Menentukan lokasi file foto
                                    $fotoPath = './uploads/mahasiswa/' . $mhs['foto'];

                                    // Jika foto ada maka tampilkan foto mahasiswa
                                    // Jika tidak ada tampilkan foto default
                                    $fotoUrl =
                                        (file_exists($fotoPath) && !empty($mhs['foto']))
                                        ? base_url('uploads/mahasiswa/' . $mhs['foto'])
                                        : base_url('assets/start/img/undraw_profile.svg');
                                    ?>

                                    <img src="<?php echo $fotoUrl; ?>"
                                         alt="Foto"
                                         class="mahasiswa-foto"

                                         <!-- Jika gambar gagal dimuat -->
                                         onerror="this.src='<?php echo base_url('assets/start/img/undraw_profile.svg'); ?>'">
                                </td>

                                <!-- NIM Mahasiswa -->
                                <td>
                                    <?php echo htmlspecialchars($mhs['nim']); ?>
                                </td>

                                <!-- Nama Mahasiswa -->
                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($mhs['nama']); ?>
                                    </strong>
                                </td>

                                <!-- Jurusan Mahasiswa -->
                                <td>
                                    <?php echo htmlspecialchars($mhs['jurusan']); ?>
                                </td>

                                <!-- Tombol melihat dan mencetak KHS -->
                                <td>
                                    <a href="<?php echo base_url('khs/detail/' . $mhs['id']); ?>"
                                       class="btn btn-info btn-sm">

                                        <i class="fas fa-eye"></i>
                                        Lihat & Cetak KHS

                                    </a>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else : ?>

                        <!-- Jika data mahasiswa kosong -->
                        <tr>
                            <td colspan="6" class="text-center">
                                Belum ada data mahasiswa.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>
</div>

<?php
// Memanggil file footer
$this->load->view('templates/footer');
?>
