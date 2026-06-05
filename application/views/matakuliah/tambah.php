<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tambah Mata Kuliah</h1>
    <a href="<?php echo base_url('matakuliah'); ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Tambah Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <?php echo form_open('matakuliah/tambah'); ?>
                
                    <div class="form-group">
                        <label for="kode_mk" class="font-weight-bold">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" placeholder="Contoh: INF-101" required>
                        <small class="form-text text-muted">Kode MK harus unik (misal: INF-101, INF-202).</small>
                    </div>

                    <div class="form-group">
                        <label for="nama_mk" class="font-weight-bold">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" placeholder="Masukkan nama mata kuliah" required>
                    </div>

                    <div class="form-group">
                        <label for="sks" class="font-weight-bold">Jumlah SKS</label>
                        <select class="form-control" id="sks" name="sks" required>
                            <option value="">-- Pilih Jumlah SKS --</option>
                            <option value="1">1 SKS</option>
                            <option value="2">2 SKS</option>
                            <option value="3">3 SKS</option>
                            <option value="4">4 SKS</option>
                            <option value="6">6 SKS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="semester" class="font-weight-bold">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <option value="">-- Pilih Semester --</option>
                            <?php for ($i = 1; $i <= 8; $i++) : ?>
                                <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                    <a href="<?php echo base_url('matakuliah'); ?>" class="btn btn-light">Batal</a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
