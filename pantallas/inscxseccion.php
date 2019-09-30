<?php
require_once("../clases/conexion.class.php");

$objConexiones=new conexion;

$conexion=$objConexiones->conectar();

$sql = "select matersem_codsec,matersem_codmat,mat_descripcion,
(select masec_capacidad from eva_materseccion where matersem_codsec=masec_codsec group by masec_codsec) as masec_capacidad,
count(*) as contador,
(select masec_statusvirtual from eva_materseccion where matersem_codmat=masec_codmat and masec_codsec=matersem_codsec group by masec_codmat) as masec_statusvirtual,
mac_comun
from eva_matersemestre inner join eva_matercarrera
on matersem_codmat=mac_materia
inner join eva_materias on matersem_codmat=eva_materias.fid
group by matersem_codsec,matersem_codmat
order by matersem_codsec,mac_comun,matersem_codmat";

$resultado = mysql_query($sql,$conexion);
/* $sql = "select if(insc_tipo='I','Nuevos',if(insc_tipo='R','Regulares','Reingreso')) as insc_tipo1,insc_tipo,count(*) as cont_insc from eva_inscripciones where insc_codusu in (select dep_cedula from eva_depositos where dep_status=1) group by insc_tipo;";
$resultado1 = mysql_query($sql,$conexion);
 */
//convertir resultado n un array
$aux_cadbuscar="@";
$aux_cont=0;
$message="";
$aux_suminsc=0;
echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">';
echo '<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="../css/estilos_nivel2.css">';

echo "<table width='90%' class='table table-striped table-bordered table-hover table-condensed'>";
echo "<tr>";
echo "<td colspan='7' align='center' class='bg-primary'><B>Inscritos por Seccion</B></td>";
echo "</tr>";
echo "<tr>";
echo "<td><B>Seccion</B></td>";
echo "<td align='center'><B>Cod Materia</B></td>";
echo "<td align='center'><B>Materia</B></td>";
echo "<td align='center'><B>Virtual</B></td>";
echo "<td align='center'><B>Comun</B></td>";
echo "<td align='center'><B>Capac</B></td>";
echo "<td align='center'><B>Inscritos</B></td>";
echo "</tr>";
while($inscripcion = mysql_fetch_array($resultado))
{
		$aux_cont=$aux_cont+1;
		if ($aux_cont==1)
		{
			$aux_codsec=$inscripcion['matersem_codsec'];
			$aux_codmat=$inscripcion['matersem_codmat'];
			$aux_comun=$inscripcion['mac_comun'];
			$aux_descmat=$inscripcion['mat_descripcion'];
			$aux_continsc=0;
		}
		//$aux_comun=$inscripcion['mac_comun'];
		if ($inscripcion['mac_comun']>0)
		{
			
			if (($aux_codsec==$inscripcion['matersem_codsec']) && ($aux_comun==$inscripcion['mac_comun']))
			{
				$aux_continsc=$aux_continsc+$inscripcion['contador'];
			}
			else
			{
				if ($aux_continsc>0)
				{
					echo "<tr>";
					echo "<td colspan='6' align='right'><B>TOTAL $aux_codsec - $aux_descmat</B></td>";
					echo "<td align='center'><B>$aux_continsc</B></td>";
					echo "</tr>";
					echo "<tr>";
					echo "<td colspan='7' align='center'><B>---</B></td>";
					echo "</tr>";
				}
				else
				{
					echo "<tr>";
					echo "<td colspan='7' align='center'><B>---</B></td>";
					echo "</tr>";
				}
				$aux_continsc=0;
				$aux_continsc=$aux_continsc+$inscripcion['contador'];
				$aux_codsec=$inscripcion['matersem_codsec'];
				$aux_codmat=$inscripcion['matersem_codmat'];
				$aux_comun=$inscripcion['mac_comun'];
				$aux_descmat=$inscripcion['mat_descripcion'];
			}
		}
		else
		{
			if ($aux_continsc>0)
			{
				echo "<tr>";
				echo "<td colspan='6' align='right'><B>TOTAL $aux_codsec - $aux_descmat</B></td>";
				echo "<td align='center'><B>$aux_continsc</B></td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td colspan='7' align='center'><B>---</B></td>";
				echo "</tr>";
			}
			$aux_continsc=0;
			
		}
		echo "<tr>";
		echo "<td align='center'>$inscripcion[matersem_codsec]</td>";
		echo "<td align='center'>$inscripcion[matersem_codmat]</td>";
		echo "<td align='left'>$inscripcion[mat_descripcion]</td>";
		if ($inscripcion['masec_statusvirtual']==NULL)
			echo "<td align='center'>0</td>";
		else
			echo "<td align='center'>$inscripcion[masec_statusvirtual]</td>";	
		if ($inscripcion['mac_comun']>0)
			echo "<td align='center'>Si</td>";
		else
			echo "<td align='center'>No</td>";
		echo "<td align='center'>$inscripcion[masec_capacidad]</td>";	
		echo "<td align='center'>$inscripcion[contador]</td>";
		echo "</tr>";
}

mysql_close($conexion)
?>