<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{

 
require 'header.php';
require_once('../modelos/Usuario.php');
  $usuario = new Usuario();
  $rsptan = $usuario->cantidad_usuario();
  
  $reg=$rsptan->fetch_object();
  $num = $reg->nombre;

  $empleados = $usuario->empleado_por_empresa();
  $data=Array();


  while ($reg=$empleados->fetch_object()) {
    $data[]=array($reg->nombres);
  }


  $conteo = $usuario->conteo_registros_por_empresa();
  $dataConteo=Array();

  while ($reg=$conteo->fetch_object()) {
    $dataConteo[]=array($reg->CONTEO);
  }


?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="panel-body">

<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="asistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Lista asistencias </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-clock-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']!='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-green">
    
    <a href="asistenciau.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Mi lista asistencias </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-clock-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="rptasistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Reporte de asistencias </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-clock-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-orange">
    <div class="inner">
      <h4 style="font-size: 20px;">
        <strong>Empleados:   </strong>
      </h4>
      <p>Total <?php echo $num; ?></p>
    </div>
    <div class="icon">
       <i class="fa fa-users" aria-hidden="true"></i>
    </div>
    <a href="usuario.php" class="small-box-footer">Agregar <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']!='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-aqua">
    
    <a href="rptasistenciau.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Mi reporte de asistencias </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-clock-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>


<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-blue">
    
    <a href="inasistencia.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Lista Inasistencia </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-check-square-o" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<?php if ($_SESSION['tipousuario']=='Administrador') {
?>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
  <div class="small-box bg-green">
    
    <a href="horasextras.php" class="small-box-footer">
    <div class="inner">
      <h5 style="font-size: 20px;">
        <strong>Lista Horas Extras </strong>
      </h5>
      <p>Módulo</p>
    </div>
    <div class="icon">
      <i class="fa fa-hourglass" aria-hidden="true"></i>
    </div>&nbsp;
     <div class="small-box-footer">
           <i class="fa"></i>
     </div>

    </a>
  </div>
</div>
<?php } ?>

<div>
  <canvas id="myChart"></canvas>
</div>

<!--fin centro-->
      </div>
      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>


<?php
require 'footer.php'; 
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>  
  const ctx = document.getElementById('myChart');
  const lv_data = <?php echo json_encode($data); ?>;
  const lv_conteo = <?php echo json_encode($dataConteo); ?>;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: lv_data,
      datasets: [{
        label: '# de Marcaciones Aplicativo',
        data: lv_conteo,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

<?php 
}
ob_end_flush();
?>