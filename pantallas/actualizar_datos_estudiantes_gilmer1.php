<?php
require_once("../clases/estudiante.class.php");
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
require_once("../clases/opciones.class.php");

$objestudiante=new estudiante();	
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
//$objConexion=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/

$objSeguridad=new seguridad;
$objOpcion = new opciones;

//$conexion=$objConexion->conectar();

$nombre_script=$objUtilidades->ruta_script(); //Averigua el nombre del script que se está ejecutando
//echo $nombre_script;
$num_opc = $objOpcion->devolver_cod_opc($conexion,$nombre_script); //Devuelve el código de la opción asociada al script

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$num_opc);
//$retorno = 1;
if($retorno==1)
{


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Datos del Estudiante</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="../bootstrap/css/alertify.min.css">
	<link type="text/css" rel="Stylesheet" href="../bootstrap/css/jquery.qtip.min.css" />
	<link rel="stylesheet" href="../bootstrap/js/jquery-ui-1.10.3/themes/base/jquery.ui.all.css">
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" type="text/css" href="../bootstrap/js/jqGrid-4.5.4/css/ui.jqgrid.css"/>
	<link rel="stylesheet" href="../bootstrap/css/bootstrap-datepicker.css"/>
	<!--<link rel="stylesheet" href="../bootstrap/datepicker/css/bootstrap-datepicker.css"/>-->
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
		<form role="form" id="datosestudiante1" >
			<div class="row">
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<input id="est_exp" name="est_exp" type="text" style='display:none;'>
						<label for="est_ced" type="text" class="form-group">Cédula</label>

						<select id='est_cedcombo' name='est_cedcombo' class='form-control' onkeyup="validacion('est_cedcombo','texto','col-xs-12 col-sm-3');" onchange="onchangeComboCed();" style='display:none;' autofocus title="Si tiene mas de un representado, Seleccione el Estudiante">
						</select>
						<input id="est_ced" name="est_ced" type="text" class="form-control" placeholder="123456789012" autofocus onkeyup="validacion('est_ced','numerico','col-xs-12 col-sm-3');" maxlength="11">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-1">
					<div class="form-group">
						<label for="est_nacionalidad" type="text" class="form-group">Nac.</label>
						<select id='est_nacionalidad' name='est_nacionalidad' class='form-control' maxlength="1" onkeyup="validacion('est_nacionalidad','texto','col-xs-12 col-sm-1');">
							<option value="V">V</option>
							<option value="E">E</option>
							<option value="P">P</option>
						</select>
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group" >
						<label for="est_nombres" type="text" class="form-group" >Nombres:</label>
						<input id="est_nombres" name="est_nombres" type="text" class="form-control" placeholder="Nombres" maxlength="60" onkeyup="validacion('est_nombres','texto','col-xs-12 col-sm-4'); aMays(event, this);" onblur="aMays(event, this)">
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-4">
					<div class="form-group">
						<label for="est_apellidos" type="text" class="form-group">Apellidos:</label>
						<input id="est_apellidos" name="est_apellidos" type="text" class="form-control" required placeholder="Apellidos" maxlength="60" onkeyup="validacion('est_apellidos','texto','col-xs-12 col-sm-4'); aMays(event, this);" onblur="aMays(event, this)">
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
				<form role="form"  id="datosestudiante2">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="est_fecnac" class="form-group">Fecha Nac.:</label>
								<input type="text" class="form-control has-error" name="est_fecnac" id="est_fecnac" placeholder="DD/MM/AAAA" readonly onkeyup=""/>
								<!--validacion('est_fecnac','numerico','col-xs-12 col-sm-2');-->
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
								<label for="est_placoddea" type="text" class="form-group">Código Plantel de Procedencia:</label>
								<input type="hidden" id="est_placod" name="est_placod">
								<input id="est_placoddea" name="est_placoddea" type="text" class="form-control" required onkeyup="validacion('est_placoddea','texto','col-xs-12 col-sm-4'); aMays(event, this);" onblur="aMays(event, this)" maxlength="10">
								<span class="help-block"></span>
							</div>
						</div>	
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
								<label for="est_nomplapro" type="text" class="form-group">Nombre Plantel de Procedencia:</label>
								<input id="est_nomplapro" name="est_nomplapro" type="text" class="form-control" disabled onkeyup="validacion('est_nomplapro','texto','col-xs-12 col-sm-6'); aMays(event, this);" onblur="aMays(event, this)" maxlength="58">
								<span class="help-block"></span>
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
				<form role="form"  id="datosestudiante3">
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<div class="form-group" >
								<label for="est_lugnac" type="text" class="form-group">Lugar de Nac.</label>
								<input id="est_lugnac" name="est_lugnac" type="text" class="form-control" required maxlength="30" onkeyup="validacion('est_lugnac','texto','col-xs-12 col-sm-6'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_codpais" type="text" class="form-group">País:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_pais",$value="fid",$mostrar="pai_nombre",$nombre="est_codpais",$sql="",$funcion="validacion('est_codpais','texto','col-xs-12 col-sm-3')"); ?>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_estnac" type="text" class="form-group">Estado:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_estado",$value="fid",$mostrar="est_nombre",$nombre="est_estnac",$sql="",$funcion="validacion('est_estnac','texto','col-xs-12 col-sm-3')"); ?>
								<span class="help-block"></span>
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
<!--				<form role="form" class="" action="" > -->
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_edocivil" type="text" class="form-group" >Estado Civil:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_estado_civil",$value="edo_id",$mostrar="edo_des_civil",$nombre="est_edocivil",$sql="",$funcion="validacion('est_edocivil','texto','col-xs-12 col-sm-2')"); ?>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
      							<label for="est_sexo" type="text" class="form-group">Sexo:</label>
      							<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_sexo",$value="cod_sex",$mostrar="sex_des",$nombre="est_sexo",$sql="",$funcion="validacion('est_sexo','texto','col-xs-12 col-sm-2')"); ?>
								<span class="help-block"></span>
      						</div>	
						</div>
					
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="est_tipoparto" type="text" class="form-group">Tipo de Parto:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_tipo_parto",$value="parto_id",$mostrar="parto_des",$nombre="est_tipoparto",$sql="",$funcion="validacion('est_tipoparto','texto','col-xs-12 col-sm-3')"); ?>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-5">
							<div class="form-group" >
								<label for="est_email" type="text" class="form-group" >E-Mail:</label>
								<input id="est_email" name="est_email" type="text" class="form-control" maxlength="100" onkeyup="validacion('est_email','email','col-xs-12 col-sm-5'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
					</div>	
