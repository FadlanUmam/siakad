<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<!-- Bagian judul halaman dan tombol kembali ke daftar mahasiswa -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Mahasiswa</h1>
    <!-- Tombol kembali ke halaman daftar mahasiswa -->
    <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <!-- Kolom form, responsif untuk berbagai ukuran layar -->
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card shadow mb-4">
            <!-- Header card formulir pendaftaran -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Pendaftaran Mahasiswa</h6>
            </div>
            <div class="card-body">
                <!-- Form dengan multipart untuk mendukung upload file foto -->
                <?php echo form_open_multipart('mahasiswa/tambah'); ?>
                
                    <!-- Field NIM: harus unik, tidak boleh sama dengan mahasiswa lain -->
                    <div class="form-group">
                        <label for="nim" class="font-weight-bold">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" class="form-control" id="nim" name="nim" placeholder="Contoh: 220101001" required>
                        <small class="form-text text-muted">NIM harus unik dan tidak boleh sama dengan mahasiswa lain.</small>
                    </div>

                    <!-- Field Nama Lengkap mahasiswa -->
                    <div class="form-group">
                        <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <!-- Dropdown pilihan jurusan / program studi -->
                    <div class="form-group">
                        <label for="jurusan" class="font-weight-bold">Jurusan / Program Studi</label>
                        <select class="form-control" id="jurusan" name="jurusan" required>
                            <!-- Option default sebagai placeholder, tidak memiliki nilai -->
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Komputer">Teknik Komputer</option>
                        </select>
                    </div>

                    <!-- Field upload foto profil mahasiswa -->
                    <div class="form-group">
                        <label for="foto" class="font-weight-bold">Foto Profil</label>
                        <input type="file" class="form-control-file" id="foto" name="foto">
                        <!-- Informasi aturan format dan ukuran file yang diizinkan -->
                        <small class="form-text text-muted">Format file yang diperbolehkan: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                    </div>

                    <!-- Field password login, bersifat opsional -->
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Password Login (Opsional)</label>
                        <!-- minlength="4" memastikan password minimal 4 karakter jika diisi -->
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password untuk akun login mahasiswa" minlength="4">
                        <!-- Informasi password default jika field dikosongkan -->
                        <small class="form-text text-muted">Biarkan kosong jika ingin menggunakan password default: <strong>mahasiswa123</strong>.</small>
                    </div>

                    <hr>

                    <!-- Tombol submit untuk menyimpan data mahasiswa baru -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <!-- Tombol batal, kembali ke halaman daftar mahasiswa tanpa menyimpan -->
                    <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-light">Batal</a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
