<?php $this->load->view('templates/header'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen Nilai Akhir</h1>
    <div>
        <a href="<?php echo base_url('nilai/sync'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm mr-2" 
           onclick="return confirm('Apakah Anda yakin ingin menyinkronkan ulang seluruh nilai huruf mahasiswa berdasarkan regulasi terbaru?')">
            <i class="fas fa-sync-alt fa-sm text-white-50"></i> Sinkronkan Nilai
        </a>
        <a href="<?php echo base_url('nilai/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Input Nilai Baru
        </a>
    </div>
</div>

<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Sukses!</strong> <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Nilai Akhir Mahasiswa</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
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
                    <?php if (!empty($nilai)) : ?>
                        <?php $no = 1; foreach ($nilai as $nl) : ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($nl['nama_mahasiswa']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($nl['nim']); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($nl['nama_mk']); ?></strong><br>
                                    <small class="badge badge-secondary"><?php echo htmlspecialchars($nl['kode_mk']); ?></small>
                                </td>
                                <td><?php echo (int) $nl['sks']; ?> SKS</td>
                                <td><?php echo (float) $nl['nilai_angka']; ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'badge-danger';
                                        if ($nl['nilai_huruf'] == 'A') $badgeClass = 'badge-success';
                                        elseif ($nl['nilai_huruf'] == 'B') $badgeClass = 'badge-primary';
                                        elseif ($nl['nilai_huruf'] == 'C') $badgeClass = 'badge-info';
                                        elseif ($nl['nilai_huruf'] == 'D') $badgeClass = 'badge-warning';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> px-3 py-2 font-weight-bold" style="font-size: 0.9rem;">
                                        <?php echo htmlspecialchars($nl['nilai_huruf']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('nilai/edit/' . $nl['id']); ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data nilai yang diinput.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
