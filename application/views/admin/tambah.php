<?php $this->load->view('templates/header'); ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-plus mr-2"></i>Tambah Admin Baru
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
            <i class="fas fa-edit mr-1"></i> Form Tambah Admin
        </h6>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo base_url('admin/tambah'); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username"><strong>Username</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                            </div>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Masukkan username (unik)" required
                                   pattern="[a-zA-Z0-9_]+" title="Hanya huruf, angka, dan underscore">
                        </div>
                        <small class="form-text text-muted">Hanya huruf, angka, dan underscore. Tidak bisa diubah nanti.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password"><strong>Password</strong> <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Masukkan password" required minlength="4">
                        </div>
                        <small class="form-text text-muted">Minimal 4 karakter. Password akan di-hash (bcrypt).</small>
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
                                   placeholder="Masukkan nama lengkap" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role"><strong>Role / Jabatan</strong> <span class="text-danger">*</span></label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin" selected>Admin</option>
                            <option value="superadmin">Superadmin</option>
                        </select>
                        <small class="form-text text-muted">
                            <strong>Admin:</strong> Dapat mengelola data & input nilai.<br>
                            <strong>Superadmin:</strong> Semua hak Admin + kelola akun admin lain.
                        </small>
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <a href="<?php echo base_url('admin'); ?>" class="btn btn-secondary mr-2">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Simpan Admin
                </button>
            </div>
        </form>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
