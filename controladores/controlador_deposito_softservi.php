<?php
require_once("../clases/conexion_softservi.class.php");
require_once("../clases/deposito_softservi.class.php");
require_once("../clases/utilidades.class.php");

/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/

$objDeposito=new deposito;
$objUtilidades=new utilidades;

/* ahora evaluaremos Polizonte*/

switch($_REQUEST["accion"]) {
	case 'buscar_deposito':
	$objDeposito->buscar_depositos($conexion,$_GET["dep_referencia"]);
		break;
	case 'modificar':
		$respuesta=$objDeposito->modificar_deposito($conexion,$_POST['referencia'],$_POST['cedula'],$_POST['estatus'],$_POST['fecha'],$_POST['monto'],$_POST['montocheque']);
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Operaciones");
		else
			$objUtilidades->mostrarMensaje("Operaciones");
			echo "<script>
			      location.replace('../pantallas/editar_deposito.php');
			    </script>";
		break;	
	case 'buscar_depositojson':
		$objDeposito->buscar_depositosjson($conexion,$_GET["dep_referencia"]);
		break;
	case 'buscar_depositoxcedula':
		//NO PASO MAS PARAMETROS PORQUE LA CEDULA DEL ESTUDIANTE LA ESTOY TOMANDO DE LAS VARIABLE DE SESION
		$objDeposito->buscar_depositoxcedula($conexion,$_POST["dep_cuenta"],$_POST["aux_montomin"],$_POST["aux_montomax"]); 
		break;
	case 'utilizarDeposito':
		$objDeposito->utilizarDeposito($conexion,$_POST["dep_referencia"],$_POST["dep_cuenta"],$_POST["dep_fecha"]);
		break;
	default:
		# code...
		break;
}
?>