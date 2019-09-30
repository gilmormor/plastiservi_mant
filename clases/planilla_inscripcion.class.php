<?php
//session_start();
//require_once("../dompdf/dompdf_config.inc.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
require("../../MPDF57/mpdf.php");
$conexion=Db::getInstance();/*instancia la clase*/


//$ced_est=$_POST['ced_estudiante'];
$ced_est=$_REQUEST['ced_estudiante'];
//$ced_est='1081150432';
//$aux_lapso=$_POST['aux_lapso'];
$aux_lapso=$_REQUEST['aux_lapso'];
//$aux_lapso = '2017-18';

//Variable de como descargar planilla: Descargar o ver en pantalla
$aux_resplan=$_REQUEST['aux_resplan'];

//Objeto Datos Colegio
$sql = "select * from eva_datoscolegio;";
$objDC=$conexion->objeto($sql);

//Objeto Filtros
$sql = "select * from eva_filtros;";
$objFiltro=$conexion->objeto($sql);

//Objeto Datos Representante
$sql = "select eva_representantes.* 
from eva_repest 
inner join eva_representantes
on rep_cedrep=rep_ced
where rep_cedalum='$ced_est';";
$objRep=$conexion->objeto($sql);

//Objeto Datos Madre
$sql = "select eva_representantes.* 
from eva_repest 
inner join eva_representantes
on rep_cedmad=rep_ced
where rep_cedalum='$ced_est';";
$objMadre=$conexion->objeto($sql);

//Objeto Datos Madre
$sql = "select eva_representantes.* 
from eva_repest 
inner join eva_representantes
on rep_cedpad=rep_ced
where rep_cedalum='$ced_est';";
$objPadre=$conexion->objeto($sql);

//Objeto Estudiantes
$sql = "select * 
from eva_estudiante inner join eva_inscripciones
on est_ced=insc_codusu
inner join eva_filtros
on insc_codlapso=fil_codlapso
inner join eva_planestudio
on insc_codcarr=estu_cod
inner join eva_matinscritas
on est_ced=ced_alum
inner join eva_codingreso
on insc_tipo=cod_cond
inner join ss_sexo
on eva_estudiante.est_sexo= cod_sex 
inner join eva_estado
on est_estnac=eva_estado.fid
inner join ss_medio_transporte
on est_medtras=ss_id_medio
inner join ss_con_quien_vive
on est_vivecon=ss_id_con
where est_ced='$ced_est' and eva_inscripciones.insc_codlapso='$aux_lapso'
group by est_ced;";
//echo $sql;
$objEst=$conexion->objeto($sql);


//Objeto Depósitos Colegio
$sql = "select est_nombres,est_apellidos,eva_repestcopy.*,eva_depositos.dep_referencia,
eva_depositos.dep_fecha,eva_depositos.dep_monto
from eva_repest as eva_repestorgi
inner join eva_repest as eva_repestcopy
on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
inner join eva_estudiante
on eva_repestcopy.rep_cedalum=eva_estudiante.est_ced
inner join eva_depositos
on eva_repestcopy.rep_cedalum = eva_depositos.dep_cedula
where eva_repestorgi.rep_cedalum='$ced_est' and eva_depositos.dep_fecha>='$objFiltro->fil_fecha_dep' 
and eva_depositos.dep_cuenta='$objFiltro->fil_numcuentacol';";
$ObjDepCol=$conexion->objeto($sql);

//Objeto Numero de Hermanos
$sql = "select eva_repestcopy.* 
from eva_repest as eva_repestorgi
inner join eva_repest as eva_repestcopy
on eva_repestorgi.rep_cedrep=eva_repestcopy.rep_cedrep
inner join eva_inscripciones
on eva_repestcopy.rep_cedalum=eva_inscripciones.insc_codusu
where eva_repestorgi.rep_cedalum='$ced_est' and eva_inscripciones.insc_codlapso='$aux_lapso';";
$NumHermanos=$conexion->filas($sql);

