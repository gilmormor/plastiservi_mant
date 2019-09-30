<?php
//session_start();
class usuario
{
	function agregar_usuario($conexion,$ema_usu,$cla_usu,$ced_usu,$nom_usu,$ape_usu,$fk_tip_usu,$tel_usu,$est_usu)
	{
		$sql="insert into usuario(ema_usu,cla_usu,clave,ced_usu,nom_usu,ape_usu,fk_tip_usu,tel_usu,est_usu) values('$ema_usu',md5('$cla_usu'),'$cla_usu','$ced_usu','$nom_usu','$ape_usu','$fk_tip_usu','$tel_usu','$est_usu');";
		//echo $sql;
		$ok=$conexion->guardar($sql);
		if($ok){
			$sql="insert into persona(rut,nombre,apellido,telefono,cargoID,departamentoAreaID,email,fechaHoraInsert,fechaHoraUpdate) values('$ced_usu','$nom_usu','$ape_usu','$tel_us','2','6','$ema_usu',now(),now());";
			//echo $sql;
			$ok1=$conexion->guardar($sql);		
		}
		return $ok;			
	}

	function agregar_usuariojson($conexion,$ema_usu,$cla_usu,$ced_usu,$nom_usu,$ape_usu,$fk_tip_usu,$tel_usu,$est_usu)
	{
		//Hice otra funcion para no modificar la que ya esta hecha. Esta va ser por json
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Fallo la creación de Usuario';

		if(empty($nom_usu) || empty($ape_usu))
		{
			$respuesta['mensaje'] = 'Nombre o Apellido no pueden quear en blanco. Ingrese Numero de cedula del estudiante';
		}else
		{
			$sql="select ema_usu from usuario where ced_usu='$ced_usu';";
			//echo $sql;
			$filas = $conexion->filas($sql);
			if($filas>0)
			{
				$respuesta['exito'] = false;
				$respuesta['mensaje'] = 'Usuario ya registrado. Debe ingresar al Sistema con su email y clave.';
			}else
			{
				$sql="select ema_usu from usuario where ema_usu='$ema_usu';";
				//echo $sql;
				$filas = $conexion->filas($sql);
				if($filas>0)
				{
					$respuesta['exito'] = false;
					$respuesta['mensaje'] = 'Correo electrónico ya existe. Debe Ingresar otro.';
				}else
				{
					$sql="insert into usuario(ema_usu,cla_usu,clave,ced_usu,nom_usu,ape_usu,fk_tip_usu,tel_usu,est_usu) values('$ema_usu',md5('$cla_usu'),'$cla_usu','$ced_usu','$nom_usu','$ape_usu','$fk_tip_usu','$tel_usu','$est_usu');";
					$ok=$conexion->guardar($sql);
					if ($ok)
					{
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = 'Usuario creado con exito';
					}
				}
			}
		}
		echo json_encode($respuesta);
	}

