<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Mahasiswa</h1>
    <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<!-- Alert Notifikasi Flashdata -->
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Mahasiswa - <?php echo htmlspecialchars($mahasiswa['nama']); ?></h6>
            </div>
            <div class="card-body">
                <?php echo form_open_multipart('mahasiswa/edit/' . $mahasiswa['id']); ?>
                
                    <div class="form-group">
                        <label for="nim" class="font-weight-bold">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo htmlspecialchars($mahasiswa['nim']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nama" class="font-weight-bold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($mahasiswa['nama']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="jurusan" class="font-weight-bold">Jurusan / Program Studi</label>
                        <select class="form-control" id="jurusan" name="jurusan" required>
                            <option value="Teknik Informatika" <?php echo ($mahasiswa['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                            <option value="Sistem Informasi" <?php echo ($mahasiswa['jurusan'] == 'Sistem Informasi') ? 'selected' : ''; ?>>Sistem Informasi</option>
                            <option value="Rekayasa Perangkat Lunak" <?php echo ($mahasiswa['jurusan'] == 'Rekayasa Perangkat Lunak') ? 'selected' : ''; ?>>Rekayasa Perangkat Lunak</option>
                            <option value="Teknik Komputer" <?php echo ($mahasiswa['jurusan'] == 'Teknik Komputer') ? 'selected' : ''; ?>>Teknik Komputer</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Foto Saat Ini</label><br>
                        <?php 
                            $fotoPath = './uploads/mahasiswa/' . $mahasiswa['foto'];
                            $fotoUrl = (file_exists($fotoPath) && !empty($mahasiswa['foto'])) ? base_url('uploads/mahasiswa/' . $mahasiswa['foto']) : base_url('assets/start/img/undraw_profile.svg');
                        ?>
                        <img src="<?php echo $fotoUrl; ?>" alt="Foto Sekarang" class="img-thumbnail mb-2" style="max-height: 150px; max-width: 150px;">
                    </div>

                    <div class="form-group">
                        <label for="foto" class="font-weight-bold">Unggah Foto Baru (Opsional)</label>
                        <input type="file" class="form-control-file" id="foto" name="foto">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah foto profil. Format: JPG, PNG. Maksimal 2MB.</small>
                    </div>

                    <div class="form-group">
                        <label for="password" class="font-weight-bold">Password Login Baru (Opsional)</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password baru untuk login mahasiswa" minlength="4">
                        <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password akun login mahasiswa.</small>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-light">Batal</a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
