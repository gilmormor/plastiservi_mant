<?php
//require_once "Conexion.class.php";
class solicitudtrabmant
{
	function consultarSolicitudMant($conexion,$maquinaID,$departamentoAreaID)
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
						<th>FecHor Ini</th>
						<th>ST</th>
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
			$sql = "select solicitudtrabmant.solicitudTrabID,solicitudtrabmant.departamentoAreaID,
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
			where solicitudtrabmant.fechafinTrab='0000-00-00 00:00:00'
			AND solicitudtrabmant.solicitudTrabID IN 
			(SELECT solicitudtrabmantpersona.solicitudTrabID FROM solicitudtrabmantpersona)
			and solicitudtrabmant.usuarioIDdelete=0
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
					$solicitudTrabID    = $datos["solicitudTrabID"];
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
						<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
						<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
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

					$sql2 = "select * FROM ordentrabmant WHERE solicitudTrabID='$solicitudTrabID and usuarioIDdelete=0';";
					//echo $sql;
					$aux_btnserv = "<a id='btnInicioServ$i' name='btnInicioServ$i' class='btn btn-success btn-sm' onclick='iniciarServicioMant($i)' title='Iniciar servicio Mantención' data-toggle='tooltip'><span id='glypcnbtnInicioServ$i' class='glyphicon glyphicon-play' style='bottom: 0px;top: 2px;'></span></a>";
					$ok2=$conexion->ejecutarQuery($sql2);
					$filas2=mysql_num_rows($ok2);
					$disabledbtnOT="disabled";
					$disabledbtnIOT="";
					if($filas2>0)
					{
						$disabledbtnIOT="disabled";
						$aux_btnserv = "<a id='btnInicioServ$i' name='btnInicioServ$i' class='btn btn-warning btn-sm' onclick='finServicioMant($i)' title='Finalizar Orden de trabajo' data-toggle='tooltip'><span id='glypcnbtnInicioServ$i' class='glyphicon glyphicon-stop' style='bottom: 0px;top: 2px;'></span></a>";
						$disabledbtnOT="";
					}

					$respuesta['tabla'] .= $cadena."
							</div>
						</td>
						<td>".$aux_btnserv." | 
							<a id='btnOrdenTrabajo$i' name='btnOrdenTrabajo$i' class='btn btn-primary btn-sm $disabledbtnOT' data-toggle='modal' data-target='#ModalCenter' onclick='ordenTrabajo($i)' title='Generar Orden de Trabajo.' data-toggle='tooltip'>Ord Trab <span class='glyphicon glyphicon-floppy-save' style='bottom: 0px;top: 2px;'></span></a>
						</td>
						<td id='ema_usu$i' name='ema_usu$i'  style='display:none;'>$ema_usu</td>
						<td id='usuarioID$i' name='usuarioID$i' style='display:none;'>$usuarioID</td>
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


	function hacer_lista_desplegableMultiple($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		$cadena = "<select class='selectpicker form-control' data-live-search='true' data-hide-disabled='true' data-actions-box='true' id='$nombre' name='$nombre' onChange='$funcion' multiple title='Seleccione'>
		    ";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  $cadena .= "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		$cadena .= "</select>";
		return $cadena;
	}

	function llenarMaquinasPorArea($conexion,$departamentoAreaID)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Dpto no Existe";
		$respuesta['botonGuardar'] = "";

