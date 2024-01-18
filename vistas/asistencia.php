<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SISCOM | Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../admin/public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../admin/public/css/font-awesome.css">
   
    <!-- Theme style -->
    <link rel="stylesheet" href="../admin/public/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../admin/public/css/blue.css">
    <link rel="shortcut icon" href="../admin/public/img/favicon.ico">
    <style>
      #map {
        height: 300px;
        width: 100%;
      }
    </style>
  </head>
<body class="hold-transition lockscreen">

<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
<?php 
 //include '../ajax/asistencia.php' ?>
    <div name="movimientos" id="movimientos">
    </div> 



  <div class="lockscreen-logo">
    <a href="#"><b>SISCOM</b> ASISTENCIA</a>
  </div>
  <!-- User name -->
  <div class="lockscreen-name">ASISTENCIA</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="../admin/files/negocio/default.jpg" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form  action="" class="lockscreen-credentials" name="formulario" id="formulario" method="POST">
      <div class="input-group">
        <input type="password" class="form-control" name="codigo_persona" id="codigo_persona" placeholder="ID de asistencia">

        <div class="input-group-btn">
          <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
 

<!-- START LOCK SCREEN ITEM -->
<div class="lockscreen-name">HORA Y FECHA</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="../admin/files/negocio/default.jpg" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form action="" class="lockscreen-credentials" name="formulario" id="formulario" method="POST">
          <div class="input-group">
            <input type="date" id="fecha" name="fecha" value="" readonly>
            <input type="time" id="hora" name="hora" value="" readonly>
          </div>
        </form>


  </div>
  <!-- /.lockscreen-item -->
  <div class="lockscreen-name">GEOLOCALIZACIÓN</div>

<!-- START LOCK SCREEN ITEM -->
<div class="lockscreen-item">
  <!-- lockscreen image -->
  <div class="lockscreen-image">
    <img src="../admin/files/negocio/default.jpg" alt="User Image">
  </div>
  <!-- /.lockscreen-image -->

  <!-- lockscreen credentials (contains the form) -->
  <form action="" class="lockscreen-credentials" name="formulario" id="formulario" method="POST">
    <div class="input-group">
      <input type="text" id="latitud" name="latitud" value="" readonly placeholder="Latitud">
      <input type="text" id="longitud" name="longitud" value="" readonly placeholder="Longitud">
      <div class="input-group-btn">
        
      <button type="button" onclick="getLocation()" class="btn btn-primary">Obtener ubicación</button>
      </div>
    </div>
  </form>
  <!-- /.lockscreen credentials -->
  <div id="map"></div>
</div>
  <!-- /.lockscreen credentials -->
<!-- START LOCK SCREEN ITEM -->
<div class="lockscreen-name">AÑADIR IMAGEN</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="../admin/files/negocio/default.jpg" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form  action="" class="lockscreen-credentials" name="formulario" id="formulario" method="POST">
      <div class="input-group">
       
    
      <input type="file" id="imagen" name="imagen" accept="image/*" required>
       
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  
</div>

  
  

</div>

<div class="lockscreen-footer text-center">
  <a href="../admin/">Iniciar Sesión</a>
</div>
</div>
<!-- /.center -->


    <!-- jQuery -->
    <script src="../admin/public/js/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../admin/public/js/bootstrap.min.js"></script>
     <!-- Bootbox -->
    <script src="../admin/public/js/bootbox.min.js"></script>

    <script type="text/javascript" src="scripts/asistencia.js"></script>
    <script type="text/javascript">
      // Obtener la fecha y hora actual
      var currentDate = new Date();
      var currentYear = currentDate.getFullYear();
      var currentMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2);
      var currentDay = ('0' + currentDate.getDate()).slice(-2);
      var currentHour = ('0' + currentDate.getHours()).slice(-2);
      var currentMinute = ('0' + currentDate.getMinutes()).slice(-2);

      // Establecer los valores en los campos de fecha y hora
      document.getElementById("fecha").value = currentYear + "-" + currentMonth + "-" + currentDay;
      document.getElementById("hora").value = currentHour + ":" + currentMinute;
      function getLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(showPosition);
        } else {
          alert("Tu navegador no soporta la geolocalización.");
        }
      }

      // Función para mostrar la posición de geolocalización
      function showPosition(position) {
        var lat = position.coords.latitude;
        var lon = position.coords.longitude;

        document.getElementById("latitud").value = lat;
        document.getElementById("longitud").value = lon;

        // Mostrar el mapa
        var mapOptions = {
          center: { lat: lat, lng: lon },
          zoom: 15
        };
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);
        var marker = new google.maps.Marker({
          position: { lat: lat, lng: lon },
          map: map,
          title: "Tu ubicación"
        });
      }
    </script>
     <script src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap" async defer></script>

  </body>
</html> 
