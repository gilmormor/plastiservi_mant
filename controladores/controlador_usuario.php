<?php
//session_start();
require_once("../clases/usuario.class.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/
$obj_usuario=new usuario; // Instanciamos un objeto de la clase cliente


switch($_REQUEST["accion"]){//RREQUEST lee valores _POST y _GET
	case 'agregar_usuario':	
//    agregar_cliente(); no se puede hacer, por eso instanciamos un objeto de la clase que contiene las funciones	
		$respuesta=$obj_usuario->agregar_usuario($conexion,$_POST["ema_usu"],$_POST["cla_usu"],$_POST["ced_usu"],$_POST["nom_usu"],$_POST["ape_usu"],$_POST["fk_tip_usu"],$_POST["tel_usu"],$_POST["est_usu"]);
	break;
	case 'cambiarclave':
		$obj_usuario->cambiar_clave($conexion,$_POST["cla_usu"],$_POST["cla_usu1"],$_POST["cla_usu2"]);
		break;
	case 'iniciosesion':
		$obj_usuario->buscarinisesion($conexion,$_POST["correo"],$_POST["clave"]);
		break;
	case 'validacion':
		$obj_usuario->validarcod($conexion,$_POST["codvalidacion"]);
		break;
	case 'consultar':
		$obj_usuario->consultaxcedula($conexion,$_POST["ced_usu"]);
		break;
	case 'agregar_usuariojson':	//Hice otro para no modificar el que ya esta hecho. Este va ser por json
		$respuesta=$obj_usuario->agregar_usuariojson($conexion,$_POST["ema_usu"],$_POST["cla_usu"],$_POST["ced_usu"],$_POST["nom_usu"],$_POST["ape_usu"],$_POST["fk_tip_usu"],$_POST["tel_usu"],$_POST["est_usu"]);
		break;
	case 'consultarXemail':	//Consultar por email
		$respuesta=$obj_usuario->consultarXemail($conexion,$_POST["ema_usu"]);
		break;
	case 'recuperarclave':	//Consultar por email
		$respuesta=$obj_usuario->recuperarclave($conexion,$_POST["email"],$_POST["cedula"]);
		break;
	case 'validarElPasoVentanaPrincipal':	//Cambiar variable de sesion depositos hechos
		$respuesta=$obj_usuario->validarElPasoVentanaPrincipal();
		break;
	case 'consultarClave':	//Consultar por email
		$respuesta=$obj_usuario->consultarClave($conexion);
		break;
	case 'nomUsuario':	//Consultar por email
		$respuesta=$obj_usuario->nomUsuario();
		break;
	
	default:
}

?>