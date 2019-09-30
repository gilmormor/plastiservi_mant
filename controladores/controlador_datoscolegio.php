<?php
//session_start();
require_once("../clases/datoscolegio.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_colegio=new datoscolegio; // Instanciamos un objeto de la clase datoscolegio


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consultar':	
//    agregar_cliente(); no se puede hacer, por eso instanciamos un objeto de la clase que contiene las funciones	
		$respuesta=$obj_colegio->colsultar($conexion);
	break;
	default:
}

?>