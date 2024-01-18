<?php
require "../config/Conexion.php";

class Diccionario {
    public function __construct() {
    }

    public function insertar($variable_dic, $descripcion_dic, $dato_dic, $tabla_dic) {
        $sql = "INSERT INTO diccionario (variable_dic, descripcion_dic, dato_dic, tabla_dic) VALUES ('$variable_dic', '$descripcion_dic', '$dato_dic', '$tabla_dic')";
        return ejecutarConsulta($sql);
    }

    public function editar($id_dic, $variable_dic, $descripcion_dic, $dato_dic, $tabla_dic) {
        $sql = "UPDATE diccionario SET variable_dic='$variable_dic', descripcion_dic='$descripcion_dic', dato_dic='$dato_dic', tabla_dic='$tabla_dic' WHERE id_dic='$id_dic'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($id_dic) {
        $sql = "DELETE FROM diccionario WHERE id_dic='$id_dic'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id_dic) {
        $sql = "SELECT * FROM diccionario WHERE id_dic='$id_dic'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar() {
        $sql = "SELECT * FROM diccionario";
        return ejecutarConsulta($sql);
    }

    public function select() {
        $sql = "SELECT * FROM diccionario";
        return ejecutarConsulta($sql);
    }

    public function regresaRolDiccionario($diccionario) {
        $sql = "SELECT variable_dic FROM diccionario where id_dic='$diccionario'";
        return ejecutarConsulta($sql);
    }
}
?>
