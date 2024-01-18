

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SISCOM | Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../public/css/blue.css">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

  </head>
  <body class="hold-transition login-page">
    <?php require '../config/Conexion.php'; ?>
    <div class="login-box">
      <div class="login-logo">
       <a href="login.php"><b>SISCOM</b> Admin</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Ingrese sus datos de Acceso</p>
        <form method="post" id="frmAcceso">
          <div class="form-group has-feedback">
            <input type="text" id="logina" name="logina" required class="form-control" placeholder="Usuario">
            <span class="fa fa-user form-control-feedback"></span>
          </div>

          <div class="form-group has-feedback input-group" id="showPasswordHide">
            <input class="form-control" type="password" id="clavea" required name="clavea" placeholder="Password">
            <div class="input-group-addon">
              <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
            </div>
          </div>

          <div class="form-group has-feedback">
            <input type="number" id="empresa" required name="empresa" class="form-control" placeholder="RUC">
            <span class="fa fa-file-text form-control-feedback"></span>
          </div>

          <div class="row">
            <div class="col-xs-8">

            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat"> Ingresar</button>
            </div><!-- /.col -->


        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery -->
    <script src="../public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../public/js/bootstrap.min.js"></script>
     <!-- Bootbox -->
    <script src="../public/js/bootbox.min.js"></script>

    <script type="text/javascript" src="scripts/login.js"></script>


  </body>
</html>