<style type="text/css">
  .modal-dialog {
    width: 70% !important;
  }

.img-css{
  width: 70% !important;
  height: 25% !important;
}

</style>

<div class="modal fade" id="modal-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Imange Tomada</h4>
      </div>

      <div class="modal-body">

        <img src="" id="image-asistencia" class="img-css">

      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
   function setImage(image) {
    $("#image-asistencia").attr("src", 'data:image/png;base64,'+image);
   }
</script> 

