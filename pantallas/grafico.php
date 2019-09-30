<?php
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
require_once("../clases/opciones.class.php");

/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
//$objConexion=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/

$objSeguridad=new seguridad;
$objOpcion = new opciones;

//$conexion=$objConexion->conectar();

$nombre_script=$objUtilidades->ruta_script(); //Averigua el nombre del script que se está ejecutando
//echo $nombre_script;
$num_opc = $objOpcion->devolver_cod_opc($conexion,$nombre_script); //Devuelve el código de la opción asociada al script

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$num_opc);
//$retorno = 1;
if ($retorno==1)
{

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Asignar Trabajos a Mecánicos</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

	<style type="text/css">
		.caja{
			margin: auto;
			max-width: 250px;
			padding: 20px;
			border: 1px solid #BDBDBD;
		}
		.caja select{
			width: 100%;
			font-size: 16px;
			padding: 5px; 
		}
		.resultados{
			margin: auto;
			margin-top: 40px;
			width: 1000px;
		}
	</style>

</head>
<body>
	<div class="caja">
		<select onchange="mostrarResultados(this.value);">
			<?php 
				for($i=2018;$i<=2021;$i++){
					if($i == 2019){
						echo '<option value="'.$i.'" selected>'.$i.'</option>';
					}else{
						echo '<option value="'.$i.'">'.$i.'</option>';
					}
				}
			?>
		</select>
	</div>
	<div class="resultados"><canvas id="grafico"></canvas></div>

<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>-->
	<script type="text/javascript" src="../bootstrap/js/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/Chart.js"></script>
	<script type="text/javascript" src="../javascript/grafico.js"></script>

	
</body>
</html>
<?php
}else
{
	echo "<script>
			alert('El usuario no tiene privilegios para acceder a esta pagina');
			location.replace('../index.php');
			</script>";
}
?>