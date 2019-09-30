<?php
require_once("../clases/estudiante.class.php");
require_once("../clases/conexion.class.php");

$objestudiante=new estudiante();	
/*Instanciamos un objeto de la clase Utilidades*/
//$objUtilidades=new utilidades;
//$objConexion=new conexion;

$conexion=Db::getInstance();/*instancia la clase*/
//$objSeguridad=new seguridad;

//$conexion=$objConexion->conectar();



?>
<!DOCTYPE html>
<html lang="es">

<head>

	<meta charset="UTF-8">
	<title>-Datos del Estudiante-</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css">
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css">
	
	<link rel="stylesheet" href="../css/estilos_nivel2.css">
							
</head>
	
<body>
	<div class="container-fluid" >
		<div class="form-group separador-md col-xs-12 col-md-12">
			<div class="bg-primary text-center titulo text-uppercase">Datos del Estudiante</div>
		</div>	
	</div>
<!--
	<HEADER>
		<div class="container-FLUID text-center">
		 	<div class="jumbotron">
		    	<h2>Actualización de Datos</h2>      
		  	</div>     
		</div>
	</HEADER>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<div class="container text-center">
					<div class="row">
					<h3 class="text-primary text-center ">.::Datos del Estudiante::.</h3>
					</div>
				</div>	
			</div>
		</div>
	</div>
-->
	<div class="container">
		<form role="form" id="datosestudiante" >
			<div class="row">
				<div class="col-xs-12 col-md-2">
					<div class="form-group">
						<label for="cedula" type="text" class="form-group"  >Cédula</label>
						<input id="cedula" name="cedula" type="text" class="form-control" placeholder="123456789012" autofocus>
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-md-1">
					<div class="form-group">
						<label for="nacionalidad" type="text" class="form-group">Nac.</label>
						<select id='nacionalidad' name='nacionalidad' class='form-control' onChange="validacion('lapso');">
							<option value="V">V</option>
							<option value="E">E</option>
							<option value="P">P</option>
						</select>	 
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 col-sm-offset-">
					<div class="form-group" >
						<label for="nombres" type="text" class="form-group" >Nombres:</label>
						<input id="nombres" name="nombres" type="text" class="form-control mayuscula" placeholder="Nombres">
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="apellidos" type="text" class="form-group">Apellidos:</label>
						<input id="apellidos" name="apellidos" type="text" class="form-control mayuscula" required />
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-2">
					<div class="form-group">
						<label for="est_fecnac" class="form-group">Fecha Nac.:</label>
						<input type="text" class="form-control" name="est_fecnac" id="est_fecnac" placeholder="DD/MM/AAAA" readonly onkeyup="validacion('est_fecnac');">
						<span class="help-block"></span>	
					</div>
				</div>
				<!--<div class="col-xs-12 col-sm-1">
					<div class="form-group">
						<label for="edad" type="text" class="form-group">Edad:</label>
						<input id="edad" type="text" class="form-control" required />
						<span class="help-block"></span>
					</div>
				</div>-->	
			</div>	
		</form>		
	</div>		
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form"  id="datosestudiante">
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_placod" type="text" class="form-group">Plantel de Procedencia:</label>
								<input id="est_placod" name="est_placod" type="text" class="form-control" required />
							</div>
						</div>	
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="lugar_nace" type="text" class="form-group">Lugar de Nac.</label>
								<input id="lugar_nace" type="text" class="form-control mayuscula" required />
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_codpais" type="text" class="form-group">País:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_pais",$value="fid",$mostrar="pai_nombre",$nombre="est_codpais",$sql="",$funcion=""); ?>	 
							 
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_estnac" type="text" class="form-group">Estado:</label>
								 <?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_estado",$value="fid",$mostrar="est_nombre",$nombre="est_estnac",$sql="",$funcion=""); ?>
							</div>
						</div>	
					</div>	
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="" action="" >
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="est_edocivil" type="text" class="form-group" >Estado Civil:</label>
								 <?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_estado_civil",$value="edo_id",$mostrar="edo_des_civil",$nombre="est_edocivil",$sql="",$funcion=""); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="est_sexo" type="text" class="form-group">Sexo:</label>
      							<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_sexo",$value="cod_sex",$mostrar="sex_des",$nombre="est_sexo",$sql="",$funcion=""); ?>

      						</div>	
						</div>
					
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_tipoparto" type="text" class="form-group">Tipo de Parto:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_tipo_parto",$value="parto_id",$mostrar="parto_des",$nombre="est_tipoparto",$sql="",$funcion=""); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="est_email" type="text" class="form-group" >E-Mail:</label>
								<input id="est_email" name="est_email" type="text" class="form-control mayuscula" maxlength="100">
							</div>
						</div>
					</div>	
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<legend></legend>
	</div>	
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group separador-md">
					<div class="bg-primary text-center titulo text-uppercase">Datos de la Madre</div>
				</div>	
