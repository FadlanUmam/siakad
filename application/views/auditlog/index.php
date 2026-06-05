<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-history mr-2"></i>History Perubahan Nilai
    </h1>
</div>

<!-- Info Alert -->
<div class="alert alert-info border-left-info">
    <i class="fas fa-info-circle mr-2"></i>
    <strong>Audit Trail:</strong> Halaman ini mencatat seluruh aktivitas input, update, dan hapus nilai oleh setiap admin. 
    Setiap perubahan dapat dilacak untuk mencegah kecurangan.
</div>

<!-- Statistik -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Log</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($logs); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Insert</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo count(array_filter($logs, function($l){ return $l['aksi'] === 'insert'; })); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-plus-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Update</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo count(array_filter($logs, function($l){ return $l['aksi'] === 'update'; })); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-edit fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Delete</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo count(array_filter($logs, function($l){ return $l['aksi'] === 'delete'; })); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trash fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Log -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-table mr-1"></i> Riwayat Lengkap Perubahan Nilai
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th width="160">Waktu</th>
                        <th>Admin</th>
                        <th width="80">Aksi</th>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Nilai Lama</th>
                        <th>Nilai Baru</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)) : ?>
                        <?php $no = 1; foreach ($logs as $log) : ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <small>
                                        <i class="fas fa-calendar mr-1 text-muted"></i>
                                        <?php echo date('d M Y', strtotime($log['created_at'])); ?><br>
                                        <i class="fas fa-clock mr-1 text-muted"></i>
                                        <?php echo date('H:i:s', strtotime($log['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($log['nama_lengkap']); ?></strong><br>
                                    <small class="text-muted">@<?php echo htmlspecialchars($log['username']); ?></small>
                                </td>
                                <td class="text-center">
                                    <?php 
                                        $aksiBadge = 'badge-info';
                                        $aksiIcon = 'fas fa-plus';
                                        if ($log['aksi'] === 'update') { $aksiBadge = 'badge-warning'; $aksiIcon = 'fas fa-edit'; }
                                        elseif ($log['aksi'] === 'delete') { $aksiBadge = 'badge-danger'; $aksiIcon = 'fas fa-trash'; }
                                        elseif ($log['aksi'] === 'insert') { $aksiBadge = 'badge-success'; $aksiIcon = 'fas fa-plus'; }
                                    ?>
                                    <span class="badge <?php echo $aksiBadge; ?> px-2 py-1">
                                        <i class="<?php echo $aksiIcon; ?> mr-1"></i>
                                        <?php echo strtoupper($log['aksi']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($log['mahasiswa_nama']); ?></td>
                                <td><?php echo htmlspecialchars($log['matakuliah_nama']); ?></td>
                                <td class="text-center">
                                    <?php if ($log['nilai_angka_lama'] !== NULL) : ?>
                                        <span class="badge badge-light border px-2 py-1" style="font-size: 0.85rem;">
                                            <?php echo $log['nilai_angka_lama']; ?> (<?php echo $log['nilai_huruf_lama']; ?>)
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($log['nilai_angka_baru'] !== NULL) : ?>
                                        <span class="badge badge-light border px-2 py-1" style="font-size: 0.85rem;">
                                            <?php echo $log['nilai_angka_baru']; ?> (<?php echo $log['nilai_huruf_baru']; ?>)
                                        </span>
                                    <?php else : ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($log['keterangan']); ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3 d-block"></i>
                                Belum ada riwayat perubahan nilai.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
