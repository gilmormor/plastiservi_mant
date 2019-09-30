<?php
class convenio
{
	function agregar_convenio($conexion,$cedula_estudiante)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Registro fue Guardado.';
		$sql="select * from eva_convenio where cedula_estudiante='$cedula_estudiante';";
		$ok=mysql_query($sql,$conexion);
		if (mysql_num_rows($ok)>0)
		{
			$respuesta['mensaje'] = 'No se guardo. Cédula ya habia sido registrada en convenio.';
		}else
		{
			$sql="insert into eva_convenio(cedula_estudiante) values('$cedula_estudiante');";
			//echo $sql;
			$ok=mysql_query($sql,$conexion);
			if ($ok)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = 'Registro Guardado con Exito.';
			}
		}
		echo json_encode($respuesta);
	}
}

?>