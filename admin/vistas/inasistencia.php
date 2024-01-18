<?php 
date_default_timezone_set('America/Lima');

//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
 ?>
    <div class="content-wrapper">

      <section class="content">


        <div class="row">
          <div class="col-md-12">
            <div class="box">

              <div class="box-header with-border">
                <h1 class="box-title">Usuarios</h1>
                  <div class="box-tools pull-right">
                    <button class="btn btn-success btn-xs" onclick="listar()"><i class="fa fa-refresh"></i></button>
                  </div>                  
              </div>

              <div class="row">
                <div class="col-md-8">
                  <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <label>Fecha Consultar</label>
                    <input type="date" class="form-control" 
                    onchange="listar(event);"
                    name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                  </div>               
                </div>

              </div>

              <div class="panel-body table-responsive" id="listadoregistros">
                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                  <thead>
                    <th>Opciones</th>
                    <th>Nombres</th>
                    <th>Área</th>
                    <th>Fecha Hora</th>
                    <th>Observaci&oacute;n</th>
                  </thead>
                  <tbody>
                  </tbody>
 
                </table>
              </div>


            </div>
          </div>
        </div>


      </section>
  </div>
<?php 

require 'modal-view-image.php';
require 'footer.php';
 ?>
 <script src="scripts/inasistencia.js"></script>
 <?php 
}

ob_end_flush();
  ?>
