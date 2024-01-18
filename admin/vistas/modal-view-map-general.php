<style type="text/css">
  .modal-dialog {
    width: 70% !important;
  }

  .view-map {
    height: 400px !important;
  }

</style>

<div class="modal fade" id="modal-map-general" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Ubicaci&oacute;n de Marcaci&oacute;nes General</h4>
      </div>

      <div class="modal-body">

        <div class="view-map" id="gmapGeneral">Cargando mapa...</div>

      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
   function init_map_general(data) {

      var options = {
         zoom: 6,
          center: new google.maps.LatLng(-1.9970064, -78.0362095),
         mapTypeId: google.maps.MapTypeId.ROADMAP
      };
      map = new google.maps.Map($("#gmapGeneral")[0], options);
      setMarkers(map, data)
   }

   function setMarkers(map, data) {

    data.forEach(element => {

      const marker = new google.maps.Marker({
        position: { lat: Number(element.latitude), lng: Number(element.longitude) },
        map,
        title: element.fecha_hora,
        label: element.nombres,
      });

    });

   }
</script>