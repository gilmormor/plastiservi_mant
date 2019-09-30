<?php
//require_once "Conexion.class.php";
class estudiante
{
	function consultar($conexion,$est_ced)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Cédula no Existe";
		$sql = "select * from eva_estudiante where est_ced='$est_ced';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Cedula encontrada.";
				$datos=mysql_fetch_assoc($ok); //Crea Vector asociativo con el mismo nombre de los datos de la Tabla
				$respuesta['tablaest'] = $datos;
				$respuesta['nombre'] = $datos['est_nombres'];
				$respuesta['apellido'] = $datos['est_apellidos'];
				$respuesta['lugar_nace'] = $datos['est_lugnac'];
				$respuesta['pla_fecha'] = $datos['est_fecnac'];	
				$respuesta['sex_id'] = $datos['sex_des'];
			}
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}


	
	function hacer_lista_desplegable($conexion,$tabla,$value,$mostrar,$nombre,$sql,$funcion)
	{
		if(empty($sql))
		{
			$sql = "select * from $tabla";
		}
		$ok = $conexion->ejecutarQuery($sql);
		//$ok = mysql_query($sql,$conexion);

		echo "<select id='$nombre' name='$nombre' onChange='$funcion' class='form-control'>";
		echo "<option value=''>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
		  echo "<option value='$datos[$value]'> $datos[$mostrar] </option>";	
	    }
		echo "</select>";
		
	}

	function consultarTodo($conexion,$est_ced,$fil_codlapso)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['exitoplantel'] = false;
		$respuesta['exitorepest'] = false;
		$respuesta['exitomadre'] = false;
		$respuesta['exitopadre'] = false;
		$respuesta['exitorep'] = false;
		$respuesta['exitooferta'] = false;
		$respuesta['exitoclialum'] = false;
		$respuesta['exitocliente'] = false;
		$respuesta['exitoinscrip'] = false; //si el estudiante esta inscrito es true
		$respuesta['statusbajado'] = false; //Status para saber si la inscripcion ya fue bajada
		$respuesta['inscritoherm'] = false; //Status para saber si la inscripcion ya fue bajada

		$respuesta['mensaje'] = "Cédula no Existe";
		$respuesta['mensajeplantel'] = "Plantel no existe.";
		$respuesta['mensajerepest'] = "Representante no existe.";
		$respuesta['mensajemadre'] = "Madre no existe.";
		$respuesta['mensajepadre'] = "Datos de padre no encontrados.";
		$respuesta['mensajerep'] = "Datos de representante no encontrados.";
		$respuesta['mensajeoferta'] = 'Oferta no Encontrada';
		$respuesta['mensajeclialum'] = "Estudiante no tiene a quien facturar asociado.";
		$respuesta['mensajecliente'] = 'Cliente no existe.';
		$respuesta["oferta_academica"] = "";
		$respuesta["titulomaterias"] = "MATERIAS INSCRITAS - ".$fil_codlapso;

		//buscar Estudiante
		$sql = "select * from eva_estudiante where est_ced='$est_ced';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Cedula encontrada.";
				$datos=mysql_fetch_assoc($ok); //Crea Vector asociativo con el mismo nombre de los datos de la Tabla
				$respuesta['datosest'] = $datos;
				$est_placoddea = $datos['est_placoddea'];

				//consultar plantel de procedencia
				$sql = "select * from eva_planteles where plan_est_codigo='$est_placoddea';";
				$ok=$conexion->ejecutarQuery($sql);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$datos=mysql_fetch_assoc($ok);
					$respuesta['exitoplantel'] = true;
					$respuesta['datosplantel'] = $datos;
				}else
				{
					$respuesta['mensajeplantel'] = "Plantel no existe.";
				}
				//Busca si ya esta por lo menos un hermano Inscrito, esto para no blanquear los datos 
				//de representante
				$sql = "select eva_inscripciones.*
				from eva_repest as eva_repestorgi
				inner join eva_repest as eva_repestcopy
				on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
				inner join eva_inscripciones
				on eva_repestcopy.rep_cedalum = eva_inscripciones.insc_codusu
				where eva_repestorgi.rep_cedalum='$est_ced';";
				//echo $sql;
				$ok=$conexion->ejecutarQuery($sql);
				if($ok)
				{
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['inscritoherm'] = true; //Verdadero si por lo menos un hermano esta inscrito
					}
				}
				//buscar en tabla de eva_resest
				$sql = "select * from eva_repest where rep_cedalum='$est_ced';";
				$ok=$conexion->ejecutarQuery($sql);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$respuesta['exitorepest'] = true;
					$respuesta['mensajerepest'] = 'Representante de estudiante encontrado.';
					$datos=mysql_fetch_assoc($ok);
					$respuesta['datosrepest'] = $datos;
					$rep_cedmad = $datos['rep_cedmad'];
					$rep_cedpad = $datos['rep_cedpad'];
					$rep_cedrep = $datos['rep_cedrep'];

					//Buscar Madre
					$sql = "select * from eva_representantes where rep_ced='$rep_cedmad';";
					$ok=$conexion->ejecutarQuery($sql);
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitomadre'] = true;
						$respuesta['mensajemadre'] = 'Datos de madre encontrado.';
						$datos=mysql_fetch_assoc($ok);
						$respuesta['datosmadre'] = $datos;
					}else
					{
						$respuesta['mensajemadre'] = "Cédula no existe, Debe agregar los datos.";
					}
					//Buscar Padre
					$sql = "select * from eva_representantes where rep_ced='$rep_cedpad';";
					$ok=$conexion->ejecutarQuery($sql);
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitopadre'] = true;
						$respuesta['mensajepadre'] = 'Datos de padre encontrado.';
						$datos=mysql_fetch_assoc($ok);
						$respuesta['datospadre'] = $datos;
					}else
					{
						$respuesta['mensajepadre'] = "Cédula no existe, Debe agregar los datos.";
					}
					//Buscar Representante
					$sql = "select * from eva_representantes where rep_ced='$rep_cedrep';";
					$ok=$conexion->ejecutarQuery($sql);
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitorep'] = true;
						$respuesta['mensajerep'] = 'Datos de representante encontrado.';
						$datos=mysql_fetch_assoc($ok);
						$respuesta['datosrep'] = $datos;
					}else
					{
						$respuesta['mensajerep'] = "Cédula no existe, Debe agregar los datos.";
					}
				}else
				{
					$respuesta['mensajerepest'] = "Cédula no existe, Debe agregar los datos.";
				}

