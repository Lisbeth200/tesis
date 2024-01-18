-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-01-2024 a las 21:36:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `siscoms`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_ACTUALIZA_HORARIO` (`p_id_asistencia` INT, `p_hora` TIME)   proc:BEGIN
   
	DECLARE var_msj char(255) DEFAULT 'OK';
	DECLARE var_cod int DEFAULT 0;
	DECLARE lv_fecha date; 
    DECLARE lv_fecha_new timestamp;
  
	DECLARE continue HANDLER FOR SQLEXCEPTION,SQLWARNING
	BEGIN 
		GET DIAGNOSTICS CONDITION 1		
		var_cod = MYSQL_ERRNO, var_msj = MESSAGE_TEXT;			
		SELECT  var_cod AS cod_rep, var_msj AS mensj_resp ; 
		ROLLBACK;	
	END;
	
    START TRANSACTION;

    SELECT fecha INTO lv_fecha FROM asistencia WHERE idasistencia = p_id_asistencia;
    SET lv_fecha_new = concat(lv_fecha, ' ' , p_hora);
    
    UPDATE asistencia SET fecha_hora = lv_fecha_new  WHERE idasistencia = p_id_asistencia;
	   
	commit;
    
    SET var_msj = 'Se actualizaron los datos correctamnte';
    
	SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
 
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CALCULAR_RUBRO` (`rubro` VARCHAR(100), `id_usuario` INT)   BEGIN
    DECLARE columna VARCHAR(255);
    DECLARE tabla VARCHAR(255);
    DECLARE sql_query VARCHAR(1000);

    -- Parámetros si es necesario
    -- DECLARE p_id_asistencia VARCHAR(255);
    -- SET p_id_asistencia = 'valor_predeterminado';

    SELECT dato_dic, tabla_dic INTO columna, tabla FROM diccionario WHERE variable_dic = rubro;

    -- Utilizando parámetros
    -- SET sql_query = CONCAT('SELECT ', columna, ' FROM ', tabla, ' WHERE id_asistencia = ?', p_id_asistencia);

    -- Sin parámetros
    SET sql_query = CONCAT('SELECT ', columna, ' AS ', rubro ,' FROM ', tabla, ' WHERE idusuario = ', id_usuario);

    PREPARE stmt FROM sql_query;
    -- Utilizando parámetros
    -- EXECUTE stmt USING p_id_asistencia;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CONTEO_MARCACIONES` (`p_id_empresa` INT)   BEGIN

SELECT 
CONCAT(nombre, ' ', apellidos) as nombres
,(SELECT COUNT(1) FROM asistencia WHERE idusuario = a.idusuario) AS CONTEO
FROM usuarios a
WHERE a.idempresa = p_id_empresa AND a.login <> 'admin' AND a.estado = 1 
ORDER BY a.idusuario DESC;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTA_ASISTENCIAS` (`p_idempresa` INT, `p_fecha` DATE, `p_tipo` CHAR(50))   BEGIN

	SELECT 
	a.fecha
	,a.fecha_hora
	,a.latitude
	,a.longitude
	,a.observacion
	,a.image
	,b.nombre
	,b.apellidos
	,c.descripcion as  tipo
    ,d.nombre AS departamento
    ,a.idasistencia
    ,concat(b.nombre,' ', b.apellidos) as nombres
    ,time(a.fecha_hora) as hora
	FROM asistencia a 
	JOIN usuarios b on a.idusuario = b.idusuario
	LEFT JOIN parametros c on a.idtipoingreso = c.idparametros
    LEFT JOIN departamento d on b.iddepartamento = d.iddepartamento
    LEFT JOIN parametros e on a.id_tipo_solicitud = e.idparametros
    WHERE b.idempresa = p_idempresa
    AND a.fecha = p_fecha
    AND e.codigo = p_tipo
    AND b.estado = 1;


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTA_ASISTENCIAS_HORAS` (`p_idempresa` INT, `p_fecha_inicio` DATE, `p_fecha_fin` DATE, `p_usuario_id` CHAR(50))   BEGIN

	SELECT 
    datos.* 
    ,HOUR(TIMEDIFF(datos.ENTRADA, datos.SALIDA)) horas_trabajadas
    FROM
    (
    SELECT 
	a.fecha_hora AS ENTRADA
	,a.observacion
	,b.nombre
	,b.apellidos
    ,d.nombre AS departamento
    ,( SELECT fecha_hora FROM  asistencia WHERE fecha = a.fecha AND idtipoingreso = 4 AND idusuario = a.idusuario ) as SALIDA
	FROM asistencia a 
	JOIN usuarios b on a.idusuario = b.idusuario
	LEFT JOIN parametros c on a.idtipoingreso = c.idparametros
    LEFT JOIN departamento d on b.iddepartamento = d.iddepartamento
    LEFT JOIN parametros e on a.id_tipo_solicitud = e.idparametros
    WHERE b.idempresa = p_idempresa
    AND a.fecha between p_fecha_inicio AND p_fecha_fin
	AND c.codigo IN ('HORA_ENTRADA_NORMAL', 'HORA_ENTRADA_ATRASO')
    ) AS datos;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRA_ASISTENCIA` (`p_user_id` INT, `p_latitude` DOUBLE, `p_longitude` DOUBLE, `p_image` LONGTEXT)   proc:BEGIN
   
	DECLARE var_msj char(255) DEFAULT 'OK';
	DECLARE var_cod int DEFAULT 1;
    DECLARE lv_hora_actual time;
    DECLARE lv_fecha_actual date;
    
    DECLARE lv_conteo			INT DEFAULT 0;
    DECLARE lv_tipo_id			INT;
    DECLARE lv_tipo_descripcion	CHAR(250);
    
    DECLARE lv_conteo_registro	INT DEFAULT 0;
  
	DECLARE continue HANDLER FOR SQLEXCEPTION,SQLWARNING
	BEGIN 
		GET DIAGNOSTICS CONDITION 1		
		var_cod = MYSQL_ERRNO, var_msj = MESSAGE_TEXT;			
		SELECT  var_cod AS cod_rep, var_msj AS mensj_resp ; 
		ROLLBACK;	
	END;
	
    START TRANSACTION;
	
    -- SET lv_hora_actual = time(NOW());
    SET lv_hora_actual = '06:31:00';
    SET lv_fecha_actual = date(NOW());
    
	SELECT count(*), idparametros, descripcion
    INTO lv_conteo, lv_tipo_id, lv_tipo_descripcion
	FROM parametros a
	WHERE lv_hora_actual  between a.valor_1 AND a.valor_2;    
    
	IF lv_conteo = 0 THEN 
	  SET var_cod = 2;
	  SET var_msj = 'Registro de Asistencia NO disponible en este horario. Consulte con administrador del sistema';
	  SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
	  LEAVE proc;
	END IF ;

	IF lv_conteo = 1 THEN
       
        SELECT 
        COUNT(1)
        INTO lv_conteo_registro
        FROM asistencia 
        where fecha = lv_fecha_actual
        AND idtipoingreso = lv_tipo_id
        AND idusuario = p_user_id;
        
		IF lv_conteo_registro > 0 THEN
			SET var_cod = 2;
            SET var_msj = concat('Ya existe un registro de ',lv_tipo_descripcion,' creado. Consulte con administrador del sistema.');
			SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
			LEAVE proc;
		END IF;
        
    END IF;

	
	IF lv_conteo = 2 THEN 
    
		IF ( !exists (SELECT * FROM asistencia  where fecha = lv_fecha_actual AND idusuario = p_user_id and idtipoingreso = 2) ) THEN
			SET lv_tipo_id = 2;
		ELSE IF ( !exists (SELECT * FROM asistencia  where fecha = lv_fecha_actual AND idusuario = p_user_id and idtipoingreso = 3) ) THEN
			SET lv_tipo_id = 3;
		ELSE
			SET var_cod = 2;
            SET var_msj = concat('Ya existen registros de almuerzos creados. Consulte con administrador del sistema.');
			SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
			LEAVE proc;        
        END IF;
        END IF;
        
	END IF ;
    
	INSERT INTO `asistencia`
	(`fecha`,
	`latitude`,
	`longitude`,
	`idusuario`,
	`idtipoingreso`,
	`image`, 
    `id_tipo_solicitud`)
	VALUES
	(lv_fecha_actual,
    p_latitude,
    p_longitude,
    p_user_id,
    lv_tipo_id,
    p_image,
    6
    );
	   
	commit;
    
    SET var_msj = concat('Realizó correctamente un registro de ',lv_tipo_descripcion,' con fecha: ', lv_fecha_actual, ' hora: ',  lv_hora_actual);
    
	SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
 
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRA_HORA_EXTRAS` (`p_user_id` INT, `p_latitude` DOUBLE, `p_longitude` DOUBLE, `p_image` LONGTEXT, `p_observacion` VARCHAR(250))   proc:BEGIN
   
	DECLARE var_msj char(255) DEFAULT 'OK';
	DECLARE var_cod int DEFAULT 1;
    DECLARE lv_hora_actual time;
    DECLARE lv_fecha_actual date;
  
	DECLARE continue HANDLER FOR SQLEXCEPTION,SQLWARNING
	BEGIN 
		GET DIAGNOSTICS CONDITION 1		
		var_cod = MYSQL_ERRNO, var_msj = MESSAGE_TEXT;			
		SELECT  var_cod AS cod_rep, var_msj AS mensj_resp ; 
		ROLLBACK;	
	END;
	
    START TRANSACTION;
	
    -- SET lv_hora_actual = time(NOW());
    SET lv_hora_actual = '06:31:00';
    SET lv_fecha_actual = date(NOW());

	INSERT INTO `asistencia`
	(`fecha`,
	`latitude`,
	`longitude`,
    `observacion`,
	`idusuario`,
	`image`, 
    `id_tipo_solicitud`)
	VALUES
	(lv_fecha_actual,
    p_latitude,
    p_longitude,
    p_observacion,
    p_user_id,
    p_image,
    8
    );
	   
	commit;
    
    SET var_msj = concat('Realizó correctamente un registro de Horas Extras',' con fecha: ', lv_fecha_actual);
	SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
 
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRA_INASISTENCIA` (`p_user_id` INT, `p_image` LONGTEXT, `p_observacion` VARCHAR(250))   proc:BEGIN
   
	DECLARE var_msj char(255) DEFAULT 'OK';
	DECLARE var_cod int DEFAULT 1;
	DECLARE lv_fecha_actual date;
   
	DECLARE continue HANDLER FOR SQLEXCEPTION,SQLWARNING
	BEGIN 
		GET DIAGNOSTICS CONDITION 1		
		var_cod = MYSQL_ERRNO, var_msj = MESSAGE_TEXT;			
		SELECT  var_cod AS cod_rep, var_msj AS mensj_resp ; 
		ROLLBACK;	
	END;
	
    START TRANSACTION;
	
    SET lv_fecha_actual = date(NOW());
    
	INSERT INTO `asistencia`
	(`fecha`,
    `observacion`,
	`idusuario`,
	`image`, 
    `id_tipo_solicitud`)
	VALUES
	(lv_fecha_actual,
    p_observacion,
    p_user_id,
    p_image,
    7
    );
	   
	commit;
    SET var_msj = concat('Realizó correctamente un registro de Inasistencia',' con fecha: ', lv_fecha_actual);
	SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
 
 END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateNewHorario` (`p_id_asistencia` INT, `p_hora` TIME)   proc:BEGIN
   
	DECLARE var_msj char(255) DEFAULT 'OK';
	DECLARE var_cod int DEFAULT 1;
	DECLARE lv_fecha date; 
    DECLARE lv_fecha_new timestamp;
  
	DECLARE continue HANDLER FOR SQLEXCEPTION,SQLWARNING
	BEGIN 
		GET DIAGNOSTICS CONDITION 1		
		var_cod = MYSQL_ERRNO, var_msj = MESSAGE_TEXT;			
		SELECT  var_cod AS cod_rep, var_msj AS mensj_resp ; 
		ROLLBACK;	
	END;
	
    START TRANSACTION;

    SELECT fecha INTO lv_fecha FROM asistencia WHERE idasistencia = p_id_asistencia;
    SET lv_fecha_new = concat(lv_fecha, ' ' , p_hora);
    
    UPDATE asistencia SET fecha_hora = lv_fecha_new  WHERE idasistencia = p_id_asistencia;
	   
	commit;
    
    SET var_msj = 'Se actualizaron los datos correctamnte';
    
	SELECT  var_cod AS cod_rep, var_msj AS mensj_resp;
 
 END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `idasistencia` int(11) NOT NULL,
  `codigo_persona` varchar(20) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo` varchar(45) DEFAULT NULL,
  `fecha` date NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `observacion` varchar(250) DEFAULT NULL,
  `idusuario` int(11) NOT NULL,
  `image` longtext DEFAULT NULL,
  `id_tipo_solicitud` int(11) DEFAULT NULL,
  `idtipoingreso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`idasistencia`, `codigo_persona`, `fecha_hora`, `tipo`, `fecha`, `latitude`, `longitude`, `observacion`, `idusuario`, `image`, `id_tipo_solicitud`, `idtipoingreso`) VALUES
(321, '09123', '2023-11-29 21:15:07', 'TIPO_ASISTENCIA', '2023-11-29', 65789324, 324234246789, 'ninguna', 91, NULL, 6, 1),
(323, '09123', '2023-11-29 21:14:41', 'TIPO_ASISTENCIA', '2023-11-29', 65789324, 324234246789, 'ninguna', 91, NULL, 6, 2),
(324, '09123', '2023-11-29 21:15:27', 'TIPO_ASISTENCIA', '2023-11-29', 65392393, 324523134534, 'ninguna', 91, NULL, 6, 3),
(325, '09123', '2023-11-29 21:15:58', 'TIPO_ASISTENCIA', '2023-11-29', 65432135, 324345645543, 'ninguna', 91, NULL, 6, 4),
(326, '09123', '2023-11-29 21:17:01', 'TIPO_ASISTENCIA', '2023-11-29', 65432346, 324345454434, 'ninguna', 91, NULL, 6, 5),
(327, '09123', '2023-11-29 21:18:21', 'TIPO_ASISTENCIA', '2023-11-29', 65765764, 323454465432, 'ninguna', 91, NULL, 6, 6),
(328, '09123', '2023-11-29 21:20:13', 'TIPO_ASISTENCIA', '2023-11-29', 65512334, 454353434325, 'ninguna', 91, NULL, 6, 7),
(329, '09123', '2023-11-29 21:21:28', 'TIPO_ASISTENCIA', '2023-11-29', 54243435, 432435454663, 'ninguna', 91, NULL, 6, 8),
(330, '09123', '2023-11-29 21:22:52', 'TIPO_ASISTENCIA', '2023-11-29', 21345466, 321324534432, 'ninguna', 91, NULL, 6, 9),
(331, '09123', '2023-11-29 21:24:31', 'TIPO_ASISTENCIA', '2023-11-29', 452631342, 456789323432, 'nuinguna', 91, NULL, 6, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `iddepartamento` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `fechacreada` datetime NOT NULL,
  `tarifa` int(11) DEFAULT NULL,
  `idusuario` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`iddepartamento`, `nombre`, `descripcion`, `fechacreada`, `tarifa`, `idusuario`) VALUES
(8, 'SISTEMAS', 'SISTEMAS', '2023-07-18 01:51:35', NULL, '18'),
(9, 'Finanzas', 'Pago empleados', '2023-07-19 10:29:32', NULL, '18'),
(10, 'Marketing12333', 'PAra ventas en generaldsfsdvdffcbfcvbvcbvc', '0000-00-00 00:00:00', NULL, '105'),
(11, 'Soporte Técnico', 'Delegar instalaciones y soportes', '2023-10-30 14:04:56', NULL, '105'),
(12, 'Juan', 'Inraestructura', '0000-00-00 00:00:00', NULL, '105');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallerol`
--

