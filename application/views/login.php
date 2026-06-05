<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="SIAKAD - Sistem Informasi Akademik Login">
    <title>SIAKAD - Login</title>

    <!-- Custom fonts -->
    <link href="<?php echo base_url('assets/start/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- SB Admin 2 Style -->
    <link href="<?php echo base_url('assets/start/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #1e3c72 100%);
            min-height: 100vh;
        }
        .login-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
        }
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            color: white;
        }
        .login-left i.main-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .login-left h2 {
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        .login-left p {
            opacity: 0.85;
            font-size: 0.95rem;
        }
        .login-right {
            padding: 3rem;
        }
        .login-right h1 {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }
        .login-right .subtitle {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .form-control-user {
            border-radius: 0.75rem !important;
            padding: 1.2rem 1rem !important;
            border: 2px solid #e2e8f0 !important;
            transition: border-color 0.3s;
        }
        .form-control-user:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15) !important;
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 0.75rem;
            padding: 0.85rem;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 0.5px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(102, 126, 234, 0.4);
        }
        .input-group-icon {
            position: relative;
        }
        .input-group-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            z-index: 5;
        }
        .input-group-icon input {
            padding-left: 2.75rem !important;
        }
        .alert {
            border-radius: 0.75rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center" style="min-height: 100vh; align-items: center;">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card login-card shadow-lg">
                    <div class="card-body p-0">
                        <div class="row" style="min-height: 480px;">
                            <!-- Left Panel - Branding -->
                            <div class="col-lg-5 d-none d-lg-flex login-left">
                                <i class="fas fa-university main-icon"></i>
                                <h2>SIAKAD</h2>
                                <p class="text-center">Sistem Informasi Akademik<br>Universitas</p>
                                <hr style="border-color: rgba(255,255,255,0.3); width: 60%; margin: 1.5rem 0;">
                                <div class="text-center" style="font-size: 0.8rem; opacity: 0.7;">
                                    <i class="fas fa-shield-alt mr-1"></i> Secured Login Portal
                                </div>
                            </div>
                            <!-- Right Panel - Login Form -->
                            <div class="col-lg-7 login-right d-flex flex-column justify-content-center">
                                <div class="text-center d-lg-none mb-4">
                                    <i class="fas fa-university fa-3x" style="color: #667eea;"></i>
                                </div>
                                <h1 class="h3 text-center">Selamat Datang!</h1>
                                <p class="subtitle text-center">Masuk ke panel administrasi SIAKAD</p>

                                <!-- Alert Messages -->
                                <?php if ($this->session->flashdata('error')) : ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <?php echo $this->session->flashdata('error'); ?>
                                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                <?php endif; ?>

                                <?php if ($this->session->flashdata('success')) : ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <?php echo $this->session->flashdata('success'); ?>
                                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                <?php endif; ?>

                                <form class="user" method="POST" action="<?php echo base_url('login/authenticate'); ?>">
                                    <div class="form-group input-group-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="text" class="form-control form-control-user"
                                            id="inputUsername" name="username"
                                            placeholder="Username" required autofocus>
                                    </div>
                                    <div class="form-group input-group-icon">
                                        <i class="fas fa-lock"></i>
                                        <input type="password" class="form-control form-control-user"
                                            id="inputPassword" name="password"
                                            placeholder="Password" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-login btn-user btn-block">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                                    </button>
                                </form>

                                <div class="text-center mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Hubungi Superadmin jika belum memiliki akun
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <small style="color: rgba(255,255,255,0.5);">
                        &copy; <?php echo date('Y'); ?>  GROUP — SIAKAD KHS
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="<?php echo base_url('assets/start/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/start/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/start/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/start/js/sb-admin-2.min.js'); ?>"></script>

</body>

</html>