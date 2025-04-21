<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Beauty Timeless</title>
  <!-- base:css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/sweetalert2.min.css"> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- inject:css -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/dataTables.dataTables.css">

  <!-- endinject -->
  <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/favicon.ico" />
</head>

<body>
    <script>var BASE_URL = "<?= base_url() ?>";</script>
  <div class="container-scroller d-flex">
    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Administración</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#admin" aria-expanded="false" aria-controls="admin">
          <i class="mdi mdi-palette menu-icon"></i>
            <span class="menu-title">Administración</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="admin">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('empresa'); ?>">Empresa</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('Users'); ?>">Usuarios</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('empleado'); ?>">Empleados</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('Cliente'); ?>">Clientes</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('servic'); ?>">Servicios</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('product'); ?>">Productos</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('report'); ?>">Reportes</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item sidebar-category">
          <p>Finanzas</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#finanzas" aria-expanded="false" aria-controls="finanzas">
            <i class="mdi mdi-cash-multiple menu-icon"></i>
            <span class="menu-title">Finanzas</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="finanzas">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('finanzas/ingresos'); ?>">Ingresos</a></li>
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('finanzas/egresos'); ?>">Egresos</a></li>
              <!-- <li class="nav-item"><a class="nav-link" href="<?php echo base_url('finanzas/caja'); ?>">Caja</a></li> -->
              <li class="nav-item"><a class="nav-link" href="<?php echo base_url('pagos'); ?>">Pagos</a></li>
              <!-- <li class="nav-item"><a class="nav-link" href="<?php echo base_url('finanzas/reportes'); ?>">Reportes financieros</a></li> -->
            </ul>
          </div>
        </li>
       

        <li class="nav-item sidebar-category">
          <p>Servicios</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url(); ?>dashboard">
            <i class="mdi mdi-calendar-check menu-icon"></i> <!-- Ícono de agenda -->
            <span class="menu-title">Agenda</span>
          </a>
        </li>
       
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('turnos'); ?>">
            <i class="mdi mdi-checkbox-multiple-marked-circle menu-icon"></i>
            <span class="menu-title">Turnos</span>
          </a>
        </li>  
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#authe" aria-expanded="false" aria-controls="authe">
            <i class="mdi mdi-cart menu-icon"></i>
            <span class="menu-title">Ventas</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="authe">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('vent'); ?>"> Vender</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('list'); ?>"> Ventas realizadas</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('finalizados'); ?>">
            <i class="mdi mdi-cloud-print-outline menu-icon"></i>
            <span class="menu-title">Facturas </span>
          </a>
        </li>

        <!-- Sección Manual (Ayuda) -->
        <li class="nav-item sidebar-category">
          <p>Ayuda</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('manual'); ?>">
            <i class="mdi mdi-file-document-box-outline menu-icon"></i>
            <span class="menu-title">Manual</span>
          </a>
        </li>
      </ul>
    </nav>