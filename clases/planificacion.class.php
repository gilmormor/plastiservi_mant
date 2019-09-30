<?php
class planificacion
{
	function insertar($conexion,$pla_lapso,$pla_codsec,$pla_codmat,$pla_porc,$pla_desc,$pla_fecha,$pla_objeti)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la inserción.";
		$fecha=substr($pla_fecha,6,4)."/".substr($pla_fecha,3,2)."/".substr($pla_fecha,0,2);
		$sql = "insert into eva_planificacion(pla_peresc,pla_lapso,pla_codmat,pla_codsec,pla_porc,pla_cedprof,pla_desc,pla_fecha,pla_objeti) values('".$_SESSION['periescolar']."','$pla_lapso','$pla_codmat','$pla_codsec','$pla_porc','".$_SESSION['cedula']."','$pla_desc','$fecha','$pla_objeti');";
		//echo $sql;
		$ok=$conexion->guardar($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Insersion Exitosa.";
		}
		echo json_encode($respuesta);
	}

	function buscarmostrar($conexion,$pla_lapso,$pla_codsec,$pla_codmat)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$aux_tabla = "";
		$aux_totalporcen = 0;
		$sql = "select * from eva_planificacion where pla_peresc='".$_SESSION['periescolar']."' and pla_cedprof='".$_SESSION['cedula']."' and pla_lapso='$pla_lapso' and pla_codmat='$pla_codmat' and pla_codsec='$pla_codsec' order by pla_fecha;";
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Planificacion de Sección '.$pla_codsec.' Encontrada.';
			$i = 0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i += 1;
				$fid        = $datos["fid"];
				$pla_codmat = $datos["pla_codmat"];
				$pla_codsec = $datos["pla_codsec"];
				$pla_porc   = $datos["pla_porc"];
				$pla_desc   = $datos["pla_desc"];
				$pla_fecha  = $datos["pla_fecha"];
				$pla_objeti = $datos["pla_objeti"];
				$pla_fechadmy = date("d-m-Y", strtotime($pla_fecha));
				$aux_totalporcen += $pla_porc;

				$aux_tabla .= "
				<tr>
					<td><input src='../imagenes/borrar.png' value='$fid' onClick='eliminar_fila(this,$i)' type='image' class='form-control input-sm' title='Eliminar' name='fid$i' id='fid$i'>
					</td>
					<td><input type='text' value='$pla_desc' name='instrumento$i' id='instrumento$i' class='form-control input-sm' readonly disabled>
					</td>
					<td><input type='text' value='$pla_fechadmy' name='fecha$i' id='fecha$i' class='form-control input-sm' readonly disabled>
					</td>
					<td><input type='text' value='$pla_objeti' name='objetivo$i' id='objetivo$i' class='form-control input-sm' readonly disabled>
					</td>
					<td><input type='text' style='text-align:right;' value='$pla_porc' name='porcentaje$i' id='porcentaje$i' class='form-control input-sm' readonly disabled>
					</td>
				</tr>";	
			}
			$respuesta['tabla'] ="
				<tr>
					<td colspan='5' align='center' class='bg-primary'>Detalle de Planificación</td>
				</tr>
				<tr>
					<td colspan='4' align='right'>Total %:</td>
					<td><input type='text' style='text-align:right;' name='total' id='total' value='$aux_totalporcen' readonly class='form-control input-sm'></td>
				</tr>";
			$respuesta['tabla'] .= $aux_tabla;
				/*
				$respuesta['tabla'] .= "
				<tr>
					<td colspan='9' align='center'>
						<button type='button' id='guardar' name='guardar' class='btn btn-primary'>Guardar</button>
					</td>
				</tr>";
				*/
				$respuesta['nroreg'] = $i;
				$respuesta['totalporcen'] = $aux_totalporcen;
		}
		else
		{
			$respuesta['mensaje'] = "Planificación No existe.";
		}
		echo json_encode($respuesta);
	}

	function eliminar($conexion,$fid)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la Eliminacion.";
		$sql = "delete from eva_planificacion where fid='$fid';";
		echo $sql;
		$ok=$conexion->eliminar($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Eliminación Exitosa.";
		}
		echo json_encode($respuesta);
	}

	function buscarmostrarplanotas($conexion,$pla_lapso,$pla_codsec,$pla_codmat)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$aux_tabla = "";
		$aux_totalporcen = 0;
		$sql = "select * from eva_planificacion where pla_peresc='".$_SESSION['periescolar']."' and pla_cedprof='".$_SESSION['cedula']."' and pla_lapso='$pla_lapso' and pla_codmat='$pla_codmat' and pla_codsec='$pla_codsec' order by pla_fecha;";
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Planificacion de Sección '.$pla_codsec.' Encontrada.';
			$i = 0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i += 1;
				$fid        = $datos["fid"];
				$pla_codmat = $datos["pla_codmat"];
				$pla_codsec = $datos["pla_codsec"];
				$pla_porc   = $datos["pla_porc"];
				$pla_desc   = $datos["pla_desc"];
				$pla_fecha  = $datos["pla_fecha"];
				$pla_objeti = $datos["pla_objeti"];
				$pla_fechadmy = date("d-m-Y", strtotime($pla_fecha));
				$aux_totalporcen += $pla_porc;

				$aux_tabla .= "
				<tr>
					<td><input src='../imagenes/borrar.png' value='$fid' onClick='nominanotas(this,$i)' type='image' class='form-control input-sm' title='Eliminar' name='fid$i' id='fid$i'>
					</td>
					<td><input type='text' value='$pla_desc' name='instrumento$i' id='instrumento$i' class='form-control input-sm' readonly disabled>
					</td>
					<td><input type='text' value='$pla_fechadmy' name='fecha$i' id='fecha$i' class='form-control input-sm' readonly disabled>
					</td>
					<td><input type='text' value='$pla_objeti' name='objetivo$i' id='objetivo$i' class='form-control input-sm' readonly disabled>
					</td>
					<td><input type='text' style='text-align:right;' value='$pla_porc' name='porcentaje$i' id='porcentaje$i' class='form-control input-sm' readonly disabled>
					</td>
				</tr>";	
			}
			$respuesta['tabla'] ="
				<tr>
					<td colspan='5' align='center' class='bg-primary'>Detalle de Planificación</td>
				</tr>
				<tr>
					<td colspan='4' align='right'>Total %:</td>
					<td><input type='text' style='text-align:right;' name='total' id='total' value='$aux_totalporcen' readonly class='form-control input-sm'></td>
				</tr>";
			$respuesta['tabla'] .= $aux_tabla;
				/*
				$respuesta['tabla'] .= "
				<tr>
					<td colspan='9' align='center'>
						<button type='button' id='guardar' name='guardar' class='btn btn-primary'>Guardar</button>
					</td>
				</tr>";
				*/
				$respuesta['nroreg'] = $i;
				$respuesta['totalporcen'] = $aux_totalporcen;
		}
		else
		{
			$respuesta['mensaje'] = "Planificación No existe.";
		}
		echo json_encode($respuesta);
	}

}
?>