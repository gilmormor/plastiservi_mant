<?php
//session_start();
require_once("../clases/listanegra.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$objListanegra=new listanegra; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consulta':
		$dep_fechad=substr($_POST['dep_fechad'],6,4).substr($_POST['dep_fechad'],3,2).substr($_POST['dep_fechad'],0,2);
		$dep_fechah=substr($_POST['dep_fechah'],6,4).substr($_POST['dep_fechah'],3,2).substr($_POST['dep_fechah'],0,2);
		$respuesta=$objListanegra->consultar($conexion,$_POST['dep_cedula'],$_POST['dep_referencia'],$dep_fechad,$dep_fechah,$_POST['dep_fechad'],$_POST['dep_fechah']);
		break;
	default:
}

?>