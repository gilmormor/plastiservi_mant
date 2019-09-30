<?php
require_once("../clases/conexion.class.php");
require_once("../clases/modulo.class.php");
require_once("../clases/utilidades.class.php");

$conexion=Db::getInstance();/*instancia la clase*/
$objOpciones=new opciones;
$objUtilidades=new utilidades;

/* ahora evaluaremos Polizonte*/

switch ($_POST["acciones"]) {
	case 'agregar_modulos':
		$respuesta=$objOpciones->agregar_opciones($conexion,$_POST['nom_mod'],"A");
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Modulo");
		else
			$objUtilidades->mostrarMensaje("Modulo");


		break;
	
	default:
		# code...
		break;
}

?>