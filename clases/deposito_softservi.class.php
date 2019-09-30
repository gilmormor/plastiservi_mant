<?php
require_once("seguridad.class.php");
class deposito extends seguridad //extends es para heredar las cosas de otra clase (Hay que hacer un require once de esa clase que deseamos heredar)
{
	function modificar_deposito($conexion,$dep_referencia,$dep_cedula,$dep_status,$dep_fecha,$dep_monto,$montocheque)
	{
		$sql="update eva_depositos set dep_cedula='$dep_cedula',dep_status='$dep_status',dep_fecha='$dep_fecha',dep_monto='$dep_monto',dep_montocheque='$montocheque' where dep_referencia='$dep_referencia'"; //fk_mod va sin comillas porque es numerico
		//echo $sql;
		$ok=mysql_query($sql,$conexion);
		return $ok;
	}

	function buscar_depositos($conexion,$dep_referencia)
	{
		$sql="select * from eva_depositos where dep_referencia='$dep_referencia'";
		//echo $sql;
		$ok=mysql_query($sql,$conexion);
		$datos=mysql_fetch_assoc($ok);
		if($datos["dep_referencia"]==$dep_referencia)
			echo "$datos[dep_cedula]#$datos[dep_status]#$datos[dep_fecha]#$datos[dep_monto]#$datos[dep_montocheque]";
		else
			echo "no Encontrado";
	}
	function buscar_depositosjson($conexion,$dep_referencia)
	{
		$sql="select * from eva_depositos where dep_referencia='$dep_referencia'";
		//echo $sql;
		$ok=mysql_query($sql,$conexion);
		$data=mysql_fetch_assoc($ok);
		echo json_encode($data);
	}

	function buscar_depositoxcedula($conexion,$dep_cuenta,$aux_montomin,$aux_montomax)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$totalefec = 0.00;
		$totalchec = 0.00;
		$dep_referencia = "";
		$dep_fecha = "";
		$dep_montod = number_format(0.00, 2, ',', '.');
		$dep_montochequed = number_format(0.00, 2, ',', '.');
		$respuesta['tabla'] = "
		<tr>
			<td align='center' class='bg-primary'>#Dep
			</td>
			<td align='center' class='bg-primary'>Fecha
			</td>
			<td align='center' class='bg-primary'>Efectivo
			</td>
			<td align='center' class='bg-primary'>Cheque
			</td>
		</tr>";
		$cedula = $_SESSION["cedula"];
		$sql = "select *,DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fechadma from eva_depositos where dep_cuenta='$dep_cuenta' and dep_cedula='$cedula'";
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Depositos Encontrados.';
			$i = 0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i += 1;
				$dep_referencia    = $datos["dep_referencia"];
				$dep_fecha         = $datos["fechadma"]; //date("d-m-Y", strtotime($dep_fecha));
				$dep_monto         = $datos["dep_monto"];
				$dep_montod        = number_format($dep_monto, 2, ',', '.');
				$dep_montocheque   = $datos["dep_montocheque"];
				$dep_montochequed  = number_format($dep_montocheque, 2, ',', '.');
				$totalefec		  += $dep_monto;
				$totalchec		  += $dep_montocheque;

