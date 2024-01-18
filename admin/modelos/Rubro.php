<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Rubro{


	//implementamos nuestro constructor
	public function __construct(){

	}

	//metodo insertar regiustro
	public function insertar($nombre_rub,$variable_rub,$tipo_rub,$formula_rub,$calculado_rub){
		$sql="INSERT INTO rubros (nombre_rub,variable_rub,tipo_rub,formula_rub,calculado_rub) VALUES ('$nombre_rub','$variable_rub','$tipo_rub','$formula_rub','$calculado_rub')";
		return ejecutarConsulta($sql);
	}

	//metodo para editar registrados
	public function editar($id_rub,$nombre_rub,$variable_rub,$tipo_rub,$formula_rub,$calculado_rub){
		$sql="UPDATE rubros SET nombre_rub='$nombre_rub',variable_rub='$variable_rub',tipo_rub='$tipo_rub',formula_rub='$formula_rub',calculado_rub='$calculado_rub'
		WHERE id_rub='$id_rub'";
		return ejecutarConsulta($sql);
	}

	//metodo para eliminar regitros
	public function eliminar($id_rub){
		$sql="DELETE FROM rubros WHERE id_rub='$id_rub'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($id_rub){
		$sql="SELECT * FROM rubros WHERE id_rub='$id_rub'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT * FROM rubros";
		return ejecutarConsulta($sql);
	}
	//listar y mostrar en selct
	public function select(){
		$sql="SELECT * FROM rubros";
		return ejecutarConsulta($sql);
	}

	public function regresaRolRubro($rubro){
		$sql="SELECT nombre_rub FROM rubros where id_rub='$rubro'";
		return ejecutarConsulta($sql);
	}

	public function obtenerListadoUsuarios() {
	    $sql = "SELECT idusuario, sueldo_fijo FROM usuarios";
	    return ejecutarConsulta($sql);
	}

}

 ?>
