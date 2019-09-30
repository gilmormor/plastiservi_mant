<?php
class datoscolegio 
{
/*******************************************************************************************/	
	function colsultar($conexion)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Usuario o clave no existe.";
		$sql="select * from eva_datoscolegio;";
		//echo $sql;
		$filas = $conexion->filas($sql);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Bienvenido";
			$datos = $conexion->ejecutar($sql);
			$respuesta['datos'] = $datos;
		}else
		{
			$respuesta['mensaje'] = "Tabla: Datos Colegio esta Vacia.";
		}
		echo json_encode($respuesta);
	}
}