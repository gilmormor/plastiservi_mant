<?php
require_once("../clases/conexion.class.php");
require_once("../clases/deposito.class.php");
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
	case 'buscar_depositonew':
	$objDeposito->buscar_depositosnew($conexion,$_POST["dep_referencia"]);
		break;
	case 'modificar':
		$fecha=substr($_POST['fecha'],6,4)."/".substr($_POST['fecha'],3,2)."/".substr($_POST['fecha'],0,2);
		$respuesta=$objDeposito->modificar_deposito($conexion,$_POST['referencia'],$_POST['cedula'],$_POST['estatus'],$fecha,$_POST['monto'],$_POST['montocheque']);
		/*
		if ($respuesta==false)
			$objUtilidades->validarErrores($conexion,"Operaciones");
		else
			$objUtilidades->mostrarMensaje("Operaciones");
			echo "<script>
			      location.replace('../pantallas/editar_deposito.php');
			    </script>";
		*/
		break;	
	case 'insertar':
		$fecha=substr($_POST['dep_fecha'],6,4)."/".substr($_POST['dep_fecha'],3,2)."/".substr($_POST['dep_fecha'],0,2);
		$respuesta=$objDeposito->insertar_deposito($conexion,$_POST['dep_cedula'],$_POST['mfor_cod'],$_POST['fid_banco'],$_POST['dep_referencia'],$fecha,$_POST['dep_lote'],$_POST['dep_clavconf'],$_POST['dep_monto'],$_POST['dep_nofacturar']);
		break;
	case 'buscar_depositojson':
		$objDeposito->buscar_depositosjson($conexion,$_GET["dep_referencia"]);
		break;
	case 'buscar_depositoxcedula':
		//NO PASO MAS PARAMETROS PORQUE LA CEDULA DEL ESTUDIANTE LA ESTOY TOMANDO DE LAS VARIABLE DE SESION
		$objDeposito->buscar_depositoxcedula($conexion,$_POST["dep_cuenta"],$_POST["aux_montomin"],$_POST["aux_montomax"],$_POST["aux_sta"],$_POST["aux_conther"]);
		break;
	case 'utilizarDeposito':
		$objDeposito->utilizarDeposito($conexion,$_POST["dep_referencia"],$_POST["dep_cuenta"],$_POST["dep_fecha"],$_POST["aux_sta"]);
		break;
	default:
		# code...
		break;
}
?>