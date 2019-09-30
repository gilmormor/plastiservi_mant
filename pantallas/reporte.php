<?php
require_once("../clases/conexion.class.php");
header("refresh:60; url=$self"); //Refrescamos cada 60 segundos
$objConexiones=new conexion;

$conexion=$objConexiones->conectar();

// sentencia sql
$sql="select usu_cod as codigo, usu_nombre as nombres, usu_fecoenx as hora_conex from eva_usuarios where usu_staact=1 order by usu_fecoenx";
$datos = mysql_query($sql,$conexion);
$resul=mysql_num_rows($datos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
</head>
<body>
	<table width="90%" class="table table-striped table-bordered table-hover table-condensed">
	<tr>
		<?php
			echo "<td colspan='6' align='center' class='bg-primary'><B>Estudiantes Conectados: ".$resul."</B></td>";
		?>
	</tr>
	<tr>
		<td><B>#</B></td>
		<td><B>Cedula.</B></td>
		<td align='center'><B>Nombres-Apellidos</B></td>
		<td align='center'><B>Hora Conexion</B></td>
	</tr>

	<?php
	$aux_cont=0;
	while($inscripcion = mysql_fetch_array($datos))
	{
			$aux_cont=$aux_cont+1;
			echo "<tr>";
			echo "<td>".$aux_cont."</td>";
			echo "<td>$inscripcion[codigo]</td>";
			echo "<td align='left'>$inscripcion[nombres]</td>";		
			echo "<td align='right'>$inscripcion[hora_conex]</td>";	
			echo "</tr>";
			//$message = $message . "$inscripcion[insc_tipo1]".": "."$inscripcion[cont_insc]"."\n"."\r <br>";
			//$aux_suminsc = $aux_suminsc + $inscripcion['cont_insc'];
	}
	?>
		<tr>
		</tr>
	</table>

<?php
/*******************************************************************************/
$sql = "select if(insc_tipo='I','Nuevos',if(insc_tipo='R','Regulares','Reingreso')) as insc_tipo1,insc_tipo,count(*) as cont_insc from eva_inscripciones group by insc_tipo;";

$resultado = mysql_query($sql,$conexion);
$sql = "select if(insc_tipo='I','Nuevos',if(insc_tipo='R','Regulares','Reingreso')) as insc_tipo1,insc_tipo,count(*) as cont_insc from eva_inscripciones where insc_codusu in (select dep_cedula from eva_depositos where dep_status=1) group by insc_tipo;";
$resultado1 = mysql_query($sql,$conexion);
$filas=mysql_num_rows($resultado1);

//convertir resultado n un array
$aux_cadbuscar="@";
$aux_cont=0;
$message="";
$aux_suminsc=0;
?>

	<table width="50%" class="table table-striped table-bordered table-hover table-condensed">
		<tr>
			<td colspan='6' align='center' class="bg-primary"><B>Estadistica de Inscripcion</B></td>
		</tr>
		<tr>
			<td><B>Tipo Insc.</B></td>
			<td align="right"><B>Cantidad</B></td>
		</tr>
<?php
		while($inscripcion = mysql_fetch_array($resultado))
		{
				$aux_cont=$aux_cont+1;
				echo "<tr>";
				echo "<td>$inscripcion[insc_tipo1]</td>";
				echo "<td align='right'>$inscripcion[cont_insc]</td>";		
				echo "</tr>";
				$message = $message . "$inscripcion[insc_tipo1]".": "."$inscripcion[cont_insc]"."\n"."\r <br>";
				$aux_suminsc = $aux_suminsc + $inscripcion['cont_insc'];
		}
		echo "<tr>";
		echo "<td align='right'><B>Total:</B></td>";
		echo "<td align='right'><B>$aux_suminsc</B></td>";
		echo "</tr>";
?>
	</table>

<?php
if ($filas>0)
{
?>
	<table width="50%" class="table table-striped table-bordered table-hover table-condensed">
		<tr>
		<td colspan='6' align='center' class="bg-primary"><B>Estudiantes No Validados</B></td>
		</tr>
		<tr>
		<td><B>Tipo Insc.</B></td>
		<td align='center'><B>Cantidad</B></td>
		</tr>
<?php
	$aux_suminsc = 0;
	while($inscripcion = mysql_fetch_array($resultado1))
	{
		echo "<tr>";
		echo "<td>$inscripcion[insc_tipo1]</td>";
		echo "<td align='right'>$inscripcion[cont_insc]</td>";		
		echo "</tr>";
		$message = $message . "$inscripcion[insc_tipo1]".": "."$inscripcion[cont_insc]"."\n"."\r <br>";
		$aux_suminsc = $aux_suminsc + $inscripcion['cont_insc'];
	}
		echo "<tr>";
		echo "<td align='right'><B>Total:</B></td>";
		echo "<td align='right'><B>$aux_suminsc</B></td>";
		echo "</tr>";
		echo "</table>";
}
?>
	

<?php
	$sql = "select carr_nombre as carrera,insc_semestre,count(*) as cont_insc from eva_inscripciones inner join eva_carrera on eva_inscripciones.insc_codcarr=eva_carrera.carr_codcar group by insc_codcarr,insc_semestre;";
	$resultado2 = mysql_query($sql,$conexion);

	$aux_cadbuscar="@";
	$aux_cont=0;
	$message="";
	$aux_suminsc=0;
	$aux_suminsccarre=0;
	$aux_carre="";
?>
	<table width="50%" class="table table-striped table-bordered table-hover table-condensed">
		<tr>
			<td colspan='6' align='center' class="bg-primary"><B>Insc por Carrera y Semestre</B></td>
		</tr>
<?php
	while($inscripcion = mysql_fetch_array($resultado2))
	{
		$aux_cont=$aux_cont+1;
		$aux_carre1 = $inscripcion['carrera'];
		if (!($aux_carre==$aux_carre1))
		{
			if ($aux_cont>1)
			{
				echo "<tr>";
				echo "<td align='right'><B>Total x Carrera:</B></td>";
				echo "<td align='center'><B>$aux_suminsccarre</B></td>";
				echo "</tr>";
				$aux_suminsccarre=0;
			}
			echo "<tr>";
			echo "<td colspan='2' align='center'><B>$inscripcion[carrera]</B></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align='center'><B>Semestre</B></td>";
			echo "<td align='center'><B>Cantidad</B></td>";
			echo "</tr>";
			$aux_carre=$inscripcion['carrera'];
		}
		echo "<tr>";
		echo "<td align='center'>$inscripcion[insc_semestre]</td>";
		echo "<td align='center'>$inscripcion[cont_insc]</td>";		
		echo "</tr>";
		$message = $message . "$inscripcion[insc_semestre]".": "."$inscripcion[cont_insc]"."\n"."\r <br>";
		$aux_suminsc = $aux_suminsc + $inscripcion['cont_insc'];
		$aux_suminsccarre = $aux_suminsccarre + $inscripcion['cont_insc'];
	}
		echo "<tr>";
		echo "<td align='right'><B>Total x Carrera:</B></td>";
		echo "<td align='center'><B>$aux_suminsccarre</B></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td align='right'><B>TOTAL GENERAL:</B></td>";
		echo "<td align='center'><B>$aux_suminsc</B></td>";
		echo "</tr>";
		echo "</table>";
/*
echo "Estadistica de Inscripcion: <br>";
echo $message;
echo "Total: ".$aux_suminsc;
*/
/*
$to      = "gmoreno@softservica.com";
$subject = "Estadistica Inscripcion ONLINE";
$message = "Estadistica de Inscripcion:"."\n"."\n"."$message";
$from    = "info@softservica.com";
$headers = "From: ". $from;	
mail($to,$subject,$message,$headers);
*/
//cerrar conexion
mysql_close($conexion)
?>
  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../bootstrap/js/jquery-3.1.1.min.js"></script>
 </body>
</html>

