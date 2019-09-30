<?php
class representante
{
	function buscar($conexion,$rep_ced)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$sql = "select * from eva_representantes where rep_ced='$rep_ced';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Representante encontrado.';
			$datos=mysql_fetch_assoc($ok);
			$respuesta['datosrepre'] = $datos;
		}else
		{
			$respuesta['mensaje'] = "Cédula no existe, Debe agregar los datos.";
		}
		echo json_encode($respuesta);
	}

	function consultarXrepest($conexion,$rep_ced)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$sql = "select est_ced,est_nombres,est_apellidos,rep_ced,rep_nomrep
				from eva_repest inner join eva_representantes
				on eva_repest.rep_cedrep = eva_representantes.rep_ced
				inner join eva_estudiante
				on rep_cedalum = est_ced
				where eva_repest.rep_cedrep='$rep_ced';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Representante encontrado.';
			$datos=mysql_fetch_assoc($ok);
			$respuesta['datosrepre'] = $datos;
		}else
		{
			$respuesta['mensaje'] = "Cédula no existe.";
		}
		echo json_encode($respuesta);
	}


}
?>