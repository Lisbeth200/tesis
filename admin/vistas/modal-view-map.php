<style type="text/css">
  .modal-dialog {
    width: 70% !important;
  }

  .view-map {
    height: 400px !important;
  }

</style>

<div class="modal fade" id="modal-map" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Ubicaci&oacute;n de Marcaci&oacute;n</h4>
      </div>

      <div class="modal-body">

        <div class="view-map" id="gmap">Cargando mapa...</div>

      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
   function init_map(latitude , longitude) {
      var options = {
         zoom: 16,
         center: new google.maps.LatLng(latitude, longitude),
         mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      map = new google.maps.Map($("#gmap")[0], options);
      marker = new google.maps.Marker({
         map: map,
         position: new google.maps.LatLng(latitude, longitude)
      });
   }
</script> 