<!--
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary text-center ">.::Datos de la Madre::.</h3>
					</div>
				</div>
-->
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_cedmad" type="text" class="form-group" >Cédula:</label>
								<input id="rep_cedmad" name="rep_cedmad" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-1">
							<div class="form-group">
								<label for="rep_nacmad" type="text" class="form-group">Nac.</label>
								<select id='rep_nacmad' name='rep_nacmad' class='form-control'>
									<option value="V"> V </option>
									<option value="E"> E </option>
									<option value="P"> P </option>
								</select>	 
							 </div>
						</div>
						<div class="col-xs-12 col-sm-3 col-sm-offset">
							<div class="form-group" >
								<label for="rep_nomrepmad" type="text" class="form-group" >Nombres:</label>
								<input id="rep_nomrepmad" name="rep_nomrepmad" type="text" class="form-control mayuscula">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_telcelmad" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="rep_telcelmad" name="rep_telcelmad" type="text" class="form-control" maxlength="11">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_telhabrepmad" type="text" class="form-group">Telf. Habitación:</label>
								<input id="rep_telhabrepmad" name="rep_telhabrepmad" type="text" class="form-control" maxlength="11">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_teltrarepmad" type="text" class="form-group">Telf. de Trabajo:</label>
								<input id="rep_teltrarepmad" name="rep_teltrarepmad" type="text" class="form-control" maxlength="11">
							</div>
						</div>	
					</div>	
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-sm-offset">
							<div class="form-group">
      							<label for="rep_lugtrarepmad" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="rep_lugtrarepmad" name="rep_lugtrarepmad" type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="rep_dirtrarepmad" type="text" class="form-group">Dirección de Trabajo:</label>
      							<input id="rep_dirtrarepmad" name="rep_dirtrarepmad" type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="rep_profrepmad" type="text" class="form-group">Profesión:</label>
      							<input id="rep_profrepmad" name="rep_profrepmad" type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_emailmad" type="text" class="form-group" >E-Mail:</label>
								<input id="rep_emailmad" name="rep_emailmad" type="text" class="form-control mayuscula">
							</div>
						</div>
					</div>		
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<legend></legend>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group separador-md">
					<div class="bg-primary text-center titulo text-uppercase">Datos del Padre</div>
				</div>	
<!--
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary text-center ">.::Datos del  Padre::.</h3>
					</div>
				</div>
