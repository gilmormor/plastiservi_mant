<?php
//session_start();
//require_once("../dompdf/dompdf_config.inc.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
require("../../MPDF57/mpdf.php");
$conexion=Db::getInstance();/*instancia la clase*/

$aux_lapso=$_REQUEST['aux_lapso'];
$aux_lapso = '2018-19';

//Variable de como descargar planilla: Descargar o ver en pantalla
$aux_resplan=$_REQUEST['aux_resplan'];
$aux_resplan = "I";


//Objeto Datos Colegio
$sql = "select * from eva_datoscolegio;";
$objDC=$conexion->objeto($sql);



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
	font-size:14px;
}
</style>
<table width='100%' border='0' align='center' id='tabla1'>
	<tr>
		<td width='15%' align='center' rowspan='3'>
			<img src='../imagenes/logo.jpg' width='100' height='100'>
		</td>
		<td align='center' colspan="3">
			<?php echo substr($objDC->nomcolegio,0,24)  ?> <br>
			<?php echo substr($objDC->nomcolegio,25,50) ?> <br>
			<?php echo $objDC->convenioavec ?> <br>
			<?php echo $objDC->rif ?>
		</td>
	</tr>
	<tr>
		<td align='center' colspan="3">
			Teléfono: <?php echo $objDC->telefono ?> <br>
			<?php echo $objDC->localidad ?> - ESTADO <?php echo $objDC->zonaeducativa?>
		</td>

	</tr>
	<tr>
		<td align='center' colspan="3">
			
		</td>

	</tr>
	<tr>
		<td align='center'>
			
		</td>
		<td align='center' colspan="3"><b>Reporte de Inscritos <br>
		AÑO ESCOLAR <?php echo $aux_lapso ?></b> <br>
		</td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<tr>
		<td width='5%'><b>Num.</b></td>
		<td width='10%'><b>Cedula</b></td>	
		<td width='35%'><b>Nombre y Apellido</b></td>
		<td width='10%'><b>Seccion</b></td>
		<td width='40%'><b>Usuario</b></td>
		<td width='5%'><b>Clave</b></td>
	</tr>
</table>
<hr />
<table width='100%' border='0' id='tabla1'>
	<?php
		//Objeto Datos Inscripcion
		$sql = "select * 
		from eva_inscripciones inner join eva_estudiante
		on eva_inscripciones.insc_codusu=eva_estudiante.est_ced
		inner join eva_matinscritas
		on eva_inscripciones.insc_codusu=eva_matinscritas.ced_alum
		left join usuario
		on eva_estudiante.est_ced=usuario.ced_usu
		inner join eva_planestudio
		on eva_inscripciones.insc_codcarr=eva_planestudio.estu_cod
		where eva_inscripciones.insc_codlapso='$aux_lapso'
		group by eva_inscripciones.insc_codusu
		order by eva_planestudio.estu_orden,cod_sec,est_nombres,est_apellidos;";

		$ok=$conexion->ejecutarQuery($sql);
		$i = 0;
		//while ($datos = mysql_fetch_object($resultado))
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
			$i = $i + 1;
			$est_ced         = $datos["est_ced"];
			$est_nombres     = $datos["est_nombres"];
			$est_apellidos   = $datos["est_apellidos"];
			$cod_sec         = $datos["cod_sec"];
			$ema_usu         = $datos["ema_usu"];
			$clave           = $datos["clave"];
	?>
	<tr>
		<td width='5%'><b><?php echo $i ?>
		</td>
		<td width='10%'><?php echo $est_ced ?>
		</td>
		<td width='35%'><?php echo $est_nombres ." ". $est_apellidos ?>
		</td>
		<td width='10%'><?php echo $cod_sec ?>
		</td>
		<td width='40%'><?php echo $ema_usu ?>
		</td>
		<td width='5%'><?php echo $clave ?>

		</td>
	</tr>
		<?php
			}
		?>
</table>
<hr />
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

//$html = ob_get_clean(); /*Todo lo capturado lo limpiamos y lo guardamos en la variable html */
//$mPDF = new mPDF('UTF-8','LETTER'); /*Instanciamos un objeto de la clase mPDF, definimos los valores que queremos por defecto al constructor */
//$mPDF->WriteHTML($html); /*La funcion WriteHTML crea el documento pdf con la variable html*/
//$mPDF->Output("Planilla.pdf",$aux_resplan); 
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