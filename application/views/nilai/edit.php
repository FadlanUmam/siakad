<?php $this->load->view('templates/header'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Nilai Akhir</h1>
    <a href="<?php echo base_url('nilai'); ?>" class="btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

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
                <h6 class="m-0 font-weight-bold text-primary">Formulir Edit Nilai Mahasiswa</h6>
            </div>
            <div class="card-body">
                <?php echo form_open('nilai/edit/' . $nilai['id']); ?>
                
                    <div class="form-group">
                        <label for="mahasiswa_id" class="font-weight-bold">Pilih Mahasiswa</label>
                        <select class="form-control" id="mahasiswa_id" name="mahasiswa_id" required>
                            <?php foreach ($mahasiswa as $mhs) : ?>
                                <option value="<?php echo $mhs['id']; ?>" <?php echo ($mhs['id'] == $nilai['mahasiswa_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($mhs['nama']); ?> (NIM: <?php echo htmlspecialchars($mhs['nim']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Filter Semester -->
                    <div class="form-group">
                        <label for="filter_semester" class="font-weight-bold">Filter Semester Mata Kuliah</label>
                        <select class="form-control" id="filter_semester">
                            <option value="">-- Semua Semester --</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="matakuliah_id" class="font-weight-bold">Pilih Mata Kuliah</label>
                        <select class="form-control" id="matakuliah_id" name="matakuliah_id" required>
                            <?php foreach ($matakuliah as $mk) : ?>
                                <option value="<?php echo $mk['id']; ?>" data-semester="<?php echo (int) $mk['semester']; ?>" <?php echo ($mk['id'] == $nilai['matakuliah_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($mk['nama_mk']); ?> (<?php echo htmlspecialchars($mk['kode_mk']); ?> - <?php echo (int) $mk['sks']; ?> SKS - Semester <?php echo (int) $mk['semester']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
 
                    <div class="form-group">
                        <label for="nilai_angka" class="font-weight-bold">Nilai Angka (Skala 0 - 100)</label>
                        <input type="number" class="form-control" id="nilai_angka" name="nilai_angka" min="0" max="100" step="0.01" value="<?php echo (float) $nilai['nilai_angka']; ?>" required>
                        <small class="form-text text-muted">
                            Konversi nilai huruf otomatis diperbarui saat Anda menyimpan perubahan.
                        </small>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                    <a href="<?php echo base_url('nilai'); ?>" class="btn btn-light">Batal</a>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSemester = document.getElementById('filter_semester');
    const matakuliahSelect = document.getElementById('matakuliah_id');
    
    // Simpan opsi asli untuk referensi filter
    const originalOptions = Array.from(matakuliahSelect.options);

    // Fungsi filter mata kuliah
    function filterCourses(selectedSem) {
        const currentSelection = matakuliahSelect.value;
        matakuliahSelect.innerHTML = '';
        
        originalOptions.forEach(option => {
            if (option.value === '') {
                matakuliahSelect.appendChild(option.cloneNode(true));
            } else {
                const sem = option.getAttribute('data-semester');
                if (selectedSem === '' || sem === selectedSem) {
                    matakuliahSelect.appendChild(option.cloneNode(true));
                }
            }
        });
        
        matakuliahSelect.value = currentSelection;
    }

    // Set filter awal saat halaman dimuat
    const selectedOption = matakuliahSelect.options[matakuliahSelect.selectedIndex];
    if (selectedOption) {
        const initialSem = selectedOption.getAttribute('data-semester');
        if (initialSem) {
            filterSemester.value = initialSem;
            filterCourses(initialSem);
        }
    }

    // Filter ketika semester diubah
    filterSemester.addEventListener('change', function() {
        filterCourses(this.value);
        
        const checkOption = matakuliahSelect.options[matakuliahSelect.selectedIndex];
        if (checkOption && checkOption.value !== '') {
            const sem = checkOption.getAttribute('data-semester');
            if (this.value !== '' && sem !== this.value) {
                matakuliahSelect.value = '';
            }
        }
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>
