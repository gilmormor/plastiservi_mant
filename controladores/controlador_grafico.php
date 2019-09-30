<?php
//session_start();
require_once("../clases/grafico.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_grafico=new grafico; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'mostrarGrafico':	
		$respuesta=$obj_grafico->mostrarGrafico($conexion,$_POST['año']);
		break;
	default:
}

?>