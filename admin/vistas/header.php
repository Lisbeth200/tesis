
 <?php
if (strlen(session_id())<1)
  session_start();
  ?>
 <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>SISCOM | ASISTENCIA </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/img/apple-touch-icon.png">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="../public/datatables/jquery.dataTables.min.css">
    <link href="../public/datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="../public/datatables/responsive.dataTables.min.css" rel="stylesheet"/>

    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-select.min.css">

  </head>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>

<!-- Your customer chat code -->
<div class="fb-customerchat"
  attribution=setup_tool
  page_id="280144326139427"
  theme_color="#0084ff"
  logged_in_greeting="Hola! deseas compartir algún sistema o descargar ?"
  logged_out_greeting="Hola! deseas compartir algún sistema o descargar ?">
</div>
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="escritorio.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>SC</b> A</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>SISCOM</b> ADMIN</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>

      <div class="navbar-header">
        <a class="navbar-brand" href="#"><?php echo $_SESSION['EMPRESA']; ?></a>
      </div>
      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../public/img/logo.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../public/img/logo.png" class="img-circle" alt="User Image">

                <p>
                  <?php echo $_SESSION['nombre'].' '.$_SESSION['departamento']; ?>
                  <small>Desarrollo de sistemas informáticos TESIS </small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <!-- <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div> -->
                <div class="pull-right">
                  <a href="../ajax/usuario.php?op=salir" class="btn btn-danger btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
<div class="user-panel">
        <div class="pull-left image">
          <img src="../public/img/logo.png" class="img-circle" style="width: 50px; height: 50px;" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['nombre']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">MENÚ DE NAVEGACIÓN</li>
                    <li><a href="escritorio.php"><i class="fa fa-dashboard"></i> <span>Escritorio</span></a></li>
                    <?php if ($_SESSION['tipousuario'] == 'Administrador'): ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-folder"></i> <span>Acceso</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                                <li><a href="tipousuario.php"><i class="fa fa-circle-o"></i> Tipo Usuario</a></li>
                                <li><a href="departamento.php"><i class="fa fa-circle-o"></i> Departamento</a></li>
                            </ul>
                        </li>
                        <li><a href="inasistencia.php"><i class="fa fa-folder"></i> <span>Inasistencias</span></a></li>
                        <li><a href="rubro.php"><i class="fa fa-rub"></i> <span>Rubro</span></a></li>
                        <li><a href="encabezado.php"><i class="fa fa-file"></i> <span>Encabezado</span></a></li>
                        <li><a href="diccionario.php"><i class="fa fa-file"></i> <span>Diccionario</span></a></li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-clock-o"></i> <span>Asistencias</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="asistencia.php"><i class="fa fa-circle-o"></i> Asistencia</a></li>
                                <li><a href="rptasistencia.php"><i class="fa fa-circle-o"></i> Reportes</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-hourglass"></i> <span>Horas Extras</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="horasextras.php"><i class="fa fa-circle-o"></i> Horas Extras</a></li>
                            </ul>
                        </li>
                    <?php elseif ($_SESSION['tipousuario'] != 'Administrador'): ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-folder"></i> <span>Mis Asistencias</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="asistenciau.php"><i class="fa fa-circle-o"></i> Asistencia</a></li>
                                <li><a href="rptasistenciau.php"><i class="fa fa-circle-o"></i> Reportes</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li><a href="https://pasantes-utc.siscom.ec/manual/manual usuario.pdf" target="_blank"><i class="fa fa-question-circle"></i> <span>Ayuda<small class="label pull-right bg-yellow">PDF</small></span></a></li>
                    <li><a href="https://siscom.ec/plantunegocio.html" target="_blank"><i class="fa fa-exclamation-circle"></i> <span>Acerca de<small class="label pull-right bg-yellow">ComCod</small></span></a></li>
                </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
