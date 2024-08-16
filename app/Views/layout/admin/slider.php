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
  <div class="container-scroller d-flex">
    <!-- partial:./partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
      <ul class="nav">
        <li class="nav-item sidebar-category">
          <p>Navigation</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url(); ?>dashboard">
            <i class="mdi mdi-calendar-check menu-icon"></i> <!-- Ícono de agenda -->
            <span class="menu-title">Agenda</span>
          </a>
        </li>

        <li class="nav-item sidebar-category">
          <p>Módulos</p>
          <span></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="mdi mdi-palette menu-icon"></i>
            <span class="menu-title">Administración</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('empresa'); ?>">Empresa</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('Users'); ?>">Usuarios</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('pagos'); ?>">Pagos</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('report'); ?>">Reportes</a></li>

            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url(); ?>Cliente">
            <i class="mdi mdi-account menu-icon"></i>
            <span class="menu-title">Clientes</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url(); ?>Empleado">
            <i class="mdi mdi-account-multiple menu-icon"></i>
            <span class="menu-title">Empleado</span>
          </a>
        </li>
        <!-- 
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url(); ?>Empleado">
          <i class="mdi mdi-account-multiple menu-icon"></i>

          <span class="menu-title">Empleado</span>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
            <i class="mdi mdi-package-variant menu-icon"></i>
            <span class="menu-title">Servicios</span>
            <i class="menu-arrow"></i>
          </a>
          <div class="collapse" id="auth">
            <ul class="nav flex-column sub-menu">
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('servic'); ?>"> Servicios</a></li>
              <li class="nav-item"> <a class="nav-link" href="<?php echo base_url('product'); ?>"> Productos</a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('turnos'); ?>">
            <i class="mdi mdi-checkbox-multiple-marked-circle menu-icon"></i>
            <span class="menu-title">Turnos</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('finalizados'); ?>">
            <i class="mdi mdi-cloud-print-outline menu-icon"></i>
            <span class="menu-title">Facturas </span>
          </a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url('ventas'); ?>">
            <i class="mdi mdi-cart menu-icon"></i>
            <span class="menu-title">Ventas </span>
          </a>
        </li> -->
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
      </ul>
    </nav>