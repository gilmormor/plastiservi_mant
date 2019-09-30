<?php
//session_start();
//require_once("../dompdf/dompdf_config.inc.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
require("../../MPDF57/mpdf.php");
$conexion=Db::getInstance();/*instancia la clase*/

//-------------------------------------------------------------------
	// variables recibidas
$cedula=$_REQUEST['ced_estudiante'];
$aux_resplan=$_REQUEST['aux_resplan'];
$sql="select concat(est_nombres, ' ',est_apellidos) as nombre_estudiante,
est_fecnac as fecha_nacimiento, est_nacionalidad as letra,
(SELECT DATE_FORMAT(fecha_nacimiento, '%W')) as dia_nacimiento_letra,
(SELECT DATE_FORMAT(fecha_nacimiento, '%d')) as dia_nacimeinto_nro,
(SELECT DATE_FORMAT(fecha_nacimiento, '%M')) as mes_nacimiento_letra,
(SELECT DATE_FORMAT(fecha_nacimiento, '%m')) as mes_nro_nacimiento,
(SELECT DATE_FORMAT(fecha_nacimiento, '%Y')) as anno_nacimiento_nro,
(SELECT DATE_FORMAT((select now()), '%m')) as mes_nro_actual,
(SELECT DATE_FORMAT((select now()), '%d')) as dia_nro_actual,
(SELECT DATE_FORMAT((select now()), '%M')) as mes_actual_letra,
(SELECT DATE_FORMAT((select now()), '%Y')) as yy_actual_nro,
(select year((select now()))- year((fecha_nacimiento))) edad,
(select nomdirector from eva_datoscolegio) as nombre_director,
(select ceddirector from eva_datoscolegio) as cedula_director,
(select codigodea from eva_datoscolegio) as nro_plantel,
(select nomcolegio from eva_datoscolegio) as nombre_inst,
(select direccion from eva_datoscolegio) as direccion_cole,
(select telefono from eva_datoscolegio) as telf_cole,
(select email from eva_datoscolegio) as email_cole,
(select rif from eva_datoscolegio) as rif,
(select localidad from eva_datoscolegio) as localidad,
(select municipio from eva_datoscolegio) as municipio,
(select zonaeducativa from eva_datoscolegio) as zonaeducativa,
(select fil_codlapso from eva_filtros) as lapso,
(select insc_codcarr from eva_inscripciones where insc_codusu =$cedula) as cod_carrera,
(select insc_semestre from eva_inscripciones where insc_codusu =$cedula) as semestre,
(select ESTU_NOMBRE from eva_planestudio
where eva_planestudio.fid=cod_carrera) as plan_estudio,
(select rep_cedrep from eva_repest where rep_cedalum=$cedula) as cedula_rep,
(select rep_nomrep from eva_representantes where rep_ced=cedula_rep) as nombre_rep,
(select rep_nac from eva_representantes where rep_ced=cedula_rep) as nacio
from eva_estudiante where est_ced=$cedula";
	$objeto=$conexion->objeto($sql);
	$nombre_estudiante=$objeto->nombre_estudiante;
	$letra=$objeto->letra;
	$dia_nacimiento_letra=$objeto->dia_nacimiento_letra;
	$dia_nacimeinto_nro=$objeto->dia_nacimeinto_nro;
	$mes_nacimiento_letra=$objeto->mes_nacimiento_letra;
	$mes_nro_nacimiento=$objeto->mes_nro_nacimiento;
	$anno_nacimiento_nro=$objeto->anno_nacimiento_nro;
	$mes_nro_actual=$objeto->mes_nro_actual;
	$dia_nro_actual=$objeto->dia_nro_actual;
	$mes_actual_letra=$objeto->mes_actual_letra;	
	$yy_actual_nro=$objeto->yy_actual_nro;
	$edad=$objeto->edad;
	$nombre_director=$objeto->nombre_director;
	$cedula_director=$objeto->cedula_director;
	$nro_plantel=$objeto->nro_plantel;
	$nombre_inst=$objeto->nombre_inst;
	
	$direccion_cole=$objeto->direccion_cole;
	$telf_cole=$objeto->telf_cole;
	$email_cole=$objeto->email_cole;
	$rif=$objeto->rif;
	
	$lapso=$objeto->lapso;
	$cod_carrera=$objeto->cod_carrera;
	$semestre=$objeto->semestre;
	$plan_estudio=$objeto->plan_estudio;
	$cedula_rep=$objeto->cedula_rep;
	$nombre_rep=$objeto->nombre_rep;
	$nacio=$objeto->nacio;
	switch ($mes_nacimiento_letra)
	{
		case 'January':
			$mes_nacimiento_letra='Enero';
			break;
		case 'February':
			$mes_nacimiento_letra='Febrero';
			break;
		case 'March':
			$mes_nacimiento_letra='Marzo';
			break;
		case 'April':
			$mes_nacimiento_letra='Abril';
			break;
		case 'May':
			$mes_nacimiento_letra='Mayo';
			break;
		case 'June':
			$mes_nacimiento_letra='Junio';
			break;
		case 'July':
			$mes_nacimiento_letra='Julio';
			break;
		case 'August':
			$mes_nacimiento_letra='Agosto';
			break;	
		case 'September':
			$mes_nacimiento_letra='Septiembre';
			break;
		case 'October':
			$mes_nacimiento_letra='Octubre';
			break;
		case 'November':
			$mes_nacimiento_letra='Noviembre';
			break;	
		case 'December':
			$mes_nacimiento_letra='Diciembre';
			break;	
	}
	//----------------------------------------------
	switch ($mes_actual_letra)
	{
		case 'January':
			$mes_actual_letra='Enero';
			break;
		case 'February':
			$mes_actual_letra='Febrero';
			break;
		case 'March':
			$mes_actual_letra='Marzo';
			break;
		case 'April':
			$mes_actual_letra='Abril';
			break;
		case 'May':
			$mes_actual_letra='Mayo';
			break;
		case 'June':
			$mes_actual_letra='Junio';
			break;
		case 'July':
			$mes_actual_letra='Julio';
			break;
		case 'August':
			$mes_actual_letra='Agosto';
			break;	
		case 'September':
			$mes_actual_letra='Septiembre';
			break;
		case 'October':
			$mes_actual_letra='Octubre';
			break;
		case 'November':
			$mes_actual_letra='Noviembre';
			break;	
		case 'December':
			$mes_actual_letra='Diciembre';
			break;	
	}
