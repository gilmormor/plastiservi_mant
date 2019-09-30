<?php
//require_once "Conexion.class.php";
class maquina
{
	function consultar($conexion,$maquinaID,$departamentoAreaID,$aux_codfiltro)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['ordenTrab'] = array();
		$respuesta['numreg'] = 0;
		$aux_filtro="where fechafinTrab='0000-00-00 00:00:00' and solicitudtrabmant.usuarioIDdelete=0 ";
		if($aux_codfiltro=="1"){
			$aux_filtro .="";
		}
		if($aux_codfiltro=="2"){
			$aux_filtro .=" and fechainiTrab!='0000-00-00 00:00:00'";
		}
		if($aux_codfiltro=="3"){
			$aux_filtro .=" and solicitudtrabmant.solicitudTrabID not in (select solicitudTrabID from solicitudtrabmantpersona)";
		}
		if(empty($departamentoAreaID)==false){
			$aux_filtro .=" and solicitudtrabmant.departamentoAreaID='$departamentoAreaID'";
		}

		$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed order-column">
			<thead>
				<tr>
					<th style="display:none;">P</th>
					<th>ST</th>
					<th>nombreDpto</th>
					<th>Número</th>
					<th>FecHor Ini</th>
					<th>Observaciones</th>
					<th>Solicitante</th>
					<th align="left">Operadores</th>
					<th align="right">Prioridad</th>
					<th align="center" class="col-xs-1 col-sm-1">Acción</th>
					<th style="display:none;">prioridad1</th>
					<th style="display:none;">ema_usu</th>
					<th style="display:none;">usuarioID</th>
					<th style="display:none;">maquinariaequiposDetalleID</th>
					<th style="display:none;">departamentoAreaID</th>

				</tr>
			</thead>
			<tbody>';
		$sql = "select solicitudtrabmant.solicitudTrabID,solicitudtrabmant.departamentoAreaID,
		maquinariaequiposdetalle.maquinariaequiposDetalleID,maquinariaequiposdetalle.codigoInterno,
		solicitudtrabmant.descripcion,fechaHoraini,fechafinTrab,
		solicitudtrabmant.usuarioID,usuario.ema_usu,usuario.nom_usu,usuario.ape_usu,solicitudtrabmant.prioridad,
		departamento.nombre,ordentrabmant.statusaceprech,ordentrabmant.obseraceprech,
		ordentrabmant.fechaini,ordentrabmant.fechafin
		from solicitudtrabmant INNER JOIN maquinariaequiposdetalle 
		ON solicitudtrabmant.maquinariaequiposDetalleID=maquinariaequiposdetalle.maquinariaequiposDetalleID 
		INNER JOIN usuario 
		ON solicitudtrabmant.usuarioID=usuario.usuarioID 
		inner JOIN departamentoarea
		ON solicitudtrabmant.departamentoAreaID=departamentoarea.departamentoAreaID
		INNER JOIN departamento
		ON departamentoarea.departamentoID=departamento.departamentoID
		LEFT JOIN ordentrabmant
		ON solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
		$aux_filtro 
		order by prioridad;";
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
				$maquinaID		    = $datos["maquinaID"];
				$codigoInterno      = $datos["codigoInterno"];
				$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
				$descripcion        = $datos["descripcion"];
				$fechaHoraini       = $datos["fechaHoraini"];
				$ema_usu            = $datos["ema_usu"];
				$usuarioID          = $datos["usuarioID"];
				$nomapeusu          = $datos["nom_usu"].' '.$datos["ape_usu"];
				$prioridad          = $datos["prioridad"];
				$statusaceprech     = $datos["statusaceprech"];
				$obseraceprech      = $datos["obseraceprech"];
				$estado             = "0"; //date("d-m-Y", strtotime($dep_fecha));

					
				$sql2 = "select * FROM ordentrabmant WHERE solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
				//echo $sql;
				$aux_btnserv = "<a id='btnInicioServ$i' name='btnInicioServ$i' class='btn btn-success btn-sm' onclick='iniciarServicioMant($i)' title='Iniciar OT' data-toggle='tooltip'><span id='glypcnbtnInicioServ$i' class='glyphicon glyphicon-play' style='bottom: 0px;top: 2px;'></span></a>";
				$ok2=$conexion->ejecutarQuery($sql2);
				$filas2=mysql_num_rows($ok2);
				$disabledbtnOT="disabled";
				$disabledbtnIOT="";
				$disabledPrior="";
				if(!empty($prioridad)){
					$disabledPrior = "disabled";
				}
				$ordentrabmantID = "";
				if($filas2>0)
				{
					$datosOT=mysql_fetch_assoc($ok2);
					$ordentrabmantID = "#".$datosOT["ordentrabmantID"];

					$disabledbtnIOT="disabled";
					$disabledPrior ="disabled";
					$aux_btnserv = "<a id='btnInicioServ$i' name='btnInicioServ$i' class='btn btn-warning btn-sm' onclick='finServicioMant($i)' title='Finalizar OT $ordentrabmantID' data-toggle='tooltip'><span id='glypcnbtnInicioServ$i' class='glyphicon glyphicon-stop' style='bottom: 0px;top: 2px;'></span></a>";
					$disabledbtnOT="";

				}
				$colorFila = "";
				if($statusaceprech=='2'){
					$colorFila = " style='background-color: rgb(255, 105, 97);'  title='Rechazo por: $obseraceprech' data-toggle='tooltip'";
				}
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i' $colorFila>
					<td id='prioridadOrd$i' name='prioridadOrd$i' style='display:none;'>$prioridad</td>
					<td id='solicitudTrabID$i' name='solicitudTrabID$i'>$solicitudTrabID</td>
					<td id='nombreDpto$i' name='nombreDpto$i'>$nombreDpto</td>
					<td id='maquinaID$i' name='maquinaID$i'>$codigoInterno</td>
					<td id='fechaHoraini$i' name='fechaHoraini$i'>$fechaHoraini</td>
					<td id='descripcion$i' name='descripcion$i'>$descripcion</td>
					<td id='nomapeusu$i' name='nomapeusu$i'>$nomapeusu</td>
					<td>
						<div class='col-lg-4' id='ope$i' name='ope$i'>";
						
