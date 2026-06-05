<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Mata Kuliah</h1>
    <a href="<?php echo base_url('matakuliah'); ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Mata Kuliah - <?php echo htmlspecialchars($matakuliah['nama_mk']); ?></h6>
            </div>
            <div class="card-body">
                <?php echo form_open('matakuliah/edit/' . $matakuliah['id']); ?>
                
                    <div class="form-group">
                        <label for="kode_mk" class="font-weight-bold">Kode Mata Kuliah</label>
                        <input type="text" class="form-control" id="kode_mk" name="kode_mk" value="<?php echo htmlspecialchars($matakuliah['kode_mk']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_mk" class="font-weight-bold">Nama Mata Kuliah</label>
                        <input type="text" class="form-control" id="nama_mk" name="nama_mk" value="<?php echo htmlspecialchars($matakuliah['nama_mk']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="sks" class="font-weight-bold">Jumlah SKS</label>
                        <select class="form-control" id="sks" name="sks" required>
                            <option value="1" <?php echo ($matakuliah['sks'] == 1) ? 'selected' : ''; ?>>1 SKS</option>
                            <option value="2" <?php echo ($matakuliah['sks'] == 2) ? 'selected' : ''; ?>>2 SKS</option>
                            <option value="3" <?php echo ($matakuliah['sks'] == 3) ? 'selected' : ''; ?>>3 SKS</option>
                            <option value="4" <?php echo ($matakuliah['sks'] == 4) ? 'selected' : ''; ?>>4 SKS</option>
                            <option value="6" <?php echo ($matakuliah['sks'] == 6) ? 'selected' : ''; ?>>6 SKS</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="semester" class="font-weight-bold">Semester</label>
                        <select class="form-control" id="semester" name="semester" required>
                            <?php for ($i = 1; $i <= 8; $i++) : ?>
                                <option value="<?php echo $i; ?>" <?php echo ($matakuliah['semester'] == $i) ? 'selected' : ''; ?>>Semester <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="<?php echo base_url('matakuliah'); ?>" class="btn btn-light">Batal</a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
