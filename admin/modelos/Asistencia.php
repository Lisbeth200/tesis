<?php 

	require "../config/Conexion.php";
	class Asistencia{

		public function __construct(){}

		public function listar($fecha){
			$idempresa=$_SESSION["idempresa"];
			$sql = "call SP_LISTA_ASISTENCIAS('$idempresa', '$fecha', 'TIPO_ASISTENCIA')";
			return ejecutarConsulta($sql);
		}

		public function listaru($idusuario){
			$sql="SELECT a.idasistencia,a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos,d.nombre as departamento FROM asistencia a INNER JOIN usuarios u INNER JOIN departamento d ON u.iddepartamento=d.iddepartamento WHERE a.codigo_persona=u.codigo_persona AND u.idusuario='$idusuario'";
			return ejecutarConsulta($sql);
		}

		public function listar_asistencia($fecha_inicio,$fecha_fin,$codigo_persona){
			// $sql="SELECT a.idasistencia,a.codigo_persona,a.fecha_hora,a.tipo,a.fecha,u.nombre,u.apellidos FROM asistencia a INNER JOIN usuarios u ON  a.idusuario =u.idusuario  WHERE DATE(a.fecha)>='$fecha_inicio' AND DATE(a.fecha)<='$fecha_fin' ";
			$idempresa=$_SESSION["idempresa"];
			
			$sql = "call SP_LISTA_ASISTENCIAS_HORAS('$idempresa', '$fecha_inicio', '$fecha_fin', '$codigo_persona')";
			
			return ejecutarConsulta($sql);
		}

		public function saveHorarioNew($id_asistencia, $hour) {
			$sql = "call SP_ACTUALIZA_HORARIO('$id_asistencia', '$hour')";
			return ejecutarConsulta($sql);
		}
	}

?>