////////////////////
				//buscar en tabla de eva_clialum
				$sql = "select * from eva_clialum where ced_alum='$est_ced';";
				$ok=$conexion->ejecutarQuery($sql);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$respuesta['exitocliest'] = true;
					$respuesta['mensajecliest'] = 'Cliente de estudiante encontrado.';
					$datos=mysql_fetch_assoc($ok);
					$cli_cedrif = $datos['cli_cedrif'];

					//Buscar eva_clientes
					$sql = "select * from eva_clientes where cli_cedrif='$cli_cedrif';";
					$ok=$conexion->ejecutarQuery($sql);
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitocliente'] = true;
						$respuesta['mensajecliente'] = 'Datos de cliente encontrado.';
						$datos=mysql_fetch_assoc($ok);
						$respuesta['datoscliente'] = $datos;
					}else
					{
						$respuesta['mensajecliente'] = "Datos de cliente no encontrados.";
					}
				}else
				{
					$respuesta['mensajecliest'] = "Estudiante no tiene Cliente.";
				}

				//Buscar Oferta Academica y crear tabla
				$respuesta['tablaoferta'] = "
					<tr>
						<td align='center' class='bg-primary'>Materia
						</td>
						<td align='center' class='bg-primary'>Año
						</td>
						<td align='center' class='bg-primary'>Secc.
						</td>
						<td align='center' class='bg-primary'>Condición
						</td>
					</tr>";
				$sql = "select cod_mat as ofac_codcar,cod_mat,mat_descripcion, desc_cond as condinsc,substr(cod_sec,2,1) as anno,
					right(cod_sec,1) as seccion,cond_materia as ofac_condalum,insc_tipo as ofac_condinsc,
					cod_sec as ofac_seccion,substr(cod_sec,3,1) as ofac_turno,substr(cod_sec,2,1) as ofac_anocursa,
					insc_status
					from eva_inscripciones inner join eva_matinscritas
					on insc_codusu=ced_alum and insc_codlapso=periescolar
					inner join eva_materias
					on eva_matinscritas.cod_mat=eva_materias.mat_cod
					inner join eva_codingreso
					on eva_matinscritas.cond_materia=eva_codingreso.cod_cond
					where insc_codusu='$est_ced' and insc_codlapso='$fil_codlapso'
					order by cod_mat";
				//echo $sql;
				$ok=$conexion->ejecutarQuery($sql);
				if($ok)
				{
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitoinscrip'] = true;
						$datosinsc=mysql_fetch_assoc($ok);
						$respuesta['datosinsc'] = $datosinsc;
						//echo 'entro';
					}else
					{
						//echo 'entro';
						$sql = "select *,
							(select desc_cond from eva_codingreso where eva_codingreso.cod_cond=eva_ofertacad.ofac_condinsc) as condinsc,
							substr(ofac_seccion,2,1) as anno, right(ofac_seccion,1) as seccion,'0' as insc_status
							from eva_ofertacad inner join eva_materias
							on eva_ofertacad.ofac_codmat = eva_materias.mat_cod
							inner join eva_codingreso
							on eva_ofertacad.ofac_condalum = eva_codingreso.cod_cond
							where ofac_cedula='$est_ced' order by ofac_codmat;";
						$ok=$conexion->ejecutarQuery($sql);
						$respuesta["titulomaterias"] = "OFERTA ACADEMICA - ".$fil_codlapso;
						$respuesta['exitoinscrip'] = false;
					}
				}
				if($ok)
				{
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitooferta'] = true;
						$respuesta['mensajeoferta'] = 'Oferta Encontrada';
						$i = 0;
						//echo $sql;
						mysql_data_seek($ok,0);
						while(($datos=mysql_fetch_assoc($ok))>0)
						{
							//echo 'entro';
							$i += 1;
							$mat_descripcion    = $datos["mat_descripcion"];
							$anno               = $datos["anno"];
							$seccion            = $datos["seccion"];
							$ofac_condalum      = $datos["ofac_condalum"];
							$respuesta['insc_status'] = $datos["insc_status"];
							$respuesta["oferta_academica"] .= '{"ofac_codcar":"' . $datos["ofac_codcar"] . '",';
							$respuesta["oferta_academica"] .= '"ofac_codmat":"' . $datos["ofac_codmat"] . '",';
							$respuesta["oferta_academica"] .= '"ofac_condalum":"' . $datos["ofac_condalum"] . '",';
							$respuesta["oferta_academica"] .= '"ofac_condinsc":"' . $datos["ofac_condinsc"] . '",';
							$respuesta["oferta_academica"] .= '"ofac_seccion":"' . $datos["ofac_seccion"] . '",';
							$respuesta["oferta_academica"] .= '"ofac_turno":"' . $datos["ofac_turno"] . '",';
							$respuesta["oferta_academica"] .= '"ofac_anocursa":"' . $datos["ofac_anocursa"] . '"},';
/*
							$respuesta['tablaoferta'] .= "
							<tr>
								<td><input type='text' value='$mat_descripcion' name='mat_descripcion$i' id='mat_descripcion$i' class='form-control input-sm' readonly>
								</td>
								<td><input type='text' style='text-align:center;' value='$anno' name='anno$i' id='anno$i' class='form-control input-sm' readonly>
								</td>
								<td><input type='text' style='text-align:center;' value='$seccion' name='seccion$i' id='seccion$i' class='form-control input-sm' readonly>
								</td>
								<td><input type='text' style='text-align:center;' value='$ofac_condalum' name='ofac_condalum$i' id='ofac_condalum$i' class='form-control input-sm' readonly>
								</td>
							</tr>";	
*/
							$respuesta['tablaoferta'] .= "
							<tr>
								<td>$mat_descripcion
								</td>
								<td align='center'>$anno
								</td>
								<td align='center'>$seccion
								</td>
								<td align='center'>$ofac_condalum
								</td>
							</tr>";	
						}				
						$respuesta["oferta_academica"] .= '{"fin":"fin"}';
					}else
					{
						$respuesta['mensajeoferta'] = "Estudiante no tiene oferta Académica.";
					}

				}else
				{
					$respuesta['mensajeoferta'] = "Falló la consulta de la Oferta.";
				}
				//Colsultar tabla eva_clialum
				$sql = "select * from eva_clialum where ced_alum='$est_ced';";
				$ok=$conexion->ejecutarQuery($sql);
				if($ok)
				{
					$filas=mysql_num_rows($ok);
					if($filas>0)
					{
						$respuesta['exitoclialum'] = true;
						$respuesta['mensajeclialum'] = "Cédula/RIF encontrada.";
						$datos=mysql_fetch_assoc($ok); //Crea Vector asociativo con el mismo nombre de los datos de la Tabla
						$cli_cedrif = $datos["cli_cedrif"];

						$sql = "select * from eva_clientes where cli_cedrif='$cli_cedrif';";
						$ok=$conexion->ejecutarQuery($sql);
						if($ok)
						{
							$filas=mysql_num_rows($ok);
							if($filas>0)
							{
								$respuesta['exitocli'] = true;
								$respuesta['mensajecli'] = "Cédula/RIF encontrada.";
								$datos=mysql_fetch_assoc($ok); //Crea Vector asociativo con el mismo nombre de los datos de la Tabla
								$respuesta['datoscli'] = $datos;
							}
						}else
						{
							$respuesta['mensajecli'] = "Fallo la consulta eva_clialum";
						}
					}
				}else
				{
					$respuesta['mensajeclialum'] = "Fallo la consulta eva_clialum";
				}



			}else
			{
				$respuesta['mensajeest'] = "Cédula no existe.";
			}
		}else
		{
			$respuesta['mensaje'] = "Fallo la consulta";
		}
		echo json_encode($respuesta);
	}



	function insertUpdateTodo($conexion,$dato,$lapsoinscp,$tipousuario,$cod_secc,$bajarinsc,$aux_elimatinsc)
	{
		//echo var_dump($dato); //esto es para mostrar el contenido de un vector
		//echo $dato->est_email;
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['exitorepest'] = false;
		$respuesta['exitomadre'] = false;
		$respuesta['exitopadre'] = false;
		$respuesta['exitorep'] = false;
		$respuesta['exitooferta'] = false;
		$respuesta['exitoclialum'] = false;
		$respuesta['exitoclientes'] = false;
		$respuesta['exitoinscrip'] = false;
		$respuesta['exitoplanteles'] = false;

		$respuesta['mensaje'] = "Cédula no Existe";
		$respuesta['mensajerepest'] = "Error al guardar en evarepest.";
		$respuesta['mensajemadre'] = "Error al guardar en eva_representante.";
		$respuesta['mensajepadre'] = "Error al guardar en eva_representante.";
		$respuesta['mensajerep'] = "Error al guardar en eva_representante.";
		$respuesta['mensajeoferta'] = 'Error al guardar en inscripcion y materia_año.';
		$respuesta['mensajeclialum'] = "Error al guardar en eva_clialum.";
		$respuesta['mensajeclientes'] = "Error al guardar en eva_clientes.";
		$respuesta['mensajeinscrip'] = "Fallo la consulta de eva_inscripciones.";
		$respuesta['mensajematins'] = "Falló la Inserción de eva_matinscritas.";		
		$respuesta['mensajeplanteles'] = "Falló la Inserción de eva_planteles.";		

		if($dato->est_ced > 0)
		{
			//buscar Estudiante
			$sql = "select * from eva_estudiante where est_ced='$dato->est_ced';";
			//echo $sql;
			$fechanac=substr($dato->est_fecnac,6,4)."/".substr($dato->est_fecnac,3,2)."/".substr($dato->est_fecnac,0,2);
			$est_feing = fechaActual();
			$ok=$conexion->ejecutarQuery($sql);
			if($ok)
			{
				$filas=mysql_num_rows($ok);
				$fechaactual = date('Y-m-d');
				if($filas>0)
				{
					$obEst=$conexion->objeto($sql);
					/*
					echo "Array: " . $dato->est_nombres;
					echo "Tabla: " . $obEst->est_nombres;
					echo "Array: " . $dato->est_apellidos;
					echo "Tabla: " . $obEst->est_apellidos;
					*/
					//if(($dato->est_nombres == $obEst->est_nombres) && ($dato->est_apellidos == $obEst->est_apellidos))
					if(($dato->est_exp == $obEst->est_exp))
					{
						$sql = "update eva_estudiante set est_nacionalidad='$dato->est_nacionalidad',est_nombres='$dato->est_nombres',est_apellidos='$dato->est_apellidos',est_fecnac='$fechanac',est_lugnac='$dato->est_lugnac',est_codpais='$dato->est_codpais',est_estnac='$dato->est_estnac',est_edocivil='$dato->est_edocivil',est_sexo='$dato->est_sexo',est_tipoparto='$dato->est_tipoparto',est_placod='$dato->est_placod',est_placoddea='$dato->est_placoddea',est_email='$dato->est_email',est_paren='$dato->est_paren',est_callemer='$dato->est_callemer',est_telfemer='$dato->est_telfemer',est_familia='$dato->est_familia',est_grafam='$dato->est_grafam',est_vivecon='$dato->est_vivecon',est_medtras='$dato->est_medtras',rep_vivecondes='$dato->rep_vivecondes' where  est_ced='$dato->est_ced';";
						//echo $sql;
						$ok=$conexion->guardar($sql);
						if($ok)
						{
							$respuesta['exito'] = true;
							$respuesta['mensaje'] = "Datos de estudiante se actualizaron con exito.";
						}else
						{
							$respuesta['exito'] = false;
							$respuesta['mensaje'] = "Fallo la actualizaron de estudiante.";					
						}
						
					}else
					{
						$respuesta['exito'] = false;
						$respuesta['mensaje'] = "Cédula no coincide con el Nombre.";
					}
				}else
				{
					$sql = "insert into eva_estudiante (est_ced,est_nacionalidad,est_nombres,est_apellidos,est_fecnac,est_lugnac,est_codpais,est_estnac,est_feing,est_edocivil,est_sexo,est_tipoparto,est_placod,est_placoddea,est_email,est_paren,est_callemer,est_telfemer,est_familia,est_grafam,est_vivecon,est_medtras,rep_vivecondes) values ('$dato->est_ced','$dato->est_nacionalidad','$dato->est_nombres','$dato->est_apellidos','$fechanac','$dato->est_lugnac','$dato->est_codpais','$dato->est_estnac','$fechaactual','$dato->est_edocivil','$dato->est_sexo','$dato->est_tipoparto','$dato->est_placod','$dato->est_placoddea','$dato->est_email','$dato->est_paren','$dato->est_callemer','$dato->est_telfemer','$dato->est_familia','$dato->est_grafam','$dato->est_vivecon','$dato->est_medtras','$dato->rep_vivecondes');";
					//echo $sql;
					$ok=$conexion->guardar($sql);
					if($ok)
					{
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = "Datos de estudiante se insertaron con exito.";
					}else
					{
						$respuesta['exito'] = false;
						$respuesta['mensaje'] = "Fallo la Inserción de estudiante.";					
					}
				}
				if($respuesta['exito']) //si fallo update o insert en estudiante no debe seguir el programa
				{
					//Colsultar si existe una entrada en repest
					$sql = "select * from eva_repest where rep_cedalum='$dato->est_ced';";
					//echo $sql;
					$ok=$conexion->ejecutarQuery($sql);
					if($ok)
					{
						$filas=mysql_num_rows($ok);
						if($filas>0)
						{
							$sql = "update eva_repest set rep_cedrep='$dato->rep_ced',rep_cedpad='$dato->rep_cedpad',rep_cedmad='$dato->rep_cedmad' where rep_cedalum='$dato->est_ced';";
							//echo $sql;
							$ok=$conexion->guardar($sql);
							if($ok)
							{
								$respuesta['exitorepest'] = true;
								$respuesta['mensajerepest'] = "Datos de repest se actualizaron con exito.";
							}else
							{
								$respuesta['exitorepest'] = false;
								$respuesta['mensajerepest'] = "Falló la actualizaron de repest.";						
							}
						}else
						{
							$sql = "insert into eva_repest (rep_cedalum,rep_cedrep,rep_cedpad,rep_cedmad) values ('$dato->est_ced','$dato->rep_ced','$dato->rep_cedpad','$dato->rep_cedmad');";
							//echo $sql;
							$ok=$conexion->guardar($sql);
							if($ok)
							{
								$respuesta['exitorepest'] = true;
								$respuesta['mensajerepest'] = "Datos de repest se insertaron con exito.";
							}else
							{
								$respuesta['exitorepest'] = false;
								$respuesta['mensajerepest'] = "Falló la Inserción de repest.";						
							}
						}
						//Datos Madre
						$aux_madre = guardarrep($conexion,$dato->rep_cedmad,$dato->rep_nacmad,$dato->rep_nomrepmad,$dato->rep_dirhabmad,$dato->rep_telcelmad,$dato->rep_telhabrepmad,$dato->rep_lugtrarepmad,$dato->rep_dirtrarepmad,$dato->rep_teltrarepmad,$dato->rep_profrepmad,$dato->rep_emailmad);
						if($aux_madre)
						{
							$respuesta['exitomadre'] = true;
							$respuesta['mensajemadre'] = "Datos de la Madre Guardados.";
						}
						//Datos Padre
						$aux_padre = guardarrep($conexion,$dato->rep_cedpad,$dato->rep_nacpad,$dato->rep_nomreppad,$dato->rep_dirhabpad,$dato->rep_telcelpad,$dato->rep_telhabreppad,$dato->rep_lugtrareppad,$dato->rep_dirtrareppad,$dato->rep_teltrareppad,$dato->rep_profreppad,$dato->rep_emailpad);
						if($aux_padre)
						{
							$respuesta['exitopadre'] = true;
							$respuesta['mensajepadre'] = "Datos del Padre Guardados.";
						}
						//Datos Representante
						$aux_rep = guardarrep($conexion,$dato->rep_ced,$dato->rep_nac,$dato->rep_nomrep,$dato->rep_dirhabrep,$dato->rep_telcel,$dato->rep_telhabrep,$dato->rep_lugtrarep,$dato->rep_dirtrarep,$dato->rep_teltrarep,$dato->rep_profrep,$dato->rep_email);
						if($aux_rep)
						{
							$respuesta['exitorep'] = true;
							$respuesta['mensajerep'] = "Datos de la Representantes Guardados.";
						}

					}else
					{
						$respuesta['mensaje'] = "Fallo la consulta";
					}

					//Colsultar si existe una entrada en eva_clialum
					$sql = "select * from eva_clialum where ced_alum='$dato->est_ced';";
					//echo $sql;
					$ok=$conexion->ejecutarQuery($sql);
					if($ok)
					{
						$filas=mysql_num_rows($ok);
						if($filas>0)
						{
							$sql = "update eva_clialum set cli_cedrif=upper('$dato->cli_cedrif') where ced_alum='$dato->est_ced';";
							//echo $sql;
							$ok=$conexion->guardar($sql);
							if($ok)
							{
								$respuesta['exitoclialum'] = true;
								$respuesta['mensajeclialum'] = "Datos de eva_clialum se actualizaron con exito.";
							}else
							{
								$respuesta['exitoclialum'] = false;
								$respuesta['mensajeclialum'] = "Falló la actualizaron de eva_clialum.";						
							}
						}else
						{
							$sql = "insert into eva_clialum (ced_alum,cli_cedrif) values ('$dato->est_ced',upper('$dato->cli_cedrif'));";
							//echo $sql;
							$ok=$conexion->guardar($sql);
							if($ok)
							{
								$respuesta['exitoclialum'] = true;
								$respuesta['mensajeclialum'] = "Datos de eva_clialum se insertaron con exito.";
							}else
							{
								$respuesta['exitoclialum'] = false;
								$respuesta['mensajeclialum'] = "Falló la Inserción de eva_clialum.";						
							}
						}

						//Actualizar datos Clientes
						if(!empty($dato->cli_cedrif))
						{
							$sql = "select * from eva_clientes where cli_cedrif=upper('$dato->cli_cedrif');";
							//echo $sql;
							$ok=$conexion->ejecutarQuery($sql);
							if($ok)
							{
								$filas=mysql_num_rows($ok);
								if($filas>0)
								{
									$sql = "update eva_clientes set cli_apenom=upper('$dato->cli_apenom'),cli_direc=upper('$dato->cli_direc'),cli_telf='$dato->cli_telf' where cli_cedrif=upper('$dato->cli_cedrif');";
									//echo $sql;
									$ok=$conexion->guardar($sql);
									if($ok)
									{
										$respuesta['exitoclientes'] = true;
										$respuesta['mensajeclientes'] = "Datos de eva_clientes se actualizaron con exito.";
									}else
									{
										$respuesta['exitoclientes'] = false;
										$respuesta['mensajeclientes'] = "Falló la actualuzación en eva_clientes.";
									}
								}else
								{
									$sql = "insert into eva_clientes (cli_cedrif,cli_apenom,cli_direc,cli_telf) values (upper('$dato->cli_cedrif'),upper('$dato->cli_apenom'),upper('$dato->cli_direc'),'$dato->cli_telf');";
									//echo $sql;
									$ok=$conexion->guardar($sql);
									if($ok)
									{
										$respuesta['exitoclientes'] = true;
										$respuesta['mensajeclientes'] = "Datos de eva_clientes se insertaron con exito.";
									}else
									{
										$respuesta['exitoclientes'] = false;
										$respuesta['mensajeclientes'] = "Falló la Inserción de eva_clientes.";
									}
								}
							}else
							{
								$respuesta['mensaje'] = "Fallo la consulta eva_clientes";
							}
						}
					}else
					{
						$respuesta['mensaje'] = "Fallo la consulta eva_clialum";
					}
					//Si el estudiante no tiene oferta es nuevo ingreso
					$sql = "select *,
						(select desc_cond from eva_codingreso where eva_codingreso.cod_cond=eva_ofertacad.ofac_condinsc) as condinsc,
						substr(ofac_seccion,2,1) as anno, right(ofac_seccion,1) as seccion,ofac_seccion
						from eva_ofertacad inner join eva_materias
						on eva_ofertacad.ofac_codmat = eva_materias.mat_cod
						inner join eva_codingreso
						on eva_ofertacad.ofac_condalum = eva_codingreso.cod_cond
						where ofac_cedula='$dato->est_ced';";
					//echo $sql;
					$ok=$conexion->ejecutarQuery($sql); 
					$insc_stanueing = 0;
					if($ok)
					{
						$filas=mysql_num_rows($ok);
						if($filas>0 && $aux_elimatinsc == 0) //Si tiene oferta y no se va a modificar la inscripcion  $aux_elimatinsc == 0,  $aux_elimatinsc == 1 Inscripcion si se va a modificar
						{
							//Si el usuario es tipo 4 
							$sql = "select *,
								(select desc_cond from eva_codingreso where eva_codingreso.cod_cond=eva_ofertacad.ofac_condinsc) as condinsc,
								substr(ofac_seccion,2,1) as anno, right(ofac_seccion,1) as seccion,ofac_seccion
								from eva_ofertacad inner join eva_materias
								on eva_ofertacad.ofac_codmat = eva_materias.mat_cod
								inner join eva_codingreso
								on eva_ofertacad.ofac_condalum = eva_codingreso.cod_cond
								where ofac_cedula='$dato->est_ced';";
							//echo $sql;
							$ok_oferta=$conexion->ejecutarQuery($sql);
							//consulta de materia regulares y repitientes. PAra que me traiga bien los datos de inscripcion para
							//insertar en eva_inscripciones
							//Si el estudiante es regular y ya esta inscrito
							$sql = "select * from eva_inscripciones where insc_codusu='$dato->est_ced' and insc_codlapso='$lapsoinscp';";
							//echo $sql;
							$ok=$conexion->ejecutarQuery($sql);
							if($ok)
							{
								$filas=mysql_num_rows($ok);
								if($filas>0)
								{
									$sqlregular = "select insc_semestre as ofac_anocursa,
										insc_turno as ofac_turno,
										insc_tipo as ofac_condinsc,insc_codcarr as ofac_codcar,'' as masec_codsec,
										'' as ofac_codmat,
										insc_tipo as ofac_condalum
										from eva_inscripciones where insc_codusu='$dato->est_ced' and 
										insc_codlapso='$lapsoinscp';";
									//echo $sql;
								}else
								{
									$sqlregular = "select *,
										(select desc_cond from eva_codingreso where eva_codingreso.cod_cond=eva_ofertacad.ofac_condinsc) as condinsc,
										substr(ofac_seccion,2,1) as anno, right(ofac_seccion,1) as seccion,
										concat(trim(estu_nombre),' ',trim(estu_mencion)) as plan_estudio,
										substr(ofac_seccion,1,1) as letra1ra_seccion,ofac_seccion
										from eva_ofertacad inner join eva_materias
										on eva_ofertacad.ofac_codmat = eva_materias.mat_cod
										inner join eva_codingreso
										on eva_ofertacad.ofac_condalum = eva_codingreso.cod_cond
										inner join eva_planestudio
										on eva_ofertacad.ofac_planest = eva_planestudio.fid
										where ofac_cedula='$dato->est_ced' and (ofac_condalum='RG' or ofac_condalum='RP');";
									//echo $sqlregular;
								}
							}
						}else
						{
							$insc_stanueing = 1; //No tiene oferta o sea el estudiante es nuevo ingreso
							$sql = "select substr(masec_codsec,2,1) as ofac_anocursa,substr(masec_codsec,3,1) as ofac_turno,
								'RG' as ofac_condinsc,estu_cod as ofac_codcar,masec_codsec as ofac_seccion,masec_codmat as ofac_codmat,
								'RG' as ofac_condalum
								from eva_materseccion inner join eva_planestudio
								on substr(masec_codsec,1,1)=estu_letra
								where masec_codsec='$cod_secc' and masec_lapso='$lapsoinscp';";
							//echo $sql;
							$ok_oferta=$conexion->ejecutarQuery($sql);
							//Si el estudiante es nuevo ingreso y ya esta inscrito
							$sql = "select * from eva_inscripciones where insc_codusu='$dato->est_ced' and insc_codlapso='$lapsoinscp';";
							//echo $sql;
							$ok=$conexion->ejecutarQuery($sql);
							if($ok)
							{
								$filas=mysql_num_rows($ok);
								//Si el estudiante esta inscrito y no quiero eliminar inscripcion
								if($filas>0 && $aux_elimatinsc == 0)
								{
									$datosInscrito=mysql_fetch_assoc($ok);
									$insc_status=$datosInscrito["insc_status"];
									if($insc_status == 0)
									{
										$sqlregular = "select insc_semestre as ofac_anocursa,
											insc_turno as ofac_turno,
											insc_tipo as ofac_condinsc,insc_codcarr as ofac_codcar,'' as masec_codsec,
											'' as ofac_codmat,
											insc_tipo as ofac_condalum
											from eva_inscripciones where insc_codusu='$dato->est_ced' and 
											insc_codlapso='$lapsoinscp';";
										//echo $sql;										
									}else
									{
										$sqlregular = "select substr(masec_codsec,2,1) as ofac_anocursa,substr(masec_codsec,3,1) as ofac_turno,
											'RG' as ofac_condinsc,estu_cod as ofac_codcar,masec_codsec,masec_codmat as ofac_codmat,
											'RG' as ofac_condalum
											from eva_materseccion inner join eva_planestudio
											on substr(masec_codsec,1,1)=estu_letra
											where masec_codsec='$cod_secc' and masec_lapso='$lapsoinscp';";
									}
								}else
								{
									$sqlregular = "select substr(masec_codsec,2,1) as ofac_anocursa,substr(masec_codsec,3,1) as ofac_turno,
										'RG' as ofac_condinsc,estu_cod as ofac_codcar,masec_codsec,masec_codmat as ofac_codmat,
										'RG' as ofac_condalum
										from eva_materseccion inner join eva_planestudio
										on substr(masec_codsec,1,1)=estu_letra
										where masec_codsec='$cod_secc' and masec_lapso='$lapsoinscp';";
									//echo $sql;
								}
							}
						}
					}
					$ok_ofertaregular=$conexion->ejecutarQuery($sqlregular); 

					if($ok_oferta)
					{
						$filasoferta=mysql_num_rows($ok_oferta);
						if($filasoferta>0)
						{
							$respuesta['exitooferta'] = true;
							$respuesta['mensajeoferta'] = 'Oferta Encontrada';
							$datosofertaregular=mysql_fetch_assoc($ok_ofertaregular);
							$ofac_anocursa      = $datosofertaregular["ofac_anocursa"];
							$ofac_turno         = $datosofertaregular["ofac_turno"];
							$ofac_condinsc      = $datosofertaregular["ofac_condinsc"];
							$ofac_codcar        = $datosofertaregular["ofac_codcar"];
							$ofac_seccion       = $datosofertaregular["ofac_seccion"];
							$ofac_codmat        = $datosofertaregular["ofac_codmat"];
							$ofac_condalum      = $datosofertaregular["ofac_condalum"];
							$matersem_codmat    = $datosofertaregular["ofac_codmat"];
							$matersem_condicion = $datosofertaregular["ofac_condalum"];
							$respuesta['secc']  = $ofac_seccion;
							$respuesta['anocursa']   = $ofac_anocursa;
							$respuesta["ofertaaca"] = $datosofertaregular;
							//Colsultar si existe una entrada en eva_inscripciones
							$sql = "select * from eva_inscripciones where insc_codusu='$dato->est_ced' and insc_codlapso='$lapsoinscp';";
							//echo $sql;
							$ok=$conexion->ejecutarQuery($sql);
							if($ok)
							{
								$filas=mysql_num_rows($ok);
								if($filas>0)
								{
									$sql = "update eva_inscripciones set insc_codusu='$dato->est_ced',insc_semestre='$ofac_anocursa',insc_turno='$ofac_turno',insc_tipo='$ofac_condinsc',insc_codlapso='$lapsoinscp',insc_codcarr='$ofac_codcar',insc_bajar='$bajarinsc' where insc_codusu='$dato->est_ced';";
									//echo $aux_elimatinsc;
									//echo $sql;
									$ok=$conexion->guardar($sql);
									if($ok)
									{
										$respuesta['exitoinscrip'] = true;
										$respuesta['exitooferta'] = true; //aqui lo cambio a true porque se supone que ya guerdo la oferta previamente. Este estatus me dice que ya paso por todos los insert y update es decir guardo todo
										$respuesta['mensajeinscrip'] = "Datos de eva_inscripciones se actualizaron con exito.";
									}else
									{
										$respuesta['exitoinscrip'] = false;
										$respuesta['mensajeinscrip'] = "Falló la actualizaron de eva_inscripciones.";
									}
									if($aux_elimatinsc == 1)
									{
										$sql = "delete from eva_matersemestre where matersem_cedula='$dato->est_ced' and matersem_codlapso='$lapsoinscp';";
										//echo $sql;
										$ok=$conexion->guardar($sql);
										$sql = "delete from eva_matinscritas where ced_alum='$dato->est_ced' and periescolar='$lapsoinscp';";
										//echo $sql;
										$ok=$conexion->guardar($sql);
										if($ok)
										{
											insertarMaterias($conexion,$respuesta,$ok_oferta,$dato->est_ced,$lapsoinscp,$filasoferta);
										}
									}
								}else
								{
									$sql = "insert into eva_inscripciones (insc_codusu,insc_semestre,insc_turno,insc_tipo,insc_stanueing,insc_codlapso,insc_codcarr,insc_bajar) values ('$dato->est_ced','$ofac_anocursa','$ofac_turno','$ofac_condinsc','$insc_stanueing','$lapsoinscp','$ofac_codcar','$bajarinsc'); ";
									//echo $sql;
									$ok=$conexion->guardar($sql);
									if($ok)
									{
										$respuesta['exitoinscrip'] = true;
										$respuesta['mensajeinscrip'] = "Datos de eva_inscripciones se insertaron con exito.";
									}else
									{
										$respuesta['exitoinscrip'] = false;
										$respuesta['mensajeinscrip'] = "Falló la Inserción de eva_inscripciones.";		
									}
									//cuando no existe el registro en inscripcion agrego las materias
									insertarMaterias($conexion,$respuesta,$ok_oferta,$dato->est_ced,$lapsoinscp,$filasoferta);
/*
									$sql = "insert into eva_matersemestre (matersem_cedula,matersem_codcarr,matersem_codlapso,matersem_codsec,matersem_codmat,matersem_condicion) values ";
									$sql1 = "insert into eva_matinscritas (periescolar,ced_alum,cod_mat,cod_carre,cod_sec,cond_materia) values ";
									$respuesta['j'] = 0;
									$respuesta['filasoferta'] = $filasoferta;
									while(($datosoferta=mysql_fetch_assoc($ok_oferta))>0)
									{
										//llenar oferta
										$ofac_anocursa      = $datosoferta["ofac_anocursa"];
										$ofac_turno         = $datosoferta["ofac_turno"];
										$ofac_condinsc      = $datosoferta["ofac_condinsc"];
										$ofac_codcar        = $datosoferta["ofac_codcar"];
										$ofac_seccion       = $datosoferta["ofac_seccion"];
										$ofac_codmat        = $datosoferta["ofac_codmat"];
										$ofac_condalum      = $datosoferta["ofac_condalum"];

										$respuesta['j'] = $respuesta['j'] + 1;
										$sql .= "('$dato->est_ced','$ofac_codcar','$lapsoinscp','$ofac_seccion','$ofac_codmat','$ofac_condalum')";
										$sql1 .= "('$lapsoinscp','$dato->est_ced','$ofac_codmat','$ofac_codcar','$ofac_seccion','$ofac_condalum')";
										if($respuesta['j'] === $filasoferta)
										{
											$sql  .= ";";
											$sql1 .= ";";
										}else
										{
											$sql  .= ",";
											$sql1 .= ",";
										}
									}
									//echo $sql;
									$ok=$conexion->guardar($sql);
									if($ok)
									{
										$respuesta['exitooferta'] = true; //Este estatus me dice que ya paso por todos los insert y update es decir guardo todo
										$respuesta['mensajematins'] = "Datos de eva_matersemestre se insertaron con exito.";
									}else
									{
											$respuesta['exitoinscrip'] = false;
											$respuesta['mensajematins'] = "Falló la Inserción de eva_matersemestre.";		
									}
									//echo $sql1;
									$ok=$conexion->guardar($sql1);
									if($ok)
									{
										$respuesta['exitooferta'] = true;
										$respuesta['mensajeoferta'] = "Datos de eva_matinscritas se insertaron con exito.";
									}else
									{
											$respuesta['exitoinscrip'] = false;
											$respuesta['mensajeinscrip'] = "Falló la Inserción de eva_matinscritas.";		
									}
									*/
								}
							}else
							{
								$respuesta['mensaje'] = "Fallo la consulta";
							}
						}else
						{
							$respuesta['mensajeoferta'] = "Estudiante no tiene oferta Académica.";
						}
					}else
					{
						$respuesta['exitooferta'] = false;
						$respuesta['mensajeoferta'] = 'Fallo la consulta de oferta Academica';
					}
					//Actualizar datos eva_planteles
					if(!empty($dato->est_placoddea))
					{
						$sql = "select * from eva_planteles where plan_est_codigo=upper('$dato->est_placoddea');";
						//echo $sql;
						$ok=$conexion->ejecutarQuery($sql);
						if($ok)
						{
							$filas=mysql_num_rows($ok);
							if($filas>0)
							{
								$sql = "update eva_planteles set plan_nombre=upper('$dato->est_nomplapro') where plan_est_codigo=upper('$dato->est_placoddea');";
								//echo $sql;
								$ok=$conexion->guardar($sql);
								if($ok)
								{
									$respuesta['exitoplanteles'] = true;
									$respuesta['mensajeplanteles'] = "Datos de eva_planteles se actualizaron con exito.";
								}else
								{
									$respuesta['exitoplanteles'] = false;
									$respuesta['mensajeplanteles'] = "Falló la actualuzación en eva_planteles.";
								}
							}else
							{
								$sql = "insert into eva_planteles (plan_est_codigo,plan_nombre) values (upper('$dato->est_placoddea'),upper('$dato->est_nomplapro'));";
								//echo $sql;
								$ok=$conexion->guardar($sql);
								if($ok)
								{
									$respuesta['exitoplanteles'] = true;
									$respuesta['mensajeplanteles'] = "Datos de eva_planteles se insertaron con exito.";
								}else
								{
									$respuesta['exitoplanteles'] = false;
									$respuesta['mensajeplanteles'] = "Falló la Inserción de eva_planteles.";
								}
							}
						}else
						{
							$respuesta['mensaje'] = "Fallo la consulta eva_planteles";
						}
					}
				}
			}else
			{
				$respuesta['mensaje'] = "Fallo la consulta";
			}
		}else
		{
			$respuesta['mensaje'] = "Cédula de estudiante fue recibida en cero (0). Vuelva a incluir los datos.";
		}
		echo json_encode($respuesta);
	}

	function consultarDatosInsc($conexion,$cedula)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Cédula no Existe";
		$sql = "select eva_inscripciones.*,
		(select desc_cond from eva_codingreso where eva_codingreso.cod_cond=eva_inscripciones.insc_tipo) as condinsc,
		substr(matersem_codsec,2,1) as anno, right(matersem_codsec,1) as seccion,
		concat(trim(estu_nombre),' ',trim(estu_mencion)) as plan_estudio,
		substr(matersem_codsec,1,1) as letra1ra_seccion,est_apellidos,est_nombres,est_fecnac,
		concat(est_apellidos,', ',est_nombres) as apenom,est_paren
		from eva_inscripciones inner join eva_matersemestre
		on eva_inscripciones.insc_codusu =eva_matersemestre.matersem_cedula
		inner join eva_materias
		on eva_matersemestre.matersem_codmat = eva_materias.mat_cod
		inner join eva_codingreso
		on eva_matersemestre.matersem_condicion = eva_codingreso.cod_cond
		inner join eva_planestudio
		on eva_inscripciones.insc_codcarr = eva_planestudio.fid
		inner join eva_filtros
		on insc_codlapso=eva_filtros.fil_codlapso
		inner join eva_estudiante
		on insc_codusu=est_ced
		where insc_codusu='$cedula' and (insc_tipo='RG' or insc_tipo='RP');";
		echo $sql;
		$ok=$conexion->ejecutarQuery($sql); 

		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensajeoferta'] = 'Oferta Encontrada';
				$datos=mysql_fetch_assoc($ok);
				$respuesta["inscrip"] = $datos;
				$respuesta["edad"] = calcularEdad($datos["est_fecnac"]);
			}else
			{
				$respuesta['mensaje'] = "Estudiante no esta inscrito.";
			}
		}else
		{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = 'Fallo la consulta de Inscripcion';
		}
		//echo json_encode($respuesta);
	}

	function estudiantesXrepresentante($conexion,$est_ced)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Cédula no Existe";
		$respuesta['tabla'] = "";
		$respuesta['contador'] = 0;
		$respuesta['combo'] = "";
		$i = 0;
		$sql = "select est_nombres,est_apellidos,eva_repestcopy.* 
		from eva_repest as eva_repestorgi
		inner join eva_repest as eva_repestcopy
		on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
		inner join eva_estudiante
		on eva_repestcopy.rep_cedalum=eva_estudiante.est_ced
		where eva_repestorgi.rep_cedalum='$est_ced';";
		//echo $sql;
		$respuesta['tabla'] = "
		<tr>
			<td colspan='2' class='bg-primary'>Estudiante(s) Representado(s)
			</td>
		</tr>";
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Cédula encontrada.";
				$respuesta['tablaestrep'] = $datos;
				while(($datos=mysql_fetch_assoc($ok))>0)
				{
					$i = $i + 1;
					$aux_cedula = $datos["rep_cedalum"];
					$aux_nom = $datos["est_nombres"];
					$aux_ape = $datos["est_apellidos"];
					$aux_apenom = $datos["est_nombres"] . ' ' .$datos["est_apellidos"]; // . ', ' . $datos["est_nombres"];

					$respuesta['tabla'] .= "
					<tr>
						<td width='30%'><input type='text' value='$aux_cedula' name='hercedalum$i' id='hercedalum$i' class='form-control input-sm' readonly title='$aux_cedula'>
						</td>
						<td width='70%'><input type='text' style='text-align:left;' value='$aux_apenom' name='hernomape$i' id='hernomape$i' class='form-control input-sm' readonly title='$aux_apenom'>
						</td>
					</tr>";
					$respuesta['combo'] .= "
					<option value='$aux_cedula'>$aux_cedula - $aux_apenom</option>
					";

				}
				$respuesta['contador'] = $i;
			}
		}else
		{
			$respuesta['mensaje'] = "Falló la consulta";
		}
		echo json_encode($respuesta);
	}

}

