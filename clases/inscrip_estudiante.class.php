<?php
class inscrip_estudiante
{

	function consultar_inscripcion($con,$cedula)
	{
		$respuesta = array();
	   //$sql="select * from operaciones order by orden_ope";
		$respuesta['tabla'] ="
			<table width='90%' class='table table-striped table-bordered table-hover table-condensed'>
			<tr>
				<td class='bg-primary'>Cód. Mat.</td>
				<td class='bg-primary'>Materia</td>
				<td class='bg-primary'>Sección</td>
				<td class='bg-primary'>Cond.</td>
				<td class='bg-primary'>U/Cred</td>
			</tr>";
		$sql="select est_nombres,est_apellidos
			from eva_estudiante
			where est_ced='$cedula';";
		$ok=mysql_query($sql,$con);
		$filas=mysql_num_rows($ok);
		$respuesta['exitoest'] = false;
		if($filas>0)
		{
			$datos=mysql_fetch_assoc($ok);
			$respuesta['exitoest'] = true;
			$respuesta['nomape'] = $datos["est_nombres"]." ".$datos["est_apellidos"];
		}

		$sql="select est_ced,ifnull(est_nombres,'') as est_nombres,ifnull(est_apellidos,'') as est_apellidos,
			carr_nombre,matersem_codmat,mat_descripcion,matersem_codsec,matersem_condicion,
			substr(cod_soft_servi,8,1) as unicre
			from eva_estudiante inner join eva_inscripciones
			on est_ced=insc_codusu
			inner join eva_matersemestre
			on est_ced=matersem_cedula
			inner join eva_materias
			on matersem_codmat=eva_materias.fid
			inner join eva_carrera
			on insc_codcarr=carr_codcar
			where est_ced='$cedula';";
		//echo $sql;
		$ok=mysql_query($sql,$con);
		$filas=mysql_num_rows($ok);
		$respuesta['exitoinsc'] = false;
		if($filas>0)
		{
			$respuesta['exitoinsc'] = true;
		}
		$sum_uc = 0;
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
			$sum_uc += $datos["unicre"];
			$respuesta['nomcarrera'] =$datos["carr_nombre"];
			$codmat=$datos["matersem_codmat"];
			$nommat=$datos["mat_descripcion"];
			$codsec=$datos["matersem_codsec"];
			$condic=$datos["matersem_condicion"];
			$unicre=$datos["unicre"];
			$respuesta['tabla'] .= "<tr>
					<td>$codmat</td>
					<td>$nommat</td>
					<td>$codsec</td>
					<td align='center'>$condic</td>
					<td align='center'>$unicre</td>
				</tr>";	   
		}
		$respuesta['tabla'] .= "
		<tr>
			<td colspan='5' align='center' class='bg-primary'>
				Total Materias: ".$filas."  -   Unid. Crédito: ".$sum_uc."
			</td>
		</tr>
		";
		$respuesta['tabla'] .= "</table>";
		echo json_encode($respuesta);

	/*
			 
		      
	   */
		 
	}
}
?>