		$respuesta['tabla'] = "
		<thead>
			<th align='center' class='bg-primary col-sm-1 col-md-1 col-lg-1 col-xl-1' style='display:none;'>MaquinaID
			</th>
			<th align='center' class='bg-primary col-sm-1 col-md-1 col-lg-1 col-xl-1'>Número
			</th>
			<th align='center' class='bg-primary col-sm-1 col-md-1 col-lg-1 col-xl-1 justify-content-md-center' style='text-align:center;'>ES
			</th>
			<th align='center' class='bg-primary col-sm-1 col-md-1 col-lg-1 col-xl-1' style='display:none;'>FS
			</th>
			<th align='center' class='bg-primary col-sm-10 col-md-10 col-lg-10 col-xl-10'>Observaciones
			</th>
			<th align='center' class='bg-primary col-sm-1 col-md-1 col-lg-1 col-xl-1' style='display:none;'>solicitudTrabID
			</th>
		</thead>
		<tbody>";
		if($departamentoAreaID!=""){
			$sql = "select * from maquinariaequiposdetalle where departamentoAreaID='$departamentoAreaID' AND statusMant=1;";
			//echo $sql;
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = 'Informacion Encontrada.';
				$i = 0;

				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$i += 1;
					$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
					$descripcionFS              = "";


					// Consulto tabla maquinaequipofueraservicio para saber los equipos fuera de servicio
					$sql1 = "select * from maquinaequipofueraservicio where maquinariaequiposDetalleID='$maquinariaequiposDetalleID' order by fechaHoraInsert;";
					//echo $sql;
					$ok1=$conexion->ejecutarQuery($sql1);
					$filas=mysql_num_rows($ok1);
					if($filas>0)
					{
						while(($datos1=mysql_fetch_assoc($ok1))>0)
						{
							$descripcionFS = $datos1["descripcion"].", ".$datos1["fechaHoraInsert"];
						}
					}

					$codigoInterno  = $datos["codigoInterno"];
					$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
					$observaciones  = ""; //$datos["observMantenimiento"];
					$aux_disabled   = "";
					$estatus        = "0";
					$aux_checkedES  = "";
					$aux_disableobs = "disabled";
					$aux_stylecheck = "";
					$solicitudTrabID= "";

					// Consulto tabla solicitudtrabmant para saber observaciones guardades de manteminiento
					$sql2 = "select * from solicitudtrabmant where maquinariaequiposDetalleID='$maquinariaequiposDetalleID' and fechafindeTrab='0000-00-00 00:00:00' and usuarioIDdelete=0 order by fechaHoraInsert;";
					//echo $sql2;
					$ok2=$conexion->ejecutarQuery($sql2);
					$filas=mysql_num_rows($ok2);
					if($filas>0)
					{
						while(($datos2=mysql_fetch_assoc($ok2))>0)
						{
							$solicitudTrabID= $datos2["solicitudTrabID"];
							$observaciones  = $datos2["descripcion"];
							$aux_checkedES  = "checked";
							$aux_disableobs = "";
							$estatus        = "2"; //En servicio pero con observacion de mantenimiento recien incluida en planta
						}
					}

					if($descripcionFS!="")
					{
						$observaciones  = $descripcionFS;
						$aux_disabled   = "disabled";
						$estatus        = "3"; //Fuera de servicio 
						$aux_stylecheck = "style='display:none;'";
					}
					$aux_texto='texto';
					$aux_col='col';

					$respuesta['tabla'] .= "
					<tr valign='middle'>
						<td valign='middle' style='display:none; padding-bottom: 4px;padding-top: 4px;'>
							<input id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i' type='text' value='$maquinariaequiposDetalleID' readonly>
						</td>
						<td valign='middle' style='padding-bottom: 4px;padding-top: 4px;'>
							<input type='text' style='text-align:center;' value='$codigoInterno' name='codigoInterno$i' id='codigoInterno$i' class='form-control input-sm' readonly title='$codigoInterno'>
						</td>
						<td valign='middle' style='padding-bottom: 4px;padding-top: 4px; text-align:center;'>
							<div $aux_stylecheck>
								<input type='checkbox' style='text-align:center;' value='$estado' name='estado$i' id='estado$i' class='form-control input-sm custom-checkbox' readonly title='En Servicio' onChange='activarObserv($i)' $aux_disabled $aux_checkedES>
								<label for='estado$i' type='text' class='form-group' style='padding-left: 20px;'></label>
							</div>
						</td>
						<td valign='middle' style='display:none; padding-bottom: 4px;padding-top: 4px;'>
							<input type='text' style='text-align:left;' value='$estatus' name='estatus$i' id='estatus$i' class='form-control input-sm custom-checkbox' readonly>
						</td>
						<td style='padding-bottom: 4px;padding-top: 4px; padding-right: 2px;'>
							<input type='text' style='text-align:left;' value='$observaciones' name='observaciones$i' id='observaciones$i' class='form-control input-sm' title='$observaciones' $aux_disableobs >
						</td>
						<td style='display:none; padding-bottom: 4px;padding-top: 4px;'>
							<input type='text' style='text-align:left;' value='$solicitudTrabID' name='solicitudTrabID$i' id='solicitudTrabID$i' class='form-control input-sm' title='$solicitudTrabID'>
							<span class='help-block' style='left: 1405px;top: 4px;'></span>
						</td>
					</tr>";	
				}
				$respuesta['tabla'] .= "</tbody>
				</table>";
				$respuesta['nroreg'] = $i;
				$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-sm-4 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()' data-toggle='tooltip'  data-placement='right'>Guardar</button>";
			}else
			{
				$respuesta['mensaje'] = "Información no encontrada.";
			}
		}
		echo json_encode($respuesta);
	}

	function guardarChDiaMaq($conexion,$usuarioID,$departamentoAreaID,$filas){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$inserciones = 0;
		$cont_guardar = 0;
		foreach ($filas as $fila) {
			$solicitudTrabID  = $fila['solicitudTrabID'];
		    $maquinaID    = $fila['maquinaID'];
		    $estatus      = $fila['estatus'];
		    $observacion  = $fila['observacion'];
		    $sql          = "";
		    if($estatus=="1"){
		    	$sql = "insert into solicitudtrabmant (maquinariaequiposDetalleID, departamentoAreaID, fechaHoraini, descripcion, usuarioID, fechaHoraInsert) values('$maquinaID',$departamentoAreaID,now(),'$observacion','$usuarioID',now())"; 
		    }
		    if($estatus=="2"){
		    	if($observacion==""){
		    		//$sql = "delete from solicitudtrabmant where solicitudTrabID='$solicitudTrabID';";
		    		$sql = "update solicitudtrabmant set fechadelete=now(),usuarioIDdelete=$usuarioID where solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
		    	}else{
		    		$sql = "update solicitudtrabmant set descripcion='$observacion' where solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";	
		    	}
		    }
		    if($sql!=""){
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
				$cont_guardar = $cont_guardar + 1;
		    }
		}
		if($cont_guardar==0){
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "No existen registros a modificar.";
		}
		echo json_encode($respuesta);
	}

	function consultaTrabMant($conexion){
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Fallo la consulta";
		$respuesta['tabla'] = "";
		$usuarioID = $_SESSION["usuarioID"];
		$sql = "select solicitudtrabmant.solicitudTrabID,solicitudtrabmant.fechaHoraini,
			solicitudtrabmant.maquinariaequiposDetalleID,
			maquinariaequiposdetalle.codigoInterno,solicitudtrabmant.descripcion,
			ordentrabmant.ordentrabmantID,ordentrabmant.fechaini,
			vistausuariopersona.usuarioID,vistausuariopersona.nom_usu,
			CONCAT(vistausuariopersona.nom_usu, ' ', vistausuariopersona.ape_usu) as nomape_usu,
			vistausuariopersona.ema_usu,
			solicitudtrabmant.departamentoAreaID,solicitudtrabmant.prioridad
			from solicitudtrabmant LEFT JOIN ordentrabmant
			on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
			inner join vistausuariopersona
			on solicitudtrabmant.usuarioID=vistausuariopersona.usuarioID
			INNER JOIN persona
			ON vistausuariopersona.ema_usu=persona.email
			INNER JOIN maquinariaequiposdetalle
			ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID
			where solicitudtrabmant.fechafinTrab='0000-00-00 00:00:00' AND solicitudtrabmant.usuarioID='$usuarioID' and  
			solicitudtrabmant.departamentoAreaID IN 
			(SELECT personadepartamentoarea.departamentoAreaID 
			FROM personadepartamentoarea inner join vistausuariopersona 
			on personadepartamentoarea.personaID=vistausuariopersona.personaID
			WHERE vistausuariopersona.usuarioID='$usuarioID')
			and solicitudtrabmant.usuarioIDdelete=0
			order by solicitudtrabmant.fechaHoraini;";
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
						<th>F.Ini Solicitud</th>
						<th>Cod Equipo</th>
						<th>ST</th>
						<th>Observaciones</th>
						<th>OT</th>
						<th>FecIni Mantención</th>
						<th>Usuario</th>
						<th class='col-xs-1 col-sm-1'>Acción</th>
						<th style='display:none;'>usuarioID</th>
						<th style='display:none;'>ema_usu</th>
						<th style='display:none;'>departamentoAreaID</th>
						<th style='display:none;'>maquinariaequiposDetalleID</th>
						<th style='display:none;'>prioridad</th>
					</tr>
				</thead>
				<tbody>";
			$i = 0;
			while(($datos=mysql_fetch_assoc($ok))>0)
			{
				$i++; 
				$solicitudTrabID    = $datos["solicitudTrabID"];
				$solicitudTrabID1   = str_pad($solicitudTrabID, 3, "0", STR_PAD_LEFT);
				$observaciones      = $datos["descripcion"];
				$ordentrabmantID    = str_pad($datos["ordentrabmantID"], 3, "0", STR_PAD_LEFT);
				$fechaini           = $datos["fechaini"];
				$estatusTrab        = true;
				$accion             = "";
				$usuarioID          = $datos["usuarioID"];
				$nom_usu            = $datos["nom_usu"];
				$ema_usu            = $datos["ema_usu"];
				$fechaHoraini       = $datos["fechaHoraini"];
				$departamentoAreaID = $datos["departamentoAreaID"];
				$codigoInterno      = $datos["codigoInterno"];
				$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
				$prioridad          = $datos["prioridad"];

				if($ordentrabmantID == "000"){
					$ordentrabmantID = "";
					$accion          = "<a id='modificarObs$i' name='modificarObs$i' class='btn btn-primary btn-sm' onclick='modificarObs($i)' title='Modificar Observación' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil' style='bottom: 0px;top: 2px;'></span></a> | <a id='elimninar$i' name='elimninar$i' class='btn btn-danger btn-sm' onclick='eliminarST($i)' title='Eliminar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash' style='bottom: 0px;top: 2px;'></span></a>";
				}
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i'>
						<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
						<td id='codigoInterno$i' name='codigoInterno$i'>$codigoInterno</td>
						<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
						<td id='observaciones$i' name='observaciones$i'>$observaciones</td>
						<td id='ordentrabmantID$i' name='ordentrabmantID$i'>$ordentrabmantID</td>
						<td id='fechaini$i' name='fechaini$i'>$fechaini</td>
						<td id='nom_usu$i' name='nom_usu$i'>$nom_usu</td>
						<td>$accion</td>
						<td id='usuarioID$i' name='usuarioID$i' style='display:none;'>$usuarioID</td>
						<td id='ema_usu$i' name='ema_usu$i' style='display:none;'>$ema_usu</td>
						<td id='departamentoAreaID$i' name='departamentoAreaID$i' style='display:none;'>$departamentoAreaID</td>
						<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i' style='display:none;'>$maquinariaequiposDetalleID</td>
						<td id='prioridad$i' name='prioridad$i' style='display:none;'>$prioridad</td>

					</tr>";
				
				//$aux_checkedES   = "checked";
				//$aux_disableobs  = "";
				$estatus         = "2"; //En servicio pero con observacion de mantenimiento recien incluida en planta
			}
			$respuesta['tabla'] .= "</tbody>
							  </table>";
		}else{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "Información no encontrada";
		}
		echo json_encode($respuesta);
	}

	function insertUpdate($conexion,$maquinariaequiposDetalleID,$departamentoAreaID,$descripcion,$prioridad,$solicitudTrabID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";

		$usuarioID = $_SESSION["usuarioID"];
		if($solicitudTrabID == ""){
			$sql = "insert into solicitudtrabmant(maquinariaequiposDetalleID,departamentoAreaID,fechaHoraini,descripcion,usuarioID,prioridad,fechaHoraInsert) value('$maquinariaequiposDetalleID','$departamentoAreaID',now(),'$descripcion','$usuarioID','$prioridad',now());";
		}else{
			$sql = "update solicitudtrabmant set descripcion='$descripcion',maquinariaequiposDetalleID='$maquinariaequiposDetalleID',prioridad='$prioridad',departamentoAreaID='$departamentoAreaID' where solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
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


}
?>