function guardarrep($conexion,$rep_ced,$rep_nac,$rep_nomrep,$rep_dirhab,$rep_telcel,$rep_telhabrep,$rep_lugtrarep,$rep_dirtrarep,$rep_teltrarep,$rep_profrep,$rep_email)
{
	//Colsultar si existe una entrada en repest
	if(!empty($rep_ced))
	{
		$sql = "select * from eva_representantes where rep_ced='$rep_ced';";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		if($ok)
		{
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$sql = "update eva_representantes set rep_nac='$rep_nac',rep_nomrep='$rep_nomrep',rep_direcalum='$rep_dirhab',rep_dirhabrep='$rep_dirhab',rep_telhabrep='$rep_telhabrep',rep_telcel='$rep_telcel',rep_lugtrarep='$rep_lugtrarep',rep_dirtrarep='$rep_dirtrarep',rep_teltrarep='$rep_teltrarep',rep_profrep='$rep_profrep',rep_email='$rep_email' where rep_ced='$rep_ced';";
				//echo $sql;
				$ok=$conexion->guardar($sql);
				if($ok)
				{
					return true;
				}else
				{
					return false;
				}
			}else
			{
				$sql = "insert into eva_representantes (rep_ced,rep_nac,rep_nomrep,rep_direcalum,rep_dirhabrep,rep_telhabrep,rep_telcel,rep_lugtrarep,rep_dirtrarep,rep_teltrarep,rep_profrep,rep_email) values ('$rep_ced','$rep_nac','$rep_nomrep','$rep_dirhab','$rep_dirhab','$rep_telhabrep','$rep_telcel','$rep_lugtrarep','$rep_dirtrarep','$rep_teltrarep','$rep_profrep','$rep_email');";
				//echo $sql;
				$ok=$conexion->guardar($sql);
				if($ok)
				{
					return true;
				}else
				{
					return false;
				}
			}
		}else
		{
			return false;
		}
	}
	return false;
}

