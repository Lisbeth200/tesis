
 // Manejar el evento de envío del formulario
  $("#frmAcceso").on('submit', function(e) {
    
    e.preventDefault();
    logina = $("#logina").val();
    clavea = $("#clavea").val();
    ruca = $("#empresa").val();

    $.post("../ajax/usuario.php?op=verificar", {
      "logina": logina,
      "clavea": clavea,
      "ruca": ruca
    }, function(data) {
      console.log(data);
      if (data != "null") {
        $(location).attr("href", "escritorio.php");
      } else {
        bootbox.alert("Usuario y/o contraseña incorrectos");
      }
    });
    

    // if ($("#logina").val() == "") {
    //   bootbox.alert("Asegúrate de llenar todos los campos");
    //   return false;
    // } else {

    // }
    // if ($("#logina").val() == "" || $("#clavea").val() == "") {
    //     bootbox.alert("Seguro deseas salir?");
    //   } else {
        
    //         $(location).attr("href", "asistencia.php");
          
        
    //   }

  });


  $(document).ready(function() {
    $("#showPasswordHide a").on('click', function(event) {
        event.preventDefault();
        if($('#showPasswordHide input').attr("type") == "text"){
            $('#showPasswordHide input').attr('type', 'password');
            $('#showPasswordHide i').addClass( "fa-eye-slash" );
            $('#showPasswordHide i').removeClass( "fa-eye" );
        }else if($('#showPasswordHide input').attr("type") == "password"){
            $('#showPasswordHide input').attr('type', 'text');
            $('#showPasswordHide i').removeClass( "fa-eye-slash" );
            $('#showPasswordHide i').addClass( "fa-eye" );
        }
    });
});