<?php
//session_start();
require_once("../clases/seguridad.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_seguridad=new seguridad; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'devolverPermisosPantalla':	
		$respuesta=$obj_seguridad->devolverPermisosPantalla();
		break;
	default:
}

?>