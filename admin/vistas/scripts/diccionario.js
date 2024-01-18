var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })
}

//funcion limpiar
function limpiar(){
	$("#id_dic").val("");
	$("#variable_dic").val("");
  $("#descripcion_dic").val("");
  $("#dato_dic").val("");
	$("#tabla_dic").val("");

}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
}

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/diccionario.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/diccionario.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(id_dic) {
  $.post(
    "../ajax/diccionario.php?op=mostrar",
    { id_dic: id_dic },
    function (data, status) {
      data = JSON.parse(data);
      mostrarform(true);

      $("#variable_dic").val(data.variable_dic);
      $("#descripcion_dic").val(data.descripcion_dic);
      $("#dato_dic").val(data.dato_dic);
      $("#tabla_dic").val(data.tabla_dic);
      $("#id_dic").val(data.id_dic);
    }
  );
}


function eliminar(id_dic){
	bootbox.confirm("¿Esta seguro de eliminar este dato?", function(result){
		if (result) {
			$.post("../ajax/diccionario.php?op=eliminar", {id_dic : id_dic}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}




init();
