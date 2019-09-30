<?php
class cliente
{
	function consultar($conexion,$cli_cedrif)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Cédula/RIF no Existe";
		$sql = "select * from eva_clientes where cli_cedrif='$cli_cedrif';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Cédula/RIF encontrada.";
				$datos=mysql_fetch_assoc($ok); //Crea Vector asociativo con el mismo nombre de los datos de la Tabla
				$respuesta['datoscli'] = $datos;
			}
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}

	
}	
?>