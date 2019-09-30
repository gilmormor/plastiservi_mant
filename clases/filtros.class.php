<?php
class filtros
{
	function buscarfiltros($conexion)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$sql = "select *,DATE_FORMAT(fil_fecha_dep,'%d/%m/%Y') as fil_fecha_dep_dma,
		if(now()<fil_fecfininsc,1,0) as sta_inscviva from eva_filtros;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Filtros encontrados';
			$datos=mysql_fetch_assoc($ok);
			$respuesta['datos'] = $datos;
		}else
		{
			$respuesta['mensaje'] = "Tabla de filtros vacia.";
		}
		echo json_encode($respuesta);
	}
}
?>