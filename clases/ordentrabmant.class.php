<?php
//require_once "Conexion.class.php";
class ordentrabmant
{
	function crear($conexion,$solicitudTrabID,$prioridad,$filasMecanicos,$departamentoAreaID,$usuarioID,$maquinariaequiposDetalleID){
		//echo $filasMecanicos;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "No guardo nada.";
		$errorGuardar = true;
		//Creo una cadena con los empleados deleccionados y eliminar los que no esten en la lista
		foreach ($filasMecanicos as $fila) {
		    $cod_empVec .= "'".$fila['personaID']."',";
		}		
		$cod_empVec = substr($cod_empVec, 1, -2);
		$sql = "delete from solicitudtrabmantpersona where solicitudTrabID='$solicitudTrabID' and personaID not in ('$cod_empVec');";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$errorGuardar = false;
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Guardó eliminar.";
		}
		if($errorGuardar == false){
			foreach ($filasMecanicos as $fila) {
				$errorGuardar = true;
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
							$errorGuardar = false;
						}else
						{
							$respuesta['exito'] = false;
							$respuesta['mensaje'] = "Falló Guardado de Mecánicos.";
						}
						$cont_guardar = $cont_guardar + 1;
					}else{
						$errorGuardar = false;
					}
				}
			}
		}
		if($errorGuardar == false){
			$errorGuardar = true;
			$sql = "update solicitudtrabmant set fechainiTrab=now(),prioridad=$prioridad where solicitudTrabID=$solicitudTrabID and usuarioIDdelete=0;"; 
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$errorGuardar = false;
				$respuesta['exito'] = true;
				$respuesta['mensaje'] .= "<br>Guardó Prioridad.";
			}else
			{
				$respuesta['exito'] = false;
				$respuesta['mensaje'] .= "<br>Falló la actualización de prioridad !";
			}
		}

		if($errorGuardar == false){
			$errorGuardar = true;
			$sql = "select * FROM ordentrabmant WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
					while(($datos=mysql_fetch_assoc($ok))>0)
					{
						$fechaini = $datos["fechaini"];
					}
					$respuesta['exito'] = false;
					$respuesta['mensaje'] = "Servicio ya iniciado el: ".$fechaini;	
			}else{
				$sql = "insert into ordentrabmant (solicitudTrabID, departamentoAreaID, usuarioID,maquinariaequiposDetalleID, fechaini,fechaHoraInsert) values('$solicitudTrabID','$departamentoAreaID','$usuarioID','$maquinariaequiposDetalleID',now(),now());"; 
				//echo $sql;
				$ok=$conexion->ejecutarQuery($sql);
				if($ok)
				{
					$errorGuardar = false;
					$respuesta['exito'] = true;
					$respuesta['mensaje'] = "Inicio del Servicio de mantencion.";
				}else
				{
					$respuesta['exito'] = false;
					$respuesta['mensaje'] = "Falló la inserción.";
				}

			}
		}
		echo json_encode($respuesta);
	}

	function consultar($conexion,$solicitudTrabID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$respuesta['ordenTrab'] = array();
		$sql = "select * FROM ordentrabmant WHERE solicitudTrabID='$solicitudTrabID and usuarioIDdelete=0';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$respuesta['ordentrabmantID'] = $datos["ordentrabmantID"];
					$ordentrabmantID              = $datos["ordentrabmantID"];
					$respuesta['fechaini']        = $datos["fechaini"];
					$respuesta['mant']            = $datos["mant"];
					$respuesta['prioridad']       = $datos["prioridad"];
					$respuesta['indSeguridad']    = $datos["indSeguridad"];
					$respuesta['descripTrabajo']  = $datos["descripTrabajo"];
					$respuesta['repuestosmat']    = $datos["repuestosmat"];
					$respuesta['observaciones']   = $datos["observaciones"];
					$respuesta['tipofalla']       = $datos["tipofalla"];
					$respuesta['tipomant']        = $datos["tipomant"];
					$respuesta['ttmID']           = $datos["ttmID"];
				}
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "";	
		}else{
			$respuesta['exito']   = false;
			$respuesta['mensaje'] = "No existe Orden de trabajo.";
		}
		echo json_encode($respuesta);
	}

	function update($conexion,$solicitudTrabID,$mant,$prioridad,$ttmID,$descripTrabajo,$repuestosmat,$observaciones,$tipofalla,$tipomant){
		//echo $filas;
		$aux_tipofalla = implode ( ',' , $tipofalla );
		$aux_ttmID     = implode ( ',' , $ttmID );
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$sql = "update ordentrabmant set mant='$mant',prioridad='$prioridad',ttmID='$aux_ttmID',descripTrabajo='$descripTrabajo',repuestosmat='$repuestosmat',observaciones='$observaciones',tipofalla='$aux_tipofalla',tipomant='$tipomant' WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito']   = true;
			$respuesta['mensaje'] = "Guardado con exito.";

		}else{
			$respuesta['exito']   = false;
			$respuesta['mensaje'] = "No se guardo la informacion.";
		}
		echo json_encode($respuesta);
	}

	function finalizarOT($conexion,$solicitudTrabID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$respuesta['fantandatosOT'] = false;

		$sql = "select * 
				from ordentrabmant 
				WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0 AND !(mant='' or tipofalla='' or tipomant='' 
				or descripTrabajo='' or ttmID='' or repuestosmat='' or observaciones='');";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$sql = "update solicitudtrabmant set fechafinTrab=now() WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$respuesta['exito']   = true;
				$respuesta['mensaje'] = "Guardado con exito.";

			}else{
				$respuesta['exito']   = false;
				$respuesta['mensaje'] = "No se guardo la informacion.";
			}
			$sql = "update ordentrabmant set fechafin=now() WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$respuesta['exito']   = true;
				$respuesta['mensaje'] = "Guardado con exito.";

			}else{
				$respuesta['exito']   = false;
				$respuesta['mensaje'] = "No se guardo la informacion.";
			}
		}else{
			$respuesta['exito']   = false;
			$respuesta['mensaje'] = "Falta llenar orden de trabajo.";
			$respuesta['fantandatosOT'] = true;
		}
		echo json_encode($respuesta);
	}

	function consultarOrdenTrabajo($conexion,$usuarioID)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['ordenTrab'] = array();
		$respuesta['numreg'] = 0;

		if(true){ //($departamentoAreaID!=""){
			$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed">
				<thead>
					<tr>
						<th>Prioridad</th>
						<th>ST</th>
						<th>OT</th>
						<th>FecHor Ini</th>
						<th>Número</th>
						<th>Observaciones</th>
						<th align="left">Operadores</th>
						<th align="center">Acción</th>
						<th style="display:none;">ema_usu</th>
						<th style="display:none;">usuarioID</th>
						<th style="display:none;">maquinariaequiposDetalleID</th>
						<th style="display:none;">departamentoAreaID</th>
						<th style="display:none;">nombreDpto</th>
					</tr>
				</thead>
				<tbody>';
			$sql = "select solicitudtrabmant.solicitudTrabID,ordentrabmant.ordentrabmantID,
			solicitudtrabmant.departamentoAreaID,
			maquinariaequiposdetalle.codigoInterno,solicitudtrabmant.descripcion,fechaHoraini,
			solicitudtrabmant.usuarioID,usuario.ema_usu,usuario.nom_usu,solicitudtrabmant.maquinariaequiposDetalleID,
			solicitudtrabmant.prioridad,departamento.nombre
			from solicitudtrabmant INNER JOIN maquinariaequiposdetalle 
			ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID 
			INNER JOIN usuario 
			ON solicitudtrabmant.usuarioID=usuario.usuarioID 
			inner JOIN departamentoarea
			ON solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
			INNER JOIN departamento
			ON departamentoarea.departamentoID=departamento.departamentoID
			inner join ordentrabmant
			on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
			where solicitudtrabmant.fechafinTrab!='0000-00-00 00:00:00'
			and ordentrabmant.fechafin='0000-00-00 00:00:00'
			AND solicitudtrabmant.solicitudTrabID IN 
			(SELECT solicitudtrabmantpersona.solicitudTrabID FROM solicitudtrabmantpersona)
			AND departamentoarea.jefeareausuarioID='$usuarioID'
			and solicitudtrabmant.usuarioIDdelete=0 and ordentrabmant.usuarioIDdelete=0
			ORDER BY solicitudtrabmant.prioridad;";
			//solicitudtrabmant.departamentoAreaID='$departamentoAreaID' and 
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['numreg'] = $filas;
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = 'Código encontrado.';
				$i = 0;
				$personas = array();
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$i += 1;
					$departamentoAreaID = $datos["departamentoAreaID"];
					$nombreDpto         = $datos["nombre"];
					$ordentrabmantID    = $datos["ordentrabmantID"];
					$solicitudTrabID    = $datos["solicitudTrabID"];
					$maquinaID		    = $datos["maquinaID"];
					$codigoInterno      = $datos["codigoInterno"];
					$descripcion        = $datos["descripcion"];
					$fechaHoraini       = $datos["fechaHoraini"];
					$ema_usu            = $datos["ema_usu"];
					$usuarioID          = $datos["usuarioID"];
					$prioridad          = $datos["prioridad"];

					$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
					$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
					$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i'>
						<td id='prioridad$i' name='prioridad$i'>$prioridad</td>
						<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
						<td id='ordentrabmantID$i' name='ordentrabmantID$i'>$ordentrabmantID</td>
						<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
						<td id='maquinaID$i' name='maquinaID$i'>$codigoInterno</td>
						<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
						<td>
							<div class='col-lg-4' id='ope$i' name='ope$i'>";
							
							$sql = "select personaID,nombre,apellido from vistapersonamant;";
							
							$ok1 = $conexion->ejecutarQuery($sql);

							$cadena = "<select class='selectpicker form-control' id='mecanico$i' name='mecanico$i' multiple title='Seleccione...' disabled>
							    ";
							while(($datos1=mysql_fetch_assoc($ok1))>0)
							{
								$personaID= $datos1["personaID"];
								$nombre= $datos1["nombre"];
								$cadena .= "<option value='$personaID'> $nombre </option>";	
						    }
							$cadena .= "</select>";

					$respuesta['tabla'] .= $cadena."
							</div>
						</td>
						<td>
							<a id='btnOrdenTrabajo$i' name='btnOrdenTrabajo$i' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#ModalCenter' onclick='validarOrdenTrabajo($i)' title='Validar Orden de Trabajo.' data-toggle='tooltip'>Validar <span class='glyphicon glyphicon-floppy-save' style='bottom: 0px;top: 2px;'></span></a>
						</td>
						<td id='ema_usu$i' name='ema_usu$i'  style='display:none;'>$ema_usu</td>
						<td id='usuarioID$i' name='usuarioID$i'  style='display:none;'>$usuarioID</td>
						<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i'  style='display:none;'>$maquinariaequiposDetalleID</td>
						<td id='departamentoAreaID$i' name='departamentoAreaID$i' style='display:none;'>$departamentoAreaID</td>
						<td id='nombreDpto$i' name='nombreDpto$i' style='display:none;'>$nombreDpto</td>
					</tr>";

					
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
							$personas = array();
							while(($datos1=mysql_fetch_assoc($ok1))>0)
							{
								$personas[$i1] = $datos1["personaID"];
								$i1 += 1;
							}
							$respuesta['ordenTrab'][$solicitudTrabID] = $personas;
						}
					}

				}

				$respuesta['tabla'] .= "</tbody>
				</table>";
				$respuesta['nroreg'] = $i;
				$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()'>Guardar</button>";
			}else
			{
				$respuesta['mensaje'] = "Información no encontrada.";
			}
		}
		echo json_encode($respuesta);
	}


	function consultarValidarOT($conexion,$usuarioID)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['ordenTrab'] = array();
		$respuesta['numreg'] = 0;

		if(true){ //($departamentoAreaID!=""){
			$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed">
				<thead>
					<tr>
						<th>Prioridad</th>
						<th>ST</th>
						<th>OT</th>
						<th>FecHor Ini</th>
						<th>Número</th>
						<th>Observaciones</th>
						<th align="left">Operadores</th>
						<th align="center">Acción</th>
						<th style="display:none;">ema_usu</th>
						<th style="display:none;">usuarioID</th>
						<th style="display:none;">maquinariaequiposDetalleID</th>
						<th style="display:none;">departamentoAreaID</th>
						<th style="display:none;">nombreDpto</th>
					</tr>
				</thead>
				<tbody>';
			$sql = "select solicitudtrabmant.solicitudTrabID,ordentrabmant.ordentrabmantID,
			solicitudtrabmant.departamentoAreaID,
			maquinariaequiposdetalle.codigoInterno,solicitudtrabmant.descripcion,fechaHoraini,ordentrabmant.fechafin,
			solicitudtrabmant.usuarioID,usuario.ema_usu,usuario.nom_usu,solicitudtrabmant.maquinariaequiposDetalleID,
			solicitudtrabmant.prioridad,departamento.nombre
			from solicitudtrabmant INNER JOIN maquinariaequiposdetalle 
			ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID 
			INNER JOIN usuario 
			ON solicitudtrabmant.usuarioID=usuario.usuarioID 
			inner JOIN departamentoarea
			ON solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
			INNER JOIN departamento
			ON departamentoarea.departamentoID=departamento.departamentoID
			inner join ordentrabmant
			on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
			where ordentrabmant.fechafin!='0000-00-00 00:00:00' 
			AND solicitudtrabmant.departamentoAreaID IN 
			(SELECT vistadptoxusuario.departamentoAreaID FROM vistadptoxusuario WHERE vistadptoxusuario.usuarioID='$usuarioID')
			and solicitudtrabmant.usuarioID in 
			(select usuarioIDPermiso from validarot where usuarioID='$usuarioID')
			and solicitudtrabmant.usuarioIDdelete=0 and ordentrabmant.usuarioIDdelete=0 AND 
			ordentrabmant.statusaceprech=0 AND ordentrabmant.evaluacion=0
			ORDER BY solicitudtrabmant.prioridad;";
			//solicitudtrabmant.departamentoAreaID='$departamentoAreaID' and 
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['numreg'] = $filas;
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = 'Código encontrado.';
				$i = 0;
				$personas = array();
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$i += 1;
					$departamentoAreaID = $datos["departamentoAreaID"];
					$nombreDpto         = $datos["nombre"];
					$ordentrabmantID    = $datos["ordentrabmantID"];
					$solicitudTrabID    = $datos["solicitudTrabID"];
					$maquinaID		    = $datos["maquinaID"];
					$codigoInterno      = $datos["codigoInterno"];
					$descripcion        = $datos["descripcion"];
					$fechaHoraini       = $datos["fechaHoraini"];
					$ema_usu            = $datos["ema_usu"];
					$usuarioID          = $datos["usuarioID"];
					$prioridad          = $datos["prioridad"];

					$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
					$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
					$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i'>
						<td id='prioridad$i' name='prioridad$i'>$prioridad</td>
						<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
						<td id='ordentrabmantID$i' name='ordentrabmantID$i'>$ordentrabmantID</td>
						<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
						<td id='maquinaID$i' name='maquinaID$i'>$codigoInterno</td>
						<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
						<td>
							<div class='col-lg-4' id='ope$i' name='ope$i'>";
							
							$sql = "select personaID,nombre,apellido from vistapersonamant;";
							
							$ok1 = $conexion->ejecutarQuery($sql);

							$cadena = "<select class='selectpicker form-control' id='mecanico$i' name='mecanico$i' multiple title='Seleccione...' disabled>
							    ";
							while(($datos1=mysql_fetch_assoc($ok1))>0)
							{
								$personaID= $datos1["personaID"];
								$nombre= $datos1["nombre"];
								$cadena .= "<option value='$personaID'> $nombre </option>";	
						    }
							$cadena .= "</select>";
/*
						<td>
							<a id='btnOrdenTrabajo$i' name='btnOrdenTrabajo$i' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#ModalCenter' onclick='validarOrdenTrabajo($i)' title='Validar Orden de Trabajo.' data-toggle='tooltip'>Validar <span class='glyphicon glyphicon-floppy-save' style='bottom: 0px;top: 2px;'></span></a>
						</td>
*/
					$respuesta['tabla'] .= $cadena."
							</div>
						</td>
						<td>
							<a id='btnOrdenTrabajo$i' name='btnOrdenTrabajo$i' class='btn btn-primary btn-sm' onclick='validarOrdenTrabajo($i)' title='Validar Orden de Trabajo.' data-toggle='tooltip'>Validar <span class='glyphicon glyphicon-floppy-save' style='bottom: 0px;top: 2px;'></span></a>
						</td>
						<td id='ema_usu$i' name='ema_usu$i'  style='display:none;'>$ema_usu</td>
						<td id='usuarioID$i' name='usuarioID$i'  style='display:none;'>$usuarioID</td>
						<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i'  style='display:none;'>$maquinariaequiposDetalleID</td>
						<td id='departamentoAreaID$i' name='departamentoAreaID$i' style='display:none;'>$departamentoAreaID</td>
						<td id='nombreDpto$i' name='nombreDpto$i' style='display:none;'>$nombreDpto</td>
					</tr>";

					
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
							$personas = array();
							while(($datos1=mysql_fetch_assoc($ok1))>0)
							{
								$personas[$i1] = $datos1["personaID"];
								$i1 += 1;
							}
							$respuesta['ordenTrab'][$solicitudTrabID] = $personas;
						}
					}

				}

				$respuesta['tabla'] .= "</tbody>
				</table>";
				$respuesta['nroreg'] = $i;
				$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()'>Guardar</button>";
			}else
			{
				$respuesta['mensaje'] = "Información no encontrada.";
			}
		}
		echo json_encode($respuesta);
	}


	function updateEvaluacion($conexion,$solicitudTrabID,$ordentrabmantID,$evaluacion,$statusaceprech,$obserEvaluacion,$obseraceprech){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$sql = "";
		if($statusaceprech=='1'){
			$sql = "update ordentrabmant set evaluacion='$evaluacion',statusaceprech='$statusaceprech',obserEvaluacion='$obserEvaluacion',obseraceprech='$obseraceprech',fechafin=now() WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
		}else{
			if($statusaceprech=='2'){
				$sql = "update solicitudtrabmant set fechafinTrab='0000-00-00 00:00:00' WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
				$ok=$conexion->ejecutarQuery($sql);
				if($ok)
				{
					$sql = "update ordentrabmant set evaluacion='$evaluacion',statusaceprech='$statusaceprech',obserEvaluacion='$obserEvaluacion',obseraceprech='$obseraceprech',fechafin='0000-00-00 00:00:00' WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
					$ok=$conexion->ejecutarQuery($sql);
					if($ok)
					{
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = "Guardado con exito.";
					}else{
						$respuesta['exito']   = false;
						$respuesta['mensaje'] .= "<br>No se guardaron las Observaciones.";
					}
				}else{
					$respuesta['exito']   = false;
					$respuesta['mensaje'] .= "<br>No se guardaron las Observaciones.";
				}
			}
		}
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$respuesta['exito']   = true;
			$respuesta['mensaje'] = "Guardado con exito.";
			$sql = "insert into ordentrabamantevalaceprech (ordentrabmantID,evaluacion,obserEvaluacion,statusaceprech,obseraceprech,fechaHoraInsert) value ('$ordentrabmantID','$evaluacion','$obserEvaluacion','$statusaceprech','$obseraceprech',now());";
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$respuesta['exito']   = true;
				$respuesta['mensaje'] = "Guardado con exito.";
			}else{
				$respuesta['exito']   = false;
				$respuesta['mensaje'] .= "<br>No se guardaron las Observaciones.";
			}


		}else{
			$respuesta['exito']   = false;
			$respuesta['mensaje'] = "Fallo el guardado.";
		}
		echo json_encode($respuesta);
	}


	function consultarTrabajosEnEjecucion($conexion)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['ordenTrab'] = array();
		$respuesta['numreg'] = 0;

		if(true){ //($departamentoAreaID!=""){
			$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed">
				<thead>
					<tr>
						<th>Fecha Ini Trab</th>
						<th>Horas</th>
						<th>Prioridad</th>
						<th>ST</th>
						<th>OT</th>
						<th>Número</th>
						<th>Observaciones</th>
						<th>Solicitante</th>
						<th align="left">Operadores</th>
						<th style="display:none;">ema_usu</th>
						<th style="display:none;">usuarioID</th>
						<th style="display:none;">maquinariaequiposDetalleID</th>
						<th style="display:none;">departamentoAreaID</th>
						<th style="display:none;">nombreDpto</th>
						<th style="display:none;">Prueba</th>
					</tr>
				</thead>
				<tbody>';
			$sql = "select solicitudtrabmant.solicitudTrabID,ordentrabmant.ordentrabmantID,
			solicitudtrabmant.departamentoAreaID,
			maquinariaequiposdetalle.codigoInterno,solicitudtrabmant.descripcion,fechaHoraini,
			solicitudtrabmant.usuarioID,usuario.ema_usu,usuario.nom_usu,usuario.ape_usu,
			solicitudtrabmant.maquinariaequiposDetalleID,
			solicitudtrabmant.prioridad,departamento.nombre,ordentrabmant.fechaini,
			ordentrabmant.fechafin,ordentrabmant.statusaceprech,ordentrabmant.obseraceprech, 
			CONCAT(TIMESTAMPDIFF(DAY, ordentrabmant.fechaini, NOW()), ' dias, ', 
			MOD(TIMESTAMPDIFF(HOUR, ordentrabmant.fechaini, NOW()), 24), ' horas y ', 
			MOD(TIMESTAMPDIFF(MINUTE, ordentrabmant.fechaini, NOW()), 60), ' minutos ') as  timetrascurrido
			from solicitudtrabmant INNER JOIN maquinariaequiposdetalle 
			ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID 
			INNER JOIN usuario 
			ON solicitudtrabmant.usuarioID=usuario.usuarioID 
			inner JOIN departamentoarea
			ON solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
			INNER JOIN departamento
			ON departamentoarea.departamentoID=departamento.departamentoID
			inner join ordentrabmant
			on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
			where (ordentrabmant.statusaceprech=0 or ordentrabmant.statusaceprech=2)
			AND solicitudtrabmant.solicitudTrabID IN 
			(SELECT solicitudtrabmantpersona.solicitudTrabID FROM solicitudtrabmantpersona)
			and solicitudtrabmant.usuarioIDdelete=0 and ordentrabmant.usuarioIDdelete=0
			ORDER BY ordentrabmant.fechaini;";
			//solicitudtrabmant.departamentoAreaID='$departamentoAreaID' and 
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['numreg'] = $filas;
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = 'Código encontrado.';
				$i = 0;
				$personas = array();
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$i += 1;
					$departamentoAreaID = $datos["departamentoAreaID"];
					$nombreDpto         = $datos["nombre"];
					$ordentrabmantID    = $datos["ordentrabmantID"];
					$solicitudTrabID    = $datos["solicitudTrabID"];
					$maquinaID		    = $datos["maquinaID"];
					$codigoInterno      = $datos["codigoInterno"];
					$descripcion        = $datos["descripcion"];
					$fechaini           = $datos["fechaini"];
					$fechafin           = $datos["fechafin"];
					$timetrascurrido    = $datos["timetrascurrido"];
					$ema_usu            = $datos["ema_usu"];
					$usuarioID          = $datos["usuarioID"];
					$prioridad          = $datos["prioridad"];
					$statusaceprech     = $datos["statusaceprech"];
					$obseraceprech      = $datos["obseraceprech"];
					$nomapeusu          = $datos["nom_usu"].' '.$datos["ape_usu"];
					$auxPrioridad       = $prioridad;
					switch($prioridad){//RREQUEST lee valores _POST y _GET
						case '1':	
							$auxPrioridad = 'Emergencia';
							break;
						case '2':	
							$auxPrioridad = 'Urgente';
							break;
						case '3':	
							$auxPrioridad = 'Normal';
							break;
						default:
					}
					$colorFila = "";
					if($statusaceprech=='0' and $fechafin!="0000-00-00 00:00:00"){
						$colorFila = " style='background-color: #87CEEB;'  title='Falta Validación por Jefe de Area' data-toggle='tooltip'";
					}
					if($statusaceprech=='2'){
						$colorFila = " style='background-color: rgb(255, 105, 97);'  title='Rechazo por: $obseraceprech' data-toggle='tooltip'";
					}

					$prioridad = $auxPrioridad;
					$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
					$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
					$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i' $colorFila>
						<td id='fechaini$i' name='fechaini$i'>$fechaini</td>
						<td id='timetrascurrido$i' name='timetrascurrido$i'>$timetrascurrido</td>
						<td id='prioridad$i' name='prioridad$i'>$prioridad</td>
						<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
						<td id='ordentrabmantID$i' name='ordentrabmantID$i'>$ordentrabmantID</td>
						<td id='maquinaID$i' name='maquinaID$i'>$codigoInterno</td>
						<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
						<td id='nomapeusu$i' name='nomapeusu$i'>$nomapeusu</td>
						<td>
							<div class='col-lg-4' id='ope$i' name='ope$i'>";
							
							$sql = "select personaID,nombre,apellido from vistapersonamant;";
							
							$ok1 = $conexion->ejecutarQuery($sql);

							$cadena = "<select class='selectpicker form-control' id='mecanico$i' name='mecanico$i' multiple title='Seleccione...' disabled>
							    ";
							while(($datos1=mysql_fetch_assoc($ok1))>0)
							{
								$personaID= $datos1["personaID"];
								$nombre= $datos1["nombre"];
								$cadena .= "<option value='$personaID'> $nombre </option>";	
						    }
							$cadena .= "</select>";

					$respuesta['tabla'] .= $cadena."
							</div>
						</td>
						<td id='ema_usu$i' name='ema_usu$i'  style='display:none;'>$ema_usu</td>
						<td id='usuarioID$i' name='usuarioID$i'  style='display:none;'>$usuarioID</td>
						<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i'  style='display:none;'>$maquinariaequiposDetalleID</td>
						<td id='departamentoAreaID$i' name='departamentoAreaID$i' style='display:none;'>$departamentoAreaID</td>
						<td id='nombreDpto$i' name='nombreDpto$i' style='display:none;'>$nombreDpto</td>
						<td id='prueba$i' name='Prueba$i' style='display:none;'>Gilmer</td>
					</tr>";

					
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
							$personas = array();
							while(($datos1=mysql_fetch_assoc($ok1))>0)
							{
								$personas[$i1] = $datos1["personaID"];
								$i1 += 1;
							}
							$respuesta['ordenTrab'][$solicitudTrabID] = $personas;
						}
					}

				}

				$respuesta['tabla'] .= "</tbody>
				</table>";
				$respuesta['nroreg'] = $i;
				$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()'>Guardar</button>";
			}else
			{
				$respuesta['mensaje'] = "Fallo la consulta";
			}
		}
		echo json_encode($respuesta);
	}


	function consultaxFiltro($conexion,$fechad,$fechah,$personaID,$departamentoAreaID,$staTrabajo)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['ordenTrab'] = array();
		$respuesta['numreg'] = 0;
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		/*
		if(empty($departamentoAreaID)){
			$aux_condDpto = " true";
		}else{
			$aux_condDpto = "solicitudtrabmant.departamentoAreaID = '$departamentoAreaID'";
		}*/
		if(empty($departamentoAreaID)){
			$aux_condDpto = "true";
		}
		else{
			//*** AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL
			foreach ($departamentoAreaID as $fila) {
			    $cod_empVec .= "'".$fila['departamentoAreaID']."',";
			}
			$cod_empVec = substr($cod_empVec, 1, -2);
			//echo $cod_empVec;
			$aux_condDpto = "solicitudtrabmant.departamentoAreaID in ('$cod_empVec')";
			//***
			//echo $cod_empVec;
		}
		if(empty($personaID)){
			$aux_condMecanico = "true";
		}else{
			$aux_condMecanico = " solicitudtrabmantpersona.personaID='$personaID'";
		}

		$aux_condTrab = "";
		$aux_fechaFinComp = "fechafin";
		switch($staTrabajo){//RREQUEST lee valores _POST y _GET
			case 1:	
				$aux_condTrab = " true";
				break;
			case 2:	
				$aux_condTrab = " solicitudtrabmant.solicitudTrabID not in (select solicitudTrabID from ordentrabmant)";
				break;
			case 3:	
				$aux_condTrab = " solicitudtrabmant.solicitudTrabID in (select solicitudTrabID from ordentrabmant where fechafin='0000-00-00 00:00:00')";
				$aux_fechaFinComp = "now()";
				break;
			case 4:	
				$aux_condTrab = " solicitudtrabmant.solicitudTrabID in (select solicitudTrabID from ordentrabmant where fechafin!='0000-00-00 00:00:00' and statusaceprech!=1)";
				$aux_fechaFinComp = "fechafin";
				break;
			case 5:	
				$aux_condTrab = " solicitudtrabmant.solicitudTrabID in (select solicitudTrabID from ordentrabmant where statusaceprech=1)";
				$aux_fechaFinComp = "fechafin";
				break;
			case 6:	
				$aux_condTrab = " solicitudtrabmant.solicitudTrabID in (select solicitudTrabID from ordentrabmant where statusaceprech=2)";
				$aux_fechaFinComp = "now()";
				break;
			default:
		}

		$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed">
			<thead>
				<tr>
					<th>ST</th>
					<th>OT</th>
					<th>Fecha Ini S</th>
					<th>Fecha Ini Trab</th>
					<th>Fecha Fin Trab</th>
					<th>Horas</th>
					<th>Prioridad</th>
					<th>Número</th>
					<th>Observaciones</th>
					<th>Solicitante</th>
					<th align="left">Mecánicos</th>
					<th style="display:none;">ema_usu</th>
					<th style="display:none;">usuarioID</th>
					<th style="display:none;">maquinariaequiposDetalleID</th>
					<th style="display:none;">departamentoAreaID</th>
					<th style="display:none;">nombreDpto</th>
					<th style="display:none;">Prueba</th>
				</tr>
			</thead>
			<tbody>';
		$usuarioID=$_SESSION["usuarioID"];
		$sql = "select solicitudtrabmant.solicitudTrabID,ordentrabmant.ordentrabmantID,
		solicitudtrabmant.departamentoAreaID,
		maquinariaequiposdetalle.codigoInterno,solicitudtrabmant.descripcion,fechaHoraini,
		solicitudtrabmant.usuarioID,usuario.ema_usu,usuario.nom_usu,usuario.ape_usu,
		solicitudtrabmant.maquinariaequiposDetalleID,
		solicitudtrabmant.prioridad,departamento.nombre,ordentrabmant.fechaini,
		ordentrabmant.fechafin,ordentrabmant.statusaceprech,ordentrabmant.obseraceprech, 
		CONCAT(TIMESTAMPDIFF(DAY, ordentrabmant.fechaini, $aux_fechaFinComp), 'd, ', 
		MOD(TIMESTAMPDIFF(HOUR, ordentrabmant.fechaini, $aux_fechaFinComp), 24), 'h y ', 
		MOD(TIMESTAMPDIFF(MINUTE, ordentrabmant.fechaini, $aux_fechaFinComp), 60), 'm') as  timetrascurrido
		from solicitudtrabmant INNER JOIN maquinariaequiposdetalle 
		ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID 
		INNER JOIN usuario 
		ON solicitudtrabmant.usuarioID=usuario.usuarioID 
		inner JOIN departamentoarea
		ON solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
		INNER JOIN departamento
		ON departamentoarea.departamentoID=departamento.departamentoID
		left join ordentrabmant
		on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID and ordentrabmant.usuarioIDdelete=0
		inner join solicitudtrabmantpersona
		on solicitudtrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
		where solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDpto and $aux_condTrab and $aux_condMecanico
		group by solicitudtrabmant.solicitudTrabID
		ORDER BY ordentrabmant.fechaini;";