-->
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_cedpad" type="text" class="form-group" >Cédula:</label>
								<input id="rep_cedpad" name="rep_cedpad type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-1">
							<div class="form-group">
								<label for="rep_nacpad" type="text" class="form-group">Nac.</label>
								<select id='rep_nacpad' name='rep_nacpad' class='form-control'>
									<option value="V"> V </option>
									<option value="E"> E </option>
									<option value="P"> P </option>
								</select>	 
							 </div>
						</div>
						<div class=" col-sm-3 col-xs-12  col-sm-offset">
							<div class="form-group" >
								<label for="rep_nomreppad" type="text" class="form-group" >Nombres:</label>
								<input id="rep_nomreppad" name="rep_nomreppad" type="text" class="form-control mayuscula">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_telcelpad" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="rep_telcelpad" name="rep_telcelpad" type="text" class="form-control" maxlength="11">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_telhabreppad" type="text" class="form-group">Telf. Habitación:</label>
								<input id="rep_telhabreppad" name="rep_telhabreppad" type="text" class="form-control" maxlength="11">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_teltrareppad" type="text" class="form-group">Telf. de Trabajo:</label>
								<input id="rep_teltrareppad" name="rep_teltrareppad" type="text" class="form-control" maxlength="11">
							</div>
						</div>	
					</div>	
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-3 col-sm-offset">
							<div class="form-group">
      							<label for="rep_lugtrareppad" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="rep_lugtrareppad" name="rep_lugtrareppad" type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="rep_dirtrareppad" type="text" class="form-group">Dirección de Trabajo:</label>
      							<input id="rep_dirtrareppad" name="rep_dirtrareppad" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="rep_profreppad" type="text" class="form-group">Profesión:</label>
      							<input id="rep_profreppad" name="rep_profreppad type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_emailpad" type="text" class="form-group" >E-Mail:</label>
								<input id="rep_emailpad" name="rep_emailpad" type="text" class="form-control mayuscula">
							</div>
						</div>
					</div>		
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<legend></legend>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group separador-md">
					<div class="bg-primary text-center titulo text-uppercase">Datos del Representante</div>
				</div>	
<!--
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary ">.::Datos del Representante::.</h3>
					</div>
				</div>
-->
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_ced" type="text" class="form-group" >Cédula:</label>
								<input id="rep_ced" name="id="rep_ced"" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-1">
							<div class="form-group">
								<label for="rep_nac" type="text" class="form-group">Nac.</label>
								<select id='rep_nac' name='rep_nac' class='form-control'>
									<option value="V"> V </option>
									<option value="E"> E </option>
									<option value="p"> P </option>
								</select>	 
							 </div>
						</div>
						<div class="col-xs-12 col-sm-3 col-sm-offset">
							<div class="form-group" >
								<label for="rep_nomrep" type="text" class="form-group" >Nombres:</label>
								<input id="rep_nomrep" name="rep_nomrep" type="text" class="form-control mayuscula">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_telcel" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="rep_telcel" name="rep_telcel" type="text" class="form-control" maxlength="11">
							</div>
						</div>
					<div class="col-xs-12 col-sm-2">
						<div class="form-group">
							<label for="rep_telhabrep" type="text" class="form-group">Telf. Habitación:</label>
							<input id="rep_telhabrep" name="rep_telhabrep" type="text" class="form-control" maxlength="11">
						</div>
						</div>
							<div class="col-xs-12 col-sm-2">
								<div class="form-group">
								<label for="rep_teltrarep" type="text" class="form-group">Telf. de Trabajo:</label>
								<input id="rep_teltrarep" name="rep_teltrarep" type="text" class="form-control" maxlength="11">
							</div>
						</div>
					</div>	
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
      							<label for="rep_lugtrarep" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="rep_lugtrarep" name="rep_lugtrarep" type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
      							<label for="rep_dirtrarep">Dirección de Trabajo:</label>
      							<input id="rep_dirtrarep" name="rep_dirtrarep" type="text" class="form-control">
    						</div>
						</div>
					</div>	
				</form>		
			</div>		
		</div>
	</div>
		<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-5">
							<div class="form-group">
      							<label for="rep_profrep">Profesión:</label>
      							<input id="rep_profrep" name="rep_profrep" type="text" class="form-control mayuscula">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-5">
							<div class="form-group" >
								<label for="rep_email" type="text" class="form-group" >E-Mail:</label>
								<input id="rep_email" name="rep_email" type="text" class="form-control mayuscula">
							</div>
						</div>	
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
      							<label for="est_paren">Parentesco:</label>
      							<input id="est_paren" name="est_paren" type="text" class="form-control mayuscula">
    						</div>
						</div>

					</div>	
				</form>		
			</div>		
		</div>
	</div>

	<div class="container">
		<legend></legend>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group separador-md">
					<div class="bg-primary text-center titulo text-uppercase">Datos de Emergencia</div>
				</div>	
<!--
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary ">.::Datos de Emergencia::.</h3>
					</div>
				</div>
