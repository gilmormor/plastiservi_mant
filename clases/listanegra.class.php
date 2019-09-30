<?php
class listanegra //extends es para heredar las cosas de otra clase (Hay que hacer un require once de esa clase que deseamos heredar)
{
	function consultar($conexion,$cedula,$referencia,$fechad,$fechah,$fechades,$fechahes)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Informacion no encontrada.";
		$respuesta['tabla'] = "";
		$referencia_cond = "true";
		$sql = "select est_ced,concat(ifnull(est_nombres,''),' ',ifnull(est_apellidos,'')) est_nombre,rin_descrip,rin_fecha 
				from eva_rechazoinsc inner join eva_estudiante
				on rin_cedalum=est_ced;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		$respuesta['exito'] = false;
		$respuesta['tabla'] .= '<table class="table display AllDataTables responsive table-hover table-condensed">';
			$respuesta['tabla'] .= '<thead>
				<tr>
					<th>Cedula</th>
					<th align="left">Nombre y Apellido</th>
					<th align="left">Descripcion</th>
					<th align="left">Fecha de Registro</th>
				</tr>
			</thead>
			<tbody>';
		if($filas>0)
		{
			$respuesta['exito'] = true;
		}
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
			$est_ced	= $datos["est_ced"];
			$est_nombre = $datos["est_nombre"];
			$rin_descrip= $datos["rin_descrip"];
			$rin_fecha	= $datos["rin_fecha"];
			$respuesta['tabla'] .= "<tr>
					<td>$est_ced</td>
					<td align='left'>$est_nombre</td>
					<td align='left'>$rin_descrip</td>
					<td align='left'>$rin_fecha</td>
				</tr>";	   
		}
		
		$respuesta['tabla'] .= "</tbody>";
		$respuesta['tabla'] .= "</table>";
		echo json_encode($respuesta);
	}
}
?>