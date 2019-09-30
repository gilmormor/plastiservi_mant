<?php
//session_start();
require_once("../clases/estudiante.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_estudiante=new estudiante; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'consultar':	
		$respuesta=$obj_estudiante->consultar($conexion,$_POST['est_ced']);
		break;
	case 'consultarTodo':	
		$respuesta=$obj_estudiante->consultarTodo($conexion,$_POST['est_ced'],$_POST['fil_codlapso']);
		break;
	case 'insertUpdateTodo':
		//echo json_decode($_POST['ofer_academica']);
		$respuesta=$obj_estudiante->insertUpdateTodo($conexion,json_decode($_POST['datosest']),$_POST['lapsoinscp'],$_POST['tipousuario'],$_POST['cod_secc'],$_POST['bajarinsc'],$_POST['aux_elimatinsc']);
		break;
	case 'consultarDatosInsc':
		//echo json_decode($_POST['ofer_academica']);
		$respuesta=$obj_estudiante->consultarDatosInsc($conexion,$_POST['cedula']);
		//imprimirConsInscripcion($conexion);
		break;
	case 'estudiantesXrepresentante':
		//echo json_decode($_POST['ofer_academica']);
		$respuesta=$obj_estudiante->estudiantesXrepresentante($conexion,$_POST['est_ced']);
		//imprimirConsInscripcion($conexion);
		break;
	default:
}

?>