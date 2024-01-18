<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{


require 'header.php';


 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h1 class="box-title">Encabezado <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
          <div class="box-tools pull-right">

          </div>
        </div>
        <!--box-header-->
        <!--centro-->
        <div class="panel-body table-responsive" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Mes</th>
              <th>Año</th>

            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Descripción</th>
              <th>Mes</th>
              <th>Año</th>

            </tfoot>
          </table>
        </div>
        <div class="panel-body" style="height: 400px;" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Nombre</label>
              <input class="form-control" type="hidden" name="id_enc" id="id_enc">
              <input class="form-control" type="text" name="nombre_enc" id="nombre_enc" maxlength="50" placeholder="Nombre" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Descripción</label>
              <input class="form-control" type="text" name="descripcion_enc" id="descripcion_enc" maxlength="300" placeholder="Descripción" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Seleccione un Mes</label>

              <select class="form-control" name="mes_enc" id="mes_enc">
                <option value="Enero">Enero</option>
                <option value="Febrero">Febrero</option>
                <option value="Marzo">Marzo</option>
                <option value="Abril">Abril</option>
                <option value="Mayo">Mayo</option>
                <option value="Junio">Junio</option>
                <option value="Julio">Julio</option>
                <option value="Agosto">Agosto</option>
                <option value="Septiembre">Septiembre</option>
                <option value="Octubre">Octubre</option>
                <option value="Noviembre">Noviembre</option>
                <option value="Diciembre">Diciembre</option>
              </select>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Año</label>
                <input class="form-control" type="date" name="anio_enc" id="anio_enc" maxlength="4" placeholder="" >

            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

              <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
            </div>
          </form>



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






 <script src="scripts/encabezado.js">

 </script>
 <?php
}

ob_end_flush();
  ?>
