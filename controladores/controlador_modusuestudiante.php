<?php
require_once("../clases/conexion.class.php");
require_once("../clases/usuestudiante.class.php");
require_once("../clases/utilidades.class.php");

$objConexiones=new conexion;
$objEstudiante=new estudiante;
$objUtilidades=new utilidades;

$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch ($_POST["buscar"]) {
	case 'modificar_usuestudiante':
		$respuesta=$objEstudiante->modificar_usuestudiante($conexion,$_POST['vcedula'],$_POST['nombre'],$_POST['clave'],$_POST['email']);
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Operaciones");
		else
			$objUtilidades->mostrarMensaje("Operaciones");


		break;
}
?>