$hersi = '';
$herno = 'X';
if($NumHermanos > 1)
{
	$hersi = 'X';
	$herno = '';
}
$esfamiliar = 'No';
if($objEst->est_familia == 1)
{
	$esfamiliar = 'Si';
}
$aux_nuevoingreso = "";
if($objEst->insc_stanueing == 1)
{
	$aux_nuevoingreso = " - Nuevo Ingreso";
}

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
<table width='100%' border='0' align='center' id='tabla1'>
	<tr>
		<td width='15%' align='center' rowspan='3'>
			<img src='../imagenes/logo.jpg' width='100' height='100'>
		</td>
		<td width='10%' align='center' valign="top" rowspan='3'>
			<!--Foto <br>
			Estudiante <br> <br>
			3 X 3-->
		</td>
		<td align='center'>
			<?php echo substr($objDC->nomcolegio,0,24)  ?> <br>
			<?php echo substr($objDC->nomcolegio,25,50) ?> <br>
			<?php echo $objDC->convenioavec ?> <br>
			<?php echo $objDC->rif ?>
		</td>
		<td width='12%' align='center' valign="top" rowspan='3'>
			<!--Foto <br>
			Estudiante <br> <br>
			3 X 3-->
		</td>
		<td width='12%' align='center' valign="top" rowspan='3'>
			<!--Foto <br>
			Madre <br> <br>
			3 X 3	-->	
		</td>
		<td width='12%' align='center' valign="top" rowspan='3'>
			Foto <br>
			Estudiante <br> <br>
			3 X 3
		</td>
	</tr>
	<tr>
		<td align='center'>
			Teléfono: <?php echo $objDC->telefono ?> <br>
			<?php echo $objDC->localidad ?> - ESTADO <?php echo $objDC->zonaeducativa?>
		</td>

	</tr>
	<tr>
		<td align='center'>
			
		</td>

	</tr>
	<tr>
		<td align='center' colspan="5"><b>PLANILLA DE INSCRIPCIÓN <br>
		AÑO ESCOLAR <?php echo $objFiltro->fil_codlapso ?></b> <br>
		<?php echo strtoupper($objEst->ESTU_NOMBRE . ' ' . $objEst->ESTU_MENCION) ?>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td width='15%'><b><?php echo $objEst->clasif_ano?>: </b><?php echo $objEst->insc_semestre?><br><br>
		</td>
		<td width='15%'><b>Seccion: </b><?php echo substr($objEst->cod_sec,3,1)?><br><br>
		</td>
		<td width='70%'><b>Tipo de Ingreso: </b><?php echo $objEst->desc_cond . $aux_nuevoingreso?><br><br>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr><td align='center' colspan='2'><b>DATOS DEL ESTUDIANTE</b></td>
	</tr>
	<tr>
		<td colspan='2'><b>Apellidos y Nombres: </b><?php echo $objEst->est_apellidos . ', ' . $objEst->est_nombres?>
		</td>
	</tr>
	<tr>
		<td width='50%'><b>C.I./Ced. Escolar: </b><?php echo $objEst->est_nacionalidad .'-'. $objEst->est_ced?>
		</td>
		<td width='50%'>
			<b>Fecha de Nacim.: </b> <?php echo date('d/m/Y',strtotime($objEst->est_fecnac)) ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Edad: </b><?php echo calcularEdad($objEst->est_fecnac) ?> Años &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Sexo: </b> <?php echo $objEst->sex_des ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Lugar de Nacimiento:</b> <?php echo $objEst->est_lugnac ?>
		</td>
		<td>
			<b>Entidad Federal:</b> <?php echo $objEst->est_nombre?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<b>Dirección:</b> <?php echo $objRep->rep_direcalum ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Teléfono Habitación:</b> <?php echo $objRep->rep_telhabrep ?>
		</td>
		<td>
			<b>Celular: </b><?php echo $objRep->rep_telcel ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Vive con:</b> <?php echo $objEst->rep_vivecondes ?>
		</td>
		<td>
			<b>Forma de Traslado: </b><?php echo $objEst->ss_des_medio ?>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td width='35%'>
			<b>TIENE HERMANOS EN OTROS GRADOS:</b> 
		</td>
		<td width='20%'>
			<b>SI: </b><?php echo $hersi ?>
		</td>
		<td width='20%'>
			<b>NO: </b><?php echo $herno ?>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td align='center' width='50%'>
			<b>DATOS DE LA MADRE</b> 
		</td>
		<td align='center' width='50%'>
			<b>DATOS DEL PADRE</b>
		</td>
	</tr>
	<tr>
		<td>
			<b>Apellidos y Nombres:</b> <?php echo $objMadre->rep_nomrep ?>
		</td>
		<td>
			<b>Apellidos y Nombres:</b> <?php echo $objPadre->rep_nomrep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>C.I.:</b>  <?php echo $objMadre->rep_nac.'-'.$objMadre->rep_ced ?>
		</td>
		<td>
			<b>C.I.:</b> <?php echo $objPadre->rep_nac.'-'.$objPadre->rep_ced ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Ocupación:</b> <?php echo $objMadre->rep_profrep ?>
		</td>
		<td>
			<b>Ocupación:</b> <?php echo $objPadre->rep_profrep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Dirección:</b> <?php echo $objMadre->rep_dirhabrep ?>
		</td>
		<td>
			<b>Dirección:</b> <?php echo $objPadre->rep_dirhabrep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Teléfono Habitación:</b> <?php echo $objMadre->rep_telhabrep ?>
		</td>
		<td>
			<b>Teléfono Habitación:</b> <?php echo $objPadre->rep_telhabrep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Teléfono Trabajo:</b> <?php echo $objMadre->rep_teltrarep ?>
		</td>
		<td>
			<b>Teléfono Trabajo:</b> <?php echo $objPadre->rep_teltrarep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Celular:</b> <?php echo $objMadre->rep_telcel ?>
		</td>
		<td>
			<b>Celular:</b> <?php echo $objPadre->rep_telcel ?>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td colspan='2' align='center'>
			<b>DATOS DE REPRESENTANTE LEGAL</b> 
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<b>Apellidos y Nombres:</b> <?php echo $objRep->rep_nomrep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>C.I.:</b>  <?php echo $objRep->rep_nac.'-'.$objRep->rep_ced ?>
		</td>
		<td>
			<b>Parentesco con el Estudiante:</b>  <?php echo $objEst->est_paren ?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<b>Ocupación:</b> <?php echo $objRep->rep_profrep ?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<b>Dirección:</b> <?php echo $objRep->rep_dirhabrep ?>
		</td>
	</tr>
	<tr>
		<td colspan='2'>
			<b>Teléfono Habitación:</b> <?php echo $objRep->rep_telhabrep ?>
		</td>
	</tr>
	<tr>
		<td>
			<b>Teléfono Trabajo:</b> <?php echo $objRep->rep_teltrarep ?>
		</td>
		<td>
			<b>Celular:</b> <?php echo $objRep->rep_telcel ?>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td width='50%'><b>EN CASO DE EMERGENCIA LLAMAR A:</b> <?php echo $objEst->est_callemer ?></td>
		<td width='50%'><b>TELEFONO:</b> <?php echo $objEst->est_telfemer ?></td>
	</tr>
	<tr>
		<td width='50%'><b>Es Familiar?:</b> <?php echo $esfamiliar ?></td>
		<td width='50%'><b>Grado Familiar:</b> <?php echo $objEst->est_grafam ?></td>
	</tr>
	<tr>
		<td width='50%'><b>Vive con:</b> <?php echo $objEst->ss_des_conquien ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Nombre: </b> <?php echo $objEst->rep_vivecondes ?></td>
		<td width='50%'><b>Medio Transporte:</b> <?php echo $objEst->ss_des_medio ?></td>
	</tr>
