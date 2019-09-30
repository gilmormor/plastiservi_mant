<?php
require_once("../clases/conexion.class.php");
require_once("../clases/opciones.class.php");
require_once("../clases/utilidades.class.php");

$conexion=Db::getInstance();/*instancia la clase*/
$objOpciones=new opciones;
$objUtilidades=new utilidades;

/* ahora evaluaremos Polizonte*/

switch ($_POST["acciones"]) {
	case 'agregar_opcion':
		$respuesta=$objOpciones->agregar_opciones($conexion,$_POST['nom_ope'],$_POST['url'],$_POST['fk_mod'],$_POST['est_ope']);
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Operaciones");
		else
			$objUtilidades->mostrarMensaje("Operaciones");


		break;
	case 'modificar_opcion':
		$respuesta=$objOpciones->modificar_opciones($conexion,$_POST['fk_ope'],$_POST['nom_ope'],$_POST['url'],$_POST['fk_mod'],$_POST['est_ope']);
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Operaciones");
		else
			$objUtilidades->mostrarMensaje("Operaciones");
		break;
	case 'consultarMenuOpciones':
		$respuesta=$objOpciones->consultarMenuOpciones($conexion,$_POST['ema_usu']);
		break;
	case 'guardarPermisos':
		$respuesta=$objOpciones->guardarPermisos($conexion,$_POST['fk_usu'],json_decode($_POST['valores'], true));
		break;
	
	default:
		# code...
		break;
}

?>