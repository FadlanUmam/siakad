<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SIAKAD - Sistem Informasi Akademik</title>

    <!-- Font Awesome Icons -->
    <link href="<?php echo base_url('assets/start/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- SB Admin 2 Style -->
    <link href="<?php echo base_url('assets/start/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    
    <!-- Custom styling inside page -->
    <style>
        .sidebar-brand-icon i {
            transform: rotate(-15deg);
        }
        .mahasiswa-foto {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('welcome'); ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="sidebar-brand-name mx-3">SIAKAD KHS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('welcome'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <?php if ($this->session->userdata('role') !== 'mahasiswa') : ?>
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Kelola Data (CRUD)
            </div>

            <!-- Nav Item - Mahasiswa -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('mahasiswa'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Mahasiswa</span>
                </a>
            </li>

            <!-- Nav Item - Mata Kuliah -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('matakuliah'); ?>">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Data Mata Kuliah</span>
                </a>
            </li>

            <!-- Nav Item - Nilai -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('nilai'); ?>">
                    <i class="fas fa-fw fa-graduation-cap"></i>
                    <span>Data Nilai Akhir</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Laporan
            </div>

            <!-- Nav Item - Cetak KHS -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('khs'); ?>">
                    <i class="fas fa-fw fa-print"></i>
                    <span><?php echo ($this->session->userdata('role') === 'mahasiswa') ? 'Lihat & Cetak KHS' : 'Cetak KHS'; ?></span>
                </a>
            </li>

            <!-- Nav Item - Audit Log (History Nilai) -->
            <?php if ($this->session->userdata('role') !== 'mahasiswa') : ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('auditlog'); ?>">
                    <i class="fas fa-fw fa-history"></i>
                    <span>History Nilai</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Divider (Superadmin Only) -->
            <?php if ($this->session->userdata('role') === 'superadmin') : ?>
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Superadmin
            </div>

            <!-- Nav Item - Manajemen Admin -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo base_url('admin'); ?>">
                    <i class="fas fa-fw fa-user-shield"></i>
                    <span>Kelola Admin</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Title -->
                    <h5 class="m-0 font-weight-bold text-primary d-none d-sm-inline-block">Sistem Akademik Universitas</h5>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- User Info -->
                        <li class="nav-item d-flex align-items-center mr-3">
                            <?php if ($this->session->userdata('role') === 'superadmin') : ?>
                                <span class="badge badge-danger px-2 py-1 mr-2" style="font-size: 0.7rem;">SUPERADMIN</span>
                            <?php else : ?>
                                <span class="badge badge-primary px-2 py-1 mr-2" style="font-size: 0.7rem;">ADMIN</span>
                            <?php endif; ?>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Dropdown -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo htmlspecialchars($this->session->userdata('nama_lengkap')); ?>
                                </span>
                                <i class="fas fa-user-circle fa-2x text-gray-400"></i>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <div class="dropdown-item-text">
                                    <strong><?php echo htmlspecialchars($this->session->userdata('nama_lengkap')); ?></strong><br>
                                    <small class="text-muted">@<?php echo htmlspecialchars($this->session->userdata('username')); ?></small>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo base_url('login/logout'); ?>">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
