var tabla;

//funcion que se ejecuta al inicio
function init(){
	listar();

	$("#form_change").on("submit",function(e){
		save_change_horario(e);
	})	
}

function listarEmpleados() {
   $.post("../ajax/asistencia.php?op=selectPersona", function(r){
   	$("#idcliente").html(r);
   	$('#idcliente').selectpicker('refresh');
   });
}

//funcion listar
function listar(){
	console.log("holaaa");
	$fecha = $('#fecha_inicio').val();

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
			url:'../ajax/asistencia.php?op=listar',
			type: "get",
			dataType : "json",
			data:{ "fecha": $fecha },
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":10,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}
function listaru(){
	tabla=$('#tbllistadou').dataTable({
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
			url:'../ajax/asistencia.php?op=listaru',
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

function listar_asistencia(){
var  fecha_inicio = $("#fecha_inicio").val();
 var fecha_fin = $("#fecha_fin").val();
 var idcliente = $("#idcliente").val();

	tabla=$('#tbllistado_asistencia').dataTable({
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
			url:'../ajax/asistencia.php?op=listar_asistencia',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin, idcliente: idcliente},
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
function listar_asistenciau(){
var  fecha_inicio = $("#fecha_inicio").val();
 var fecha_fin = $("#fecha_fin").val();

	tabla=$('#tbllistado_asistenciau').dataTable({
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
			url:'../ajax/asistencia.php?op=listar_asistenciau',
			data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},
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

function view_map(latitude, longitude) {
	$("#modal-map").modal('show');
	init_map(latitude, longitude);
}


function view_image(image) {
	$("#modal-image").modal('show');
	setImage(image);
}


function change_ingreso(id, nombres, fecha, tipo, hora) {
	$("#sp_tipo").html(tipo);
	$("#id_asistencia").val(id);
	$("#txt_empleado").val(nombres);
	$("#txt_fecha").val(fecha);
	$("#txt_hora").val(hora);
	$("#modal-change-ingreso").modal('show');
}

function save_change_horario(c){
	c.preventDefault();//no se activara la accion predeterminada 
	$("#btn_save_hour_change").prop("disabled",true);
	var formData=new FormData($("#form_change")[0]);

	$.ajax({
		url: "../ajax/asistencia.php?op=saveChangeHorario",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(datos){
			data=JSON.parse(datos);
			if ( data[0].code == 0) {
				bootbox.alert(data[0].sms);
				tabla.ajax.reload();
				$("#modal-change-ingreso").modal('hide');
			} else {
				bootbox.alert(datos['sms']);
			}
			$("#btn_save_hour_change").prop("disabled",false);
		}
	});
}

function view_map_general() {
	$fecha = $('#fecha_inicio').val();
	var data = new FormData();
	data.append("fecha", $fecha);// getting value from form feleds 


	$.ajax({
		url: "../ajax/asistencia.php?op=listar_mapa",
		type: "POST",
		data: data,
		contentType: false,
		processData: false,
		success: function(datos){
			data=JSON.parse(datos);
			$("#modal-map-general").modal('show');
			init_map_general(data);
		}
	});
}
init();