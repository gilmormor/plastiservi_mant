<?php
class opciones
{
	function agregar_opciones($conexion,$nom_ope,$url,$fk_mod,$est_ope)
	{
		$sql="insert into operaciones(nom_ope,url,fk_mod,est_ope) values('$nom_ope','$url',$fk_mod,'$est_ope')"; //fk_mod va sin comillas porque es numerico
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		return $ok;
	}
	function modificar_opciones($conexion,$id_ope,$nom_ope,$url,$fk_mod,$est_ope)
	{
		$sql="update operaciones set nom_ope='$nom_ope',url='$url',fk_mod=$fk_mod,est_ope='$est_ope' where id_ope=$id_ope"; //fk_mod va sin comillas porque es numerico
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		return $ok;
	}

	function devolver_cod_opc($conexion,$url)
	{
		$sql="select id_ope from operaciones where trim(url)='$url'";
		$ok=$conexion->ejecutarQuery($sql);
		if (mysql_num_rows($ok)>0)
		{
			$datos=mysql_fetch_assoc($ok);
			//echo $datos["cod_opc"];
			return $datos["id_ope"];
			}
		return 0;
	}

	function devolver_desc_opc($conexion,$num_opc)
	{
		$sql="select nom_ope from operaciones where id_ope='$num_opc';";
		$ok=$conexion->ejecutarQuery($sql);
		if (mysql_num_rows($ok)>0)
		{
			$datos=mysql_fetch_assoc($ok);
			//echo $datos["cod_opc"];
			return $datos["nom_ope"];
			}
		return 0;
	}

	function consultarMenuOpciones($conexion,$ema_usu)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Código no Existe";
		$respuesta['tabla'] = "";
		$respuesta['numReg'] = 0;

		$respuesta['tabla'] .= '<table id="tablaOrdTrab" name="tablaOrdTrab" class="table display AllDataTables responsive table-hover table-condensed">
			<thead>
				<tr>
					<th style="display:none;">Orden</th>					
					<th>Módulo</th>
					<th>Operación</th>
					<th>Abrir</th>
					<th>Eli | Inset | Mod</th>
					<th style="display:none;">id_ope</th>
				</tr>
			</thead>
			<tbody>';
		$sql = "select concat(LPAD(modulo.orden,4,'0'),LPAD(operaciones.orden_ope,4,'0')) AS orden1,
				operaciones.id_ope,modulo.nom_mod,operaciones.nom_ope
				FROM modulo INNER JOIN operaciones
				ON modulo.id_mod=operaciones.fk_mod
				WHERE modulo.est_mod='A' AND operaciones.est_ope='A'
				ORDER BY modulo.orden,operaciones.orden_ope;";
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
				$orden1   = $datos["orden1"];
				$id_ope   = $datos["id_ope"];
				$nom_mod  = $datos["nom_mod"];
				$nom_ope  = $datos["nom_ope"];
				$open     = '0';
				$checkOpe = '';
				$checkDel = '';
				$checkIns = '';
				$checkUpd = '';

				$sql1 = "select fk_ope,fk_usu,staDelete,staInsert,staUpdate
						from seguridad
						where fk_usu='$ema_usu' and fk_ope='$id_ope';";
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
							$staExiste = 1;
							$open      = '1';
							$staDelete = $datos1["staDelete"];
							$staInsert = $datos1["staInsert"];
							$staUpdate = $datos1["staUpdate"];