				$respuesta['tabla'] .= "
				<tr>
					<td><input type='text' value='$dep_referencia' name='referencia$i' id='referencia$i' class='form-control input-sm' readonly>
					</td>
					<td><input type='text' style='text-align:center;' value='$dep_fecha' name='fecha$i' id='fecha$i' class='form-control input-sm' readonly>
					</td>
					<td><input type='text' style='text-align:right;' value='$dep_montod' name='dep_monto$i' id='dep_monto$i' class='form-control input-sm' readonly>
					</td>
					<td><input type='text' style='text-align:right;' value='$dep_montochequed' name='$dep_montocheque$i' id='$dep_montocheque$i' class='form-control input-sm' readonly>
					</td>
				</tr>";	
			}
			$respuesta['nroreg'] = $i;
		}
		else
		{
			$respuesta['mensaje'] = "Cédula no tiene depósitos asignados.";
			$respuesta['tabla'] .= "
			<tr>
				<td><input type='text' value='$dep_referencia' name='referencia$i' id='referencia$i' class='form-control input-sm' readonly>
				</td>
				<td><input type='text' style='text-align:center;' value='$dep_fecha' name='fecha$i' id='fecha$i' class='form-control input-sm' readonly>
				</td>
				<td><input type='text' style='text-align:right;' value='$dep_montod' name='dep_monto$i' id='dep_monto$i' class='form-control input-sm' readonly>
				</td>
				<td><input type='text' style='text-align:right;' value='$dep_montochequed' name='dep_montocheque$i' id='dep_montocheque$i' class='form-control input-sm' readonly>
				</td>
			</tr>";	

		}
		$totalefecd = number_format($totalefec, 2, ',', '.');
		$totalchecd = number_format($totalchec, 2, ',', '.');
		$montomind = number_format($aux_montomin, 2, ',', '.');
		$faltante = $aux_montomin-$totalefec;
		if($faltante<0)
		{
			$faltante = 0;
		}
		$respuesta['faltante'] = $faltante;
		$faltanted = number_format($faltante, 2, ',', '.');
		$respuesta['tabla'] .="
		<tr>
			<td colspan='2' align='center'>Total Depósitos</td>
			<td align='left' class='bg-primary'><b><input type='text' style='text-align:right;' value='$totalefecd' name='totalefec$i' id='totalefec$i' class='form-control input-sm' readonly></b>
			</td>
			<td align='left'><input type='text' style='text-align:right;' value='$totalchecd' name='totalchec$i' id='totalchec$i' class='form-control input-sm' readonly>
			</td>
		</tr>
		<tr>
			<td colspan='2' align='center' class='bg-primary'><b>Total a depositar</b></td>
			<td align='left'><b><input type='text' style='text-align:right;' value='$montomind' name='aux_montominsoft' id='aux_montominsoft' class='form-control input-sm' readonly></b>
			</td>
		</tr>
		<tr>
			<td colspan='2' align='center'><b>Faltante</b></td>
			<td align='left'><b><input type='text' style='text-align:right;' value='$faltanted' name='aux_montominsoft' id='aux_montominsoft' class='form-control input-sm' readonly></b>
			</td>
		</tr>";

		echo json_encode($respuesta);
	}

	function utilizarDeposito($conexion,$dep_referencia,$dep_cuenta,$dep_fecha)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$sql="select * from depositos_estudiante where dep_cuenta='$dep_cuenta' and dep_referencia='$dep_referencia' and dep_fecha>='$dep_fecha';";

		//echo $sql;
		
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$datos=mysql_fetch_assoc($ok);
			$respuesta['datos'] = $datos;
			if($datos["dep_cedula"] > 0)
			{
				$respuesta['mensaje'] = "Depósito ya fue usado por la Cédula: " . $datos["dep_cedula"] . "<br>Nombre: " . $datos["est_nombres"] . " " . $datos["est_apellidos"];
			}else
			{
				if($datos["dep_status"] > 0)
				{
					$respuesta['mensaje'] = "Depósito ha sido bloqueado para su uso.";
				}else
				{
					$cedula = $_SESSION["cedula"];
					$sql="update eva_depositos set dep_status='1',dep_cedula='$cedula' where dep_referencia='$dep_referencia' and dep_fecha>='$dep_fecha';";
					$ok=$conexion->guardar($sql);
					if($ok>0)
					{
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = "Deposito fue asignado con exito!";
					}
				}
			}
		}else
		{
			$respuesta['mensaje'] = "Depósito no existe.";
		}
		echo json_encode($respuesta);
	}


}

?>
