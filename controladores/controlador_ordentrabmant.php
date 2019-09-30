<?php
//session_start();
require_once("../clases/ordentrabmant.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_ordentrabmant=new ordentrabmant; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'crear':	
		$respuesta=$obj_ordentrabmant->crear($conexion,$_POST['solicitudTrabID'],$_POST['prioridad'],json_decode($_POST['filasMecanicos'], true),$_POST['departamentoAreaID'],$_POST['usuarioID'],$_POST['maquinariaequiposDetalleID']);
		break;
	case 'consultar':	
		$respuesta=$obj_ordentrabmant->consultar($conexion,$_POST['solicitudTrabID']);
		break;
	case 'update':	
		$respuesta=$obj_ordentrabmant->update($conexion,$_POST['solicitudTrabID'],$_POST['mant'],$_POST['prioridad'],$_POST['ttmID'],$_POST['descripTrabajo'],$_POST['repuestosmat'],$_POST['observaciones'],json_decode($_POST['tipofalla']),$_POST['tipomant']);
		break;
	case 'finalizarOT':	
		$respuesta=$obj_ordentrabmant->finalizarOT($conexion,$_POST['solicitudTrabID']);
		break;
	case 'consultarOrdenTrabajo':	
		$respuesta=$obj_ordentrabmant->consultarOrdenTrabajo($conexion,$_POST['usuarioID']);
		break;
	case 'updateEvaluacion':	
		$respuesta=$obj_ordentrabmant->updateEvaluacion($conexion,$_POST['solicitudTrabID'],$_POST['ordentrabmantID'],$_POST['evaluacion'],$_POST['statusaceprech'],$_POST['obserEvaluacion'],$_POST['obseraceprech']);
		break;
	case 'consultarTrabajosEnEjecucion':	
		$respuesta=$obj_ordentrabmant->consultarTrabajosEnEjecucion($conexion);
		break;
	case 'consultarValidarOT':	
		$respuesta=$obj_ordentrabmant->consultarValidarOT($conexion,$_POST['usuarioID']);
		break;
	case 'consultaxFiltro':	
		$respuesta=$obj_ordentrabmant->consultaxFiltro($conexion,$_POST['fechad'],$_POST['fechah'],json_decode($_POST['departamentoAreaID'],true),$_POST['staTrabajo']);
		break;
	case 'graficoOT':	
		$respuesta=$obj_ordentrabmant->graficoOT($conexion,$_POST['fechad'],$_POST['fechah'],json_decode($_POST['departamentoAreaID'], true));
		break;
	case 'graficoEvalOT':	
		$respuesta=$obj_ordentrabmant->graficoEvalOT($conexion,$_POST['fechad'],$_POST['fechah'],json_decode($_POST['departamentoAreaID'], true));
		break;
	case 'consultarValidarOT01':	
		$respuesta=$obj_ordentrabmant->consultarValidarOT01($conexion,$_POST['usuarioID']);
	default:
}

?>