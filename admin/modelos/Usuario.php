<?php
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$clavehash,$imagen,$usuariocreado,$codigo_persona, $sueldo_fijo, $idempresa){
	date_default_timezone_set('America/Mexico_City');
	$fechacreado=date('Y-m-d H:i:s');
	$sql="INSERT INTO usuarios (nombre,apellidos,login,iddepartamento,idtipousuario,email,password,imagen,estado,fechacreado,usuariocreado,codigo_persona, sueldo_fijo, idempresa) VALUES ('$nombre','$apellidos','$login','$iddepartamento','$idtipousuario','$email','$clavehash','$imagen','1','$fechacreado','$usuariocreado','$codigo_persona', '$sueldo_fijo', '$idempresa')";

	return ejecutarConsulta($sql);

}

public function editar($idusuario,$nombre,$apellidos,$login,$iddepartamento,$idtipousuario,$email,$imagen,$usuariocreado,$codigo_persona, $sueldo_fijo, $idempresa){
	$sql="UPDATE usuarios SET nombre='$nombre',apellidos='$apellidos',login='$login',iddepartamento='$iddepartamento',idtipousuario='$idtipousuario',email='$email',imagen='$imagen' ,usuariocreado='$usuariocreado',codigo_persona='$codigo_persona', sueldo_fijo='$sueldo_fijo', idempresa= '$idempresa'
	WHERE idusuario='$idusuario'";
	 return ejecutarConsulta($sql);

}
public function editar_clave($idusuario,$clavehash){
	$sql="UPDATE usuarios SET password='$clavehash' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function mostrar_clave($idusuario){
	$sql="SELECT idusuario, password FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}
public function desactivar($idusuario){
	$sql="UPDATE usuarios SET estado='0' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function activar($idusuario){
	$sql="UPDATE usuarios SET estado='1' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

public function eliminar($idusuario){
	$sql="DELETE FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	$sql="SELECT * FROM usuarios WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$idempresa=$_SESSION["idempresa"];
	$sql="SELECT * FROM usuarios WHERE idempresa = '$idempresa' AND login <> 'admin'";
	return ejecutarConsulta($sql);
}

public function cantidad_usuario(){
	$idempresa=$_SESSION["idempresa"];
	$sql="SELECT count(*) nombre FROM usuarios WHERE estado = 1 AND idempresa = '$idempresa' AND login <> 'admin'";
	return ejecutarConsulta($sql);
}

//Función para verificar el acceso al sistema
	public function verificar($login,$clave, $ruc)
    {
			$sql = "SELECT u.codigo_persona, u.idusuario, u.nombre, u.apellidos, u.login, u.idtipousuario, u.iddepartamento, u.email, u.imagen, u.login, u.sueldo_fijo, tu.nombre as tipousuario, e.idempresa, e.ruc, e.razonsocial FROM usuarios u INNER JOIN tipousuario tu ON u.idtipousuario = tu.idtipousuario JOIN empresa e ON u.idempresa = e.idempresa WHERE login = '$login' AND password = '$clave' AND estado = '1' AND e.ruc = '$ruc'";


    	return ejecutarConsulta($sql);
    }


	public function empleado_por_empresa() {
		$idempresa=$_SESSION["idempresa"];
		$sql="SELECT CONCAT(nombre, ' ', apellidos) as nombres FROM usuarios WHERE idempresa = '$idempresa' AND login <> 'admin' AND estado = 1 ORDER BY idusuario DESC";
		return ejecutarConsulta($sql);
	}

	public function conteo_registros_por_empresa() {
		$idempresa=$_SESSION["idempresa"];

		$sql = "call SP_CONTEO_MARCACIONES('$idempresa')";

		return ejecutarConsulta($sql);
	}
}

 ?>
