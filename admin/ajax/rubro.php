<?php
require_once "../modelos/Rubro.php";


if (strlen(session_id()) < 1) {
    session_start();
}


$rubro = new Rubro();

$id_rub = isset($_POST["id_rub"]) ? $_POST["id_rub"] : "";
$nombre_rub = isset($_POST["nombre_rub"]) ? limpiarCadena($_POST["nombre_rub"]) : "";
$variable_rub = isset($_POST["variable_rub"]) ? limpiarCadena($_POST["variable_rub"]) : "";
$tipo_rub = isset($_POST["tipo_rub"]) ? limpiarCadena($_POST["tipo_rub"]) : "";
$formula_rub = isset($_POST["formula_rub"]) ? limpiarCadena($_POST["formula_rub"]) : "";
$calculado_rub = isset($_POST["calculado_rub"]) ? limpiarCadena($_POST["calculado_rub"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($id_rub)) {
            $rspta = $rubro->insertar($nombre_rub, $variable_rub, $tipo_rub, $formula_rub, $calculado_rub);
            echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
        } else {
            $rspta = $rubro->editar($id_rub, $nombre_rub, $variable_rub, $tipo_rub, $formula_rub, $calculado_rub);
            echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
        }
        break;

    case 'eliminar':
        $rspta = $rubro->eliminar($id_rub);
        echo $rspta ? "Datos Eliminados correctamente" : "No se pudo eliminar los datos";
        break;

    case 'mostrar':
        $rspta = $rubro->mostrar($id_rub);
        echo json_encode($rspta);
        break;

    case 'consultar':
        header("Location: vista_consulta_usuarios.php"); // Redirigir a la nueva vista
        break;

    case 'listar':
        $rspta = $rubro->listar();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="mostrar(' . $reg->id_rub . ')"><i class="fa fa-pencil"></i></button>'
                    . ' ' . '<button class="btn btn-default btn-xs" onclick="eliminar(' . $reg->id_rub . ')"><i class="fa fa-trash"></i></button>'
                    . ' ' . '<button class="btn btn-primary btn-xs" onclick="consultar(' . $reg->id_rub . ')"><i class="fa fa-search"></i></button>',
                "1" => $reg->nombre_rub,
                "2" => $reg->variable_rub,
                "3" => $reg->tipo_rub,
                "4" => $reg->formula_rub,
                "5" => $reg->calculado_rub
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

switch ($_GET["op"]) {
    case 'listarUsuarios':
        $rspta = $rubro->obtenerListadoUsuarios();
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "idusuario" => $reg->idusuario,
                "sueldo_fijo" => $reg->sueldo_fijo
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