function calcularEdad($aux_fecnac)
{
	$hoy = getdate();
	$diahoy = substr("0".$hoy["mday"],-2);
	$meshoy = substr("0".$hoy["mon"],-2);
	$aammddhoy = intval($hoy["year"].$meshoy.$diahoy);
	$aammddfna = intval(substr($aux_fecnac,0,4).substr($aux_fecnac,5,2).substr($aux_fecnac,8,2));
	$edad = trim(strval(intval(($aammddhoy-$aammddfna)/10000)));
	return $edad;
}

function fechaActual()
{
	$hoy = getdate();
	$diahoy = substr("0".$hoy["mday"],-2);
	$meshoy = substr("0".$hoy["mon"],-2);
	$aammddhoy = intval($hoy["year"]."-".$meshoy."-".$diahoy);
	return $aammddhoy;
}

function insertarMaterias($conexion,&$respuesta,$ok_oferta,$est_ced,$lapsoinscp,$filasoferta)
{
	$sql = "insert into eva_matersemestre (matersem_cedula,matersem_codcarr,matersem_codlapso,matersem_codsec,matersem_codmat,matersem_condicion) values ";
	$sql1 = "insert into eva_matinscritas (periescolar,ced_alum,cod_mat,cod_carre,cod_sec,cond_materia) values ";
	$respuesta['j'] = 0;
	$respuesta['filasoferta'] = $filasoferta;
	while(($datosoferta=mysql_fetch_assoc($ok_oferta))>0)
	{
		//llenar oferta
		$ofac_anocursa      = $datosoferta["ofac_anocursa"];
		$ofac_turno         = $datosoferta["ofac_turno"];
		$ofac_condinsc      = $datosoferta["ofac_condinsc"];
		$ofac_codcar        = $datosoferta["ofac_codcar"];
		$ofac_seccion       = $datosoferta["ofac_seccion"];
		$ofac_codmat        = $datosoferta["ofac_codmat"];
		$ofac_condalum      = $datosoferta["ofac_condalum"];

		$respuesta['j'] = $respuesta['j'] + 1;
		$sql .= "('$est_ced','$ofac_codcar','$lapsoinscp','$ofac_seccion','$ofac_codmat','$ofac_condalum')";
		$sql1 .= "('$lapsoinscp','$est_ced','$ofac_codmat','$ofac_codcar','$ofac_seccion','$ofac_condalum')";
		if($respuesta['j'] === $filasoferta)
		{
			$sql  .= ";";
			$sql1 .= ";";
		}else
		{
			$sql  .= ",";
			$sql1 .= ",";
		}
	}
	//echo $sql;
	$ok=$conexion->guardar($sql);
	if($ok)
	{
		$respuesta['exitooferta'] = true; //Este estatus me dice que ya paso por todos los insert y update es decir guardo todo
		$respuesta['mensajematins'] = "Datos de eva_matersemestre se insertaron con exito.";
	}else
	{
			$respuesta['exitoinscrip'] = false;
			$respuesta['mensajematins'] = "Falló la Inserción de eva_matersemestre.";		
	}
	//echo $sql1;
	$ok=$conexion->guardar($sql1);
	if($ok)
	{
		$respuesta['exitooferta'] = true;
		$respuesta['mensajeoferta'] = "Datos de eva_matinscritas se insertaron con exito.";
	}else
	{
		$respuesta['exitoinscrip'] = false;
		$respuesta['mensajeinscrip'] = "Falló la Inserción de eva_matinscritas.";		
	}
}

?>