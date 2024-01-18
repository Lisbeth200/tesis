var tabla;

function init(){
   listar();
}




//funcion listar
function listar(){
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
			url:'../ajax/inasistencia.php?op=listar',
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


function view_image(image) {
	$("#modal-image").modal('show');
	setImage(image);
}



init();