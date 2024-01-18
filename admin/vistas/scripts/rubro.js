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
	$("#id_rub").val("");
	$("#nombre_rub").val("");
  $("#variable_rub").val("");
  $("#tipo_rub").val("");
	$("#formula_rub").val("");
  $("#calculado_rub").val("");
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
			url:'../ajax/rubro.php?op=listar',
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
     	url: "../ajax/rubro.php?op=guardaryeditar",
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

function mostrar(id_rub){
	$.post("../ajax/rubro.php?op=mostrar",{id_rub : id_rub},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre_rub").val(data.nombre_rub);
			$("#variable_rub").val(data.variable_rub);
      $("#tipo_rub").val(data.tipo_rub);
      $("#formula_rub").val(data.formula_rub);
      $("#calculado_rub").val(data.calculado_rub);
			$("#id_rub").val(data.id_rub);
		})
}

function eliminar(id_rub){
	bootbox.confirm("¿Esta seguro de eliminar este dato?", function(result){
		if (result) {
			$.post("../ajax/rubro.php?op=eliminar", {id_rub : id_rub}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function consultarUsuarios() {
    window.location.href = 'vista_consulta_usuarios.php';
}





init();
