<?php
require_once("utilidades.class.php");
class seccion extends utilidades
{

	function update_matersecc($conexion,$codsec,$codmat,$capaci,$activa,$virtua,$pendie,$actcom,$nuevos)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Registro no fue guardado';
		$sql="update eva_materseccion set masec_capacidad='$capaci',masec_activa='$activa',masec_statusvirtual='$virtua',masec_matpen='$pendie',masec_cod_activ='$actcom',masec_nuevoing='$nuevos'  where masec_codmat='$codmat' and masec_codsec='$codsec';";
		//echo $sql;
		$ok=mysql_query($sql,$conexion);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Materia $codmat guardada con exito.";
		}
		echo json_encode($respuesta);

	}
	function consultar($conexion,$masec_codsec)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Sección no existe.';
		$respuesta['nroreg'] = 0;
		if (empty($masec_codsec))
			$respuesta['mensaje'] = 'Seleccione una Sección.';
		else
		{
			$sql="select masec_codsec,masec_codmat,mat_descripcion,substr(cod_soft_servi,8,1) as unicre,
			masec_capacidad,masec_activa,masec_statusvirtual,masec_matpen,masec_cod_activ,masec_nuevoing
			from eva_materseccion inner join eva_materias
			on masec_codmat=eva_materias.fid
			where masec_codsec='$masec_codsec';";
			$ok=mysql_query($sql,$conexion);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = 'Sección '.$masec_codsec.' Encontrada.';
				$respuesta['tabla'] ="
					<table id='seccion' width='90%' class='table table-striped table-bordered table-hover table-condensed'>
					<tr>
						<td class='bg-primary'>CodMat</td>
						<td class='bg-primary'>Materia</td>
						<td class='bg-primary'>U/Cred</td>
						<td class='bg-primary' width=20px>Capacidad</td>
						<td class='bg-primary'>Activa</td>
						<td class='bg-primary'>Virtual</td>
						<td class='bg-primary'>Pendiente</td>
						<td class='bg-primary'>Act.Comp.</td>
						<td class='bg-primary'>Est.Nuevos</td>
						<td class='bg-primary' style='display:none'>sta Mod</td>
					</tr>";
				/*
				while ($row = @mysql_fetch_array($ok))
					$data[] = $row;
				*/
				$i = 0;
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$i += 1;
					$codmat=$datos["masec_codmat"];
					$descri=$datos["mat_descripcion"];
					$unicre=$datos["unicre"];
					$capaci=$datos["masec_capacidad"];
					$activa=$datos["masec_activa"];
					$stavir=$datos["masec_statusvirtual"];
					$matpen=$datos["masec_matpen"];
					$actcom=$datos["masec_cod_activ"];
					$nuevos=$datos["masec_nuevoing"];
					$act0 = "selected";
					$act1 = "";
					if ($activa=='1')
					{
						$act0 = "";
						$act1 = "selected";
					}
					$sv0 = "selected";
					$sv1 = "";
					if ($stavir=='1')
					{
						$sv0 = "";
						$sv1 = "selected";
					}
					$mp0 = "selected";
					$mp1 = "";
					if ($matpen=='1')
					{
						$mp0 = "";
						$mp1 = "selected";
					}
					$ac0 = "selected";
					$ac1 = "";
					if ($actcom=='1')
					{
						$ac0 = "";
						$ac1 = "selected";
					}
					$n0 = "selected";
					$n1 = "";
					if ($nuevos=='1')
					{
						$n0 = "";
						$n1 = "selected";
					}
					$respuesta['tabla'] .= "<tr>
							<td name='codmat$i' id='codmat$i'>$codmat</td>
							<td>$descri</td>
							<td align='center'>$unicre</td>
							<td align='center' width=20px><input type='text' name='capaci$i' id='capaci$i' class='form-control' value=$capaci onChange='actstaguar($i)'></td>
							<td><select name='activa$i' id='activa$i' class='form-control' onChange='actstaguar($i)'>
							<option value='1' $act1>Si</option>
							<option value='0' $act0>No</option></select></td>
							<td><select name='virtua$i' id='virtua$i' class='form-control' onChange='actstaguar($i)'>
							<option value='1' $sv1>Si</option>
							<option value='0' $sv0>No</option></select></td>
							<td><select name='pendie$i' id='pendie$i' class='form-control' onChange='actstaguar($i)'>
							<option value='1' $mp1>Si</option>
							<option value='0' $mp0>No</option></select></td>
							<td><select name='actcom$i' id='actcom$i' class='form-control' onChange='actstaguar($i)'>
							<option value='1' $ac1>Si</option>
							<option value='0' $ac0>No</option></select></td>
							<td><select name='nuevos$i' id='nuevos$i' class='form-control' onChange='actstaguar($i)'>
							<option value='1' $n1>Si</option>
							<option value='0' $n0>No</option></select></td>
							<td style='display:none'><input type='checkbox' id='staguardar$i' name='staguardar$i' class='form-control'></td>
						</tr>";	
				}
				/*
				$respuesta['tabla'] .= "
				<tr>
					<td colspan='9' align='center'>
						<button type='button' id='guardar' name='guardar' class='btn btn-primary'>Guardar</button>
					</td>
				</tr>";
				*/
				$respuesta['tabla'] .= "</table>";
				$respuesta['nroreg'] = $i;

			}
		}
		//echo json_encode($data);
		echo json_encode($respuesta);

	}
}
?>