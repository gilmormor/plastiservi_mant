<?php
//require_once "Conexion.class.php";
class inscripciones
{
	function consultar($conexion,$est_ced,$periescol)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Cédula no Existe";
		$sql = "select * from eva_inscripciones where insc_codusu='$est_ced' and insc_codlapso='$periescol';";
		$sql = "select * 
		from eva_inscripciones inner join eva_matinscritas
		on insc_codusu=ced_alum
		where insc_codusu='$est_ced' and insc_codlapso='$periescol' and periescolar='$periescol'
		and (cond_materia='RG' or cond_materia='RP')
		group by ced_alum;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Cedula encontrada.";
				$datos=mysql_fetch_assoc($ok); //Crea Vector asociativo con el mismo nombre de los datos de la Tabla
				$respuesta['datos'] = $datos;
			}
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}
}
?>