-->
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-5">
							<div class="form-group" >
								<label for="est_callemer" type="text" class="form-group" >En caso de Emergencia llamar:</label>
								<input id="est_callemer" name="est_callemer" type="text" class="form-control mayuscula">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_telfemer" type="text" class="form-group" >Teléfono:</label>
								<input id="est_telfemer" name="est_telfemer" type="text" class="form-control" maxlength="40" title="Puede incluir mas 1 teléfono.">
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="est_grafam" type="text" class="form-group" >Grado Familiar:</label>
								<input id="est_grafam" name="est_grafam" type="text" class="form-control mayuscula"  maxlength="20">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_familia" type="text" class="form-group" >¿Es Familiar?</label>
								<select id='est_familia' name='est_familia' class='form-control'>
								<option value="1">Seleccione...</option>
								<option value="1">Si</option>
								<option value="0">No</option>
								
								</select>		
							</div>
						</div>
					</div>	
				</form>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_vivecon" type="text" class="form-group" >Vive con:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_con_quien_vive",$value="ss_id_con",$mostrar="ss_des_conquien",$nombre="est_vivecon",$sql="",$funcion=""); ?>
							</div>
						</div>		
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_medtras" type="text" class="form-group" >Medio Transporte:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_medio_transporte",$value="ss_id_medio",$mostrar="ss_des_medio",$nombre="est_medtras",$sql="",$funcion=""); ?>	
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_vivecondes" type="text" class="form-group" >Nombre con quien Vive:</label>
								<input id="rep_vivecondes" name="rep_vivecondes" type="text" class="form-control mayuscula">
							</div>
						</div>	
					</div>	
				</form>		
			</div>		
		</div>
	</div>
	<div class="container">
		<legend></legend>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="form-group separador-md">
					<div class="bg-primary text-center titulo text-uppercase">Destinatario de la Factura (En caso de facturar a otros)</div>
				</div>	
<!--
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary ">.::Destinatario de la Factura (En caso de facturar a otros)::.</h3>
					</div>
				</div>
-->
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cli_cedrif" type="text" class="form-group" >Cédula:</label>
								<input id="cli_cedrif" name="cli_cedrif" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-sm-offset">
							<div class="form-group" >
								<label for="cli_apenom" type="text" class="form-group" >Facturar a Nombre de:</label>
								<input id="cli_apenom" name="cli_apenom" type="text" class="form-control mayuscula">
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
								<label for="cli_direc" type="text" class="form-group">Dirección Fiscal:</label>
								<input id="cli_direc" name="cli_direc" type="text" class="form-control mayuscula">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cli_telf" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="cli_telf" name="cli_telf" type="text" class="form-control">
							</div>
						</div>
					</div>	
				</form>		
			</div>
		</div>
	</div>			
	<div class="container">
		<legend></legend>
	</div>
	<br>

	<div class="container text-right">
		<div class="row">
			<div class="col-xs-12">
				<button type="button" class="glyphicon glyphicon-floppy-disk '' btn pull-right btn-primary" name="btnvalidar" id="btnvalidar">Guardar</button>			
			</div>		
		</div>
	</div>
<!--	<div id="dialog_carga" title="Cargando..." style="display:none;" align="center">-->

	<div id="dialog_carga" title="Cargando..." align="center">
	<img src="../imagenes/cargando.gif" alt="q" width="50" height="50"></div>



	<script src="../bootstrap/js/jquery.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	
	<script type="text/javascript" src="../bootstrap/js/jquery.numeric.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/alertify.js"></script>
	<script type="text/javascript" src="../bootstrap/js/livevalidation.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/grid.locale-es.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/jquery.jqGrid.min.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	<script type="text/javascript" src="../bootstrap/js/bootstrap-datepicker.js"></script>
	<script src="../bootstrap/js/sessvars.js"></script>
	<script type="text/javascript" src="../javascript/actualizar_datos_estudiantes.js" language="javascript" type="text/javascript"></script>
	<script src="../javascript/principal.js" language="javascript" type="text/javascript"> </script>
</body>
</html>