CREATE TABLE `detallerol` (
  `id_detr` int(11) NOT NULL,
  `valor_detr` decimal(10,2) NOT NULL,
  `tipo_detr` varchar(100) NOT NULL,
  `descripcion_detr` varchar(255) NOT NULL,
  `id_rub` int(11) NOT NULL,
  `idusuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diccionario`
--

CREATE TABLE `diccionario` (
  `id_dic` int(11) NOT NULL,
  `variable_dic` varchar(200) NOT NULL,
  `descripcion_dic` varchar(500) NOT NULL,
  `dato_dic` varchar(200) NOT NULL,
  `tabla_dic` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `diccionario`
--

INSERT INTO `diccionario` (`id_dic`, `variable_dic`, `descripcion_dic`, `dato_dic`, `tabla_dic`) VALUES
(1, 'SF', 'Sueldo Fijo', 'sueldo_fijo', 'usuarios'),
(2, 'HE', 'Horas Extras', 'hora', 'asistencia'),
(3, 'DT', 'Dias Trabajados', 'variable', 'vista_asistencia'),
(4, 'IESS', 'Seguro del Empleado', 'diccionario', 'empleado'),
(5, 'FR', 'Fondos de reserva', 'salario', 'empleado'),
(6, 'DIII', 'Decimo Tercero', 'kjvnf', 'huahuda'),
(7, 'DIII', 'Decimo Tercero', 'kjvnf', 'huahuda'),
(8, 'ygoehgowehg', 'kjsabdasbj', 'hsfabfiua', 'hsbfiaus');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `idempresa` int(11) NOT NULL,
  `razonsocial` varchar(250) NOT NULL,
  `ruc` varchar(13) NOT NULL,
  `logo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`idempresa`, `razonsocial`, `ruc`, `logo`) VALUES
(1, 'FUNDACION KARLYS-SISCOM', '0591763668001', NULL),
(2, 'SISTEMAS Y COMUNICACIONES SISCOM', '0591763000001', NULL),
(3, 'QUIMBITA PANCHI LUIS ANIBAL - SAI', '0502481583001', NULL),
(4, 'VELASCO PILATASIG BLANCA CECILIA - SOLTEC', '0502795149001', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encabezado`
--

CREATE TABLE `encabezado` (
  `id_enc` int(11) NOT NULL,
  `nombre_enc` varchar(500) DEFAULT NULL,
  `descripcion_enc` varchar(500) DEFAULT NULL,
  `mes_enc` varchar(500) DEFAULT NULL,
  `anio_enc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encabezado`
--

INSERT INTO `encabezado` (`id_enc`, `nombre_enc`, `descripcion_enc`, `mes_enc`, `anio_enc`) VALUES
(1, 'Rol', 'Rol de pagos al empledo', 'Noviembre', 2023),
(2, 'sdf', 'sdf', 'Febrero', 0),
(3, 'ewqeqw', 'ewqe', 'Enero', 0),
(4, 'eew', 'few', 'Marzo', 0),
(5, 'as', 'asS', 'Marzo', 2024),
(6, '23', 'gv', 'Junio', 0),
(7, 'jhbdsd', 'dsajhddsada', 'Diciembre', 2023);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `idmensaje` int(11) NOT NULL,
  `idusuariomensaje` int(11) NOT NULL,
  `textomensaje` text NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechamensaje` datetime NOT NULL,
  `fechacreada` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE `parametros` (
  `idparametros` int(11) NOT NULL,
  `codigo` varchar(50) DEFAULT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `valor_1` varchar(250) DEFAULT NULL,
  `valor_2` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `parametros`
--

INSERT INTO `parametros` (`idparametros`, `codigo`, `descripcion`, `valor_1`, `valor_2`) VALUES
(1, 'HORA_ENTRADA_NORMAL', 'Entrada', '06:30:00', '06:59:59'),
(2, 'HORA_SALIDA_ALMUERZO', 'Salida de Almuerzo', '12:00:00', '14:00:00'),
(3, 'HORA_ENTRADA_ALMUERZO', 'Entrada del almuerzo', '12:00:00', '14:00:00'),
(4, 'HORA_SALIDA', 'Salida', '16:00:00', '18:00:00'),
(5, 'HORA_ENTRADA_ATRASO', 'Entrada del empleado con atraso', '07:00:00', '07:15:59'),
(6, 'TIPO_ASISTENCIA', 'Asistencia', NULL, NULL),
(7, 'TIPO_INASISTENCIA', 'Inasistencia', NULL, NULL),
(8, 'TIPO_HEXTRAS', 'Hora Extras', NULL, NULL),
(9, '34567', 'tyyhj', 'ftgh', 'fgy'),
(10, '4567', 'ftyuhij', 'frtghuj', 'gvyhuji');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubros`
--

CREATE TABLE `rubros` (
  `id_rub` int(11) NOT NULL,
  `nombre_rub` varchar(200) NOT NULL,
  `variable_rub` varchar(500) NOT NULL,
  `tipo_rub` varchar(500) NOT NULL,
  `formula_rub` text NOT NULL,
  `calculado_rub` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `rubros`
--

INSERT INTO `rubros` (`id_rub`, `nombre_rub`, `variable_rub`, `tipo_rub`, `formula_rub`, `calculado_rub`) VALUES
(4, 'SALARIO', 'SALARIO', 'ingreso', 'SF*15/30', 1),
(5, 'IESS', 'IESS', 'egreso', 'SF*0.0945', 1),
(8, 'suedo fijp', 'gvj', 'ingreso', 'Bono', 10),
(9, 'GHJK', 'SF', 'ingreso', '500+12', 0),
(10, 'rdcftv', 'tf', 'egreso', 'SF+ IESS+12', 0),
(11, 'mi testotyro testq', 'var', 'ingreso', 'HE + FR + DT', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipousuario`
--

CREATE TABLE `tipousuario` (
  `idtipousuario` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `fechacreada` datetime NOT NULL,
  `idusuario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `tipousuario`
--

INSERT INTO `tipousuario` (`idtipousuario`, `nombre`, `descripcion`, `fechacreada`, `idusuario`) VALUES
(3, 'Administrador', 'Adminisstrador', '2023-07-12 13:06:15', ''),
(4, 'Vendedor', 'Vende y promueve los productos', '2023-07-18 00:00:00', NULL),
(5, 'fghjk', 'fcvgbhjk', '2023-11-22 22:55:26', NULL),
(6, 'fedsdf', 'fasdwadsa', '2023-11-22 22:55:26', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellidos` varchar(45) NOT NULL,
  `login` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(64) NOT NULL,
  `imagen` varchar(50) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `fechacreado` datetime NOT NULL,
  `usuariocreado` varchar(45) NOT NULL,
  `codigo_persona` varchar(20) DEFAULT NULL,
  `sueldo_fijo` decimal(20,8) NOT NULL,
  `idmensaje` int(11) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `iddepartamento` int(11) DEFAULT NULL,
  `idtipousuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `nombre`, `apellidos`, `login`, `email`, `password`, `imagen`, `estado`, `fechacreado`, `usuariocreado`, `codigo_persona`, `sueldo_fijo`, `idmensaje`, `idempresa`, `iddepartamento`, `idtipousuario`) VALUES
(91, 'Luis Anibal', 'Quimbita Panchi', '0502481583', 'anibalquimbita@siscom.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1692618717.jpeg', 1, '2023-08-21 05:51:57', 'Luis Anibal', '', 0.00000000, NULL, 1, 8, 3),
(92, 'Carlos Marcelo', 'Damián Tiuma', '0604630947', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1692618776.jpeg', 1, '2023-08-21 05:52:56', 'Carlos Marcelo', '', 0.00000000, NULL, 1, 8, 3),
(93, 'Diego Paul', 'Quimbita Velasco', '0502090996', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1692618823.jpeg', 1, '2023-08-21 05:53:43', 'Diego Paul', '', 0.00000000, NULL, 1, 8, 3),
(95, 'Oscar Alfonso', 'Jami Toaza', '0503683286', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1692618950.jpeg', 1, '2023-08-21 05:55:49', 'Oscar Alfonso', '', 0.00000000, NULL, 1, 8, 3),
(96, 'Jorge Luis', 'Yasig Miranda', '0503204430', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1692618995.jpeg', 1, '2023-08-21 05:56:35', 'Jorge Luis', '', 0.00000000, NULL, 1, 8, 3),
(105, 'Administrador', 'Administrador', '0591763668001', 'adminstrador@example.com.ec', 'da23890e111536e631be13a069ebc5432c9cf28cdbc5deb2a70770ec9597db6d', '1692886672.jpeg', 1, '2023-08-24 05:56:35', 'Administrador', '', 0.00000000, NULL, 1, 8, 3),
(106, 'Administrador', 'Administrador', '0591763000001', 'adminstrador@example.com.ec', 'da23890e111536e631be13a069ebc5432c9cf28cdbc5deb2a70770ec9597db6d', '1692886839.jpeg', 1, '2023-08-24 05:56:35', 'Administrador', '', 0.00000000, NULL, 2, 8, 3),
(107, 'Administrador', 'Administrador', '0502481583001', 'adminstrador@example.com.ec', 'da23890e111536e631be13a069ebc5432c9cf28cdbc5deb2a70770ec9597db6d', '1692886897.jpeg', 1, '2023-08-24 05:56:35', 'Administrador', '', 0.00000000, NULL, 3, 8, 3),
(108, 'Administrador', 'Administrador', '0502795149001', 'adminstrador@example.com.ec', 'da23890e111536e631be13a069ebc5432c9cf28cdbc5deb2a70770ec9597db6d', '1692886806.jpeg', 1, '2023-08-24 05:56:35', 'Administrador', '', 0.00000000, NULL, 4, 8, 3),
(109, 'Jimmys', 'Iza Iza', '0550525083', 'jimmy.iza5083@utc.edu.ec', 'ac2ee4dfb4aa9ee8cfe18adddc0507a6276101cdb8aaa6b34833c17d4b5294cc', '1692920265.jpeg', 1, '2023-08-24 17:37:44', 'Jimmys', '', 0.00000000, NULL, 3, 8, 3),
(112, 'Daniel Fernando', 'Casa Oña', '0550634232', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1692970544.jpeg', 1, '2023-08-25 07:35:43', 'Daniel Fernando', '', 0.00000000, NULL, 1, 8, 3),
(113, 'Victor', 'Medina', 'wilson', 'victor.medina@utc.edu.ec', '39450d504cabda49067da7bee0459ac886d3f4d9ac7adbcbc2dc005c45b7fcd9', '1692971394.jpeg', 1, '2023-08-25 07:49:54', 'Victor', '', 500.00000000, NULL, 1, 8, 3),
(115, 'Marco Luis', 'Casa Casa', '0550423263', 'jimmy.iza5083@utc.edu.ec', '4e574077af34b23a356dd896e39a1e8edfba85727e5413b631f8eebeba3b7338', '1693251933.jpeg', 1, '2023-08-28 13:45:33', 'Marco Luis', '', 0.00000000, NULL, 1, 8, 3),
(117, 'Ana', 'Pruna', '0550133202', 'jimmy.iza5083@utc.edu.ec', '7ec8abb2bed96edaf44aabf204be8241d1635197c070dd19ba27fa9a11ce55ae', '1693252449.jpeg', 1, '2023-08-28 13:54:08', 'Ana', '', 0.00000000, NULL, 1, 8, 3),
(118, 'Manuel', 'Iza', 'manuel', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1693252725.jpeg', 1, '2023-08-28 13:58:45', 'Manuel', '', 0.00000000, NULL, 1, 8, 3),
(119, 'Jorge', 'Casa', 'jorge', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1693252901.jpeg', 1, '2023-08-28 14:01:40', 'Jorge', '', 0.00000000, NULL, 1, 8, 3),
(120, 'Kevin', 'Casa', 'kevin', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1698551151.jpeg', 1, '2023-10-28 21:45:50', 'Kevin', '', 0.00000000, NULL, 1, 8, 3),
(121, 'lazaro', 'casa', 'lazaro', 'jimmy.iza5083@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1698552001.jpg', 1, '2023-10-28 22:00:01', 'lazaro', '', 0.00000000, NULL, 1, 0, 3),
(122, 'Pamela Estefania', 'Sarabia Velasco', 'Pamela', 'pamela.sarabia@siscom.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1698696396.jpeg', 1, '2023-10-30 14:06:36', 'Pamela Estefania', '', 0.00000000, NULL, 1, 11, 3),
(123, 'Lisbeth Johana', 'Rocha Toca', 'Lisbeth', 'lisbeth.rocha7242@utc.edu.ec', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '1698696929.jpeg', 1, '2023-10-30 14:15:29', 'Lisbeth Johana', '', 0.00000000, NULL, 1, 11, 3),
(124, 'PAMELA', 'Sarabia', 'pame', 'pamela.sarabia@siscom.com', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', '', 1, '2023-11-09 13:48:21', 'PAMELA', '', 0.00000000, NULL, 2, 8, 3),
(125, 'Andy', 'Panhci', 'ap', 'andystveen@gmail.com', '2c53606cdc25fded3d2e696776b69ee7c7c1048c9b91ff97b6e1e3a1437f8f5a', '', 1, '2024-01-03 09:35:41', 'Andy', '', 0.00000000, NULL, 1, 8, 3),
(126, 'khdas', 'uyvuyv', 'bib', 'dasdsaq@hdsa', 'fecf82ccf0f163ab7447bd346665bfbdc50d8dd567037af7ae443d0273c8cdaf', '', 1, '2024-01-03 10:11:20', 'khdas', '', 500.00000000, NULL, 2, 8, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`idasistencia`) USING BTREE,
  ADD KEY `fk_asistencia_parametros1_idx` (`id_tipo_solicitud`) USING BTREE,
  ADD KEY `fk_asistencia_parametros2_idx` (`idtipoingreso`) USING BTREE,
  ADD KEY `fk_asistencia_usuarios_idx` (`idusuario`) USING BTREE;

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`iddepartamento`) USING BTREE;

--
-- Indices de la tabla `detallerol`
--
ALTER TABLE `detallerol`
  ADD PRIMARY KEY (`id_detr`),
  ADD KEY `id_rub` (`id_rub`),
  ADD KEY `fk_detallerol_usuarios` (`idusuario`);

--
-- Indices de la tabla `diccionario`
--
ALTER TABLE `diccionario`
  ADD PRIMARY KEY (`id_dic`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idempresa`) USING BTREE,
  ADD UNIQUE KEY `ruc_UNIQUE` (`ruc`) USING BTREE;

--
-- Indices de la tabla `encabezado`
--
ALTER TABLE `encabezado`
  ADD PRIMARY KEY (`id_enc`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`idmensaje`) USING BTREE;

--
-- Indices de la tabla `parametros`
--
ALTER TABLE `parametros`
  ADD PRIMARY KEY (`idparametros`) USING BTREE;

--
-- Indices de la tabla `rubros`
--
ALTER TABLE `rubros`
  ADD PRIMARY KEY (`id_rub`);

--
-- Indices de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`idtipousuario`) USING BTREE;

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`) USING BTREE,
  ADD UNIQUE KEY `login_UNIQUE` (`login`) USING BTREE,
  ADD KEY `fk_usuarios_empresa1_idx` (`idempresa`) USING BTREE,
  ADD KEY `fk_usuarios_tipousuario1_idx` (`idtipousuario`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `idasistencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=332;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `iddepartamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `detallerol`
--
ALTER TABLE `detallerol`
  MODIFY `id_detr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `diccionario`
--
ALTER TABLE `diccionario`
  MODIFY `id_dic` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idempresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `encabezado`
--
ALTER TABLE `encabezado`
  MODIFY `id_enc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `idmensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `parametros`
--
ALTER TABLE `parametros`
  MODIFY `idparametros` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `rubros`
--
ALTER TABLE `rubros`
  MODIFY `id_rub` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tipousuario`
--
ALTER TABLE `tipousuario`
  MODIFY `idtipousuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `fk_asistencia_parametros1` FOREIGN KEY (`id_tipo_solicitud`) REFERENCES `parametros` (`idparametros`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_asistencia_parametros2` FOREIGN KEY (`idtipoingreso`) REFERENCES `parametros` (`idparametros`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_asistencia_usuarios` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `detallerol`
--
ALTER TABLE `detallerol`
  ADD CONSTRAINT `detallerol_ibfk_1` FOREIGN KEY (`id_rub`) REFERENCES `rubros` (`id_rub`),
  ADD CONSTRAINT `fk_detallerol_usuarios` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_empresa1` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuarios_tipousuario1` FOREIGN KEY (`idtipousuario`) REFERENCES `tipousuario` (`idtipousuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
