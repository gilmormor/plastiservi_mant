<?php
class repest
{
	function buscar($conexion,$rep_cedalum)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$sql = "select * from eva_repest where rep_cedalum='$rep_cedalum';";
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Representante de estudiante encontrado.';
			$datos=mysql_fetch_assoc($ok);
			$respuesta['datosrep'] = $datos;
		}else
		{
			$respuesta['mensaje'] = "Estudiante no tiene Representantes.";
		}
		echo json_encode($respuesta);
	}
}
?>