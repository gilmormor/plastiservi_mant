<?php
class utilidades
{

	function validarErrores($conexion,$tabla){
	echo '<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery-3.1.1.min.js"></script>';


	$error=mysql_errno($conexion); //extraigo el codigo de error
	
		switch($error){
		case '1146': echo "El nombre de la tabla esta incorrecto. Error en $tabla";	break;
		case '1136': echo "La cantidad de columnas no coincide con la cantidad de valores. Error en $tabla";	break;
		case '1064': echo "Error de sintaxis, revisa con detenimiento la sentencia. Error en $tabla";	break;
		/*case '1062': echo "Error: El $tabla ya se encuentra registrado";	break;*/
		case '1062': echo "<script>alert('El registro ya existe en la Base de Datos');</script>";
					 echo "Error: El $tabla ya se encuentra registrado";	break;
		default: echo "Error Desconocido. Investigar en la documentacion el error numero $error";	break;
		
		
		
		}
				
	}	

	function hacer_lista_desplegable($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='form-control' title='Seleccione una opcion'>";
		echo "<option value=''>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}


	function hacer_lista_desplegableB4($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='selectpicker show-tick form-control' data-toggle='tooltip' title='Seleccione...'>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegableB4SubText($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion,$subtext)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='selectpicker show-tick form-control' data-toggle='tooltip' title='Seleccione...' data-placement='top' data-live-search='true' data-size='8'>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]' data-subtext='$datos[$subtext]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegableB41($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='selectpicker show-tick form-control' title='Seleccione...' data-toggle='tooltip'>";
		echo "<option value=''>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegableBusB41($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='selectpicker show-tick form-control' title='Seleccione...' data-toggle='tooltip' data-live-search='true'>";
		//echo "<option value=''>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegableBusB41S($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='selectpicker show-tick form-control' title='Seleccione...' data-toggle='tooltip' data-live-search='true'>";
		echo "<option value=''>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegableMultiple($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion,$subtext)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		//echo $datos[$subtext];
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select class='selectpicker form-control form-control-lg' id='$nombre' name='$nombre' onChange='$funcion' multiple title='Seleccione...' data-toggle='tooltip' data-placement='top' data-live-search='true' data-size='8'>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  	echo "<option value='$datos[$value]' data-subtext='$datos[$subtext]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegableMultiSelecB3($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select class='selectpicker' multiple title='Seleccione...'>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
	}

	function hacer_lista_desplegable_requerida($conexion,$tabla,$value,$mostrar,$nombre,$funcion)
	{
		$sql="select * from $tabla";
		//$ok=mysql_query($sql,$conexion);
		$ok = $conexion->ejecutarQuery($sql);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='form-control' required>";
		echo "<option value=''>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option> value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
		
	}
	
	function mostrarMensaje($tabla)
	{
		//echo "<div>Registro procesado correctamente en $tabla</div>";
		echo "<script>
		    alert('Registro procesado correctamente.');
		    </script>";
	}

	function seleccionad($valor)
	{
		$respuesta = "";
		switch($valor) {
			case 0:
				$respuesta = "selected";
				break;
			case 1:
				$respuesta = "selected";
				break;
			case 2:
				$respuesta = "selected";
				break;
			case 3:
				$respuesta = "selected";
				break;
			default:
				# code...
				break;
			return $respuesta;
		}
	}

	function ruta_script()
	{
		return trim(substr($_SERVER['PHP_SELF'],13));
		/*return trim($_SERVER['SERVER_NAME']);
		$ruta = trim($_SERVER['PHP_SELF']);
		$posicion = strpos($ruta,'/',2) + 1;
		return trim(substr($_SERVER['PHP_SELF'],1));
		*/
	}

	
}

?>