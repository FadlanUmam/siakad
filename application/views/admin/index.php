<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-shield mr-2"></i>Manajemen Admin
    </h1>
    <a href="<?php echo base_url('admin/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Admin Baru
    </a>
</div>

<!-- Alert Notifikasi -->
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <strong>Sukses!</strong> <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
<?php endif; ?>

<!-- Info Card -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Admin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($users); ?> Akun</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users-cog fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Admin -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list mr-1"></i> Daftar Semua Admin
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="50">No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th width="130">Role</th>
                        <th width="100">Status</th>
                        <th width="130">Dibuat</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)) : ?>
                        <?php $no = 1; foreach ($users as $u) : ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong>@<?php echo htmlspecialchars($u['username']); ?></strong>
                                    <?php if ($u['username'] === 'superadmin') : ?>
                                        <br><small class="text-muted"><i class="fas fa-crown text-warning"></i> Akun Utama</small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($u['nama_lengkap']); ?></td>
                                <td>
                                    <?php if ($u['role'] === 'superadmin') : ?>
                                        <span class="badge badge-danger px-3 py-2" style="font-size: 0.8rem;">
                                            <i class="fas fa-star mr-1"></i>Superadmin
                                        </span>
                                    <?php else : ?>
                                        <span class="badge badge-primary px-3 py-2" style="font-size: 0.8rem;">
                                            <i class="fas fa-user mr-1"></i>Admin
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($u['is_active']) : ?>
                                        <span class="badge badge-success px-2 py-1">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    <?php else : ?>
                                        <span class="badge badge-secondary px-2 py-1">
                                            <i class="fas fa-ban mr-1"></i>Nonaktif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?php echo date('d M Y', strtotime($u['created_at'])); ?></small>
                                </td>
                                <td>
                                    <a href="<?php echo base_url('admin/edit/' . $u['id']); ?>" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($u['username'] !== 'superadmin') : ?>
                                        <a href="<?php echo base_url('admin/toggle/' . $u['id']); ?>" 
                                           class="btn btn-<?php echo $u['is_active'] ? 'secondary' : 'success'; ?> btn-sm"
                                           title="<?php echo $u['is_active'] ? 'Nonaktifkan' : 'Aktifkan'; ?>"
                                           onclick="return confirm('Yakin ingin <?php echo $u['is_active'] ? 'menonaktifkan' : 'mengaktifkan'; ?> akun ini?')">
                                            <i class="fas fa-<?php echo $u['is_active'] ? 'ban' : 'check'; ?>"></i>
                                        </a>
                                        <a href="<?php echo base_url('admin/hapus/' . $u['id']); ?>" 
                                           class="btn btn-danger btn-sm" title="Hapus"
                                           onclick="return confirm('PERHATIAN: Yakin ingin menghapus akun @<?php echo $u['username']; ?> secara permanen?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada data admin.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
