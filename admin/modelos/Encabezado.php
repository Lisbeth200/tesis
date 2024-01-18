<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Encabezado{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre_enc,$descripcion_enc,$mes_enc,$anio_enc){
	$sql="INSERT INTO encabezado (nombre_enc,descripcion_enc,mes_enc,anio_enc) VALUES ('$nombre_enc','$descripcion_enc','$mes_enc','$anio_enc')";
	return ejecutarConsulta($sql);
}

//metodo para editar registrados
public function editar($id_enc,$nombre_enc,$descripcion_enc,$mes_enc,$anio_enc){
	$sql="UPDATE encabezado SET nombre_enc='$nombre_enc',descripcion_enc='$descripcion_enc',mes_enc='$mes_enc',anio_enc='$anio_enc'
	WHERE id_enc='$id_enc'";
	return ejecutarConsulta($sql);
}

//metodo para eliminar regitros
public function eliminar($id_enc){
	$sql="DELETE FROM encabezado WHERE id_enc='$id_enc'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($id_enc){
	$sql="SELECT * FROM encabezado WHERE id_enc='$id_enc'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM encabezado";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM encabezado";
	return ejecutarConsulta($sql);
}

public function regresaRolEncabezado($encabezado){
	$sql="SELECT nombre_enc FROM encabezado where id_enc='$encabezado'";
	return ejecutarConsulta($sql);
}



}

 ?>
