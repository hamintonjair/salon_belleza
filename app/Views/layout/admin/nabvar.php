    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:./partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 px-0 py-0 py-lg-4 d-flex flex-row">
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <div class="navbar-brand-wrapper">
            <a class="navbar-brand brand-logo" href="#"><img src="<?php echo base_url(); ?>assets/images/logo.png" style="width:60px;" alt="logo" /></a>
            <!-- <a class="navbar-brand brand-logo" href="index.html"><img src="<?php echo base_url(); ?>assets/images/logo.svg" alt="logo" /></a> -->

            <a class="navbar-brand brand-logo-mini" href="#"><img src="<?php echo base_url(); ?>assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <?php
          $session = session();
          // Establecer la zona horaria a Colombia
          date_default_timezone_set('America/Bogota');
          // Obtener la hora actual en formato de 12 horas con AM/PM
          $current_time = date('h:i A'); // 'h:i A' muestra la hora en formato 12 horas con AM/PM

          // Obtener la fecha actual en formato completo
          $current_date = date('d M Y'); // 'd M Y' muestra la fecha como 'día Mes Año'
          ; ?>
          <h4 class="font-weight-bold mb-0 d-none d-md-block mt-1">Bienvenido de nuevo, <?php echo $session->get('name') ?></h4>
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item">
              <h4 class="mb-0 font-weight-bold d-none d-xl-block"><?php echo $current_time; ?> - <?php echo $current_date; ?> </h4>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper navbar-search-wrapper d-none d-lg-flex align-items-center">
          
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item nav-profile dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                <img src="<?php echo base_url(); ?>assets/images/faces/face5.jpg" alt="profile" />
                <span class="nav-profile-name"><?php echo $session->get('rol') ?></span>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <!-- <a class="dropdown-item">
                  <i class="mdi mdi-settings text-primary"></i>
                  Perfil
                </a> -->
                <a class="dropdown-item" href="<?php echo base_url(); ?>logout">
                  <i class="mdi mdi-exit-to-app text-primary"></i>
                  Cerrar sesion
                </a>
              </div>
            </li>

          </ul>
        </div>
      </nav>