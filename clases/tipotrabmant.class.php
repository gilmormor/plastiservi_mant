<?php
//require_once "Conexion.class.php";
class tipotrabmant
{
	function llenarTabla($conexion){
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Fallo la consulta";
		$respuesta['tabla'] = "";
		$usuarioID = $_SESSION["usuarioID"];
		$sql = "select *
			from tipotrabmant where usuarioIDdelete=0;";
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
						<th>Tipo de actividad</th>
						<th>Indicaciones de Seguridad</th>
						<th class='col-xs-2 col-sm-2'>Acción</th>
						<th style='display:none;'>definicion</th>
						<th style='display:none;'>displegal</th>
						<th style='display:none;'>usuarioID</th>
					</tr>
				</thead>
				<tbody>";
			$i = 0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i++; 
				$ttmID      = $datos["ttmID"];
				$descrip    = $datos["descrip"];
				$indseg     = $datos["indseg"];
				$definicion = $datos["definicion"];
				$displegal  = $datos["displegal"];
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i'>
						<td id='ttmID$i' name='ttmID$i'>$ttmID</td>
						<td id='descrip$i' name='descrip$i'>$descrip</td>
						<td id='indseg$i' name='indseg$i'>$indseg</td>
						<td>
							<a id='modificar$i' name='modificar$i' class='btn btn-primary btn-sm' onclick='modificar($i)' title='Modificar Observación' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil' style='bottom: 0px;top: 2px;'></span></a> | <a id='eliminar$i' name='eliminar$i' class='btn btn-danger btn-sm' onclick='eliminar($i)' title='Eliminar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash' style='bottom: 0px;top: 2px;'></span></a>
						</td>
						<td id='definicion$i' name='definicion$i' style='display:none;'>$definicion</td>
						<td id='displegal$i' name='displegal$i' style='display:none;'>$displegal</td>
						<td id='usuarioID$i' name='usuarioID$i' style='display:none;'>$usuarioID</td>
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

	function insertUpdate($conexion,$ttmID,$descrip,$indseg,$definicion,$displegal){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";

		$usuarioID = $_SESSION["usuarioID"];
		if($ttmID == ""){
			$sql = "insert into tipotrabmant(descrip,indseg,definicion,displegal,fechaInsert) value('$descrip','$indseg','$definicion','$displegal',now());";
		}else{
			$sql = "update tipotrabmant set descrip='$descrip',indseg='$indseg',definicion='$definicion',displegal='$displegal' where ttmID='$ttmID' and usuarioIDdelete=0;";
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

	function eliminar($conexion,$ttmID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$aux_usuarioID = $_SESSION["usuarioID"];
		$sql = "update tipotrabmant set fechadelete=now(),usuarioIDdelete=$aux_usuarioID where ttmID='$ttmID' and usuarioIDdelete=0;"; 
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