<?php
//session_start();
require_once("../clases/tipotrabmant.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_tipotrabmant=new tipotrabmant; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consultar':	
		$respuesta=$obj_tipotrabmant->consultar($conexion,$_POST['maquinaID'],$_POST['departamentoAreaID']);
		break;
	case 'llenarMaquinasPorArea':	
		$respuesta=$obj_tipotrabmant->llenarMaquinasPorArea($conexion,$_POST['departamentoAreaID']);
		break;
	case 'guardarChDiaMaq':	
		$respuesta=$obj_tipotrabmant->guardarChDiaMaq($conexion,$_POST['usuarioID'],$_POST['departamentoAreaID'],json_decode($_POST['valores'], true));
		break;
	case 'consultarSolicitudMant':	
		$respuesta=$obj_tipotrabmant->consultarSolicitudMant($conexion,$_POST['maquinaID'],$_POST['departamentoAreaID']);
		break;
	case 'llenarTabla':	
		$respuesta=$obj_tipotrabmant->llenarTabla($conexion);
		break;
	case 'insertUpdate':	
		$respuesta=$obj_tipotrabmant->insertUpdate($conexion,$_POST['ttmID'],$_POST['descrip'],$_POST['indseg'],$_POST['definicion'],$_POST['displegal']);
		break;
	case 'eliminar':	
		$respuesta=$obj_tipotrabmant->eliminar($conexion,$_POST['ttmID']);
		break;
	default:
}

?>