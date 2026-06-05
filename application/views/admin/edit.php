<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-edit mr-2"></i>Edit Admin
    </h1>
    <a href="<?php echo base_url('admin'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
        <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
    </a>
</div>

<!-- Alert -->
<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
<?php endif; ?>

<!-- Form Card -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-edit mr-1"></i> Edit Data Admin: @<?php echo htmlspecialchars($user['username']); ?>
        </h6>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo base_url('admin/edit/' . $user['id']); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><strong>Username</strong></label>
                        <input type="text" class="form-control" value="@<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        <small class="form-text text-muted">Username tidak bisa diubah.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password"><strong>Password Baru</strong></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Kosongkan jika tidak diubah" minlength="4">
                        </div>
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_lengkap"><strong>Nama Lengkap</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role"><strong>Role / Jabatan</strong> <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required
                                <?php echo ($user['username'] === 'superadmin') ? 'disabled' : ''; ?>>
                            <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="superadmin" <?php echo ($user['role'] === 'superadmin') ? 'selected' : ''; ?>>Superadmin</option>
                        </select>
                        <?php if ($user['username'] === 'superadmin') : ?>
                            <input type="hidden" name="role" value="superadmin">
                            <small class="form-text text-danger">
                                <i class="fas fa-lock mr-1"></i>Role Superadmin utama tidak bisa diubah.
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Info tambahan -->
            <div class="alert alert-light border mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted"><i class="fas fa-calendar mr-1"></i> Dibuat:</small><br>
                        <strong><?php echo date('d M Y, H:i', strtotime($user['created_at'])); ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted"><i class="fas fa-clock mr-1"></i> Terakhir update:</small><br>
                        <strong><?php echo date('d M Y, H:i', strtotime($user['updated_at'])); ?></strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted"><i class="fas fa-circle mr-1"></i> Status:</small><br>
                        <?php if ($user['is_active']) : ?>
                            <span class="badge badge-success">Aktif</span>
                        <?php else : ?>
                            <span class="badge badge-secondary">Nonaktif</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <a href="<?php echo base_url('admin'); ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
