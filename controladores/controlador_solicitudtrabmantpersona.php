<?php
//session_start();
require_once("../clases/solicitudtrabmantpersona.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_solicitudtrabmantpersona=new solicitudtrabmantpersona; // Instanciamos un objeto de la clase solicitudtrabmantpersona


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consultar':	
		$respuesta=$obj_solicitudtrabmantpersona->consultar($conexion,$_POST['solicitudTrabID']);
		break;
	case 'guardar':	
		$respuesta=$obj_solicitudtrabmantpersona->guardar($conexion,$_POST['solicitudTrabID'],$_POST['prioridad'],json_decode($_POST['filas'], true));
		break;
	case 'eliminar':	
		$respuesta=$obj_solicitudtrabmantpersona->eliminar($conexion,$_POST['solicitudTrabID']);
		break;
	default:
}

?>