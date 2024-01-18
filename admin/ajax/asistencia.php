<?php 
require_once "../modelos/Asistencia.php";
if (strlen(session_id())<1) 
	session_start();
$asistencia=new Asistencia();

$codigo_persona=isset($_POST["codigo_persona"])? limpiarCadena($_POST["codigo_persona"]):"";
$iddepartamento=isset($_POST["iddepartamento"])? limpiarCadena($_POST["iddepartamento"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		$result=$asistencia->verificarcodigo_persona($codigo_persona);

      	if($result > 0) {
		date_default_timezone_set('America/Bogota');
      		$fecha = date("Y-m-d");
			$hora = date("H:i:s");

			$result2=$asistencia->seleccionarcodigo_persona($codigo_persona);
			   
     		$par = abs($result2%2);

          if ($par == 0){ 
                              
                $tipo = "Entrada";
        		$rspta=$asistencia->registrar_entrada($codigo_persona,$tipo);
    			//$movimiento = 0;
    			echo $rspta ? '<h3><strong>Nombres: </strong> '. $result['nombre'].' '.$result['apellidos'].'</h3><div class="alert alert-success"> Ingreso registrado '.$hora.'</div>' : 'No se pudo registrar el ingreso';
   		  }else{ 
                $tipo = "Salida";
         		$rspta=$asistencia->registrar_salida($codigo_persona,$tipo);
     			//$movimiento = 1;
     			echo $rspta ? '<h3><strong>Nombres: </strong> '. $result['nombre'].' '.$result['apellidos'].'</h3><div class="alert alert-danger"> Salida registrada '.$hora.'</div>' : 'No se pudo registrar la salida';             
        } 
        } else {
		         echo '<div class="alert alert-danger">
                       <i class="icon fa fa-warning"></i> No hay empleado registrado con esa código...!
                         </div>';
        }

	break;

	case 'mostrar':
		// $rspta=$asistencia->mostrar($idasistencia);
		// echo json_encode($rspta);
	break;

	case 'listar':

		$fecha = $_GET["fecha"];
	
		$rspta=$asistencia->listar($fecha);
		//declaramos un array
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$disabled = "";
			if ( !$reg->latitude || !$reg->longitude ) {
				$disabled = "disabled";
			}

			$disableImage = "";
			if ( ! $reg->image) {
				$disableImage = "disabled";
			}

			$data[]=array(
				"0"=>'<button class="btn btn-warning btn-xs" '.$disabled.'><i class="fa fa-location-arrow" onclick="view_map('.$reg->latitude.','.$reg->longitude.')"></i></button>'.' '.
						'<button class="btn btn-info btn-xs" '.$disableImage.' onclick="view_image('.  "'"  .$reg->image. "'" .')"><i class="fa fa-camera"></i></button>'. ' '.
						'<button class="btn btn-default btn-xs" onclick="change_ingreso('.$reg->idasistencia.','."'".$reg->nombres."'".','."'".$reg->fecha_hora."'".','."'".$reg->tipo."'".','."'".$reg->hora."'".')"><i class="fa fa-cog"></i></button>',
				"1"=>$reg->nombre . ' '.$reg->apellidos,
				"2"=>$reg->departamento,
				"3"=>$reg->fecha_hora,
				"4"=>$reg->tipo,
				"5"=>$reg->fecha
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'listaru':
    	$idusuario=$_SESSION["idusuario"];
		$rspta=$asistencia->listaru($idusuario);
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>'<button class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>',
				"1"=>$reg->codigo_persona,
				"2"=>$reg->nombre,
				"3"=>$reg->departamento,
				"4"=>$reg->fecha_hora,
				"5"=>$reg->tipo,
				"6"=>$reg->fecha
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'listar_asistencia':
		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$codigo_persona=$_SESSION["codigo_persona"]; 
		$rspta=$asistencia->listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona);
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>$reg->nombre .' '.$reg->apellidos,
				"1"=>$reg->departamento,
				"2"=>$reg->ENTRADA,
				"3"=>$reg->SALIDA,
				"4"=>$reg->horas_trabajadas
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'listar_asistenciau':
		$fecha_inicio=$_REQUEST["fecha_inicio"];
		$fecha_fin=$_REQUEST["fecha_fin"];
		$codigo_persona=$_SESSION["codigo_persona"]; 
		$rspta=$asistencia->listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona);
		//declaramos un array
		$data=Array();


		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"0"=>$reg->nombre .' '.$reg->apellido,
				"1"=>$reg->departamento,
				"2"=>$reg->ENTRADA,
				"3"=>$reg->SALIDA,
				"4"=>$reg->horas_trabajadas
				);
		}

		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);

	break;

	case 'selectPersona':
		require_once "../modelos/Usuario.php";
		$usuario=new Usuario();

		$rspta=$usuario->listar();

		while ($reg=$rspta->fetch_object()) {
			echo '<option value=' . $reg->codigo_persona.'>'.$reg->nombre.' '.$reg->apellidos.'</option>';
		}
	break;

	case 'saveChangeHorario' :
		$id_asistencia = isset($_POST["id_asistencia"])? limpiarCadena($_POST["id_asistencia"]):"";
		$new_horario = isset($_POST["txt_hora"])? limpiarCadena($_POST["txt_hora"]):"";

		$rspta=$asistencia->saveHorarioNew($id_asistencia,$new_horario);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
				"code"=>$reg->cod_rep,
				"sms"=>$reg->mensj_resp,
				);
		}		

		echo json_encode($data);
	break;

	case 'listar_mapa':
		$fecha = $_POST["fecha"];
		$rspta=$asistencia->listar($fecha);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {

		$data[]=array(
				"nombres"=>$reg->nombre . ' '.$reg->apellidos,
				"latitude"=>$reg->latitude,
				"longitude"=>$reg->longitude,
				"fecha_hora"=>$reg->fecha_hora
				);
		}
		echo json_encode($data);

	break;	
}
?>