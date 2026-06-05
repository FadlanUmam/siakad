<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<!-- Bagian judul halaman dan tombol kembali ke daftar mahasiswa -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Mahasiswa</h1>
    <!-- Tombol kembali ke halaman daftar mahasiswa -->
    <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <!-- Kolom form, responsif untuk berbagai ukuran layar -->
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card shadow mb-4">
            <!-- Header card menampilkan nama mahasiswa yang sedang diedit -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Mahasiswa - <?php echo htmlspecialchars($mahasiswa['nama']); ?></h6>
            </div>
            <div class="card-body">
                <!-- Form dengan multipart untuk mendukung upload file foto -->
                <?php echo form_open_multipart('mahasiswa/edit/' . $mahasiswa['id']); ?>
                
                    <!-- Field NIM: diisi otomatis dari data mahasiswa yang ada -->
                    <div class="form-group">
                        <label for="nim" class="font-weight-bold">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($mahasiswa['nim']); ?>" required>
                    </div>

                    <!-- Field Nama: diisi otomatis dari data mahasiswa yang ada -->
                    <div class="form-group">
                        <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($mahasiswa['nama']); ?>" required>
                    </div>

                    <!-- Dropdown Jurusan: option yang sesuai data mahasiswa akan otomatis terpilih -->
                    <div class="form-group">
                        <label for="jurusan" class="font-weight-bold">Jurusan / Program Studi</label>
                        <select class="form-control" id="jurusan" name="jurusan" required>
                            <!-- Kondisi ternary untuk menandai option yang sesuai dengan data mahasiswa -->
                            <option value="Teknik Informatika" <?php echo ($mahasiswa['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                            <option value="Sistem Informasi" <?php echo ($mahasiswa['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                            <option value="Rekayasa Perangkat Lunak" <?php echo ($mahasiswa['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Komputer" <?php echo ($mahasiswa['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                        </select>
                    </div>

                    <!-- Preview foto profil mahasiswa saat ini -->
                    <div class="form-group">
                        <label class="font-weight-bold">Foto Saat Ini</label><br>
                        <?php 
                            // Cek apakah file foto mahasiswa benar-benar ada di server
                            // Jika tidak ada atau kosong, tampilkan gambar default (placeholder)
                            $fotoPath = './uploads/mahasiswa/' . $mahasiswa['foto'];
                            $fotoUrl = (file_exists($fotoPath) && !empty($mahasiswa['foto'])) ? base_url('uploads/mahasiswa/' . $mahasiswa['foto']) : base_url('assets/start/img/undraw_profile.svg');
                        ?>
                        <!-- Tampilkan foto dengan ukuran thumbnail maksimal 150x150px -->
                        <img src="<?php echo $fotoUrl; ?>" alt="Foto Sekarang" class="img-thumbnail mb-2" style="max-height: 150px; max-width: 150px;">
                    </div>

                    <!-- Field upload foto baru, bersifat opsional -->
                    <div class="form-group">
                        <label for="foto" class="font-weight-bold">Unggah Foto Baru (Opsional)</label>
                        <input type="file" class="form-control-file" id="foto" name="foto">
                        <!-- Informasi tambahan mengenai aturan upload foto -->
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto profil. Format: JPG, PNG. Maksimal 2MB.</small>
                    </div>

                    <!-- Field password baru, bersifat opsional -->
                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Password Login Baru (Opsional)</label>
                        <!-- minlength="4" memastikan password minimal 4 karakter jika diisi -->
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru untuk login mahasiswa" minlength="4">
                        <!-- Informasi bahwa field ini boleh dikosongkan jika tidak ingin ganti password -->
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password akun login mahasiswa.</small>
                    </div>

                    <hr>

                    <!-- Tombol submit untuk menyimpan semua perubahan -->
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <!-- Tombol batal, kembali ke halaman daftar mahasiswa tanpa menyimpan -->
                    <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-light">Batal</a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
