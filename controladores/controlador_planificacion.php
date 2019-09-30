<?php
//session_start();
require_once("../clases/planificacion.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexion.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$objPlanificacion=new planificacion; // Instanciamos un objeto de la clase planificacion


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'insertar':	
		$respuesta=$objPlanificacion->insertar($conexion,$_POST["pla_lapso"],$_POST["pla_codsec"],$_POST["pla_codmat"],$_POST["pla_porc"],$_POST["pla_desc"],$_POST["pla_fecha"],$_POST["pla_objeti"]);
	break;
	case 'buscarmostrar':	
		$respuesta=$objPlanificacion->buscarmostrar($conexion,$_POST["pla_lapso"],$_POST["pla_codsec"],$_POST["pla_codmat"]);
	break;
	case 'eliminar':	
		$respuesta=$objPlanificacion->eliminar($conexion,$_POST["fid"]);
	break;
	default:
	case 'buscarmostrarplanotas':	
		$respuesta=$objPlanificacion->buscarmostrarplanotas($conexion,$_POST["pla_lapso"],$_POST["pla_codsec"],$_POST["pla_codmat"]);
	break;
	case 'nominanotas':	
		$respuesta=$objPlanificacion->buscarmostrarplanotas($conexion,$_POST["pla_lapso"],$_POST["pla_codsec"],$_POST["pla_codmat"]);
	break;
}

?>