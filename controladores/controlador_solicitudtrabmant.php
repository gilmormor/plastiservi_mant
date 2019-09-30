<?php
//session_start();
require_once("../clases/solicitudtrabmant.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_solicitudtrabmant=new solicitudtrabmant; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consultar':	
		$respuesta=$obj_solicitudtrabmant->consultar($conexion,$_POST['maquinaID'],$_POST['departamentoAreaID']);
		break;
	case 'llenarMaquinasPorArea':	
		$respuesta=$obj_solicitudtrabmant->llenarMaquinasPorArea($conexion,$_POST['departamentoAreaID']);
		break;
	case 'guardarChDiaMaq':	
		$respuesta=$obj_solicitudtrabmant->guardarChDiaMaq($conexion,$_POST['usuarioID'],$_POST['departamentoAreaID'],json_decode($_POST['valores'], true));
		break;
	case 'consultarSolicitudMant':	
		$respuesta=$obj_solicitudtrabmant->consultarSolicitudMant($conexion,$_POST['maquinaID'],$_POST['departamentoAreaID']);
		break;
	case 'consultaTrabMant':	
		$respuesta=$obj_solicitudtrabmant->consultaTrabMant($conexion);
		break;
	case 'insertUpdate':	
		$respuesta=$obj_solicitudtrabmant->insertUpdate($conexion,$_POST['maquinariaequiposDetalleID'],$_POST['departamentoAreaID'],$_POST['descripcion'],$_POST['prioridad'],$_POST['solicitudTrabID']);
		break;
	default:
}

?>