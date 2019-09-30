<?php
//require_once "Conexion.class.php";
class maquinaequipofueraservicio
{
	function llenarTabla($conexion){
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Fallo la consulta";
		$respuesta['tabla'] = "";
		$usuarioID = $_SESSION["usuarioID"];
		$sql = "select maquinaequipofueraservicio.*,
		concat(maquinariaequiposdetalle.codigoInterno,' - ', maquinariaequipos.descripcion) AS codigoInterno
		FROM maquinaequipofueraservicio INNER JOIN maquinariaequiposdetalle
		ON maquinaequipofueraservicio.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID
		INNER JOIN maquinariaequipos
		on maquinariaequiposdetalle.maquinariaEquiposID=maquinariaequipos.maquinariaEquiposID
		WHERE maquinaequipofueraservicio.usuarioIDdelete=0
		order by maquinaequipofueraservicio.fueraServicioID;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Información encontrada.";

			$respuesta['tabla'] = "
				<table id='tablaOrdTrab' name='tablaOrdTrab' class='table display AllDataTables responsive table-hover table-condensed order-column'>
				<thead>
					<tr>
						<th>ID</th>
						<th>Máquina Equipo</th>
						<th>Descripción</th>
						<th>Acción</th>
						<th style='display:none;'>maquinariaequiposDetalleID</th>
					</tr>
				</thead>
				<tbody>";
			$i = 0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i++; 
				$fueraServicioID            = $datos["fueraServicioID"];
				$codigoInterno              = $datos["codigoInterno"];
				$descripcion                = $datos["descripcion"];
				$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i'>
						<td id='fueraServicioID$i' name='fueraServicioID$i'>$fueraServicioID</td>
						<td id='codigoInterno$i' name='codigoInterno$i'>$codigoInterno</td>
						<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
						<td>
							<a id='modificar$i' name='modificar$i' class='btn btn-primary btn-sm' onclick='modificar($i)' title='Modificar' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil' style='bottom: 0px;top: 2px;'></span></a> | <a id='eliminar$i' name='eliminar$i' class='btn btn-danger btn-sm' onclick='eliminar($i)' title='Eliminar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash' style='bottom: 0px;top: 2px;'></span></a>
						</td>
						<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i' style='display:none;'>$maquinariaequiposDetalleID</td>
					</tr>";
			}
			$respuesta['tabla'] .= "</tbody>
							  </table>";
		}else{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "Información no encontrada";
		}
		echo json_encode($respuesta);
	}

	function insertUpdate($conexion,$fueraServicioID,$descripcion,$maquinariaequiposDetalleID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";

		$usuarioID = $_SESSION["usuarioID"];
		if($fueraServicioID == ""){
			$sql = "insert into maquinaequipofueraservicio(descripcion,maquinariaequiposDetalleID,fechaHoraInsert) value('$descripcion','$maquinariaequiposDetalleID',now());";
		}else{
			$sql = "update maquinaequipofueraservicio set descripcion='$descripcion',maquinariaequiposDetalleID='$maquinariaequiposDetalleID' where fueraServicioID='$fueraServicioID' and usuarioIDdelete=0;";
		}
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Datos se actualizaron con exito.";
		}else
		{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "Falló la actualización.";
		}

		echo json_encode($respuesta);
	}

	function eliminar($conexion,$fueraServicioID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$aux_usuarioID = $_SESSION["usuarioID"];
		$sql = "update maquinaequipofueraservicio set fechadelete=now(),usuarioIDdelete=$aux_usuarioID where fueraServicioID='$fueraServicioID' and usuarioIDdelete=0;"; 
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Registro se eliminó con exito.";
		}else
		{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "Falló la actualización.";
		}
		echo json_encode($respuesta);
	}
}
?>