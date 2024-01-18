<?php 
require_once "../modelos/Inasistencia.php";
if (strlen(session_id())<1) 
	session_start();
$asistencia=new Inasistencia();



switch ($_GET["op"]) {
	
	case 'listar':

		$fecha = $_GET["fecha"];
	
		$rspta=$asistencia->listar($fecha);

		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {

			$disableImage = "";
			if ( ! $reg->image) {
				$disableImage = "disabled";
			}

			$data[]=array(
				"0"=>'<button class="btn btn-info btn-xs" '.$disableImage.' onclick="view_image('.  "'"  .$reg->image. "'" .')"><i class="fa fa-camera"></i></button>',
				"1"=>$reg->nombre . ' '.$reg->apellidos,
				"2"=>$reg->departamento,
				"3"=>$reg->fecha_hora,
				"4"=>$reg->observacion
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

}
?>