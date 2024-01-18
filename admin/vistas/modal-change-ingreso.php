<style type="text/css">
  .modal-dialog {
    width: 50% !important;
  }
</style>

<div class="modal fade" id="modal-change-ingreso" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <form action="" name="form_change" id="form_change" method="POST">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Cambiar hora de <span id="sp_tipo"></span></h4>
        </div>
  
        <div class="modal-body">
  
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="txt_empleado" class="control-label">Empleado:</label>
                <input type="text" class="form-control" id="txt_empleado" disabled>
              </div>
            </div>
          </div>
  
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="txt_fecha" class="control-label">Fecha:</label>
                <input type="text" class="form-control" id="txt_fecha" disabled>
              </div>        
            </div>
          </div>
  
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">Cambiar Hora:</label>
                <input class="form-control" type="hidden" name="id_asistencia" id="id_asistencia">
                <input class="form-control" type="text" name="txt_hora" id="txt_hora">
              </div>    
  
            </div>
          </div>
  
        </div>
  
        <div class="modal-footer">
            <button class="btn btn-success" id="btn_save_hour_change" type="submit">Cambiar</button>
        </div>
  
      </div>
    </div>
  </form>
</div>