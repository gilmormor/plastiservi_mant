<?php
require_once("../clases/conexion.class.php");
require_once("../clases/planteles.class.php");

/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$objPlanteles=new planteles;

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscarplantel':
	$objPlanteles->buscarplantel($conexion,$_POST['plan_est_codigo']);
		break;
	default:
		# code...
		break;
}
?>