						$sql = "select personaID,nombre,apellido from vistapersonamant;";
						
						$ok1 = $conexion->ejecutarQuery($sql);

						$cadena = "<select class='selectpicker form-control $disabledbtnIOT' id='mecanico$i' name='mecanico$i' multiple title='Seleccione...' $disabledbtnIOT>
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
					<td><div style='display:none;'>$prioridad</div>
							<select  class='selectpicker show-tick form-control $disabledPrior' id='prioridad$i' name='prioridad$i' title='Seleccione...' $disabledPrior>
								<option value='1'>Emergencia</option>
								<option value='2'>Urgente</option>
								<option value='3'>Normal</option>
							</select>
					</td>";

					$respuesta['tabla'] .= "<td>"
					.$aux_btnserv." |
					<a id='btnOrdenTrabajo$i' name='btnOrdenTrabajo$i' class='btn btn-primary btn-sm $disabledbtnOT'  onclick='ordenTrabajo($i)' title='Modificar OT $ordentrabmantID' data-toggle='tooltip'><span class='glyphicon glyphicon-floppy-save' style='bottom: 0px;top: 2px;'></span> </a>
					</td>
					<td id='prioridadC$i' name='prioridadC$i' style='display:none;'>$prioridad</td>
					<td id='ema_usu$i' name='ema_usu$i'  style='display:none;'>$ema_usu</td>
					<td id='usuarioID$i' name='usuarioID$i'  style='display:none;'>$usuarioID</td>
					<td id='maquinariaequiposDetalleID$i' name='maquinariaequiposDetalleID$i' style='display:none;'>$maquinariaequiposDetalleID</td>
					<td id='departamentoAreaID$i' name='departamentoAreaID$i' style='display:none;'>$departamentoAreaID</td>
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
			<th align='center' class='btn-primary col-sm-1 col-md-1 col-lg-1 col-xl-1' style='display:none;'>MaquinaID
			</th>
			<th align='center' class='btn-primary col-sm-1 col-md-1 col-lg-1 col-xl-1'>Número
			</th>
			<th align='center' class='btn-primary col-sm-1 col-md-1 col-lg-1 col-xl-1 justify-content-md-center' style='text-align:center;'>ES
			</th>
			<th align='center' class='btn-primary col-sm-1 col-md-1 col-lg-1 col-xl-1' style='display:none;'>FS
			</th>
			<th align='center' class='btn-primary col-sm-10 col-md-10 col-lg-10 col-xl-10'>Observaciones
			</th>
			<th align='center' class='btn-primary col-sm-10 col-md-10 col-lg-10 col-xl-10'>Acción
			</th>
			<th align='center' class='btn-primary col-sm-1 col-md-1 col-lg-1 col-xl-1' style='display:none;'>solicitudTrabID
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
					$sql1 = "select * from maquinaequipofueraservicio where maquinariaequiposDetalleID='$maquinariaequiposDetalleID' and usuarioIDdelete=0 order by fechaHoraInsert;";
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

					$codigoInterno   = $datos["codigoInterno"];
					$estado          = "0"; //date("d-m-Y", strtotime($dep_fecha));
					$observaciones   = ""; //$datos["observMantenimiento"];
					$estatusTrab     = false;
					$aux_disabled    = "";
					$estatus         = "0";
					$aux_checkedES   = "";
					$aux_disableobs  = "disabled";
					$aux_stylecheck  = "";
					$solicitudTrabID = "";