	function cambiar_clave($conexion,$cla_usu,$cla_usu1,$cla_usu2)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		if (empty($cla_usu))
			$respuesta['mensaje'] = 'Clave actual esta en blanco.';
		else if(empty($cla_usu1))
			$respuesta['mensaje'] = 'Clave nueva esta en blanco.';
		else if(empty($cla_usu2))
			$respuesta['mensaje'] = 'Verificacion de clave esta en blanco.';
		else if(!($cla_usu1===$cla_usu2))
			$respuesta['mensaje'] = 'Nueva Clave no coinciden con confirmación de clave.';
		else
		{
			$sql="select * from usuario where ema_usu='".$_SESSION["usuario"]."';";
			$ok=$conexion->ejecutarQuery($sql);
			$filas=mysql_num_rows($ok);
			if($filas>0)
			{
				$datos=mysql_fetch_assoc($ok);
				if($datos["cla_usu"]==md5($cla_usu))
				{
					$sql="update usuario set cla_usu=md5('$cla_usu1'),clave='$cla_usu1' where ema_usu='".$_SESSION["usuario"]."';";
					//echo $sql;
					$ok=$conexion->guardar($sql);
					if($ok)
					{
						$respuesta['exito'] = true;
						$respuesta['mensaje'] = 'Cambio de clave exitoso.';
					}
				}else
				{
					$respuesta['mensaje'] = 'Clave actual no coincide con la almacenada.';
				}
			}else
			{
				$respuesta['mensaje'] = 'Usuario no existe.';
			}
		}
		echo json_encode($respuesta);
	}

	function buscarinisesion($conexion,$correo,$clave)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Usuario o clave no existe.";
		$sqlusu="select usuarioID,ema_usu,ced_usu,nom_usu,ape_usu,fk_tip_usu,nom_tip_usu,est_tip_usu,personaID
				from vistausuariopersona inner join tipo_usuario
				on fk_tip_usu=id_tip_usu where ema_usu='$correo' and cla_usu=md5('$clave');";
		//echo $sql;
		$filas = $conexion->filas($sqlusu);
		if($filas>0)
		{
			$sqlcol="select * from eva_datoscolegio;";
			$filas = $conexion->filas($sqlcol);
			if($filas>0)
			{
				$datoscolegio = $conexion->ejecutar($sqlcol);
				$sqlfil = "select *,DATE_FORMAT(fil_fecha_dep,'%d/%m/%Y') as fil_fecha_dep_dma,if(now()<fil_fecfininsc,1,0) as sta_inscviva from eva_filtros;";
				$ok=$conexion->ejecutarQuery($sqlfil);
				$filas=mysql_num_rows($ok);
				if($filas>0)
				{
					$datosfil=mysql_fetch_assoc($ok);
					$respuesta['datosfil'] = $datosfil;
					//VARIABLES DE SESION NUMEROS DE CUENTA
					$_SESSION["fil_numcuentasoft"] = $datos["fil_numcuentasoft"];
					$_SESSION["fil_numcuentacol"] = $datos["fil_numcuentacol"];
					$_SESSION["fil_numcuentapad"] = $datos["fil_numcuentapad"];
					$_SESSION["lapsoinsc"] = $datos["fil_codlapso"];
					$_SESSION['fil_mtoseguroescolar']= $datos["fil_mtoseguroescolar"];

					//$datos = $conexion->ejecutar($sqlusu);
					$ok=$conexion->ejecutarQuery($sqlusu);
					$datosest=mysql_fetch_assoc($ok);

					$_SESSION["usuario"]     = $correo;
					$_SESSION["usuarioreal"] = $correo;
					$_SESSION["usuarioID"]   = $datosest["usuarioID"];
					$_SESSION["cedula"]      = $datosest["ced_usu"];
					$_SESSION["nom_usu"]     = $datosest["nom_usu"];
					$_SESSION["ape_usu"]     = $datosest["ape_usu"];
					$_SESSION["nomape"]      = $datosest["nom_usu"] . " " . $datosest["ape_usu"];
					$respuesta["usunomape"]  = $datosest["nom_usu"] . " " . $datosest["ape_usu"];
					$respuesta["usuarioID"]  = $datosest["usuarioID"];
					$_SESSION["fk_tip_usu"]  = $datosest["fk_tip_usu"];

					
					$_SESSION["statusdepositosok"] = true; //variable de sesion  que indica si el usuario pago todos los depositos. Si es Docente u otro tipo de usuario por defecto queda en true
					if($datosest["fk_tip_usu"]==4)
					{
						$_SESSION["usuario"] = "estudiante@softservica.com";
						$_SESSION["statusdepositosok"] = false;
					}
					$_SESSION["tipousuario"] = $datosest["fk_tip_usu"];
					$_SESSION["periescolar"] = $datoscolegio[0]["peresc_act"]; //Periodo escolar actual

					$respuesta['exito'] = true;
					$respuesta['mensaje'] = "Bienvenido";
					$respuesta['datosest'] = $datosest;
					$respuesta['fk_tip_usu'] = $datosest["fk_tip_usu"];
					$respuesta['ced_usu'] = $datosest["ced_usu"];
					$respuesta['personaID'] = $datosest["personaID"];
					$_SESSION["personaID"] = $datosest["personaID"];


					if($datosest["fk_tip_usu"]==2)
					{
						$sql = "select right(RAND(),5) as codaleatorio;";
						$datosale = $conexion->ejecutar($sql);
						$codaleatorio = $datosale[0]["codaleatorio"];
						$_SESSION["codAleatorioEncriptado"] = md5($codaleatorio); //lo asigno a una variable de session
						$to      = $correo;
						$subject = "Clave de Validacion ".$datoscolegio[0]['nomcolegio'];
						$message = "Clave de validacion al Sistema Web: ".$codaleatorio;
						$from    = "info@softservica.com";
						$headers = "From: ". $from;
						$respuesta['codaleatorio'] = $codaleatorio;  //esto tengo que quitarlo
						//mail($to,$subject,$message,$headers); //esto tengo que activarlo
					}
				}else
				{
					$respuesta['mensaje'] = "Tabla de filtros vacia.";
				}

			}else
			{
				$respuesta['mensaje'] = "Tabla: Datos Colegio esta Vacia.";
			}
		}
		echo json_encode($respuesta);
	}

	function validarcod($conexion,$codvalidacion)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Clave no es valida.";
		$respuesta['clave1'] = $_SESSION["codAleatorioEncriptado"];
		$respuesta['clave2'] = md5($codvalidacion);
		$respuesta['nombreape'] = $_SESSION["nomape"];
		if($_SESSION["codAleatorioEncriptado"]==md5($codvalidacion))
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Bienvenido al Sistema.";
		}
		echo json_encode($respuesta);
	}

	function consultaxcedula($conexion,$ced_usu) //Consulta por Cedula
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Usuario no existe.";
		$sqlusu="select ema_usu,ced_usu,nom_usu,ape_usu,fk_tip_usu,nom_tip_usu,est_tip_usu
				from usuario inner join tipo_usuario
				on fk_tip_usu=id_tip_usu where ced_usu='$ced_usu';";
		//echo $sql;
		$filas = $conexion->filas($sqlusu);
		if($filas>0)
		{

			$datos = $conexion->ejecutar($sqlusu);
			$respuesta['ema_usu'] = $datos[0]["ema_usu"];
			$respuesta["nom_usu"] = $datos[0]["nom_usu"];
			$respuesta["ape_usu"] = $datos[0]["ape_usu"];
			$respuesta["nomape"] = $datos[0]["nom_usu"] . " " . $datos[0]["ape_usu"];

			$respuesta['exito'] = true;
			$respuesta['mensaje'] = "Usuario ya existe";
			$respuesta['datos'] = $datos;
		}
		echo json_encode($respuesta);
	}

	function consultarXemail($conexion,$ema_usu)
	{
		//Buscar email
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = 'Fallo la consulta';

		$sql="select ema_usu from usuario where ema_usu='$ema_usu';";
		//echo $sql;
		$filas = $conexion->filas($sql);
		if($filas>0)
		{
			$respuesta['exito'] = true;
			$respuesta['mensaje'] = 'Correo electrónico ya existe. Debe Ingresar otro.';
		}else
		{
			$respuesta['exito'] = false;
			$respuesta['mensaje'] = '';
		}
		echo json_encode($respuesta);
	}

	function recuperarclave($conexion,$email,$cedula)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Correo o cédula no existen.";
		$sqlusu="select ema_usu,ced_usu,nom_usu,ape_usu,fk_tip_usu,nom_tip_usu,est_tip_usu,clave
				from usuario inner join tipo_usuario
				on fk_tip_usu=id_tip_usu where ema_usu='$email' or ced_usu='$cedula';";
		echo $sql;
		$filas = $conexion->filas($sqlusu);
		if($filas>0)
		{
			$sqlcol="select * from eva_datoscolegio;";
			$filas = $conexion->filas($sqlcol);
			if($filas>0)
			{
				$datoscolegio = $conexion->ejecutar($sqlcol);

				$datos = $conexion->ejecutar($sqlusu);
				$apenom = $datos[0]["nom_usu"] . " " . $datos[0]["ape_usu"];
				$correo = $datos[0]["ema_usu"];
				$clave = $datos[0]["clave"];

				$respuesta['exito'] = true;
				$respuesta['mensaje'] = "Clave fue enviada al correo: ".$datos[0]["ema_usu"];
				$respuesta['datos'] = $datos;

				$to      = $correo;
				$subject = "Clave recuperada: ".$datoscolegio[0]['nomcolegio'];
				$message = "Usuario: ".$correo." Clave: ".$clave;
				$from    = "noreply@plastiservi.cl";
				$headers = "From: ". $from;
				mail($to,$subject,$message,$headers); //esto tengo que activarlo
			}else
			{
				$respuesta['mensaje'] = "Tabla: Datos Colegio esta Vacia.";
			}
		}
		echo json_encode($respuesta);
	}

	function validarElPasoVentanaPrincipal()
	{
		$respuesta = array();
		$respuesta['exito'] = true;
		$respuesta['mensaje'] = "Bienvenido al Sistema!";
		//$respuesta['statusdepositosok'] = $_SESSION["statusdepositosok"];
		$_SESSION["statusdepositosok"] = true;
		echo json_encode($respuesta);
	}

	function consultarClave($conexion)
	{
		$respuesta = array();
		$respuesta['exito'] = false;
		$respuesta['mensaje'] = "Informacion no encontrada.";
		$respuesta['tabla'] = "";
		$referencia_cond = "true";
		$sql = "select *,concat(ifnull(nom_usu,''),' ',ifnull(ape_usu,'')) nombre 
				from usuario
				where fk_tip_usu=4 and ema_usu!='estudiante@softservica.com'
				order by nom_usu,ape_usu;";
		//echo $sql;
		$ok=$conexion->ejecutarQuery($sql);
		$filas=mysql_num_rows($ok);
		$respuesta['exito'] = false;
		$respuesta['tabla'] .= '<table class="table display AllDataTables responsive table-hover table-condensed">';
			$respuesta['tabla'] .= '<thead>
				<tr>
					<th>Cedula</th>
					<th align="left">Nombre y Apellido</th>
					<th align="left">Usuario - Email</th>
					<th align="left">Clave</th>
				</tr>
			</thead>
			<tbody>';
		if($filas>0)
		{
			$respuesta['exito'] = true;
		}
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
			$ced_usu	= $datos["ced_usu"];
			$nombre = $datos["nombre"];
			$ema_usu= $datos["ema_usu"];
			$clave	= $datos["clave"];
			$respuesta['tabla'] .= "<tr>
					<td>$ced_usu</td>
					<td align='left'>$nombre</td>
					<td align='left'>$ema_usu</td>
					<td align='left'>$clave</td>
				</tr>";	   
		}
		
		$respuesta['tabla'] .= "</tbody>";
		$respuesta['tabla'] .= "</table>";
		echo json_encode($respuesta);
	}

	function nomUsuario()
	{
		$respuesta = array();
		$respuesta['nomUsuario'] = $_SESSION["nomape"];	
		echo json_encode($respuesta);
	}


}
?>