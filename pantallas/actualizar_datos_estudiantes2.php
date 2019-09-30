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
	
	<link rel="stylesheet" href="../css/estiloss.css">
							
</head>
	
<body>
	<HEADER>
		<div class="container-FLUID text-center">
		 	<div class="jumbotron">
		    	<h2>Actualización de Datos</h2>      
		  	</div>     
		</div>
	</HEADER>
</body>
</html>
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
	<div class="container">
		<form role="form" id="datosestudiante" >
			<div class="row">
				<div class="col-xs-12 col-sm-2">
					<div class="form-group">
						<label for="cedula" type="text" class="form-group"  >Cédula</label>
						<input id="cedula" type="text" class="form-control" placeholder="12345678" autofocus>
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-1">
					<div class="form-group">
						<label for="fecha de nacimiento" type="text" class="form-group">Nac.</label>
						<select id='nacionalidad' name='lapso' class='form-control' onChange="validacion('lapso');">
							<option value="1"> V. </option>
							<option value="2"> E. </option>
							<option value="3"> P. </option>
						</select>	 
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 col-sm-offset-">
					<div class="form-group" >
						<label for="nombres" type="text" class="form-group" >Nombres:</label>
						<input id="nombres" type="text" class="form-control" placeholder="Nombres">
					</div>
				</div>
				<div class="col-xs-12 col-sm-3">
					<div class="form-group">
						<label for="apellidos" type="text" class="form-group">Apellidos:</label>
						<input id="apellidos" type="text" class="form-control" required />
						<span class="help-block"></span>
					</div>
				</div>
				<div class="col-xs-12 col-sm-2">
					<div class="form-group">
						<label for="pla_fecha" class="form-group">Fecha de nacimiento:</label>
						<input type="text" class="form-control" name="pla_fecha" id="pla_fecha" placeholder="DD/MM/AAAA" readonly onchange="validacion('pla_fecha');">
						<span class="help-block"></span>	
					</div>
				</div>
				<!--<div class="col-xs-12 col-sm-1">
					<div class="form-group">
						<label for="edad" type="text" class="form-group">Edad:</label>
						<input id="edad" type="text" class="form-control" required />
						<span class="help-block"></span>-->
					</div>
				</div>	
			</div>	
		</form>		
	</div>		
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<form role="form" class="" action="" method="post">
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="apellidos" type="text" class="form-group">Plantel de Procedencia:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_planteles",$value="fid",$mostrar="plan_nombre",$nombre="fid_plan_nombre",$sql="",$funcion=""); ?>
							</div>
						</div>	
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Lugar de Nac.</label>
								<input id="lugar_nace" type="text" class="form-control" required />
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">País:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_pais",$value="fid",$mostrar="pai_nombre",$nombre="fid_pais",$sql="",$funcion=""); ?>	 
							 
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="pais" type="text" class="form-group">Estado:</label>
								 <?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_estado",$value="fid",$mostrar="est_nombre",$nombre="fid_estado",$sql="",$funcion=""); ?>
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
								<label for="cedula" type="text" class="form-group" >Estado Civil:</label>
								 <?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_estado_civil",$value="edo_id",$mostrar="edo_des_civil",$nombre="fid_esta_civil",$sql="",$funcion=""); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="sexo" type="text" class="form-group">Sexo:</label>
      							<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_sexo",$value="sex_id",$mostrar="sex_des",$nombre="fid_sexo",$sql="",$funcion=""); ?>
      						</div>	
						</div>
					
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
								<label for="pais" type="text" class="form-group">Tipo De Parto:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_tipo_parto",$value="parto_id",$mostrar="parto_des",$nombre="fid_parto",$sql="",$funcion=""); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="email" type="text" class="form-group" >E-Mail:</label>
								<input id="e-mail" type="text" class="form-control">
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
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary text-center ">.::Datos del Padre::.</h3>
					</div>
				</div>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Cédula:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-1">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">Nac.</label>
								<select id='nacionalidad' name='lapso' class='form-control' onChange="validacion('lapso');">
									<option value="1"> V </option>
									<option value="2"> E </option>
									<option value="3"> P </option>
								</select>	 
							 </div>
						</div>
						<div class="col-xs-12 col-sm-3 col-sm-offset">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >Nombres:</label>
								<input id="nombres" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">Teléfono Habitación:</label>
								<input id="fecha de nacimiento" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="pais" type="text" class="form-group">Teléfono De Trabajo:</label>
								<input id="pais" type="text" class="form-control">
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
      							<label for="pais" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="pais" type="text" class="form-group">Dirección de Trabajo:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="sexo" type="text" class="form-group">Profesión:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="email" type="text" class="form-group" >E-Mail:</label>
								<input id="e-mail" type="text" class="form-control">
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
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary text-center ">.::Datos del Madre::.</h3>
					</div>
				</div>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Cédula:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-1">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">Nac.</label>
								<select id='nacionalidad' name='lapso' class='form-control' onChange="validacion('lapso');">
									<option value="1"> V </option>
									<option value="2"> E </option>
									<option value="3"> P </option>
								</select>	 
							 </div>
						</div>
						<div class=" col-sm-3 col-xs-12  col-sm-offset">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >Nombres:</label>
								<input id="nombres" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">Teléfono Habitación:</label>
								<input id="fecha de nacimiento" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group">
								<label for="pais" type="text" class="form-group">Teléfono De Trabajo:</label>
								<input id="pais" type="text" class="form-control">
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
      							<label for="pais" type="text" class="form-group">Lugar de Trabajo:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="pais" type="text" class="form-group">Dirección de Trabajo:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="sexo" type="text" class="form-group">Profesión:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="email" type="text" class="form-group" >E-Mail:</label>
								<input id="e-mail" type="text" class="form-control">
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
			<div class="container text-center">
				<div class="row">
					<h3 class="text-primary ">.::Datos del Representante::.</h3>
				</div>
			</div>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Cédula:</label>
								<input id="cedul" type="text" class="form-control" autofocus>
							</div>
						</div>
						<div class="col-xs-12 col-sm-1">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">Nac.</label>
								<select id='nacionalidad' name='lapso' class='form-control' onChange="validacion('lapso');">
									<option value="1"> V </option>
									<option value="2"> E </option>
									<option value="3"> P </option>
								</select>	 
							 </div>
						</div>
						<div class="col-xs-12 col-sm-3 col-sm-offset">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >Nombres:</label>
								<input id="nombres" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
					<div class="col-xs-12 col-sm-2">
						<div class="form-group">
							<label for="fecha de nacimiento" type="text" class="form-group">Teléfono Habitación:</label>
							<input id="fecha de nacimiento" type="text" class="form-control">
						</div>
						</div>
							<div class="col-xs-12 col-sm-2">
								<div class="form-group">
								<label for="pais" type="text" class="form-group">Teléfono De Trabajo:</label>
								<input id="pais" type="text" class="form-control">
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
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="comment">Dirección de Trabajo:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="comment">Parentesco:</label>
      							<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_parentesco",$value="ss_id_paren",$mostrar="ss_des_paren",$nombre="fid_parentesco",$sql="",$funcion=""); ?>
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group">
      							<label for="comment">Profesión:</label>
      							<input id="pais" type="text" class="form-control">
    						</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="email" type="text" class="form-group" >E-Mail:</label>
								<input id="e-mail" type="text" class="form-control">
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
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary ">.::Datos de Emergencia::.</h3>
					</div>
				</div>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >En caso De Emrgencia llamar:</label>
								<input id="nombres" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Teléfono:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >Grado Familiar:</label>
								<input id="nombres" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Es Familiar:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="eva_pais",$value="fid",$mostrar="pai_nombre",$nombre="fid_pais",$sql="",$funcion=""); ?>	
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Vive con:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_con_quien_vive",$value="ss_id_con",$mostrar="ss_des_conquien",$nombre="fid_conquien",$sql="",$funcion=""); ?>	
							</div>
						</div>		
					</div>	
				</form>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >Medio Transporte:</label>
								<?php $objestudiante->hacer_lista_desplegable($conexion,$tabla="ss_medio_transporte",$value="ss_id_medio",$mostrar="ss_des_medio",$nombre="fid_transporte",$sql="",$funcion=""); ?>	
							</div>
						</div>
						<div class="col-xs-12 col-sm-3">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Nombre con quien Vive:</label>
								<input id="cedul" type="text" class="form-control">
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
				<div class="container text-center">
					<div class="row">
						<h3 class="text-primary ">.::Destinatario de la Factura (En caso de no facturar el Representante)::.</h3>
					</div>
				</div>
				<form role="form" class="" action="">
					<div class="row">
						<div class="col-xs-12 col-sm-4 col-sm-offset">
							<div class="form-group" >
								<label for="nombres" type="text" class="form-group" >Facturar a Nombre de:</label>
								<input id="nombres" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Cédula:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2">
							<div class="form-group" >
								<label for="cedula" type="text" class="form-group" >Teléfono Celular:</label>
								<input id="cedul" type="text" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-4">
							<div class="form-group">
								<label for="fecha de nacimiento" type="text" class="form-group">Dirección Fiscal:</label>
								<input id="fecha de nacimiento" type="text" class="form-control">
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
				<input type="button" id="btnvalidar" class="btn pull-right btn-primary" value="Guardar">
				<!--<button type="button" class="btn pull-right btn-primary" name="btnvalidar" id="btnvalidar">Guardar</button>-->			
			</div>		
		</div>
	</div>
		
		
		
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
		<script type="text/javascript" src="../javascript/campos.js" language="javascript" type="text/javascript"></script>
		<script type="text/javascript" src="../javascript/editar_datos_estudiantes.js" language="javascript" type="text/javascript"></script>

		
		
	</body>
</html>