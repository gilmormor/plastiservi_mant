<?php
require_once("../clases/conexion.class.php");
require_once("../clases/deposito.class.php");
require_once("../clases/utilidades.class.php");

$objConexiones=new conexion;
$objDeposito=new deposito;
$objUtilidades=new utilidades;

$conexion=$objConexiones->conectar();

/* ahora evaluaremos Polizonte*/

switch ($_POST["acciones"]) {
	case 'modificar':
		$respuesta=$objDeposito->modificar_deposito($conexion,$_POST['referencia'],$_POST['cedula'],$_POST['estatus'],$_POST['fecha'],$_POST['monto']);
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Operaciones");
		else
			$objUtilidades->mostrarMensaje("Operaciones");


		break;
}

?>