</table>
<hr />
<br>
<br>
<br>
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td align='center'>______________________________________</td>
		<td align='center'>______________________________________</td>
	</tr>
	<tr>
		<td align='center'>FIRMA DEL REPRESENTANTE </td>
		<td align='center'>POR LA INSTITUCIÓN </td>
	</tr>
	<tr>
		<td colspan='2' align='right'><br><br>Fecha de Actualización: <?php echo date('d/m/Y g:i A',strtotime($objEst->insc_fechor)) ?> </td>
	</tr>
</table>


<!--'d/m/Y G:ia'-->

<?php
//echo $html;
/*
$html=utf8_decode($html);
$dompdf=new DOMPDF();	
$dompdf->load_html($html);
ini_set("memory_limit","32M");
$dompdf->render();
$dompdf->stream($cedula_estudiante.".pdf");
*/

$html = ob_get_clean(); /*Todo lo capturado lo limpiamos y lo guardamos en la variable html */
//$html =$htmlInscripcion;
$mPDF = new mPDF('UTF-8','LETTER'); /*Instanciamos un objeto de la clase mPDF, definimos los valores que queremos por defecto al constructor */
$mPDF->WriteHTML($html); /*La funcion WriteHTML crea el documento pdf con la variable html*/
$mPDF->Output("Planilla.pdf",$aux_resplan); 
//$mPDF->Output("Planilla.pdf","I"); 
/*La funcion output da la salida al pdf en la pantalla o guarda en el computador
I: Mostrar el documento en pantalla
D: Descargar el Documento 
*/


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

?>