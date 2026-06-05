<?php $this->load->view('templates/header'); ?>

<!-- Alert Notification -->
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle mr-2"></i>
        <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
<?php endif; ?>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        Dashboard SIAKAD KHS
        <small class="text-muted d-block" style="font-size: 0.7em;">
            Login sebagai: <strong><?php echo htmlspecialchars($this->session->userdata('nama_lengkap')); ?></strong>
            (<?php echo ucfirst($this->session->userdata('role')); ?>)
        </small>
    </h1>
</div>


<!-- Content Row (Statistik Card) -->
<div class="row">

    <?php if ($this->session->userdata('role') === 'mahasiswa') : ?>
        <!-- Card: IPK (GPA) -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                IPK (Indeks Prestasi Kumulatif)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $ipk; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Total SKS Lulus -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total SKS Diambil</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_sks_mhs; ?> SKS</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Program Studi -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Program Studi / Jurusan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $mahasiswa_data ? htmlspecialchars($mahasiswa_data['jurusan']) : '-'; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Card: Total Mahasiswa -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Mahasiswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int) $total_mahasiswa; ?> Orang</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Total Mata Kuliah -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Mata Kuliah</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int) $total_matakuliah; ?> Matkul</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Total Nilai Terinput -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Nilai Terinput</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo (int) $total_nilai; ?> Entri</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Welcome Content Row -->
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?php echo ($this->session->userdata('role') === 'mahasiswa') ? 'Informasi Akademik Mahasiswa' : 'Selamat Datang di SIAKAD KHS'; ?>
                </h6>
            </div>
            <div class="card-body">
                <?php if ($this->session->userdata('role') === 'mahasiswa') : ?>
                    <p>Halo <strong><?php echo htmlspecialchars($this->session->userdata('nama_lengkap')); ?></strong> (NIM: <?php echo htmlspecialchars($this->session->userdata('username')); ?>), selamat datang di portal akademik Anda. Di portal ini, Anda dapat memantau hasil belajar secara transparan.</p>
                    
                    <div class="row my-4 justify-content-center">
                        <div class="col-md-5 text-center mb-3">
                            <div class="p-4 bg-light rounded shadow-sm">
                                <i class="fas fa-file-invoice-dollar fa-4x text-primary mb-3"></i>
                                <h5>Lihat & Cetak KHS</h5>
                                <p class="small text-muted mb-3">Periksa rincian nilai mata kuliah, SKS, IP Semester, IPK, dan unduh laporan KHS resmi Anda.</p>
                                <a href="<?php echo base_url('khs'); ?>" class="btn btn-primary btn-block">
                                    <i class="fas fa-print mr-1"></i> Buka KHS Saya
                                </a>
                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <p>Sistem ini dirancang khusus untuk memenuhi kebutuhan pengelolaan data akademik kampus secara mudah dan cepat. Sistem mencakup fitur lengkap:</p>
                    
                    <div class="row my-4">
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <i class="fas fa-user-plus fa-3x text-primary mb-2"></i>
                                <h5>Kelola Mahasiswa</h5>
                                <p class="small text-muted mb-3">Pendaftaran mahasiswa & upload foto profil resmi.</p>
                                <a href="<?php echo base_url('mahasiswa'); ?>" class="btn btn-sm btn-primary">Buka Modul</a>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <i class="fas fa-book-open fa-3x text-success mb-2"></i>
                                <h5>Kelola Matkul</h5>
                                <p class="small text-muted mb-3">Manajemen data mata kuliah dan jumlah SKS.</p>
                                <a href="<?php echo base_url('matakuliah'); ?>" class="btn btn-sm btn-success">Buka Modul</a>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <i class="fas fa-pencil-alt fa-3x text-info mb-2"></i>
                                <h5>Input Nilai</h5>
                                <p class="small text-muted mb-3">Input nilai angka, konversi nilai huruf otomatis oleh sistem.</p>
                                <a href="<?php echo base_url('nilai'); ?>" class="btn btn-sm btn-info">Buka Modul</a>
                            </div>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <i class="fas fa-file-pdf fa-3x text-warning mb-2"></i>
                                <h5>Cetak KHS PDF</h5>
                                <p class="small text-muted mb-3">Lihat rincian KHS per semester, hitung IP & cetak PDF.</p>
                                <a href="<?php echo base_url('khs'); ?>" class="btn btn-sm btn-warning">Buka Laporan</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('templates/footer'); ?>
