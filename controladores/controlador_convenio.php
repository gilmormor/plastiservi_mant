<?php
require_once("../clases/conexion.class.php");
require_once("../clases/convenio.class.php");
require_once("../clases/utilidades.class.php");

$objConexiones=new conexion;
$objConvenio=new convenio;
$objUtilidades=new utilidades;

$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch ($_POST["accion"]) {
	case 'insertar':
		$respuesta=$objConvenio->agregar_convenio($conexion,$_POST['cedula']);
		break;	
	default:
		# code...
		break;
}

?>