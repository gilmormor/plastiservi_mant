<?php
require_once("seguridad.class.php");
class deposito extends seguridad //extends es para heredar las cosas de otra clase (Hay que hacer un require once de esa clase que deseamos heredar)
{
	function modificar_deposito($conexion,$dep_referencia,$dep_cedula,$dep_status,$dep_fecha,$dep_monto,$montocheque)
	{
		$respuesta = array();
		$respuesta['exito'] = false; 
		$respuesta['mensaje'] = "No se guardo.";
		$sql="update eva_depositos set dep_cedula='$dep_cedula',dep_status='$dep_status',dep_fecha='$dep_fecha',dep_monto='$dep_monto',dep_montocheque='$montocheque' where dep_referencia='$dep_referencia'"; //fk_mod va sin comillas porque es numerico
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		if ($ok)
		{
			$respuesta['exito'] = true; 
			$respuesta['mensaje'] = "Se Guardo con exito.";
		}
		echo json_encode($respuesta);
	}

	function buscar_depositos($conexion,$dep_referencia)
	{
		$sql="select * from eva_depositos where dep_referencia='$dep_referencia'";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		$datos=mysql_fetch_assoc($ok);
		if($datos["dep_referencia"]==$dep_referencia)
			echo "$datos[dep_cedula]#$datos[dep_status]#$datos[dep_fecha]#$datos[dep_monto]#$datos[dep_montocheque]";
		else
			echo "no Encontrado";
	}