//-------------------------------------------------------------------	
	if ($mes_nro_nacimiento>$mes_nro_actual) // si el mes del nacimiento es mayor al actual
	{
		// no pasa nada, ya cumplio años
	}
	else if ($mes_nro_actual < $mes_nro_nacimiento) // si el mes del nacimiento es menor al actual
	{
		// se resta 1 a la edad
		$edad=$edad-1;
	}
	else if ($mes_nro_actual==$mes_nro_nacimiento) // si esta en el mismo mes
	{
		if ($dia_nacimeinto_nro>$dia_nro_actual)// si el dia de nacimiento es mayor al acutal
		{
			// no  a cumplido años se resta 1 a la edad
			$edad=$edad-1;
		}
		else if ($dia_nro_actual>$dia_nacimeinto_nro) // si el dia de nacimento es menor al actual
		{
			// no pasa nada ya cumplio años
		}
	}
//-------------------------------------------------------------------
	if ($cod_carrera==3)
	{
		$nivel ='Nivel';
	}
	else if ($cod_carrera==4)
	{
		$nivel ='Grado';
	}
	else if ($cod_carrera==5)
	{
		$nivel ='Año';
	}
//-------------------------------------------------------------------

ob_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<style type='text/css'>
#tabla1 {
	
	left: 0px;
	top: 50px;	
	z-index: 1;
	font-size:10px;
}
</style>
</head>
<body>
	<div  id='titulo' style='width:740px; float:left; padding:0;'>	
	<table width='100%' border='0'>
	<tr>
		<td width='30%'>
			<div id='logo' style='width:100%; text-align:center;'>
				<img src='../imagenes/logo.jpg' width='100' height='100'>
			</div>
		</td>
		<td width='70%'>
			<div style='width:100%; text-align:left;'>
				<p><?php echo $nombre_inst ?></p> 
				<p>Inscrito en el Ministerio del Poder Popular para la Educación</p>
				<p><?php echo $objeto->localidad.' - Municipio '.$objeto->municipio.' - Estado'.$objeto->zonaeducativa ?></p>
			</div>
		</td>
	</tr>
	</table>
	</div>
	<div id='cuerpo'  style='width:740px; text-align:center;'>
		<div style='width:100%; text-align:center;'>
			<br>
			<h2> BOLETA DE RETIRO</h2> 
			<br>
			<p style='line-height: 2.1;  text-indent: 5em; text-align:justify;'> 
				Quien suscribe, <?php echo $nombre_director ?> ,titular de la cédula
				de identidad N° <?php echo $cedula_director ?>,
			
				en su carácter de Directora de la
				<?php echo $nombre_inst ?>,
				
				 Código de Plantel 
				<?php echo $nro_plantel ?>, certifica que el estudiante: <strong><?php echo $nombre_estudiante ?></strong>.
			
				Natural de SAN CRISTOBAL, Estado táchira con
				fecha de nacimiento <?php echo $dia_nacimeinto_nro ?> de <?php echo $mes_nacimiento_letra ?> 
				de <?php echo $anno_nacimiento_nro ?>,
			
			 	de <?php echo $edad ?> años de edad. Con cédula de identidad : <?php echo $letra ?>-<?php echo $cedula ?>,
				
				fue inscrito en esta 
				Institución en el <?php echo $semestre ?>° <?php echo $nivel ?> de <?php echo $plan_estudio ?>,
				
				durante el año escolar <?php echo $lapso ?>.
			
				Y hoy se RETIRA por solicitud de su
				representante <?php echo $nombre_rep ?>,
			
				cédula de identidad <?php echo $nacio ?> - <?php echo $cedula_rep ?>, 
				según la siguiente causa legal: <strong>RETIRO VOLUNTARIO</strong>.	
			
				Constancia que se expide a petición de la parte interesada para
				fines legales
			
				 y se firma en <?php echo $objeto->localidad ?>, el <?php echo $dia_nro_actual ?> 
				 de <?php echo $mes_actual_letra ?> de <?php echo $yy_actual_nro ?>.
			</p>
			<br/><br/><br/><br/><br/><br/>
			__________________________________________
			<p style='float:right;'>
				<?php echo $nombre_director ?> .<br/> Directora
			</p>
		</div>
	</div>
	<br/><br/><br/><br/><br/>
	<div id='pie_pag' style='width:740px; text-align:center;'>
		<table width='100%' border='0'>
		  <tr>
			<td width='50%'><?php echo $direccion_cole ?></td>
			<td width='50%'> Teléfono: <?php echo $telf_cole ?> </td>
		  </tr>
		  <tr>
		  	<td>&nbsp;</td>
			<td width='50%'> Correo: <?php echo $email_cole ?> </td>
		  </tr>
		  <tr>
		  	<td>&nbsp;</td>
			<td width='50%'> Rif: <?php echo $rif ?></td>
		  </tr>
		</table>
	</div>
</body>
</html>

<?php

$html = ob_get_clean(); /*Todo lo capturado lo limpiamos y lo guardamos en la variable html */
//$html =$htmlInscripcion;
$mPDF = new mPDF('UTF-8','LETTER'); /*Instanciamos un objeto de la clase mPDF, definimos los valores que queremos por defecto al constructor */
$mPDF->WriteHTML($html); /*La funcion WriteHTML crea el documento pdf con la variable html*/
$mPDF->Output("ConstanciaRetiro.pdf",$aux_resplan); 
//$mPDF->Output("Planilla.pdf","I"); 
/*La funcion output da la salida al pdf en la pantalla o guarda en el computador
I: Mostrar el documento en pantalla
D: Descargar el Documento 
*/
?>