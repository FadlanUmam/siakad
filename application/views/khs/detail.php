<?php
// Memanggil template header
$this->load->view('templates/header');
?>

<!-- Judul halaman -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">

    <h1 class="h3 mb-0 text-gray-800">
        Detail Kartu Hasi l Studi (KHS)
    </h1>

    <!-- Tombol kembali sesuai role user -->
    <?php if ($this->session->userdata('role') === 'mahasiswa') : ?>

        <!-- Jika login sebagai mahasiswa -->
        <a href="<?php echo base_url('welcome'); ?>"
           class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i>
            Kembali ke Dashboard
        </a>

    <?php else : ?>

        <!-- Jika login sebagai admin -->
        <a href="<?php echo base_url('khs'); ?>"
           class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i>
            Kembali
        </a>

    <?php endif; ?>

</div>


<!-- NOTIFIKASI SUCCESS -->

<?php if ($this->session->flashdata('success')) : ?>

    <div class="alert alert-success alert-dismissible fade show">

        <!-- Menampilkan pesan sukses -->
        <i class="fas fa-check-circle mr-2"></i>
        <?php echo $this->session->flashdata('success'); ?>

        <button type="button"
                class="close"
                data-dismiss="alert">
            <span>&times;</span>
        </button>

    </div>

<?php endif; ?>


<!-- NOTIFIKASI ERROR -->

<?php if ($this->session->flashdata('error')) : ?>

    <div class="alert alert-danger alert-dismissible fade show">

        <!-- Menampilkan pesan error -->
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?php echo $this->session->flashdata('error'); ?>

        <button type="button"
                class="close"
                data-dismiss="alert">
            <span>&times;</span>
        </button>

    </div>

<?php endif; ?>

