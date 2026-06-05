<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-search mr-2"></i>Detail History Nilai #<?php echo $nilai ? $nilai['id'] : '-'; ?>
    </h1>
    <a href="<?php echo base_url('auditlog'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<!-- Info Nilai -->
<?php if ($nilai) : ?>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-info-circle mr-1"></i> Informasi Nilai Saat Ini
        </h6>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <small class="text-muted">Nilai Angka:</small><br>
                <h4 class="font-weight-bold"><?php echo $nilai['nilai_angka']; ?></h4>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Nilai Huruf:</small><br>
                <h4><span class="badge badge-primary px-3 py-2"><?php echo $nilai['nilai_huruf']; ?></span></h4>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Timeline Log -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-history mr-1"></i> Timeline Perubahan
        </h6>
    </div>
    <div class="card-body">
        <?php if (!empty($logs)) : ?>
            <?php foreach ($logs as $log) : ?>
                <div class="card mb-3 border-left-<?php 
                    echo ($log['aksi'] === 'insert') ? 'success' : 
                         (($log['aksi'] === 'update') ? 'warning' : 'danger'); ?>">
                    <div class="card-body py-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <?php 
                                    $aksiBadge = 'badge-success';
                                    if ($log['aksi'] === 'update') $aksiBadge = 'badge-warning';
                                    elseif ($log['aksi'] === 'delete') $aksiBadge = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $aksiBadge; ?> mb-2">
                                    <?php echo strtoupper($log['aksi']); ?>
                                </span>
                                <br>
                                <strong><?php echo htmlspecialchars($log['nama_lengkap']); ?></strong>
                                <small class="text-muted">(@<?php echo htmlspecialchars($log['username']); ?>)</small>
                                <br>
                                <small class="text-muted"><?php echo htmlspecialchars($log['keterangan']); ?></small>
                            </div>
                            <div class="text-right">
                                <small class="text-muted">
                                    <?php echo date('d M Y, H:i:s', strtotime($log['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                        <?php if ($log['aksi'] === 'update') : ?>
                            <div class="mt-2 p-2 bg-light rounded">
                                <small>
                                    <span class="text-danger">
                                        <i class="fas fa-minus-circle mr-1"></i>
                                        <?php echo $log['nilai_angka_lama']; ?> (<?php echo $log['nilai_huruf_lama']; ?>)
                                    </span>
                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                    <span class="text-success">
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        <?php echo $log['nilai_angka_baru']; ?> (<?php echo $log['nilai_huruf_baru']; ?>)
                                    </span>
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-gray-300 mb-3 d-block"></i>
                <p class="text-muted">Tidak ada riwayat perubahan untuk nilai ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
