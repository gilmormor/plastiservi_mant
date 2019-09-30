<?php
//require_once "Conexion.class.php";
class solicitudtrabmantpersona
{
	function guardar($conexion,$solicitudTrabID,$prioridad,$filas){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$inserciones = 0;
		$cont_guardar = 0;
		$cod_empVec = "";
		//Creo una cadena con los empleados deleccionados y eliminar los que no esten en la lista
		foreach ($filas as $fila) {
		    $cod_empVec .= "'".$fila['personaID']."',";
		}		
		$cod_empVec = substr($cod_empVec, 1, -2);
		$sql = "delete from solicitudtrabmantpersona where solicitudTrabID='$solicitudTrabID' and personaID not in ('$cod_empVec');";
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$cont_guardar = $cont_guardar + 1;
		}
		foreach ($filas as $fila) {
		    $personaID    = $fila['personaID'];
			$sql = "select * from solicitudtrabmantpersona where solicitudTrabID='$solicitudTrabID' and personaID='$personaID';";
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$filas1=mysql_num_rows($ok);
				//echo $filas1;
				if($filas1==0)
				{
			    	$sql1 = "insert into solicitudtrabmantpersona (solicitudTrabID, personaID,fechaHoraInsert) values('$solicitudTrabID',$personaID,now())"; 
			    	//echo $sql;
					$ok1=$conexion->ejecutarQuery($sql1);
					if($ok1)
					{
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = "Guardó Mecánicos.";
					}else
					{
						$respuesta['exito'] = false;
						$respuesta['mensaje'] = "Falló la actualuzación.";
					}
					$cont_guardar = $cont_guardar + 1;
				}
			}
		}
		if($cont_guardar==0){
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "No existen registros a modificar.";
		}
		$sql = "update solicitudtrabmant set prioridad=$prioridad where solicitudTrabID=$solicitudTrabID;"; 
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] .= "<br>Guardó Prioridad.";
		}else
		{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] .= "<br>Falló la actualización de prioridad !";
		}
		echo json_encode($respuesta);
	}


	function consultar($conexion,$solicitudTrabID){
		$respuesta1 = array();
		$respuesta1['exito'] = false;
		$respuesta1['mensaje'] = "";
		$personas = array();
		$sql1 = "select * from solicitudtrabmantpersona where solicitudTrabID='$solicitudTrabID';";
		//echo $sql;
		$ok1=$conexion->ejecutarQuery($sql1);
		if($ok1)
		{
			$filas1=mysql_num_rows($ok1);
			//echo $filas1;
			if($filas1>0)
			{
				$i1 = 0;
				$respuesta1['exito'] = true;
				while(($datos1=mysql_fetch_assoc($ok1))>0)
				{
					$personas[$i1] = $datos1["personaID"];
					$i1 += 1;
				}
				$respuesta1['personas'] = $personas;
			}else{
				$respuesta1['exito'] = false;
				$respuesta1['mensaje'] = "Información no encontrada.";	
			}
		}
		echo json_encode($respuesta1);
	}

	function eliminar($conexion,$solicitudTrabID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$sql = "delete from solicitudtrabmantpersona where solicitudTrabID='$solicitudTrabID';";
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Datos se eliminaron con exito.";
		}
		echo json_encode($respuesta);
	}

}
?>