							$checkOpe  = 'checked';
							$checkDel  = '';
							$checkIns  = '';
							$checkUpd  = '';
							if($staDelete==1)
								$checkDel = 'checked';
							if($staInsert==1)
								$checkIns = 'checked';
							if($staUpdate==1)
								$checkUpd = 'checked';
							$i1 += 1;
						}
					}
				}

				$prioridad = $auxPrioridad;
				$maquinariaequiposDetalleID = $datos["maquinariaequiposDetalleID"];
				$estado         = "0"; //date("d-m-Y", strtotime($dep_fecha));
				$respuesta['tabla'] .= "<tr id='fila$i' name='fila$i' $colorFila>
					<td id='orden1$i' name='orden1$i' style='display:none;'>$orden1</td>
					<td id='nom_mod$i' name='nom_mod$i'>$nom_mod</td>
					<td id='nom_ope$i' name='nom_ope$i'>$nom_ope</td>
					<td>
						<div class='checkbox'>
							<label style='font-size: 1.2em' data-toggle='tooltip' title='Permiso Abrir'>
								<input type='checkbox' id='open$i' name='open$i' value='$open' $checkOpe>
								<span class='cr'><i class='cr-icon fa fa-check'></i></span>
							</label>
						</div>
					</td>
					<td>
						<div class='checkbox'>
							<label style='font-size: 1.2em' data-toggle='tooltip' title='Permiso Eliminar'>
								<input type='checkbox' id='staDelete$i' name='staDelete$i' value='$staDelete' $checkDel>
								<span class='cr'><i class='cr-icon fa fa-check'></i></span>
							</label>
						</div>
						<div class='checkbox'>
							<label style='font-size: 1.2em' data-toggle='tooltip' title='Permiso Insertar'>
								<input type='checkbox' id='staInsert$i' name='staInsert$i' value='$staInsert' $checkIns>
								<span class='cr'><i class='cr-icon fa fa-check'></i></span>
							</label>
						</div>
						<div class='checkbox'>
							<label style='font-size: 1.2em' data-toggle='tooltip' title='Permiso Actualizar'>
								<input type='checkbox' id='staUpdate$i' name='staUpdate$i' value='$staUpdate' $checkUpd>
								<span class='cr'><i class='cr-icon fa fa-check'></i></span>
							</label>
						</div>
					</td>
					<td id='id_ope$i' name='id_ope$i' style='display:none;'>$id_ope</td>
				</tr>";
			}
			$respuesta['tabla'] .= "</tbody>
			</table>";
			$respuesta['numReg'] = $i;
			$respuesta['botonGuardar'] = "<button type='button' class='btn btn-primary col-xs-2 col-md-2 col-xs-offset-5 col-md-offset-5' id='btnguardar' name='btnguardar' title='Guardar' onclick='guardarMant()'>Guardar</button>";
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}


	function guardarPermisos($conexion,$fk_usu,$filas){
		//echo $filas;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Información no fue guardada.";
		$inserciones = 0;
		$cont_guardar = 0;
		//echo $filas;
		foreach ($filas as $fila) {
		    $fk_ope    = $fila['fk_ope'];
		    $open      = $fila['open'];
		    $staDelete = $fila['staDelete'];
		    $staInsert = $fila['staInsert'];
		    $staUpdate = $fila['staUpdate'];
		    $sql       = "";
		    if($open){
		    	$sql = "select * FROM seguridad where fk_usu='$fk_usu' and fk_ope='$fk_ope';";
		    	//echo $sql;
				$ok=$conexion->ejecutarQuery($sql);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$sql = "update seguridad set staDelete='$staDelete',staInsert='$staInsert',staUpdate='$staUpdate' where fk_usu='$fk_usu' and fk_ope='$fk_ope';";
					$ok=$conexion->ejecutarQuery($sql);
					if($ok){
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = "Información se guardo con exito.";
					}
				}else{
					$sql = "insert into seguridad(fk_usu,fk_ope,staDelete,staInsert,staUpdate) value('$fk_usu','$fk_ope','$staDelete','$staInsert','$staUpdate');";
					$ok=$conexion->ejecutarQuery($sql);
					if($ok){
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = "Información se guardo con exito.";
					}
				}
		    }else{
				$sql = "delete from seguridad where fk_usu='$fk_usu' and fk_ope='$fk_ope';";
				$ok=$conexion->ejecutarQuery($sql);
				if($ok){
					$respuesta['exito'] = true;
					$respuesta['mensaje'] = "Información se guardo con exito.";
				}
		    }
		}
		echo json_encode($respuesta);
	}


}

?>