	function buscar_depositosnew($conexion,$dep_referencia)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Depósito No existe.';
		$sql="select * from eva_depositos where dep_referencia='$dep_referencia'";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		$filas = mysql_num_rows($ok);
		if ($filas>0)
		{
			$datos=mysql_fetch_assoc($ok);
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Depósito encontrado.';
			$respuesta['json'] = $datos;
		}
		echo json_encode($respuesta);
	}

	function insertar_deposito($conexion,$dep_cedula,$mfor_cod,$fid_banco,$dep_referencia,$dep_fecha,$dep_lote,$dep_clavconf,$dep_monto,$dep_nofacturar)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Error al Guardar.';
		$sql="select * from eva_filtros";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		$datos=mysql_fetch_assoc($ok);
		$sql="insert into eva_depositos(dep_cuenta,dep_cedula,dep_status,dep_neumonico,dep_formapago,dep_banco,dep_referencia,dep_fecha,dep_lote,dep_clavconf,dep_monto,dep_nofacturar,dep_cajero) values('$datos[fil_numcuentacol]','$dep_cedula','1','PDV','$mfor_cod','$fid_banco','$dep_referencia','$dep_fecha','$dep_lote','$dep_clavconf','$dep_monto','$dep_nofacturar','SUBIDO');";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Depósito Guardado con exito.';
		}
		echo json_encode($respuesta);
	}

	function buscar_depositosjson($conexion,$dep_referencia)
	{
		$sql="select * from eva_depositos where dep_referencia='$dep_referencia'";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);
		$data=mysql_fetch_assoc($ok);
		echo json_encode($data);
	}

	function buscar_depositoxcedula($conexion,$dep_cuenta,$aux_montomin,$aux_montomax,$aux_sta,$aux_conther)
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
		if($aux_sta == 1) //Si el deposito es de softservi buscar en la vista creada en la BD del colegio
		{
			//Select que busca todos los depositos de hermanos o que tengan el mismo representante
			//esto para que cuando creen el usuario y se les ocurra meterle depositos a todos los hermanos pues 
			//se sumen todos al momento que se conecte 
			$sql = "select eva_depositos_softservica.*,DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fechadma
			from eva_repest as eva_repestorgi
			inner join eva_repest as eva_repestcopy
			on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
			inner join eva_depositos_softservica
			on eva_repestcopy.rep_cedalum=eva_depositos_softservica.dep_cedula
			where eva_depositos_softservica.dep_cuenta='$dep_cuenta' and eva_repestorgi.rep_cedalum='$cedula'";	
			/*
			$sql = "select *,DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fechadma from eva_depositos_softservica where dep_cuenta='$dep_cuenta' and dep_cedula='$cedula'";
			*/
		}else
		{
			$sql = "select eva_depositos.*,DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fechadma
			from eva_repest as eva_repestorgi
			inner join eva_repest as eva_repestcopy
			on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
			inner join eva_depositos
			on eva_repestcopy.rep_cedalum=eva_depositos.dep_cedula
			where eva_depositos.dep_cuenta='$dep_cuenta' and eva_repestorgi.rep_cedalum='$cedula'";
			/*
			$sql = "select *,DATE_FORMAT(dep_fecha,'%d/%m/%Y') as fechadma from eva_depositos where dep_cuenta='$dep_cuenta' and dep_cedula='$cedula'";
			*/
		}
		//echo $sql;
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
					<td><input type='text' value='$dep_referencia' name='referencia$i' id='referencia$i' class='form-control input-sm' readonly title='$dep_referencia'>
					</td>
					<td><input type='text' style='text-align:center;' value='$dep_fecha' name='fecha$i' id='fecha$i' class='form-control input-sm' readonly title='$dep_fecha'>
					</td>
					<td><input type='text' style='text-align:right;' value='$dep_montod' name='dep_monto$i' id='dep_monto$i' class='form-control input-sm' readonly title='$dep_montod'>
					</td>
					<td><input type='text' style='text-align:right;' value='$dep_montochequed' name='$dep_montocheque$i' id='$dep_montocheque$i' class='form-control input-sm' readonly title='$dep_montochequed'>
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
				<td><input type='text' value='$dep_referencia' name='referencia$i' id='referencia$i' class='form-control input-sm' readonly title='$dep_referencia'>
				</td>
				<td><input type='text' style='text-align:center;' value='$dep_fecha' name='fecha$i' id='fecha$i' class='form-control input-sm' readonly title='$dep_fecha'>
				</td>
				<td><input type='text' style='text-align:right;' value='$dep_montod' name='dep_monto$i' id='dep_monto$i' class='form-control input-sm' readonly title='$dep_montod'>
				</td>
				<td><input type='text' style='text-align:right;' value='$dep_montochequed' name='$dep_montocheque$i' id='$dep_montocheque$i' class='form-control input-sm' readonly title='$dep_montochequed'>
				</td>
			</tr>";	
		}
		$aux_deuda = 0;
		if($aux_sta == 2)
		{
			$sql = "select eva_repestorgi.rep_cedrep,eva_repestcopy.rep_cedalum,sum(cuo_saldo) as saldototal
			from eva_repest as eva_repestorgi
			inner join eva_repest as eva_repestcopy
			on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
			inner join eva_estudiante
			on eva_repestcopy.rep_cedalum=eva_estudiante.est_ced
			inner join iuf_cuotas
			on eva_repestcopy.rep_cedalum=iuf_cuotas.cuo_cedula
			where eva_repestorgi.rep_cedalum='$cedula' and cuo_fecvnto<=date('%Y%m%d')
			group by eva_repestorgi.rep_cedrep";
			// and cuo_fecvnto<='20170721'
/*
			$sql = "select cuo_cedula,cuo_monto,cuo_abono,cuo_saldo,sum(cuo_saldo) as saldototal
			from iuf_cuotas
			where cuo_cedula='$cedula' group by cuo_cedula;";
*/
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$objDeuda = $conexion->objeto($sql);
				$aux_deuda = $objDeuda->saldototal;
			}
		}
		//Si es deposito softservi o Colegio multiplico el $aux_montomin y $aux_montomax x la cantidad 
		//de hermanos ($aux_conther)
		/*
		if(($aux_sta == 1) || ($aux_sta == 2)) 
		{
			$aux_montomin = $aux_montomin * $aux_conther;
			$aux_montomax = $aux_montomax * $aux_conther;
		}
		*/
		$aux_montoins = $aux_montomin; // Creo una variable auxiliar para guardar el monto minimo de insc 
		$aux_montomin  = $aux_montomin + $aux_deuda;

		$totalefecd = number_format($totalefec, 2, ',', '.');
		$totalchecd = number_format($totalchec, 2, ',', '.');
		$montomin = number_format($aux_montomin, 2, ',', '.');
		$montomind = number_format($aux_montomin, 2, ',', '.');
		$montoinsd = number_format($aux_montoins, 2, ',', '.');
		$deudad = number_format($aux_deuda, 2, ',', '.');
		$faltante = $aux_montomin-$totalefec;
		if($faltante<0)
		{
			$faltante = 0;
		}
		$respuesta['faltante'] = $faltante;
		$faltanted = number_format($faltante, 2, ',', '.');
		$respuesta['tabla'] .="
		<tr>
			<td colspan='2' align='center'><b>Total Depósitos</b></td>
			<td align='left'><b><input type='text' style='text-align:right;' value='$totalefecd' name='$totalefec$i' id='$totalefec$i' class='form-control input-sm' readonly></b>
			</td>
			<td align='left'><input type='text' style='text-align:right;' value='$totalchecd' name='$totalchec$i' id='$totalchec$i' class='form-control input-sm' readonly>
			</td>
		</tr>";
		if($aux_sta == 2) //Si es depositos del colegio espesifico deuda y monto minimo Inscripcion
		{
			$respuesta['tabla'] .="
			<tr>
				<td colspan='2' align='center' class='bg-primary'><b>Deuda</b></td>
				<td align='left'><b><input type='text' style='text-align:right;' value='$deudad' name='aux_deudasoft' id='aux_deudasoft' class='form-control input-sm' readonly title='Deuda pendiente Mensualidad y Otros'></b>
				</td>
			</tr>		
			<tr>
				<td colspan='2' align='center' class='bg-primary'><b>Monto Inscripción</b></td>
				<td align='left'><b><input type='text' style='text-align:right;' value='$montoinsd' name='aux_inscsoft' id='aux_inscsoft' class='form-control input-sm' readonly title='Monto Mínimo de Inscripción'></b>
				</td>
			</tr>";		
		}
		$respuesta['tabla'] .="
		<tr>
			<td colspan='2' align='center' class='bg-primary'><b>Total a depositar</b></td>
			<td align='left'><b><input type='text' style='text-align:right;' value='$montomin' name='aux_montominsoft' id='aux_montominsoft' class='form-control input-sm' readonly title='Monto total a depositar'></b>
			</td>
		</tr>
		<tr>
			<td colspan='2' align='center'><b><font color='red'>Faltante</font></b></td>
			<td align='left'><b><input type='text' style='text-align:right;' value='$faltanted' name='aux_montominsoft' id='aux_montominsoft' class='form-control input-sm' readonly title='Faltante o Saldo (Este monto debe quedar en cero (0) para hacer click en Siguiente'></b>
			</td>
		</tr>";
		echo json_encode($respuesta);
	}

	function utilizarDeposito($conexion,$dep_referencia,$dep_cuenta,$dep_fecha,$aux_sta)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Falló la consulta.";
		$cedula = $_SESSION["cedula"];
		if($cedula==0 || $cedula==0 || empty($cedula))
		{
			$respuesta['mensaje'] = "Deposito no se guardo. Vuelva a entrar con su cuenta de Usuario";
		}else
		{
			if($aux_sta == 1) //Si el deposito es de softservi buscar en la vista creada en la BD del colegio
			{
				$sql="select eva_depositos_softservica.*,est_nombres,est_apellidos
				from eva_depositos_softservica left join eva_estudiante
				on dep_cedula=est_ced
				where dep_cuenta='$dep_cuenta' and dep_referencia='$dep_referencia' and dep_fecha>='$dep_fecha';";

			}else
			{
				$sql="select eva_depositos.*,est_nombres,est_apellidos
				from eva_depositos left join eva_estudiante
				on dep_cedula=est_ced
				where dep_cuenta='$dep_cuenta' and dep_referencia='$dep_referencia' and dep_fecha>='$dep_fecha';";
			}

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
						$cedula = $_SESSION["cedula"]; //$est_ced; //
						if($aux_sta == 1) //Si el deposito es de softservi buscar en la vista creada en la BD del colegio
						{
							$sql="update eva_depositos_softservica set dep_status='1',dep_cedula='$cedula' where dep_referencia='$dep_referencia' and dep_cuenta='$dep_cuenta' and dep_fecha>='$dep_fecha';";
						}else
						{
							$sql="update eva_depositos set dep_status='1',dep_cedula='$cedula' where dep_referencia='$dep_referencia' and dep_cuenta='$dep_cuenta' and dep_fecha>='$dep_fecha';";
						}
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
		}

		echo json_encode($respuesta);
	}

}

?>