<?php
require_once "../modelos/Diccionario.php";

if (strlen(session_id()) < 1) {
    session_start();
}

$diccionario = new Diccionario();

$id_dic = isset($_POST["id_dic"]) ? $_POST["id_dic"] : "";
$variable_dic = isset($_POST["variable_dic"]) ? limpiarCadena($_POST["variable_dic"]) : "";
$descripcion_dic = isset($_POST["descripcion_dic"]) ? limpiarCadena($_POST["descripcion_dic"]) : "";
$dato_dic = isset($_POST["dato_dic"]) ? limpiarCadena($_POST["dato_dic"]) : "";
$tabla_dic = isset($_POST["tabla_dic"]) ? limpiarCadena($_POST["tabla_dic"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        $respuesta = null;

        if (empty($id_dic)) {
            $respuesta = $diccionario->insertar($variable_dic, $descripcion_dic, $dato_dic, $tabla_dic);
        } else {
            $respuesta = $diccionario->editar($id_dic, $variable_dic, $descripcion_dic, $dato_dic, $tabla_dic);
        }

        echo ($respuesta) ? "Datos registrados/actualizados correctamente" : "No se pudieron guardar/actualizar los datos";
        break;

    case 'eliminar':
        $respuesta = $diccionario->eliminar($id_dic);
        echo ($respuesta) ? "Datos eliminados correctamente" : "No se pudieron eliminar los datos";
        break;

    case 'mostrar':
        $resultado = $diccionario->mostrar($id_dic);
        echo json_encode($resultado);
        break;


    case 'listar':
        $resultado = $diccionario->listar();
        $data = [];

        while ($reg = $resultado->fetch_object()) {
            $data[] = [
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_dic . ')"><i class="fa fa-pencil"></i></button>'
                    . ' ' . '<button class="btn btn-default btn-xs" onclick="eliminar(' . $reg->id_dic . ')"><i class="fa fa-trash"></i></button>',
                "1" => $reg->variable_dic,
                "2" => $reg->descripcion_dic,
                "3" => $reg->dato_dic,
                "4" => $reg->tabla_dic
            ];
        }

        $results = [
            "sEcho" => 1, // info para datatables
            "iTotalRecords" => count($data), // enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), // enviamos el total de registros a visualizar
            "aaData" => $data
        ];
        echo json_encode($results);
        break;
}

switch ($_GET["op"]) {
    // ... (código existente)

    case 'obtenerDatos':
        $rspta = $diccionario->listar(); // Asegúrate de que el método listar() está devolviendo datos
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "variable_dic" => $reg->variable_dic,
                "descripcion_dic" => $reg->descripcion_dic
            );
        }
        echo json_encode($data);
        break;
}
?>
