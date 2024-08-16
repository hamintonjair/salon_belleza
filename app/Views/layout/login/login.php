<?php
require_once 'google-auth.php'; // Incluye la configuración de autenticación
$session = session();
// $session->destroy();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Beauty Timeless</title>
    <!-- Base CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" />

</head>

<body>
    <div class="container-scroller d-flex">
        <div class="container-fluid page-body-wrapper full-page-wrapper d-flex">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <h4>Bienvenido!</h4>
                            <h6 class="font-weight-light">Feliz en verte de nuevo!</h6>
                            <?php if(session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger">
                                    <?php echo session()->getFlashdata('error'); ?>

                                </div>
                            <?php endif; ?>
                            <form class="pt-3" id="loginForm" method="post" action="<?php echo base_url(); ?>login/validar">
                                <div class="form-group">
                                    <label for="exampleInputEmail">Correo</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-account-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="email" class="form-control form-control-lg border-left-0" id="email" name="email" placeholder="Correo" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                            <span class="input-group-text bg-transparent border-right-0">
                                                <i class="mdi mdi-lock-outline text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg border-left-0" id="password" name="password" placeholder="Password" required>
                                    </div>
                                </div>
                                <div class="my-3">
                                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">INICIAR SESIÓN</button>
                                </div>
                                <div class="mb-2">
                                    <a href="<?php echo $authUrl; ?>" class="btn btn-google btn-block auth-form-btn">
                                        <i class="mdi mdi-google mr-2"></i>Iniciar sesión con Google
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-flex flex-row">
                        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2018 All rights reserved.</p>
                        <img src="<?php echo base_url(); ?>assets/images/fondo.jpg" alt="Footer Background" class="img-fluid footer-bg-image">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Base JS -->
    <script src="<?php echo base_url(); ?>assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/off-canvas.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/hoverable-collapse.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/template.js"></script>
    <!-- Google Sign-In SDK -->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <!-- Custom JS -->
    <script src="<?php echo base_url(); ?>assets/js/auth.js"></script>
</body>
<style>
    .footer-bg-image {
        width: 100%;
        /* Asegura que la imagen cubra el ancho del contenedor */
        height: 700px;
        /* Ajusta la altura a lo que desees, por ejemplo, 300px */
        object-fit: cover;
        /* Hace que la imagen cubra el contenedor sin distorsión */
    }

    .auth-form-btn {
        width: 100%;
        /* Asegura que el botón cubra todo el ancho disponible */
        text-align: center;
        /* Centra el texto en el botón */
    }

    .btn-google {
        /* Color de fondo de Google */
        border: none;
        /* Elimina el borde del botón */
        color: #fff;
        /* Color del texto */
    }
</style>

</html>


<script>
    // Load the Google API client library and the Google Sign-In API.
</script>