					// Consulto tabla solicitudtrabmant para saber observaciones guardades de manteminiento
					$sql2 = "select solicitudtrabmant.solicitudTrabID,solicitudtrabmant.descripcion,
						ordentrabmant.ordentrabmantID,ordentrabmant.fechaini,
						usuario.usuarioID,CONCAT(usuario.nom_usu, ' ', usuario.ape_usu) as nomape_usu,usuario.ema_usu
						from solicitudtrabmant LEFT JOIN ordentrabmant
						on solicitudtrabmant.solicitudTrabID=ordentrabmant.solicitudTrabID
						inner join usuario
						on solicitudtrabmant.usuarioID=usuario.usuarioID
						where solicitudtrabmant.maquinariaequiposDetalleID='$maquinariaequiposDetalleID' 
						and solicitudtrabmant.usuarioIDdelete=0 and 
						solicitudtrabmant.fechafinTrab='0000-00-00 00:00:00' 
						order by solicitudtrabmant.solicitudTrabID ASC;";
					//echo $sql2;
					$ok2=$conexion->ejecutarQuery($sql2);
					$filas=mysql_num_rows($ok2);
					$tablaOrdTrab = "";
					if($filas>0)
					{
						$tablaOrdTrab = "
							<table id='tablaOrdTrab' name='tablaOrdTrab' class='table display AllDataTables responsive table-hover table-condensed order-column'>
							<thead>
								<tr>
									<th>ST</th>
									<th>Observaciones</th>
									<th>OT</th>
									<th>FecIni Mantención</th>
									<th>Acción</th>
									<th style='display:none;'>usuarioID</th>
									<th style='display:none;'>nomape_usu</th>
									<th style='display:none;'>ema_usu</th>
								</tr>
							</thead>
							<tbody>";
						while(($datos2=mysql_fetch_assoc($ok2))>0)
						{
							$solicitudTrabID  = $datos2["solicitudTrabID"];
							$solicitudTrabID1 = str_pad($solicitudTrabID, 3, "0", STR_PAD_LEFT);
							$observaciones    = $datos2["descripcion"];
							$ordentrabmantID  = str_pad($datos2["ordentrabmantID"], 3, "0", STR_PAD_LEFT);
							$fechaini         = $datos2["fechaini"];
							$estatusTrab      = true;
							$accion           = "";
							$usuarioID        = $datos2["usuarioID"];
							$nomape_usu       = $datos2["nomape_usu"];
							$ema_usu          = $datos2["ema_usu"];

							$iST = $i.$solicitudTrabID;
							if($ordentrabmantID == "000"){
								$ordentrabmantID = "";
								$accion          = "<a id='modificarObs$i' name='modificarObs$i' class='btn btn-primary btn-sm' onclick='modificarObs($iST)' title='Modificar Observación' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil' style='bottom: 0px;top: 2px;'></span></a> | <a id='elimninar$i' name='elimninar$i' class='btn btn-danger btn-sm' onclick='eliminarST($i$solicitudTrabID)' title='Eliminar' data-toggle='tooltip'><span class='glyphicon glyphicon-trash' style='bottom: 0px;top: 2px;'></span></a>";
							}
							$tablaOrdTrab .= "<tr id='fila$iST' name='fila$iST'>
									<td id='solicitudTrabID$iST' name='solicitudTrabID$iST'>$solicitudTrabID</td>
									<td id='observaciones$iST' name='observaciones$iST'>$observaciones</td>
									<td id='ordentrabmantID$iST' name='ordentrabmantID$iST'>$ordentrabmantID</td>
									<td id='fechaini$iST' name='fechaini$iST'>$fechaini</td>
									<td>$accion</td>
									<td id='usuarioID$iST' name='usuarioID$iST' style='display:none;'>$usuarioID</td>
									<td id='nomape_usu$iST' name='nomape_usu$iST' style='display:none;'>$nomape_usu</td>
									<td id='ema_usu$iST' name='ema_usu$iST' style='display:none;'>$ema_usu</td>
								</tr>";
							
							//$aux_checkedES   = "checked";
							//$aux_disableobs  = "";
							$estatus         = "2"; //En servicio pero con observacion de mantenimiento recien incluida en planta
						}
						$tablaOrdTrab .= "</tbody>
										  </table>";
					}
					$observaciones  = "";
					if($descripcionFS!="")
					{
						$observaciones  = $descripcionFS;
						$aux_disabled   = "disabled";
						$estatus        = "3"; //Fuera de servicio 
						$aux_stylecheck = "style='display:none;'";
					}
					$aux_texto='texto';
					$aux_col='col';
					$aux_botonMostrar = "";
					if($estatusTrab){
						$aux_botonMostrar = "<button type='button' name='botonM$i' id='botonM$i' class='btn btn-primary btn-sm' style='margin-top: -5px;' title='Mostrar ST previos y OT Iniciados.' onclick='mostrarH($i)' data-toggle='tooltip'><span name='botonD$i' id='botonD$i' class='glyphicon glyphicon-triangle-bottom'></span></button>";
					}
					 
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
								<label for='estado$i' type='text' class='form-group' style='padding-left: 20px;margin-top: 10px;'></label>
							</div>
						</td>
						<td valign='middle' style='display:none; padding-bottom: 4px;padding-top: 4px;'>
							<input type='text' style='text-align:left;' value='$estatus' name='estatus$i' id='estatus$i' class='form-control input-sm custom-checkbox' readonly>
						</td>
						<td style='padding-bottom: 4px;padding-top: 4px; padding-right: 2px;'>
							<input type='text' style='text-align:left;' value='$observaciones' name='observaciones$i' id='observaciones$i' class='form-control input-sm' title='$observaciones' $aux_disableobs>
							<div id='divTabOT$i'>
								$tablaOrdTrab
							</div>
						</td>
						<td> $aux_botonMostrar
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
				$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-sm-4 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()' data-placement='right' data-toggle='tooltip'>Guardar</button>";
			}else
			{
				$respuesta['mensaje'] = "Información no encontrada.";
			}
		}
		$respuesta['tablaOrdTrab'] = $tablaOrdTrab;
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
		    if($observacion!="" && ($estatus=="1" || $estatus=="2")){
		    	$sql = "insert into solicitudtrabmant (maquinariaequiposDetalleID, departamentoAreaID, fechaHoraini, descripcion, usuarioID, fechaHoraInsert) values('$maquinaID',$departamentoAreaID,now(),'$observacion','$usuarioID',now());";
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
		    /*
		    if($estatus=="1"){
		    	$sql = "insert into solicitudtrabmant (maquinariaequiposDetalleID, departamentoAreaID, fechaHoraini, descripcion, usuarioID, fechaHoraInsert) values('$maquinaID',$departamentoAreaID,now(),'$observacion','$usuarioID',now())"; 
		    }
		    if($estatus=="2"){
		    	if($observacion==""){
		    		$sql = "delete from solicitudtrabmant where solicitudTrabID='$solicitudTrabID';";
		    	}else{
		    		$sql = "update solicitudtrabmant set descripcion='$observacion' where solicitudTrabID='$solicitudTrabID';";	
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
		    }*/
		}
		if($cont_guardar==0){
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = "No existen registros a modificar.";
		}
		echo json_encode($respuesta);
	}

	function eliminarST($conexion,$solicitudTrabID,$usuarioID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$aux_usuarioID = $_SESSION["usuarioID"];
		$aux_tusuario  = $_SESSION["fk_tip_usu"];
		$aux_staEli = false;
		if($aux_tusuario == 3){ //Si es Super usuario puede eliminar sin importar si tiene derecho a eliminar o no
			$sql = "update solicitudtrabmant set fechadelete=now(),usuarioIDdelete=$aux_usuarioID where solicitudTrabID='$solicitudTrabID' and usuarioIDdelete=0;";
			$aux_staEli=true; 
		}else{
			if ($usuarioID == $aux_usuarioID){
	//			$sql = "delete from solicitudtrabmant where solicitudTrabID='$solicitudTrabID' and usuarioID='$aux_usuarioID';"; 
				$sql = "update solicitudtrabmant set fechadelete=now(),usuarioIDdelete=$aux_usuarioID where solicitudTrabID='$solicitudTrabID' and usuarioID='$aux_usuarioID' and usuarioIDdelete=0;"; 
				$aux_staEli=true;
			}
		}	
		if($aux_staEli){
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Registro se elimino con exito.";
			}else
			{
				$respuesta['exito'] = false;
				$respuesta['mensaje'] = "Falló la actualización.";
			}
			$cont_guardar = $cont_guardar + 1;
		}else{
			$respuesta['exito']   = false;
			$respuesta['mensaje'] = "No se guardo la informacion.<br>Registro generado por otro usuario.";			
		}
		echo json_encode($respuesta);
	}

	function update($conexion,$solicitudTrabID,$descripcion,$usuarioID){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "";
		$aux_usuarioID = $_SESSION["usuarioID"];
		if($usuarioID == $aux_usuarioID){
			$sql = "update solicitudtrabmant set descripcion='$descripcion' WHERE solicitudTrabID='$solicitudTrabID' and usuarioID='$aux_usuarioID' and usuarioIDdelete=0;";
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
			$respuesta['mensaje'] = "No se guardo la informacion.<br>Registro generado por otro usuario.";			
		}
		echo json_encode($respuesta);
	}
}
?>