<!--				</form>		 -->
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
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_cedmad" type="text" class="form-group" >Cédula:</label>
								<input id="rep_cedmad" name="rep_cedmad" type="text" class="form-control" maxlength="8" onkeyup="validacion('rep_cedmad','numerico','col-xs-12 col-sm-2');">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_nacmad" type="text" class="form-group">Nac.</label>
								<select id='rep_nacmad' name='rep_nacmad' class='form-control' onkeyup="validacion('rep_nacmad','texto','col-xs-12 col-sm-2');">
									<option value="V"> V </option>
									<option value="E"> E </option>
									<option value="P"> P </option>
								</select>
								<span class="help-block"></span>
							 </div>
						</div>
						<div class="col-xs-12 col-sm-8">
							<div class="form-group" >
								<label for="rep_nomrepmad" type="text" class="form-group" >Nombres y Apellidos:</label>
								<input id="rep_nomrepmad" name="rep_nomrepmad" type="text" class="form-control mayuscula" maxlength="50" onkeyup="validacion('rep_nomrepmad','texto','col-xs-12 col-sm-8'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
      							<label for="rep_dirhabmad" type="text" class="form-group">Dirección de Habitación:</label>
      							<input id="rep_dirhabmad" name="rep_dirhabmad" type="text" class="form-control mayuscula" maxlength="80" onkeyup="validacion('rep_dirhabmad','texto','col-xs-12 col-sm-6'); aMays(event, this);" onblur="aMays(event, this)">
      							<!---->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_telcelmad" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="rep_telcelmad" name="rep_telcelmad" type="text" class="form-control" maxlength="11" onkeyup="validacion('rep_telcelmad','numerico','col-xs-12 col-sm-3');">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="rep_telhabrepmad" type="text" class="form-group">Telf. Habitación:</label>
								<input id="rep_telhabrepmad" name="rep_telhabrepmad" type="text" class="form-control" maxlength="11" onkeyup="validacion('rep_telhabrepmad','numerico','col-xs-12 col-sm-3');">
								<span class="help-block"></span>
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
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
      							<label for="rep_lugtrarepmad" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="rep_lugtrarepmad" name="rep_lugtrarepmad" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!--onkeyup="validacion('rep_lugtrarepmad','texto','col-xs-12 col-sm-4');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-5">
							<div class="form-group">
      							<label for="rep_dirtrarepmad" type="text" class="form-group">Dirección de Trabajo:</label>
      							<input id="rep_dirtrarepmad" name="rep_dirtrarepmad" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!--onkeyup="validacion('rep_dirtrarepmad','texto','col-xs-12 col-sm-5');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="rep_teltrarepmad" type="text" class="form-group">Telf. de Trabajo:</label>
								<input id="rep_teltrarepmad" name="rep_teltrarepmad" type="text" class="form-control" maxlength="11">
								<span class="help-block"></span>
							</div>
						</div>	
						<div class="col-xs-12 col-sm-5">
							<div class="form-group">
      							<label for="rep_profrepmad" type="text" class="form-group">Profesión:</label>
      							<input id="rep_profrepmad" name="rep_profrepmad" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!--onkeyup="validacion('rep_profrepmad','texto','col-xs-12 col-sm-5');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-7">
							<div class="form-group" >
								<label for="rep_emailmad" type="text" class="form-group" >E-Mail:</label>
								<input id="rep_emailmad" name="rep_emailmad" type="text" class="form-control" maxlength="150" onkeyup="validacion('rep_emailmad','email','col-xs-12 col-sm-7'); aMays(event, this)">
								<span class="help-block"></span>
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
								<input id="rep_cedpad" name="rep_cedpad" type="text" class="form-control" maxlength="8" onkeyup="validacion('rep_cedpad','numerico','col-xs-12 col-sm-2');>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_nacpad" type="text" class="form-group">Nac.</label>
								<select id='rep_nacpad' name='rep_nacpad' class='form-control' onkeyup="validacion('rep_nacpad','texto','col-xs-12 col-sm-2');">
									<option value="V"> V </option>
									<option value="E"> E </option>
									<option value="P"> P </option>
								</select>
								<span class="help-block"></span>
							 </div>
						</div>
						<div class="col-xs-12 col-sm-8">
							<div class="form-group" >
								<label for="rep_nomreppad" type="text" class="form-group" >Nombres y Apellidos:</label>
								<input id="rep_nomreppad" name="rep_nomreppad" type="text" class="form-control" maxlength="50" onkeyup="validacion('rep_nomreppad','texto','col-xs-12 col-sm-8'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div class="form-group">
      							<label for="rep_dirhabpad" type="text" class="form-group">Dirección de Habitación:</label>
      							<input id="rep_dirhabpad" name="rep_dirhabpad" type="text" class="form-control" maxlength="80" onkeyup="validacion('rep_dirhabpad','texto','col-xs-12 col-sm-6'); aMays(event, this);" onblur="aMays(event, this)">
      							<!---->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_telcelpad" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="rep_telcelpad" name="rep_telcelpad" type="text" class="form-control" maxlength="11" onkeyup="validacion('rep_telcelpad','numerico','col-xs-12 col-sm-3'); aMays(event, this);">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="rep_telhabreppad" type="text" class="form-group">Telf. Habitación:</label>
								<input id="rep_telhabreppad" name="rep_telhabreppad" type="text" class="form-control" maxlength="11" onkeyup="validacion('rep_telhabreppad','numerico','col-xs-12 col-sm-3'); aMays(event, this);">
								<span class="help-block"></span>
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
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
      							<label for="rep_lugtrareppad" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="rep_lugtrareppad" name="rep_lugtrareppad" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!-- onkeyup="validacion('rep_lugtrareppad','numerico','col-xs-12 col-sm-4');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-5">
							<div class="form-group">
      							<label for="rep_dirtrareppad" type="text" class="form-group">Dirección de Trabajo:</label>
      							<input id="rep_dirtrareppad" name="rep_dirtrareppad" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!-- onkeyup="validacion('rep_dirtrareppad','texto','col-xs-12 col-sm-5');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="rep_teltrareppad" type="text" class="form-group">Telf. de Trabajo:</label>
								<input id="rep_teltrareppad" name="rep_teltrareppad" type="text" class="form-control" maxlength="11">
								<!--onkeyup="validacion('rep_teltrareppad','numerico','col-xs-12 col-sm-3');"-->
								<span class="help-block"></span>
							</div>
						</div>	

						<div class="col-xs-12 col-sm-5">
							<div class="form-group">
      							<label for="rep_profreppad" type="text" class="form-group">Profesión:</label>
      							<input id="rep_profreppad" name="rep_profreppad" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!--onkeyup="validacion('rep_profreppad','texto','col-xs-12 col-sm-5');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-7">
							<div class="form-group" >
								<label for="rep_emailpad" type="text" class="form-group" >E-Mail:</label>
								<input id="rep_emailpad" name="rep_emailpad" type="text" class="form-control" maxlength="150" onkeyup="validacion('rep_emailpad','email','col-xs-12 col-sm-7'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
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
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="rep_ced" type="text" class="form-group" >Cédula:</label>
								<input id="rep_ced" name="rep_ced" type="text" class="form-control" maxlength="8" onkeyup="validacion('rep_ced','numerico','col-xs-12 col-sm-2');">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="rep_nac" type="text" class="form-group">Nac.</label>
								<select id="rep_nac" name="rep_nac" class="form-control" onkeyup="validacion('rep_nac','numerico','col-xs-12 col-sm-2');">
									<option value="V"> V </option>
									<option value="E"> E </option>
									<option value="p"> P </option>
								</select>	 
								<span class="help-block"></span>
							 </div>
						</div>
						<div class="col-xs-12 col-sm-8">
							<div class="form-group" >
								<label for="rep_nomrep" type="text" class="form-group" >Nombres y Apellidos:</label>
								<input id="rep_nomrep" name="rep_nomrep" type="text" class="form-control" maxlength="50" onkeyup="validacion('rep_nomrep','texto','col-xs-12 col-sm-8'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
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
      							<label for="rep_dirhabrep" type="text" class="form-group">Dirección de Habitación:</label>
      							<input id="rep_dirhabrep" name="rep_dirhabrep" type="text" class="form-control" maxlength="80" onkeyup="validacion('rep_dirhabrep','texto','col-xs-12 col-sm-6'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_telcel" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="rep_telcel" name="rep_telcel" type="text" class="form-control" maxlength="11"  onkeyup="validacion('rep_telcel','numerico','col-xs-12 col-sm-3');">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="rep_telhabrep" type="text" class="form-group">Telf. Habitación:</label>
								<input id="rep_telhabrep" name="rep_telhabrep" type="text" class="form-control" maxlength="11" onkeyup="validacion('rep_telhabrep','numerico','col-xs-12 col-sm-3');">
								<span class="help-block"></span>
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
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
      							<label for="rep_lugtrarep" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="rep_lugtrarep" name="rep_lugtrarep" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!-- onkeyup="validacion('rep_lugtrarep','texto','col-xs-12 col-sm-4');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-5">
							<div class="form-group">
      							<label for="rep_dirtrarep" type="text" class="form-group">Dirección de Trabajo:</label>
								<input id="rep_dirtrarep" name="rep_dirtrarep" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
								<!-- onkeyup="validacion('rep_dirtrarep','texto','col-xs-12 col-sm-5');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="rep_teltrarep" type="text" class="form-group">Telf. de Trabajo:</label>
								<input id="rep_teltrarep" name="rep_teltrarep" type="text" class="form-control" maxlength="11">
								<!-- onkeyup="validacion('rep_teltrarep','numerico','col-xs-12 col-sm-3');"-->
								<span class="help-block"></span>
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
      							<input id="rep_profrep" name="rep_profrep" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
      							<!-- onkeyup="validacion('rep_profrep','texto','col-xs-12 col-sm-5');"-->
								<span class="help-block"></span>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-5">
							<div class="form-group" >
								<label for="rep_email" type="text" class="form-group" >E-Mail:</label>
								<input id="rep_email" name="rep_email" type="text" class="form-control" maxlength="150" onkeyup="validacion('rep_email','email','col-xs-12 col-sm-5'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>	
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
      							<label for="est_paren" type="text" class="form-group" >Parentesco:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_parentesco",$value="par_desc",$mostrar="par_desc",$nombre="est_paren",$sql="",$funcion="validacion('est_paren','texto','col-xs-12 col-sm-2')"); ?>
								<span class="help-block"></span>
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
						<div class="col-xs-12 col-sm-4">
							<div class="form-group" >
								<label for="est_callemer" type="text" class="form-group" >En caso de Emergencia llamar:</label>
								<input id="est_callemer" name="est_callemer" type="text" class="form-control" maxlength="100" onkeyup="validacion('est_callemer','texto','col-xs-12 col-sm-4'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group" >
								<label for="est_telfemer" type="text" class="form-group" >Teléfono:</label>
								<input id="est_telfemer" name="est_telfemer" type="text" class="form-control" maxlength="40" title="Puede incluir mas 1 teléfono." maxlength="40" onkeyup="validacion('est_telfemer','texto','col-xs-12 col-sm-4'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_familia" type="text" class="form-group" >¿Es Familiar?</label>
								<select id='est_familia' name='est_familia' class='form-control' onkeyup="validacion('est_familia','texto','col-xs-12 col-sm-2');">
								<option value="">Seleccione...</option>
								<option value="1">Si</option>
								<option value="0">No</option>
								</select>
								<span class="help-block"></span>	
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_grafam" type="text" class="form-group" >Grado Familiar:</label>
								<input id="est_grafam" name="est_grafam" type="text" class="form-control"  maxlength="20" onkeyup="validacion('est_grafam','texto','col-xs-12 col-sm-2'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
							</div>
						</div>
					</div>	
				</form>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_vivecon" type="text" class="form-group" >Vive con:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_con_quien_vive",$value="ss_id_con",$mostrar="ss_des_conquien",$nombre="est_vivecon",$sql="",$funcion="validacion('est_vivecon','texto','col-xs-12 col-sm-2')"); ?>
								<span class="help-block"></span>
							</div>
						</div>		
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="est_medtras" type="text" class="form-group" >Medio Transporte:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_medio_transporte",$value="ss_id_medio",$mostrar="ss_des_medio",$nombre="est_medtras",$sql="",$funcion="validacion('est_medtras','texto','col-xs-12 col-sm-2')"); ?>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="rep_vivecondes" type="text" class="form-group" >Nombre con quien Vive:</label>
								<input id="rep_vivecondes" name="rep_vivecondes" type="text" class="form-control" maxlength="10"  onkeyup="validacion('rep_vivecondes','texto','col-xs-12 col-sm-3'); aMays(event, this);" onblur="aMays(event, this)">
								<span class="help-block"></span>
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
								<label for="cli_cedrif" type="text" class="form-group" >Cédula/RIF:</label>
								<input id="cli_cedrif" name="cli_cedrif" type="text" class="form-control" maxlength="12" >
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group" >
								<label for="cli_apenom" type="text" class="form-group" >Facturar a Nombre de:</label>
								<input id="cli_apenom" name="cli_apenom" type="text" class="form-control" maxlength="55" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
								<label for="cli_direc" type="text" class="form-group">Dirección Fiscal:</label>
								<input id="cli_direc" name="cli_direc" type="text" class="form-control" maxlength="80" onkeyup="aMays(event, this);" onblur="aMays(event, this)">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cli_telf" type="text" class="form-group" >Teléfono:</label>
								<input id="cli_telf" name="cli_telf" type="text" class="form-control" maxlength="11">
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
					<div id="divmaterias" class="bg-primary text-center titulo text-uppercase">Oferta Académica</div>
				</div>	
				<div class="col-xs-12 col-sm-12" id="ofertanuevo">
					<div class="col-xs-12 col-sm-12">
						<div class="form-group">
							<label for="masec_codsec" type="text" class="form-group" >Año/Grado - Seccion:</label>
							<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_materseccion",$value="masec_codsec",$mostrar="secc_nombre",$nombre="masec_codsec",$sql="select substr(masec_codsec,3,1) as anno,masec_codsec,concat(masec_codsec,' - ',secc_nombre) as secc_nombre from eva_materseccion inner join ss_seccion_yy on masec_codsec=concat(secc_yy,secc_codigo) inner join eva_planestudio on substr(masec_codsec,1,1)=estu_letra group by masec_codsec order by estu_orden,masec_codsec",$funcion=""); ?>
							<span class="help-block"></span>
						</div>
					</div>

				</div>
				<div class="col-xs-12 col-sm-8 col-md-offset-2" id="vidtablaofer" name="vidtablaoferta">
					<table class="table table-striped table-bordered" width="100%" id="tablaoferta" name="tablaoferta">
					</table>
				</div>

			</div>
		</div>
	</div>
	<div class="container">
		<legend></legend>
	</div>

 	<div class="col-xs-12">
 		<button type="button" class="btn btn-primary col-xs-4 col-md-4 col-xs-offset-4 col-md-offset-4" id="btnguardar" name="btnguardar" title="Guardar - Imprimir">Guardar - Imprimir</button>
		<button type="button" class="btn btn-primary col-xs-4 col-md-4 col-xs-offset-4 col-md-offset-4" id="btnimprimir" name="btnimprimir" title="Imprimir" style='display:none;'>Imprimir</button>
 	</div>

	<div id="inscp" class="col-xs-12">
	</div>


	<div id="dialog_cargar" title="Cargando..." style="display:none;" align="center">
	<img src="../imagenes/cargando.gif" alt="q" width="50" height="50"></div>


	<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery.js"></script>
	<script src="../bootstrap/js/jquery.numeric.min.js"></script>
	<script src="../bootstrap/js/alertify.js"></script>
	<script src="../bootstrap/js/livevalidation.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/grid.locale-es.js"></script>
	<script type="text/javascript" src="../bootstrap/js/jqGrid-4.5.4/js/jquery.jqGrid.min.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery.ui.core.js"></script>
	<script src="../bootstrap/js/jquery-ui-1.10.3/ui/jquery-ui.js"></script>
	<script src="../bootstrap/js/jquery.validate.js"></script> <!--Plugin para Validar Formularios -->
	<script src="../bootstrap/js/messages_es.js"></script> <!--JS para cambiar mensaje de validación a español -->
	<script src="../bootstrap/js/bootstrap-datepicker.js"></script>
<!--	<script src="../bootstrap/datepicker/js/bootstrap-datepicker.js"></script> -->
	<script src="../bootstrap/js/sessvars.js"></script>
	<script src="../javascript/actualizar_datos_estudiantes.js" language="javascript" type="text/javascript"> </script>
	<script src="../javascript/principal.js" language="javascript" type="text/javascript"> </script>

</body>
</html>
<?php
}else
{
	/*
	echo "<script>
			alert('El usuario no tiene privilegios para acceder a esta pagina');
			location.replace('../index.php');
			</script>";
			*/
}
?>
