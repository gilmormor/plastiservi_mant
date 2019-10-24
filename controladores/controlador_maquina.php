<?php
//session_start();
require_once("../clases/maquina.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_maquina=new maquina; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consultar':	
		$respuesta=$obj_maquina->consultar($conexion,$_POST['maquinaID'],$_POST['departamentoAreaID'],$_POST['aux_codfiltro']);
		break;
	case 'llenarMaquinasPorArea':	
		$respuesta=$obj_maquina->llenarMaquinasPorArea($conexion,$_POST['departamentoAreaID']);
		break;
	case 'guardarChDiaMaq':	
		$respuesta=$obj_maquina->guardarChDiaMaq($conexion,$_POST['usuarioID'],$_POST['departamentoAreaID'],json_decode($_POST['valores'], true));
		break;
	case 'eliminarST':	
		$respuesta=$obj_maquina->eliminarST($conexion,$_POST['solicitudTrabID'],$_POST['usuarioID']);
		break;
	case 'update':	
		$respuesta=$obj_maquina->update($conexion,$_POST['solicitudTrabID'],$_POST['descripcion'],$_POST['usuarioID']);
		break;
	case 'listadoMaqxArea':	
		$respuesta=$obj_maquina->listadoMaqxArea($conexion,$_POST['departamentoAreaID']);
		break;
	default:
}

?>