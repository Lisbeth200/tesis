<?php
require_once "../modelos/Encabezado.php";

if (strlen(session_id()) < 1) {
    session_start();
}

$encabezado = new Encabezado();

$id_enc = isset($_POST["id_enc"]) ? $_POST["id_enc"] : "";
$nombre_enc = isset($_POST["nombre_enc"]) ? limpiarCadena($_POST["nombre_enc"]) : "";
$descripcion_enc = isset($_POST["descripcion_enc"]) ? limpiarCadena($_POST["descripcion_enc"]) : "";
$mes_enc = isset($_POST["mes_enc"]) ? limpiarCadena($_POST["mes_enc"]) : "";
$anio_enc = isset($_POST["anio_enc"]) ? limpiarCadena($_POST["anio_enc"]) : "";


switch ($_GET["op"]) {
    case 'guardaryeditar':
    if (empty($id_enc)) {
      $rspta=$encabezado->insertar($nombre_enc,$descripcion_enc,$mes_enc,$anio_enc);
      echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
    }else{
           $rspta=$encabezado->editar($id_enc,$nombre_enc,$descripcion_enc,$mes_enc,$anio_enc);
      echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
    }
      break;

    case 'eliminar':
        $rspta = $encabezado->eliminar($id_enc);
        echo $rspta ? "Datos Eliminados correctamente" : "No se pudo eliminar los datos";
        break;

    case 'mostrar':
        $rspta = $encabezado->mostrar($id_enc);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $encabezado->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_enc . ')"><i class="fa fa-pencil"></i></button>'
                    . ' ' . '<button class="btn btn-default btn-xs" onclick="eliminar(' . $reg->id_enc . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->nombre_enc,
                "2" => $reg->descripcion_enc,
                "3" => $reg->mes_enc,
                "4" => $reg->anio_enc

            );
        }

        $results = array(
            "sEcho" => 1, // info para datatables
            "iTotalRecords" => count($data), // enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total de registros a visualizar
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}


?>
