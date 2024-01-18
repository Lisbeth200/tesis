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
          <h1 class="box-title">Diccionario <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
          <div class="box-tools pull-right">

          </div>
        </div>
        <!--box-header-->
        <!--centro-->
        <div class="panel-body table-responsive" id="listadoregistros">
          <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
            <thead>
              <th>Opciones</th>
              <th>Variable</th>
              <th>Descripción</th>
              <th>Dato</th>
              <th>Tabla</th>

            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Variable</th>
              <th>Descripción</th>
              <th>Dato</th>
              <th>Tabla</th>
            </tfoot>
          </table>
        </div>
        <div class="panel-body" style="height: 400px;" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Variable</label>
              <input class="form-control" type="hidden" name="id_dic" id="id_dic">
              <input class="form-control" type="text" name="variable_dic" id="variable_dic" maxlength="50" placeholder="Nombre de la Variable" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Descripción</label>
              <input class="form-control" type="text" name="descripcion_dic" id="descripcion_dic" maxlength="300" placeholder="Descripción" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Dato</label>
              <input class="form-control" type="text" name="dato_dic" id="dato_dic" maxlength="300" placeholder="Ingrese el dato donde se encuentra" required>
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Tabla</label>
                <input class="form-control" type="text" name="tabla_dic" id="tabla_dic" maxlength="400" placeholder="Ingrese la tabla" required>
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






 <script src="scripts/diccionario.js">

 </script>
 <?php
}

ob_end_flush();
  ?>
