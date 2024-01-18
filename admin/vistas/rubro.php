<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.php");
}else{


require 'header.php';


 ?>

 <?php
// ... (código PHP existente)

// Obtener datos del diccionario
require_once "../modelos/Diccionario.php";
$diccionario = new Diccionario();
$datosDiccionario = $diccionario->listar();
?>


 <style>
         table {
             width: 50%;
             margin: 20px;
         }

         th, td {
             border: 1px solid #ddd;
             padding: 8px;
             text-align: center;
         }

         th {
             background-color: #f2f2f2;
         }

         tbody tr {
             cursor: grab;
         }

         tbody tr:hover {
             background-color: #f5f5f5;
         }

         #formula_rub {
             border: 2px dashed #aaa;
             padding: 10px;
             margin-top: 20px;
         }
     </style>

    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h1 class="box-title">Rubro <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
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
              <th>Variable</th>
              <th>Tipo</th>
              <th>Fórmula</th>
              <th>Calculado</th>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Variable</th>
              <th>Tipo</th>
              <th>Fórmula</th>
              <th>Calculado</th>
            </tfoot>
          </table>
        </div>
        <div class="panel-body" style="height: 400px;" id="formularioregistros">
          <form action="" name="formulario" id="formulario" method="POST">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Nombre</label>
                    <input class="form-control" type="hidden" name="id_rub" id="id_rub">
                    <input class="form-control" type="text" name="nombre_rub" id="nombre_rub" maxlength="50" placeholder="Nombre" required>
                  </div>
                  <div class="form-group">
                    <label for="">Variable</label>
                    <input class="form-control" type="text" name="variable_rub" id="variable_rub" maxlength="50" placeholder="Variable" required>
                  </div>
                  <div class="form-group">
                    <label for="">Seleccione una opción</label>

                    <select class="form-control" name="tipo_rub" id="tipo_rub">
                      <option value="ingreso">Ingreso</option>
                      <option value="egreso">Egreso</option>
                    </select>
                  </div>
                  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                  <div class="form-group">
                      <label for="">Calculado</label> &nbsp;&nbsp;

                      <input type="checkbox"  name="calculado_rub" id="calculado_rub" placeholder="Calculado">

                  </div>
                  <div class="form-group" id="formula_rub" style="display: none;">
                      <label for="formula_rub_input">Fórmula</label>
                      <input class="form-control" type="text" name="formula_rub" id="formula_rub_input" maxlength="256" placeholder="Formula">
                  </div>


                </div>
                <div class="col-md-6">
                  <h4 class="text-center">DICCIONARIO DE DATOS</h4>
                  <table style="background-color:#A6F9DA" id="drag-and-drop-table" class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <th style="background-color:#F97777" >Nombre de la Variable</th>
                    <th style="background-color:#F97777">Descripción</th>
                </thead>
                <tbody>
                  <?php foreach ($datosDiccionario as $filaDiccionario): ?>
              <tr>
                  <td draggable="true" ondragstart="drag(event)">
                      <?php echo $filaDiccionario['variable_dic']; ?>
                  </td>
                  <td><?php echo $filaDiccionario['descripcion_dic']; ?></td>
              </tr>
            <?php endforeach; ?>

                </tbody>
            </table>



              <script>
                  function drag(event) {
                      event.dataTransfer.setData("text", event.target.innerText);
                  }

                  document.addEventListener('DOMContentLoaded', function () {
                      var formulaRubInput = document.getElementById('formula_rub_input');

                      // Permitir soltar en el campo de fórmula
                      formulaRubInput.addEventListener('dragover', function (event) {
                          event.preventDefault();
                      });

                      formulaRubInput.addEventListener('drop', function (event) {
                          event.preventDefault();
                          var draggedText = event.dataTransfer.getData("text");

                          // Agregar el texto al campo de fórmula
                        //  formulaRubInput.value = draggedText;

                          //PARA QUE SE PUEDA SEGUIR INSERTANDO
                         formulaRubInput.value += " " + draggedText;
                      });
                  });
                </script>
                </div>
              </div>
            </div>


            <script>
                $(document).ready(function() {
                    $('#calculado_rub').change(function() {
                        $('#formula_rub').toggle();
                    });
                });
            </script>
            <br><br><br>

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

  <!-- jsDelivr :: Sortable :: Latest (https://www.jsdelivr.com/package/npm/sortablejs) -->
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
  <script src="js/main.js"></script>

<?php


require 'footer.php';
 ?>
 <script src="scripts/rubro.js"></script>
 <?php
}

ob_end_flush();
  ?>