/*
			inner join vistadptoxusuario
			on solicitudtrabmant.departamentoAreaID=vistadptoxusuario.departamentoAreaID and vistadptoxusuario.usuarioID='$usuarioID'
*/


		//echo $sql;
		//return 0;
/*ordentrabmant.fechafin='0000-00-00 00:00:00'
			AND solicitudtrabmant.solicitudTrabID IN 
			(SELECT solicitudtrabmantpersona.solicitudTrabID FROM solicitudtrabmantpersona)
			and
*/
			//solicitudtrabmant.departamentoAreaID='$departamentoAreaID' and 
			//echo $sql;
			
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['numreg'] = $filas;
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Código encontrado.';
			$i = 0;
			$personas = array();
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i += 1;
				$departamentoAreaID = $datos["departamentoAreaID"];
				$nombreDpto         = $datos["nombre"];
				$ordentrabmantID    = $datos["ordentrabmantID"];
				$solicitudTrabID    = $datos["solicitudTrabID"];
				$maquinaID		    = $datos["maquinaID"];
				$codigoInterno      = $datos["codigoInterno"];
				$descripcion        = $datos["descripcion"];
				$fechainitra        = $datos["fechaini"];
				$fechafintra        = $datos["fechafin"];
				$timetrascurrido    = $datos["timetrascurrido"];
				$ema_usu            = $datos["ema_usu"];
				$usuarioID          = $datos["usuarioID"];
				$nomapeusu          = $datos["nom_usu"].' '.$datos["ape_usu"];
				$prioridad          = $datos["prioridad"];
				$statusaceprech     = $datos["statusaceprech"];
				$obseraceprech      = $datos["obseraceprech"];
				$fechaHoraini       = $datos["fechaHoraini"];
				$auxPrioridad       = $prioridad;
				switch($prioridad){//RREQUEST lee valores _POST y _GET
					case '1':	
						$auxPrioridad = 'Emergencia';
						break;
					case '2':	
						$auxPrioridad = 'Urgente';
						break;
					case '3':	
						$auxPrioridad = 'Normal';
						break;
					default:
				}
				$colorFila = "";
				if($statusaceprech=='2'){
					$colorFila = " style='background-color: rgb(255, 105, 97);'  title='Rechazo por: $obseraceprech' data-toggle='tooltip'";
				}
				$sql1 = "select vistausuariopersona.personaID,vistausuariopersona.nombre 
						from solicitudtrabmantpersona inner join vistausuariopersona
						on solicitudtrabmantpersona.personaID=vistausuariopersona.personaID
						where solicitudTrabID='$solicitudTrabID';";
				//echo $sql;
				
				$mecanicos = "";
				$ok1=$conexion->ejecutarQuery($sql1);
				if($ok1)
				{
					$filas1=mysql_num_rows($ok1);
					//echo $filas1;
					if($filas1>0)
					{
						$i1 = 0;
						$personas = array();
						while(($datos1=mysql_fetch_assoc($ok1))>0)
						{
							$personas[$datos1["nombre"]] = $datos1["nombre"];
							$i1 += 1;
						}
						$respuesta['ordenTrab'][$solicitudTrabID] = $personas;
						$mecanicos = implode(",", $personas);
					}
				}

				$prioridad = $auxPrioridad;
				$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
				$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i' $colorFila>
					<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
					<td id='ordentrabmantID$i' name='ordentrabmantID$i'>$ordentrabmantID</td>
					<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
					<td id='fechainitra$i' name='fechainitra$i'>$fechainitra</td>
					<td id='fechafintra$i' name='fechafintra$i'>$fechafintra</td>
					<td id='timetrascurrido$i' name='timetrascurrido$i'>$timetrascurrido</td>
					<td id='prioridad$i' name='prioridad$i'>$prioridad</td>
					<td id='maquinaID$i' name='maquinaID$i'>$codigoInterno</td>
					<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
					<td id='nomapeusu$i' name='nomapeusu$i'>$nomapeusu</td>
					<td id='mecanicos$i' name='mecanicos$i'>$mecanicos</td>
					<td id='ema_usu$i' name='ema_usu$i'  style='display:none;'>$ema_usu</td>
					<td id='usuarioID$i' name='usuarioID$i'  style='display:none;'>$usuarioID</td>
					<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i'  style='display:none;'>$maquinariaequiposDetalleID</td>
					<td id='departamentoAreaID$i' name='departamentoAreaID$i' style='display:none;'>$departamentoAreaID</td>
					<td id='nombreDpto$i' name='nombreDpto$i' style='display:none;'>$nombreDpto</td>
					<td id='prueba$i' name='Prueba$i' style='display:none;'>Gilmer</td>
				</tr>";

				

			}
			$mecanicos = implode(",", $personas);

			$respuesta['tabla'] .= "</tbody>
			</table>";
			$respuesta['nroreg'] = $i;
			$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()'>Guardar</button>";
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}


	function graficoOT($conexion,$fechad,$fechah,$departamentoAreaID)
	{

		$respuesta = array();
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		/*
		if(empty($departamentoAreaID)){
			$aux_condDpto = " true";
		}else{
			$aux_condDpto = "solicitudtrabmant.departamentoAreaID = '$departamentoAreaID'";
		}
		*/
		if(empty($departamentoAreaID)){
			$aux_condDpto = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($departamentoAreaID as $fila) {
			    $cod_empVec .= "'".$fila['departamentoAreaID']."',";
			}
			$cod_empVec = substr($cod_empVec, 1, -2);

			$aux_condDpto = "solicitudtrabmant.departamentoAreaID in ('$cod_empVec')";			
		}

		$sql = "select DAYOFMONTH(fechaHoraini) as dia,DATE_FORMAT(date(fechaHoraini), '%d/%m/%Y') AS fechaHoraini,COUNT(*) AS contSOT,
		SUM(case when fechainiTrab='0000-00-00 00:00:00' then 1 ELSE 0 end) AS SOTSinIniciar,
		SUM(case when fechainiTrab!='0000-00-00 00:00:00' and fechafinTrab='0000-00-00 00:00:00' then 1 ELSE 0 end) AS enEjecucion,
		SUM(case when fechafinTrab!='0000-00-00 00:00:00' and (ordentrabmant.statusaceprech=0 OR isnull(ordentrabmant.statusaceprech)) then 1 ELSE 0 end) AS SOTConFinSinVal,
		SUM(case when fechafinTrab!='0000-00-00 00:00:00' and ordentrabmant.statusaceprech=1 then 1 ELSE 0 end) AS SOTConFinConVal,
		SUM(case when fechafinTrab!='0000-00-00 00:00:00' and ordentrabmant.statusaceprech=2 then 1 ELSE 0 end) AS SOTRechazadas,
		SUM(case when tipomant='C' then 1 ELSE 0 end) AS mantCorrectivo,
		SUM(case when tipomant='P' then 1 ELSE 0 end) AS mantPreventivo,
		SUM(case when (tipomant='' or isnull(tipomant)) then 1 ELSE 0 end) AS mantSinAsignar
		FROM solicitudtrabmant LEFT JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		where solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDpto
		GROUP BY DATE_FORMAT(date(fechaHoraini), '%d/%m/%Y')
		ORDER BY solicitudtrabmant.fechaHoraini;";
		//echo $sql;
		
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$respuesta[] = $datos;
			}
			//$respuesta['exito'] = true;
		}else
		{
			//$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}


	function graficoPieOT($conexion,$fechad,$fechah,$departamentoAreaID)
	{
		$respuesta = array();
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		if(empty($departamentoAreaID)){
			$aux_condDpto = " true";
		}else{
			$aux_condDpto = "solicitudtrabmant.departamentoAreaID = '$departamentoAreaID'";
		}


		$sql = "select DAYOFMONTH(fechaHoraini) as dia,DATE_FORMAT(date(fechaHoraini), '%d/%m/%Y') AS fechaHoraini,COUNT(*) AS contSOT,
		SUM(case when fechainiTrab='0000-00-00 00:00:00' then 1 ELSE 0 end) AS SOTSinIniciar,
		SUM(case when fechainiTrab!='0000-00-00 00:00:00' and fechafinTrab='0000-00-00 00:00:00' then 1 ELSE 0 end) AS enEjecucion,
		SUM(case when fechafinTrab!='0000-00-00 00:00:00' and (ordentrabmant.statusaceprech=0 OR isnull(ordentrabmant.statusaceprech)) then 1 ELSE 0 end) AS SOTConFinSinVal,
		SUM(case when fechafinTrab!='0000-00-00 00:00:00' and ordentrabmant.statusaceprech=1 then 1 ELSE 0 end) AS SOTConFinConVal,
		SUM(case when fechafinTrab!='0000-00-00 00:00:00' and ordentrabmant.statusaceprech=2 then 1 ELSE 0 end) AS SOTRechazadas
		FROM solicitudtrabmant LEFT JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		where solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDpto
		ORDER BY solicitudtrabmant.fechaHoraini;";
		//echo $sql;
		
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$respuesta[] = $datos;
			}
			//$respuesta['exito'] = true;
		}else
		{
			//$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}


	function graficoEvalOT($conexion,$fechad,$fechah,$departamentoAreaID)
	{
		$respuesta = array();
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		/*
		if(empty($departamentoAreaID)){
			$aux_condDpto = " true";
		}else{
			$aux_condDpto = "solicitudtrabmant.departamentoAreaID = '$departamentoAreaID'";
		}
		*/
		if(empty($departamentoAreaID)){
			$aux_condDpto = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($departamentoAreaID as $fila) {
			    $cod_empVec .= "'".$fila['departamentoAreaID']."',";
			}
			$cod_empVec = substr($cod_empVec, 1, -2);

			$aux_condDpto = "solicitudtrabmant.departamentoAreaID in ('$cod_empVec')";			
		}

		$sql = "select DAYOFMONTH(fechaHoraini) as dia,DATE_FORMAT(date(fechaHoraini), '%d/%m/%Y') AS fechaHoraini,COUNT(*) AS contSOT,
		SUM(case when (evaluacion=0 AND fechafinTrab!='0000-00-00 00:00:00') then 1 ELSE 0 end) AS sinvalidar,
		SUM(case when evaluacion=1 then 1 ELSE 0 end) AS malo,
		SUM(case when evaluacion=2 then 1 ELSE 0 end) AS regular,
		SUM(case when evaluacion=3 then 1 ELSE 0 end) AS bien,
		SUM(case when evaluacion=4 then 1 ELSE 0 end) AS muyBien,
		SUM(case when evaluacion=5 then 1 ELSE 0 end) AS excelente
		FROM solicitudtrabmant LEFT JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		where solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDpto
		GROUP BY DATE_FORMAT(date(fechaHoraini), '%d/%m/%Y')
		ORDER BY solicitudtrabmant.fechaHoraini;";
		//echo $sql;
		//return 0;
		
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$respuesta[] = $datos;
			}
			//$respuesta['exito'] = true;
		}else
		{
			//$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}

	function consultarValidarOT01($conexion,$usuarioID)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['ordenTrab'] = array();
		$respuesta['numreg'] = 0;

		$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed">
			<thead>
				<tr>
					<th>Prioridad</th>
					<th>ST</th>
					<th>OT</th>
					<th>Fecha Ini</th>
					<th>Fecha Fin</th>
					<th>Tiempo</th>
					<th>Número</th>
					<th>Observaciones</th>
				</tr>
			</thead>
			<tbody>';
		$sql = "select solicitudtrabmant.solicitudTrabID,ordentrabmant.ordentrabmantID,
		solicitudtrabmant.departamentoAreaID,
		maquinariaequiposdetalle.codigoInterno,solicitudtrabmant.descripcion,fechaHoraini,ordentrabmant.fechafin,
		solicitudtrabmant.usuarioID,usuario.ema_usu,usuario.nom_usu,solicitudtrabmant.maquinariaequiposDetalleID,
		solicitudtrabmant.prioridad,departamento.nombre, 
		CONCAT(TIMESTAMPDIFF(DAY, ordentrabmant.fechaini, ordentrabmant.fechafin), 'd, ', 
		MOD(TIMESTAMPDIFF(HOUR, ordentrabmant.fechaini, ordentrabmant.fechafin), 24), 'h y ', 
		MOD(TIMESTAMPDIFF(MINUTE, ordentrabmant.fechaini, ordentrabmant.fechafin), 60), 'm') as  timetrascurrido
		from solicitudtrabmant INNER JOIN maquinariaequiposdetalle 
		ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID 
		INNER JOIN usuario 
		ON solicitudtrabmant.usuarioID=usuario.usuarioID 
		inner JOIN departamentoarea
		ON solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
		INNER JOIN departamento
		ON departamentoarea.departamentoID=departamento.departamentoID
		inner join ordentrabmant
		on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		where ordentrabmant.fechafin!='0000-00-00 00:00:00'
		AND solicitudtrabmant.departamentoAreaID IN 
		(SELECT vistadptoxusuario.departamentoAreaID FROM vistadptoxusuario WHERE vistadptoxusuario.usuarioID='$usuarioID')
		and solicitudtrabmant.usuarioID in 
		(select usuarioIDPermiso from validarot where usuarioID='$usuarioID')
		and solicitudtrabmant.usuarioIDdelete=0 and ordentrabmant.usuarioIDdelete=0 AND 
		ordentrabmant.statusaceprech=0 AND ordentrabmant.evaluacion=0
		ORDER BY solicitudtrabmant.prioridad;";
		//solicitudtrabmant.departamentoAreaID='$departamentoAreaID' and 
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$respuesta['numreg'] = $filas;
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Código encontrado.';
			$i = 0;
			$personas = array();
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i += 1;
				$ordentrabmantID    = $datos["ordentrabmantID"];
				$solicitudTrabID    = $datos["solicitudTrabID"];
				$maquinaID		    = $datos["maquinaID"];
				$codigoInterno      = $datos["codigoInterno"];
				$descripcion        = $datos["descripcion"];
				$fechaHoraini       = $datos["fechaHoraini"];
				$fechafin 			= $datos["fechafin"];
				$timetrascurrido	= $datos["timetrascurrido"];
				$prioridad          = $datos["prioridad"];

				$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i'>
					<td id='prioridad$i' name='prioridad$i'>$prioridad</td>
					<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
					<td id='ordentrabmantID$i' name='ordentrabmantID$i'>$ordentrabmantID</td>
					<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
					<td id='fechafin$i' name='fechafin$i'>$fechafin</td>
					<td id='timetrascurrido$i' name='timetrascurrido$i'>$timetrascurrido</td>
					<td id='maquinaID$i' name='maquinaID$i'>$codigoInterno</td>
					<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
				</tr>";
			}
			$respuesta['tabla'] .= "</tbody>
			</table>";
			$respuesta['nroreg'] = $i;
			$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()'>Guardar</button>";
		}else
		{
			$respuesta['mensaje'] = "Información no encontrada.";
		}
		echo json_encode($respuesta);
	}

	function graficoTAM($conexion,$fechad,$fechah,$personaID,$departamentoAreaID)
	{
		$respuesta = array();
		$horasProm = array();
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		if(empty($departamentoAreaID)){
			$aux_condDpto = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($departamentoAreaID as $fila) {
			    $cod_empVec .= "'".$fila['departamentoAreaID']."',";
			}
			$cod_empVec = substr($cod_empVec, 1, -2);

			$aux_condDpto = "solicitudtrabmant.departamentoAreaID in ('$cod_empVec')";			
		}
		if(empty($personaID)){
			$aux_condPers = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($personaID as $fila) {
			    $cod_persVec .= "'".$fila['personaID']."',";
			}
			$cod_persVec = substr($cod_persVec, 1, -2);

			$aux_condPers = "solicitudtrabmantpersona.personaID in ('$cod_persVec')";			
		}
		$sql = "select solicitudtrabmant.departamentoAreaID,
		departamento.nombre as nombreDpto
		FROM solicitudtrabmant INNER JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		inner join departamentoarea
		on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
		inner join departamento
		on departamentoarea.departamentoID=departamento.departamentoID
		inner join solicitudtrabmantpersona
		ON solicitudtrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
		WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDpto
		and $aux_condPers
		GROUP BY solicitudtrabmant.departamentoAreaID;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$i=0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$respuesta['dpto'][] = $datos;
				$departamentoAreaID = $datos['departamentoAreaID'];
				$i++;
			}
			//$respuesta['exito'] = true;
		}else
		{
			//$respuesta['mensaje'] = "Fallo la consulta";
		}
		$sql1 = "select vistapersonamant.nombre as label,solicitudtrabmantpersona.personaID
				FROM solicitudtrabmantpersona
				INNER JOIN vistapersonamant
				ON solicitudtrabmantpersona.personaID=vistapersonamant.personaID
				INNER JOIN ordentrabmant
				ON solicitudtrabmantpersona.solicitudTrabID=ordentrabmant.solicitudTrabID
				INNER JOIN solicitudtrabmant
				ON solicitudtrabmantpersona.solicitudTrabID=solicitudtrabmant.solicitudTrabID
				WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
				and $aux_condFecha
				and $aux_condDpto
				and $aux_condPers
				GROUP BY solicitudtrabmantpersona.personaID;";

		$ok1=$conexion->ejecutarQuery($sql1);
		$filas1=mysql_num_rows($ok1);
		if($filas1>0)
		{
			$i=0;
			while(($datos1=mysql_fetch_assoc($ok1))>0)
			{
				$id = $datos1['personaID'];
				$respuesta['mecanicos'][$i] = $datos1;
				$respuesta['mecanicosContTrab'][$i] = $datos1;

				switch($i){//RREQUEST lee valores _POST y _GET
					case 0:	
						$respuesta['mecanicos'][$i]['backgroundColor'] = 'rgba(255, 99, 132, 0.5)';
						$respuesta['mecanicos'][$i]['borderColor'] = 'rgb(255, 99, 132)';
						$respuesta['mecanicosContTrab'][$i]['backgroundColor'] = 'rgba(255, 99, 132, 0.5)';
						$respuesta['mecanicosContTrab'][$i]['borderColor'] = 'rgb(255, 99, 132)';
						break;
					case 1:	
						$respuesta['mecanicos'][$i]['backgroundColor'] = 'rgba(153, 102, 255, 0.5)';
						$respuesta['mecanicos'][$i]['borderColor'] = 'rgb(153, 102, 255)';
						$respuesta['mecanicosContTrab'][$i]['backgroundColor'] = 'rgba(153, 102, 255, 0.5)';
						$respuesta['mecanicosContTrab'][$i]['borderColor'] = 'rgb(153, 102, 255)';
						break;
					case 2:	
						$respuesta['mecanicos'][$i]['backgroundColor'] = 'rgba(255, 205, 86, 0.8)';
						$respuesta['mecanicos'][$i]['borderColor'] = 'rgb(255, 205, 86)';						
						$respuesta['mecanicosContTrab'][$i]['backgroundColor'] = 'rgba(255, 205, 86, 0.8)';
						$respuesta['mecanicosContTrab'][$i]['borderColor'] = 'rgb(255, 205, 86)';						
						break;
					case 3:	
						$respuesta['mecanicos'][$i]['backgroundColor'] = 'rgba(54, 162, 235, 0.5)';
						$respuesta['mecanicos'][$i]['borderColor'] = 'rgb(54, 162, 235)';					
						$respuesta['mecanicosContTrab'][$i]['backgroundColor'] = 'rgba(54, 162, 235, 0.5)';
						$respuesta['mecanicosContTrab'][$i]['borderColor'] = 'rgb(54, 162, 235)';					
						break;
					case 4:	
						
						break;
					case 5:	
						
						break;
					case 6:	
						
						break;
					default:
				}
				$respuesta['mecanicos'][$i]['borderWidth'] = 1;
				$respuesta['mecanicosContTrab'][$i]['borderWidth'] = 1;

				$sql2 = "select solicitudtrabmant.departamentoAreaID,
						(sum(MOD(TIMESTAMPDIFF(HOUR, ordentrabmant.fechaini, ordentrabmant.fechafin), 50000))/COUNT(*)) AS promHoras,
						COUNT(*) AS contTrab
						FROM solicitudtrabmantpersona
						INNER JOIN vistapersonamant
						ON solicitudtrabmantpersona.personaID=vistapersonamant.personaID
						INNER JOIN ordentrabmant
						ON solicitudtrabmantpersona.solicitudTrabID=ordentrabmant.solicitudTrabID
						INNER JOIN solicitudtrabmant
						ON solicitudtrabmantpersona.solicitudTrabID=solicitudtrabmant.solicitudTrabID
						inner join departamentoarea
						on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
						inner join departamento
						on departamentoarea.departamentoID=departamento.departamentoID
						WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
						and $aux_condFecha
						and $aux_condDpto
						and solicitudtrabmantpersona.personaID='$id'
						GROUP BY solicitudtrabmant.departamentoAreaID
						ORDER BY solicitudtrabmant.departamentoAreaID;";
						//echo $sql2;
						//return 0;
				$ok2=$conexion->ejecutarQuery($sql2);
				$filas2=mysql_num_rows($ok2);
				if($filas2>0)
				{
					unset($horasProm);
					$horasProm=array();
					while(($datos2=mysql_fetch_assoc($ok2))>0)
					{
						$idDpto = $datos2['departamentoAreaID'];
						$horasProm[$idDpto] = $datos2;
					}
				}
				//echo json_encode($horasProm);

				$sql = "select solicitudtrabmant.departamentoAreaID,
				departamento.nombre as nombreDpto
				FROM solicitudtrabmant INNER JOIN ordentrabmant
				ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
				inner join departamentoarea
				on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
				inner join departamento
				on departamentoarea.departamentoID=departamento.departamentoID
				inner join solicitudtrabmantpersona
				ON solicitudtrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
				WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
				and $aux_condFecha
				and $aux_condDpto
				and $aux_condPers
				GROUP BY solicitudtrabmant.departamentoAreaID;";
				//echo $sql;
				
				$ok=$conexion->ejecutarQuery($sql);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$j = 0;
					while(($datos=mysql_fetch_assoc($ok))>0)
					{
						$respuesta['mecanicos'][$i]['data'][$j] = 0;
						$respuesta['mecanicosContTrab'][$i]['data'][$j] = 0;
						$idDpto = $datos['departamentoAreaID'];
						$respuesta['mecanicos'][$i]['data'][$j] = $horasProm[$idDpto]['promHoras'];
						$respuesta['mecanicosContTrab'][$i]['data'][$j] = $horasProm[$idDpto]['contTrab'];
						$j++;
					}
					//$respuesta['exito'] = true;
				}
				$i++;
			}
		}
		echo json_encode($respuesta);
	}

	function graficoTAMaq($conexion,$fechad,$fechah,$departamentoAreaID,$maquinariaequiposDetalleID,$valTablacolores)
	{
		$respuesta = array();
		$horasProm = array();
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		if(empty($departamentoAreaID)){
			$aux_condDptoArea = "true";
		}else{
			$aux_condDptoArea = "solicitudtrabmant.departamentoAreaID='$departamentoAreaID'";			
		}
		if(empty($maquinariaequiposDetalleID)){
			$aux_condMaq = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($maquinariaequiposDetalleID as $fila) {
			    $cod_MaqVec .= "'".$fila['maquinariaequiposDetalleID']."',";
			}
			$cod_MaqVec = substr($cod_MaqVec, 1, -2);

			$aux_condMaq = "solicitudtrabmant.maquinariaequiposDetalleID in ('$cod_MaqVec')";			
		}
		$sql = "select solicitudtrabmant.departamentoAreaID,
		departamento.nombre as nombreDpto
		FROM solicitudtrabmant INNER JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		inner join departamentoarea
		on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
		inner join departamento
		on departamentoarea.departamentoID=departamento.departamentoID
		WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDptoArea
		GROUP BY solicitudtrabmant.departamentoAreaID;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$i=0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$respuesta['dpto'][] = $datos;
				$departamentoAreaID = $datos['departamentoAreaID'];
				$i++;
			}
			//$respuesta['exito'] = true;
		}else
		{
			//$respuesta['mensaje'] = "Fallo la consulta";
		}
		$sql1 = "select maquinariaequiposdetalle.codigoInterno as label,solicitudtrabmant.maquinariaequiposDetalleID
				FROM solicitudtrabmant
				INNER JOIN maquinariaequiposdetalle
				ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID
				INNER JOIN ordentrabmant
				ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
				inner join maquinariaequipos
				on maquinariaequiposdetalle.maquinariaEquiposID = maquinariaequipos.maquinariaEquiposID
				WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
				and $aux_condFecha
				and $aux_condMaq
				and $aux_condDptoArea
				GROUP BY solicitudtrabmant.maquinariaequiposDetalleID;";

		$ok1=$conexion->ejecutarQuery($sql1);
		$filas1=mysql_num_rows($ok1);
		if($filas1>0)
		{
			$i=0;
			while(($datos1=mysql_fetch_assoc($ok1))>0)
			{
				$id = $datos1['maquinariaequiposDetalleID'];
				$respuesta['maquinas'][$i] = $datos1;
				$respuesta['maquinas'][$i]['backgroundColor'] = $valTablacolores[$i]['colorbackgr'];
				$respuesta['maquinas'][$i]['borderColor'] = $valTablacolores[$i]['colorborder'];

				$respuesta['maquinas'][$i]['borderWidth'] = 1;

				$sql2 = "select solicitudtrabmant.departamentoAreaID,
						(sum(MOD(TIMESTAMPDIFF(HOUR, ordentrabmant.fechaini, ordentrabmant.fechafin), 50000))/COUNT(*)) AS promHoras
						FROM solicitudtrabmant
						INNER JOIN ordentrabmant
						ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
						inner join departamentoarea
						on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
						inner join departamento
						on departamentoarea.departamentoID=departamento.departamentoID
						WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
						and $aux_condFecha
						and $aux_condMaq
						and solicitudtrabmant.maquinariaequiposDetalleID='$id'
						GROUP BY solicitudtrabmant.departamentoAreaID
						ORDER BY solicitudtrabmant.departamentoAreaID;";
						//echo $sql2;
						//return 0;
				$ok2=$conexion->ejecutarQuery($sql2);
				$filas2=mysql_num_rows($ok2);
				if($filas2>0)
				{
					unset($horasProm);
					$horasProm=array();
					while(($datos2=mysql_fetch_assoc($ok2))>0)
					{
						$idDpto = $datos2['departamentoAreaID'];
						$horasProm[$idDpto] = $datos2;
					}
				}
				//echo json_encode($horasProm);
				$sql = "select solicitudtrabmant.departamentoAreaID,
				departamento.nombre as nombreDpto
				FROM solicitudtrabmant INNER JOIN ordentrabmant
				ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
				inner join departamentoarea
				on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
				inner join departamento
				on departamentoarea.departamentoID=departamento.departamentoID
				inner join solicitudtrabmantpersona
				ON solicitudtrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
				WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
				and $aux_condFecha
				and $aux_condMaq
				and $aux_condDptoArea
				GROUP BY solicitudtrabmant.departamentoAreaID;";
				//echo $sql;
				
				$ok=$conexion->ejecutarQuery($sql);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$j = 0;
					while(($datos=mysql_fetch_assoc($ok))>0)
					{
						$respuesta['maquinas'][$i]['data'][$j] = 0;
						$idDpto = $datos['departamentoAreaID'];
						$respuesta['maquinas'][$i]['data'][$j] = $horasProm[$idDpto]['promHoras'];
						$j++;
					}
					//$respuesta['exito'] = true;
				}
				$i++;
			}
		}
		echo json_encode($respuesta);
	}

	function graficoDxTFallas($conexion,$fechad,$fechah,$personaID,$departamentoAreaID,$tipofalla)
	{
		$respuesta = array();
		$horasProm = array();
		if(empty($fechad) or empty($fechah)){
			$aux_condFecha = " true";
		}else{
			$fecha = date_create_from_format('d/m/Y', $fechad);
			$fechad = date_format($fecha, 'Y-m-d')." 00:00:00";
			$fecha = date_create_from_format('d/m/Y', $fechah);
			$fechah = date_format($fecha, 'Y-m-d')." 23:59:59";
			$aux_condFecha = "solicitudtrabmant.fechaHoraini>='$fechad' and solicitudtrabmant.fechaHoraini<='$fechah'";
		}
		if(empty($departamentoAreaID)){
			$aux_condDpto = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($departamentoAreaID as $fila) {
			    $cod_empVec .= "'".$fila['departamentoAreaID']."',";
			}
			$cod_empVec = substr($cod_empVec, 1, -2);

			$aux_condDpto = "solicitudtrabmant.departamentoAreaID in ('$cod_empVec')";			
		}
		if(empty($personaID)){
			$aux_condPers = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($personaID as $fila) {
			    $cod_persVec .= "'".$fila['personaID']."',";
			}
			$cod_persVec = substr($cod_persVec, 1, -2);

			$aux_condPers = "solicitudtrabmantpersona.personaID in ('$cod_persVec')";			
		}
		if(empty($tipofalla)){
			$aux_codTfalla = "true";
		}else{
			/* AQUI CONVIERTO EL VECTOR EN UNA CADENA PARA LUEGO BUSCAR DENTRO DE ELLA EN EL SQL*/
			foreach ($tipofalla as $fila) {
			    $cod_tipofallaVec .= "'".$fila['tipofalla']."',";
			}
			$cod_tipofallaVec = substr($cod_tipofallaVec, 1, -2);

			$aux_codTfalla = "ordentrabmant.tipofalla in ('$cod_tipofallaVec')";			
		}
		//echo $aux_codTfalla;
		//return 0;

		$sql = "select solicitudtrabmant.departamentoAreaID,
		departamento.nombre as nombreDpto
		FROM solicitudtrabmant INNER JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		inner join departamentoarea
		on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
		inner join departamento
		on departamentoarea.departamentoID=departamento.departamentoID
		inner join solicitudtrabmantpersona
		ON solicitudtrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
		WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
		and $aux_condFecha
		and $aux_condDpto
		and $aux_condPers
		and $aux_codTfalla
		GROUP BY solicitudtrabmant.departamentoAreaID;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		if($filas>0)
		{
			$i=0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$respuesta['dpto'][] = $datos;
				$departamentoAreaID = $datos['departamentoAreaID'];
				$i++;
			}
			//$respuesta['exito'] = true;
		}else
		{
			//$respuesta['mensaje'] = "Fallo la consulta";
		}
		$i=0;
		foreach ($tipofalla as $fila) {
			//echo $fila['tipofalla'].'-';
			$aux_tipoFalla = $fila['tipofalla'];
			$respuesta['tipofalla'][$i]['cod_tipFalla'] = $fila['tipofalla'];
			$respuesta['tipofalla'][$i]['label'] = $fila['label'];

			$respuesta['tipofallaCant'][$i]['cod_tipFalla'] = $fila['tipofalla'];
			$respuesta['tipofallaCant'][$i]['label'] = $fila['label'];

			switch($i){//RREQUEST lee valores _POST y _GET
				case 0:	
					$respuesta['tipofalla'][$i]['backgroundColor'] = 'rgba(255, 99, 132, 0.5)';
					$respuesta['tipofalla'][$i]['borderColor'] = 'rgb(255, 99, 132)';
					$respuesta['tipofallaCant'][$i]['backgroundColor'] = 'rgba(255, 99, 132, 0.5)';
					$respuesta['tipofallaCant'][$i]['borderColor'] = 'rgb(255, 99, 132)';
					break;
				case 1:	
					$respuesta['tipofalla'][$i]['backgroundColor'] = 'rgba(153, 102, 255, 0.5)';
					$respuesta['tipofalla'][$i]['borderColor'] = 'rgb(153, 102, 255)';
					$respuesta['tipofallaCant'][$i]['backgroundColor'] = 'rgba(153, 102, 255, 0.5)';
					$respuesta['tipofallaCant'][$i]['borderColor'] = 'rgb(153, 102, 255)';
					break;
				case 2:	
					$respuesta['tipofalla'][$i]['backgroundColor'] = 'rgba(255, 205, 86, 0.8)';
					$respuesta['tipofalla'][$i]['borderColor'] = 'rgb(255, 205, 86)';						
					$respuesta['tipofallaCant'][$i]['backgroundColor'] = 'rgba(255, 205, 86, 0.8)';
					$respuesta['tipofallaCant'][$i]['borderColor'] = 'rgb(255, 205, 86)';						
					break;
				case 3:	
					$respuesta['tipofalla'][$i]['backgroundColor'] = 'rgba(54, 162, 235, 0.5)';
					$respuesta['tipofalla'][$i]['borderColor'] = 'rgb(54, 162, 235)';					
					$respuesta['tipofallaCant'][$i]['backgroundColor'] = 'rgba(54, 162, 235, 0.5)';
					$respuesta['tipofallaCant'][$i]['borderColor'] = 'rgb(54, 162, 235)';					
					break;
				default:
			}
			$respuesta['tipofalla'][$i]['borderWidth'] = 1;
			$respuesta['tipofallaCant'][$i]['borderWidth'] = 1;

			$sql2 = "select solicitudtrabmant.departamentoAreaID,
					(sum(MOD(TIMESTAMPDIFF(HOUR, ordentrabmant.fechaini, ordentrabmant.fechafin), 50000))/COUNT(*)) AS promHoras,
					COUNT(*) as cantTipFallas
					FROM ordentrabmant INNER JOIN solicitudtrabmant
					ON ordentrabmant.solicitudTrabID=solicitudtrabmant.solicitudTrabID
					inner join departamentoarea
					on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
					inner join departamento
					on departamentoarea.departamentoID=departamento.departamentoID
					inner join solicitudtrabmantpersona
					on ordentrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
					WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
					and $aux_condFecha
					and $aux_condDpto
					and $aux_condPers
					and locate('$aux_tipoFalla',tipofalla)
					GROUP BY solicitudtrabmant.departamentoAreaID
					ORDER BY solicitudtrabmant.departamentoAreaID;";
					//echo $sql2;
					//return 0;
			$ok2=$conexion->ejecutarQuery($sql2);
			$filas2=mysql_num_rows($ok2);
			if($filas2>0)
			{
				unset($horasProm);
				$horasProm=array();
				while(($datos2=mysql_fetch_assoc($ok2))>0)
				{
					$idDpto = $datos2['departamentoAreaID'];
					$horasProm[$idDpto] = $datos2;
				}
			}
			//echo json_encode($horasProm);
			$sql = "select solicitudtrabmant.departamentoAreaID,
			departamento.nombre as nombreDpto
			FROM solicitudtrabmant INNER JOIN ordentrabmant
			ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
			inner join departamentoarea
			on solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
			inner join departamento
			on departamentoarea.departamentoID=departamento.departamentoID
			inner join solicitudtrabmantpersona
			ON solicitudtrabmant.solicitudTrabID=solicitudtrabmantpersona.solicitudTrabID
			WHERE ordentrabmant.usuarioIDdelete=0 and solicitudtrabmant.usuarioIDdelete=0
			and $aux_condFecha
			and $aux_condDpto
			and $aux_condPers
			and $aux_codTfalla
			GROUP BY solicitudtrabmant.departamentoAreaID;";
			//echo $sql;
			
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$j = 0;
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$respuesta['tipofalla'][$i]['data'][$j] = 0;
					$respuesta['tipofallaCant'][$i]['data'][$j] = 0;
					$idDpto = $datos['departamentoAreaID'];
					$respuesta['tipofalla'][$i]['data'][$j] = $horasProm[$idDpto]['promHoras'];
					$respuesta['tipofallaCant'][$i]['data'][$j] = $horasProm[$idDpto]['cantTipFallas'];
					$j++;
				}
				//$respuesta['exito'] = true;
			}
			$i++;
		}
		echo json_encode($respuesta);
	}

}
?>