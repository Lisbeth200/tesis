<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class HorasExtras{


	//implementamos nuestro constructor
public function __construct(){

}


//listar registros
public function listar($fecha){
	$idempresa=$_SESSION["idempresa"];
	
	$sql = "call SP_LISTA_ASISTENCIAS('$idempresa', '$fecha', 'TIPO_HEXTRAS')";
	return ejecutarConsulta($sql);
}

}

 ?>