<div class="row">


    <!-- PROFIL MAHASISWA -->
  
    <div class="col-xl-4 col-lg-5 col-12">

        <div class="card shadow mb-4">

            <!-- Header Card -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Informasi Mahasiswa
                </h6>
            </div>

            <div class="card-body text-center">

                <?php

                // Menentukan lokasi file foto
                $fotoPath = './uploads/mahasiswa/' . $mahasiswa['foto'];

                // Jika foto tersedia gunakan foto mahasiswa
                // Jika tidak gunakan foto default
                $fotoUrl =
                    (file_exists($fotoPath) && !empty($mahasiswa['foto']))
                    ? base_url('uploads/mahasiswa/' . $mahasiswa['foto'])
                    : base_url('assets/start/img/undraw_profile.svg');

                ?>

                <!-- Foto Mahasiswa -->
                <img src="<?php echo $fotoUrl; ?>"
                     alt="Foto"
                     class="img-thumbnail rounded-circle mb-3"
                     style="width:150px;height:150px;object-fit:cover;"

                      Jika gambar gagal dimuat 
                     onerror="this.src='<?php echo base_url('assets/start/img/undraw_profile.svg'); ?>'">

                <!-- Nama Mahasiswa -->
                <h4 class="font-weight-bold text-gray-900">
                    <?php echo htmlspecialchars($mahasiswa['nama']); ?>
                </h4>

                <!-- NIM -->
                <p class="text-primary font-weight-bold mb-1">
                    <?php echo htmlspecialchars($mahasiswa['nim']); ?>
                </p>

                <!-- Jurusan -->
                <p class="text-muted small">
                    <?php echo htmlspecialchars($mahasiswa['jurusan']); ?>
                </p>

                <!-- Form upload foto hanya untuk mahasiswa -->
                <?php if ($this->session->userdata('role') === 'mahasiswa') : ?>

                    <hr>

                    <form action="<?php echo base_url('khs/upload_foto'); ?>"
                          method="POST"
                          enctype="multipart/form-data">

                        <!-- Input upload foto -->
                        <div class="form-group mb-2">

                            <label class="small font-weight-bold text-muted">
                                Ubah Foto Profil
                            </label>

                            <input type="file"
                                   name="foto"
                                   class="form-control-file form-control-sm"
                                   accept="image/*"
                                   required>

                        </div>

                        <!-- Tombol upload -->
                        <button type="submit"
                                class="btn btn-sm btn-primary btn-block">

                            <i class="fas fa-upload"></i>
                            Unggah Foto

                        </button>

                    </form>

                <?php endif; ?>

            </div>
        </div>
    </div>

    
    <!-- DATA NILAI KHS -->

    <div class="col-xl-8 col-lg-7 col-12">

        <div class="card shadow mb-4">

            <!-- Header Card -->
            <div class="card-header py-3 d-flex justify-content-between">

                <h6 class="m-0 font-weight-bold text-primary">
                    Nilai Semester
                </h6>

                <!-- Filter Semester -->
                <form method="GET" class="form-inline">

                    <label class="mr-2">
                        Semester:
                    </label>

                    <!-- Dropdown semester -->
                    <select name="semester"
                            class="form-control form-control-sm"
                            onchange="this.form.submit()">

                        <?php if (empty($list_semester)) : ?>

                            <!-- Jika belum ada nilai -->
                            <option value="">
                                Tidak ada nilai
                            </option>

                        <?php else : ?>

                            <!-- Menampilkan daftar semester -->
                            <?php foreach ($list_semester as $sem) : ?>

                                <option value="<?php echo $sem['semester']; ?>"
                                    <?php echo ($selected_semester == $sem['semester']) ? 'selected' : ''; ?>>

                                    Semester <?php echo $sem['semester']; ?>

                                </option>

                            <?php endforeach; ?>

                        <?php endif; ?>

                    </select>

                </form>

            </div>

            <div class="card-body">

                
                <!-- TABEL NILAI -->
                
                <div class="table-responsive">

                    <table class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode MK</th>
                                <th>Mata Kuliah</th>
                                <th>SKS (K)</th>
                                <th>Nilai Huruf (N)</th>
                                <th>Bobot (B)</th>
                                <th>Mutu (K × B)</th>
                            </tr>
                        </thead>

                        <tbody>

                            <!-- Jika data nilai ini tersedia -->
                            <?php if (!empty($khs_list)) : ?>

                                <?php $no = 1; ?>

                                <?php foreach ($khs_list as $khs) : ?>

                                    <tr>

                                        <!-- Nomor -->
                                        <td><?php echo $no++; ?></td>

                                        <!-- Kode MataKuliah -->
                                        <td>
                                            <span class="badge badge-secondary">
                                                <?php echo htmlspecialchars($khs['kode_mk']); ?>
                                            </span>
                                        </td>

                                        <!-- Nama MK -->
                                        <td>
                                            <?php echo htmlspecialchars($khs['nama_mk']); ?>
                                        </td>

                                        <!-- SKS -->
                                        <td>
                                            <?php echo $khs['sks']; ?>
                                        </td>

                                        <!-- Nilai Huruf -->
                                        <td>
                                            <strong>
                                                <?php echo $khs['nilai_huruf']; ?>
                                            </strong>
                                        </td>

                                        <!-- Bobot -->
                                        <td>
                                            <?php echo number_format($khs['bobot'], 2); ?>
                                        </td>

                                        <!-- Nilai Mutu -->
                                        <td>
                                            <strong>
                                                <?php echo number_format($khs['nilai_mutu'], 2); ?>
                                            </strong>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else : ?>

                                <!-- Jika belum ada nilai -->
                                <tr>
                                    <td colspan="7" class="text-center">

                                        Tidak ada nilai untuk semester yang dipilih.

                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                        
                        <!-- REKAP IPS DAN IPK -->
                        
                        <?php if (!empty($khs_list)) : ?>

                            <tfoot>

                                <!-- Total SKS -->
                                <tr>

                                    <th colspan="3" class="text-right">
                                        Total SKS (K)
                                    </th>

                                    <th>
                                        <?php echo $total_sks; ?> SKS
                                    </th>

                                    <th colspan="3"></th>

                                </tr>

                                <!-- IPS -->
                                <tr class="table-primary">

                                    <th colspan="3" class="text-right">
                                        Indeks Prestasi Semester (IPS)
                                    </th>

                                    <th colspan="4" class="text-center">

                                        IPS = <?php echo $ip; ?>

                                    </th>

                                </tr>

                                <!-- IPK -->
                                <tr class="table-success">

                                    <th colspan="3" class="text-right">
                                        Indeks Prestasi Kumulatif (IPK)
                                    </th>

                                    <th colspan="4" class="text-center">

                                        IPK = <?php echo $ipk; ?>

                                        <small>
                                            (Total SKS:
                                            <?php echo $total_sks_kumulatif; ?>)
                                        </small>

                                    </th>

                                </tr>

                            </tfoot>

                        <?php endif; ?>

                    </table>
                </div>

                <hr>

                
                <!-- ini TOMBOL CETAK KHS -->
                
                <?php if (!empty($khs_list) && !empty($selected_semester)) : ?>

                    <a href="<?php echo base_url('khs/cetak/' . $mahasiswa['id'] . '/' . $selected_semester); ?>"
                       target="_blank"
                       class="btn btn-primary btn-block">

                        <i class="fas fa-print"></i>
                        Cetak KHS (Buka Print PDF)

                    </a>

                <?php else : ?>

                    <button class="btn btn-secondary btn-block" disabled>

                        <i class="fas fa-print"></i>
                        Cetak KHS (Nilai Belum Tersedia)

                    </button>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php
// Memanggil template footer
$this->load->view('templates/footer');
?>
