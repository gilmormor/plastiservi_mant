<?php
class planteles
{
	function buscarplantel($conexion,$plan_est_codigo)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$sql = "select * from eva_planteles where plan_est_codigo='$plan_est_codigo';";
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Plantel encontrado.';
			$datos=mysql_fetch_assoc($ok);
			$respuesta['datosplantel'] = $datos;
		}else
		{
			$respuesta['mensaje'] = "Plantel no existe.";
		}
		echo json_encode($respuesta);
	}
}
?>