<?php
class rechazadosIncripcion
{
	function buscar_json($conexion,$cedula)
	{
		$sql="select eva_rechazoinsc.*,ifnull(est_nombres,'') as est_nombres,ifnull(est_apellidos,'') as est_apellidos 
			from eva_rechazoinsc left join eva_estudiante 
			on rin_cedalum=est_ced 
			where rin_cedalum='$cedula';";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		$data=mysql_fetch_assoc($ok);
		echo json_encode($data);
	}

	function buscar($conexion,$est_ced)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Cédula no Existe";
		$sql="select eva_rechazoinsc.*,ifnull(est_nombres,'') as est_nombres,ifnull(est_apellidos,'') as est_apellidos 
			from eva_estudiante left join eva_rechazoinsc 
			on est_ced = rin_cedalum 
			where est_ced='$est_ced';";
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
				$respuesta['tablaest'] = $datos;
			}
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}
	function eliminar($conexion,$cedula)
	{
		$errores = array();
		$datos = array();
		if (empty($cedula))
			$errores['cedula'] = 'Se requiere esperificar Num de Cedula';
		if (empty($errores))
		{
			$sql="delete from eva_rechazoinsc where rin_cedalum='$cedula';";
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			//var $respuesta= array();
			if ($ok)
			{
				$datos['exito'] = true;
				$datos['mensaje'] = 'Registro Eliminado.';
			}
			else
			{
				$errores['errorbd'] = 'NO se Elimino el registro. Error con BD.';
				$datos['exito'] = false;
				$datos['errores'] = $errores;				
			}
		}else
		{
			$datos['exito'] = false;
			$datos['errores'] = $errores;
		}
		//Dar respuesta
		echo json_encode($datos);
	}

	function guardar($conexion,$cedula,$descrip)
	{
		$errores = array();
		$datos = array();

		//Validamos los parametros
		if (empty($cedula))
			$errores['cedula'] = 'Se requiere esperificar Num de Cedula';
		if (empty($descrip))
			$errores['descrip'] = 'Se requiere esperificar una descripcion';

		// Respuesta
		if (empty($errores))
		{
			$sql="select * from eva_rechazoinsc where rin_cedalum='$cedula';";
			//echo $sql;
			//$ok=mysql_query($sql,$conexion) or die(mysql_error());
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if ($filas>0)
				$sql="update eva_rechazoinsc set rin_descrip='$descrip' where rin_cedalum='$cedula';";
			else
				$sql="insert into eva_rechazoinsc(rin_cedalum,rin_descrip) values('$cedula','$descrip');";
			$ok=$conexion->ejecutarQuery($sql);
			if ($ok)
			{
				$datos['exito'] = true;
				$datos['mensaje'] = 'El registro se ha guardado con exito.';
			}
			else
			{
				$errores['errorbd'] = 'Fallo la conexion con la Base de Datos. NO se guardo el registro.';
				$datos['exito'] = false;
				$datos['errores'] = $errores;
			}
		}else
		{
			$datos['exito'] = false;
			$datos['errores'] = $errores;
		}

		//Dar respuesta
		echo json_encode($datos);
		

	}	
}

?>