<?php
//session_start();
require_once("../dompdf/dompdf_config.inc.php");
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
require("../MPDF57/mpdf.php");
$conexion=Db::getInstance();/*instancia la clase*/



$cedula_estudiante=$_REQUEST['ced_estudiante'];
//$cedula_estudiante='29699141';

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
where insc_codusu='$cedula_estudiante' and (insc_tipo='RG' or insc_tipo='RP');";

$objeto_est=$conexion->objeto($sql);
/*datos del estudiante */

if($objeto_est->letra1ra_seccion=='0'  or $objeto_est->letra1ra_seccion=='D' ) // 7mo,8vo, 9no
{
	//$("#tit_anno").html("Año:"); 
	$anno_grado_nivel="Año:";
}
if($objeto_est->letra1ra_seccion=='P'  ) // primaria
{
	//$("#tit_anno").html("Grado:"); 
	$anno_grado_nivel="Grado:";
}
if($objeto_est->letra1ra_seccion=='M'   ) //maternal
{
	//$("#tit_anno").html("Nivel:"); 
	$anno_grado_nivel="Nivel:";
}
//anno escolar
$lapso_escolar = $objeto_est->insc_codlapso;
//grado o año escolar 
$grado = $objeto_est->anno;
//sección

$seccion = $objeto_est->seccion;
//plan de estudio
$plan_estudio = $objeto_est->plan_estudio;
//Tipo de ingreso
$tipo_ingreso = $objeto_est->condinsc;
//nombre y apellido del alumno
$nom_ape_alum = ($objeto_est->est_apellidos+' '+$objeto_est->est_nombres);
//cedula estudiante
//ced_estudiante = sessvars.cedula;
//edad
$edad_est = calcularEdad($objeto_est->est_fecnac) + ' Años';
$hermano_no = "X";
$hermano_si = "";
$parentesco_repre = $objeto_est->est_paren;
$cond_ingreso =  $objeto_est->condinsc;



//$anno_grado_nivel=$_REQUEST['anno_grado_nivel'];// se recibe de la web
/*$lapso_escolar=$_REQUEST['lapso_escolar'];
if ($lapso_escolar==null)
{
	$lapso_escolar =$objeto_consult->lapso_escolar;
}*/
/*
$lapso_escolar =$objeto_est->lapso_escolar;
$grado=$_REQUEST['grado']; // se recibe de la web
$seccion=substr($_REQUEST['seccion'],3,1); // se recibe de la web
$plan_estudio=$_REQUEST['plan_estudio'];// se recibe de la web
$tipo_ingreso=$_REQUEST['tipo_ingreso']; // se recibe de la web
$edad_est=$_REQUEST['edad_est']; // se recibe de la web
$hermano_no=$_REQUEST['hermano_no'];
$hermano_si=$_REQUEST['hermano_si'];
$parentesco_repre=$_REQUEST['parentesco_repre']; //LLEGA DE LA WEB

$cond_ingreso=$_REQUEST['cond_ingreso'];// llega de la web

*/





$consult="select 
(select concat(est_apellidos,' ',est_nombres)
 from eva_estudiante 
 where est_ced='$cedula_estudiante')as nom_ape_alum , 
 (select insc_fechor from eva_inscripciones where insc_codusu='$cedula_estudiante') as fec_horainsc,
est_ced, est_edocivil, est_fecnac as fec_nac_est,
est_lugnac as lugar_nacimiento_est,
(select sex_des
from ss_sexo left join eva_estudiante
on ss_sexo.cod_sex=eva_estudiante.est_sexo
where  est_ced='$cedula_estudiante') as sexo,
(select est_nombre
from eva_estado  left join eva_estudiante
on eva_estado.fid = eva_estudiante.est_estnac
where est_ced='$cedula_estudiante') as entidad_federal_alumno,
(select parto_des 
from ss_tipo_parto left join eva_estudiante
on ss_tipo_parto.parto_id= eva_estudiante.est_tipoparto
where est_ced='$cedula_estudiante') as tipo_parto,
est_paren, 
est_callemer as nom_telf_emer,
est_familia, 
(select ss_des_medio
from ss_medio_transporte left join eva_estudiante
on ss_medio_transporte.ss_id_medio =eva_estudiante.est_medtras
where  est_ced='$cedula_estudiante' ) as forma_traslado,
(select ss_des_conquien
from ss_con_quien_vive left join eva_estudiante
on ss_con_quien_vive.ss_id_con =eva_estudiante.est_vivecon
where est_ced='$cedula_estudiante') as vive_con,
rep_vivecondes,
(select insc_semestre 
from eva_inscripciones left join eva_estudiante
on eva_inscripciones.insc_codusu =eva_estudiante.est_ced 
where est_ced='$cedula_estudiante') as semestre,
(select insc_turno 
from eva_inscripciones left join eva_estudiante
on eva_inscripciones.insc_codusu =eva_estudiante.est_ced 
where est_ced='$cedula_estudiante') as turno,
(select ss_lapso_escolar from ss_filtros) lapso_escolar,

/* datos del padre */
(select eva_representantes.rep_nomrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as nom_ape_padre,

(select eva_representantes.rep_ced
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad  =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as ced_padre,

(select eva_representantes.rep_nac
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as nacio_papa,

(select eva_representantes.rep_profrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as prof_padre,

(select eva_representantes.rep_dirhabrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as dir_hab_padre,

(select eva_representantes.rep_telhabrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_casa_padre,

(select eva_representantes.rep_teltrarep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_trab_padre,

(select eva_representantes.rep_telcel
from eva_repest left join eva_representantes 
on eva_repest.rep_cedpad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_cel_padre,

/* datos de la madre*/

(select eva_representantes.rep_nomrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as nom_ape_madre,

(select eva_representantes.rep_ced
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad  =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as ced_madre,

(select eva_representantes.rep_nac
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as nacio_mama,

(select eva_representantes.rep_profrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as prof_madre,

(select eva_representantes.rep_dirhabrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as dir_hab_madre,

(select eva_representantes.rep_telhabrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_casa_madre,

(select eva_representantes.rep_teltrarep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_trabajo_madre,

(select eva_representantes.rep_telcel
from eva_repest left join eva_representantes 
on eva_repest.rep_cedmad =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_celular_madre,


/* datos del representante */
(select eva_representantes.rep_nomrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as nom_ape_repre,

(select eva_representantes.rep_ced
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep  =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as ced_repre,

(select eva_representantes.rep_nac
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as nacio_rep,

(select eva_representantes.rep_profrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as prof_repre,

(select eva_representantes.rep_dirhabrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as dir_repre,

(select eva_representantes.rep_telhabrep
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_casa_repre,

(select eva_representantes.rep_telcel
from eva_repest left join eva_representantes 
on eva_repest.rep_cedrep =eva_representantes.rep_ced
where eva_repest.rep_cedalum='$cedula_estudiante' ) as telf_cel_repre

from eva_estudiante
where est_ced='$cedula_estudiante'";

$objeto_consult=$conexion->objeto($consult);

/*datos del estudiante */

$fec_horinsc=$objeto_consult->fec_horainsc;

$nom_ape_alum=$objeto_consult->nom_ape_alum;
$ced_estudiante=$cedula_estudiante;
$fec_nac_est=$objeto_consult->fec_nac_est;

$fec_nac_est=date("d-m-Y",strtotime($fec_nac_est));
$sexo =$objeto_consult->sexo;

$lugar_nacimiento_est =$objeto_consult->lugar_nacimiento_est;
$entidad_federal_alumno =$objeto_consult->entidad_federal_alumno;
$vive_con =$objeto_consult->vive_con;
$forma_traslado =$objeto_consult->forma_traslado;
$direc_est =$objeto_consult->dir_repre;

//$direc_est='en la casa';
$telf_hogar_est =$objeto_consult->telf_casa_repre;
$telf_celular_est =$objeto_consult->telf_cel_repre;
/*datos de la madre*/
$nom_ape_madre =$objeto_consult->nom_ape_madre;
//---------------------------------------------------
// cortar dirrecion de habitacion madre
if(strlen($nom_ape_madre)>30)
{
	$nom_aux_madre=substr($nom_ape_madre,0,30);
	$nom_aux_madre2="<br>".substr($nom_ape_madre,30,strlen($nom_ape_madre))."</br>";
	$nom_ape_madre=$nom_aux_madre;
	$nom_ape_madre2=$nom_aux_madre2;
}
else
{
	$nom_ape_madre2="";
}
//---------------------------------------------------
$ced_madre =$objeto_consult->ced_madre;
$prof_madre =$objeto_consult->prof_madre;
$dir_hab_madre =$objeto_consult->dir_hab_madre;
// cortar profesion madre
if(strlen($prof_madre)>30)
{
	$pro_aux_madre=substr($prof_madre,0,30);
	$pro_aux_madre2="<br>".substr($prof_madre,30,strlen($prof_madre))."</br>";
	$prof_madre=$pro_aux_madre;
	$prof_madre2=$pro_aux_madre2;
}
else
{
	$prof_madre2="";
}
//$dir_hab_madre ='direc madre';
$telf_casa_madre =$objeto_consult->telf_casa_madre;
$telf_trabajo_madre =$objeto_consult->telf_trabajo_madre;
$telf_celular_madre =$objeto_consult->telf_celular_madre;
//---------------------------------------------
				/*datos del padre */
$nom_ape_padre =$objeto_consult->nom_ape_padre;
//---------------------------------------------
if(strlen($nom_ape_padre)>30)
{
	$nom_aux_padre=substr($nom_ape_padre,0,30);
	$nom_aux_padre2="<br>".substr($nom_ape_padre,30,strlen($nom_ape_padre))."</br>";
	$nom_ape_padre=$nom_aux_padre;
	$nom_ape_padre2=$nom_aux_padre2;
}
else
{
	$nom_ape_padre2="";
}
//---------------------------------------------
$ced_padre =$objeto_consult->ced_padre;
$prof_padre =$objeto_consult->prof_padre;
//$dir_hab_padre ='direc padre';
$dir_hab_padre =$objeto_consult->dir_hab_padre;
//---------------------------------------------------
// cortar dirrecion del padre
if(strlen($dir_hab_padre)>60)
{
	$dir_aux_padre=substr($dir_hab_padre,0,30);
	$dir_aux_padre2="<br>".substr($dir_hab_padre,30,30)."</br>";
	$dir_aux_padre3="<br>".substr($dir_hab_padre,60,strlen($dir_hab_padre))."</br>";
	$dir_hab_padre=$dir_aux_padre;
	$dir_hab_padre2=$dir_aux_padre2;
	$dir_hab_padre3=$dir_aux_padre3;
}
else
{	
	if(strlen($dir_hab_padre)>=30 && strlen($dir_hab_padre)<=60)
	{
		$dir_aux_padre=substr($dir_hab_padre,0,30);
		$dir_aux_padre2="<br>".substr($dir_hab_padre,30,30)."</br>";
		
		$dir_hab_padre=$dir_aux_padre;
		$dir_hab_padre2=$dir_aux_padre2;
		
	}
	else
	{
		$dir_hab_padre2="";
		$dir_hab_padre3="";
	}
}
if(strlen($dir_hab_madre)>60)
{
	$dir_aux_madre=substr($dir_hab_madre,0,30);
	$dir_aux_madre2="<br>".substr($dir_hab_madre,30,30)."</br>";
	$dir_aux_madre3="<br>".substr($dir_hab_madre,60,strlen($dir_hab_madre))."</br>";
	$dir_hab_madre=$dir_aux_madre;
	$dir_hab_madre2=$dir_aux_madre2;
	$dir_hab_madre3=$dir_aux_madre3;
	//echo "dir1=".$dir_hab_madre;
	//echo "\ndir2=".$dir_hab_madre2;
	//echo "\ndir3=".$dir_hab_madre3;
}
else
{	

	if(strlen($dir_hab_madre)>=30 && strlen($dir_hab_madre)<=60)
	{
		$dir_aux_madre=substr($dir_hab_madre,0,30);
		$dir_aux_madre2="<br>".substr($dir_hab_madre,30,30)."</br>";
		
		$dir_hab_madre=$dir_aux_madre;
		$dir_hab_madre2=$dir_aux_madre2;
		
	}
	else
	{
		$dir_hab_madre2="";
		$dir_hab_madre3="";
	}
}
//---------------------------------------------------
// cortar profesion padre
if(strlen($prof_padre)>30)
{
	$pro_aux_padre=substr($prof_padre,0,30);
	$pro_aux_padre2="<br>".substr($prof_padre,30,strlen($prof_padre))."</br>";
	$prof_padre=$pro_aux_padre;
	$prof_padre2=$pro_aux_padre2;
}
else
{
	$prof_padre2="";
}
//---------------------------------------------
$telf_casa_padre =$objeto_consult->telf_casa_padre;
$telf_trab_padre =$objeto_consult->telf_trab_padre;
$telf_cel_padre =$objeto_consult->telf_cel_padre;
		/* datos del representante */
$nom_ape_repre =$objeto_consult->nom_ape_repre;
//---------------------------------------------------
// cortar el nombre del representante
if(strlen($nom_ape_repre)>30)
{
	$nom_aux_repre=substr($nom_ape_repre,0,30);
	$nom_aux_repre2="<br>".substr($nom_ape_repre,30,strlen($nom_ape_repre))."</br>";
	$nom_ape_repre=$nom_aux_repre;
	$nom_ape_repre2=$nom_aux_repre2;
}
else
{
	$nom_ape_repre2="";
}
//---------------------------------------------------
$ced_repre =$objeto_consult->ced_repre;
$prof_repre =$objeto_consult->prof_repre;
$dir_repre =$objeto_consult->dir_repre;
$telf_casa_repre =$objeto_consult->telf_casa_repre;
$telf_cel_repre =$objeto_consult->telf_cel_repre;
/* llamar en caso de emergencia*/
$nom_telf_emer =$objeto_consult->nom_telf_emer;
$cond_ingreso =$objeto_consult->cond_ingreso;
//-----------------------------------------------------------------
	// variables que llegan de la web
               //$anno_grado_nivel=$_REQUEST['anno_grado_nivel'];// se recibe de la web
/*$lapso_escolar=$_REQUEST['lapso_escolar'];
if ($lapso_escolar==null)
{
	$lapso_escolar =$objeto_consult->lapso_escolar;
}*/
              //$lapso_escolar =$objeto_consult->lapso_escolar;
              //$grado=$_REQUEST['grado']; // se recibe de la web
              //$seccion=substr($_REQUEST['seccion'],3,1); // se recibe de la web
              //$plan_estudio=$_REQUEST['plan_estudio'];// se recibe de la web
              //$tipo_ingreso=$_REQUEST['tipo_ingreso']; // se recibe de la web
              //$edad_est=$_REQUEST['edad_est']; // se recibe de la web
              //$hermano_no=$_REQUEST['hermano_no'];
              //$hermano_si=$_REQUEST['hermano_si'];
              //$parentesco_repre=$_REQUEST['parentesco_repre']; //LLEGA DE LA WEB
//-----------------------------------------------------------------
if ($ced_padre==$ced_repre)
{
	$parentesco_repre='Papa';
}
else if ($ced_madre==$ced_repre)
{
	$parentesco_repre='Mama';
}
else 
{
	$parentesco_repre='otro';
}
//-----------------------------------------------------------------
//$parentesco_repre='papa';
               //$cond_ingreso=$_REQUEST['cond_ingreso'];// llega de la web

//*************************************************
quitar_undefined($nom_ape_madre);
quitar_undefined($ced_madre);
quitar_undefined($prof_madre);
quitar_undefined($dir_hab_madre);
quitar_undefined($telf_casa_madre);
quitar_undefined($telf_trabajo_madre);
quitar_undefined($telf_celular_madre);

quitar_undefined($nom_ape_padre);
quitar_undefined($ced_padre);
quitar_undefined($prof_padre);
quitar_undefined($dir_hab_padre);
quitar_undefined($telf_casa_padre);
quitar_undefined($telf_trab_padre);
quitar_undefined($telf_cel_padre);

//DATOS DE EMERGENCIA
quitar_undefined($nom_telf_emer);
quitar_undefined($cond_ingreso);


/**************************************************/


function quitar_undefined(&$vble)
{	
	$vble2=substr($vble,0,9);
	if($vble2=="undefined")
		$vble="";
	else if ($vble2=="null")
		$vble="";
}
ob_start();
$htmlInscripcion="
<style type='text/css'>
#apDiv1 {
	
	left: 0px;
	top: 50px;	
	z-index: 1;
	font-size:10px;
}
</style>
<div id='apDiv1'>
<form id='id_formulario_oferta_academica' title='Registro de nuevos usuarios'>  
	<label id='lblnotab4'>
		<center>
		</center>
	</label>
  <table width='100%' border='0' align='center' bordercolor='#000000' class=''>
      <tr>
        <td width='95%' height='23' align='center'>
		<table width='100%' border='0' bordercolor='#666666'>
          <tr>
            <td width='20%' align='left'><img src='../imagenes/LogoColegio_3.jpg' width='100' height='100' /></td>
            <td width='40%'><table width='80%' height='100%' border='0'>
              <tr>
                <td align='center' width='15%'>UNIDAD EDUCATIVA COLEGIO </td>
              </tr>
              <tr>
                <td align='center' width='15%'> SANTA MARIANA DE JESUS </td>
              </tr>
			<tr>
                <td align='center' width='15%'>RIF. J-31070462-7 </td>
              </tr>
              <tr>
                <td align='center' width='15%'>PREGONERO - ESTADO TÁCHIRA </td>
              </tr>
            </table>              </td>
            <td width='15%'><table width='100%' height='100%' border='0' align='center'>
              <tr>
                <td align='center'>FOTO DEL ALUMNO </td>
              </tr>
              <tr>
                <td align='center'>
                  <p>3X3</p>                  </td>
              </tr>
            </table></td>
            <td width='15%'><table width='100%' height='100%' border='0' align='center'>
              <tr>
                <td align='center'>FOTO DEL REPRESENTANTE</td>
              </tr>
              <tr>
                <td align='center'>
                 
                    <p>3X3</p>                                  </td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan='7' align='center'><table width='100%' border='0'>
              <tr>
                <td align='center'><strong>PLANILLA DE INSCRIPCION </strong></td>
              </tr>
            </table></td>
            </tr>
          <tr>
            <td colspan='7'><table width='100%' border='0'>
              <tr>
                <td align='center'><label id='a'>
                <strong><strong><strong>AÑO ESCOLAR  </strong>
              <label id='lapso_escolar'>$lapso_escolar</label>
                </strong></strong>
              </label></td>
              </tr>
            </table></td>
            </tr>
        </table>        
       
      </tr>
      <tr>
        <td height='23'><table width='60%' border='0'>
          <tr>
            <td width='10%'><span class='titulos_texto'>$anno_grado_nivel<label> $grado</label></span></td>
            <td width='5%'>&nbsp;</td>
            <td width='10%'>Sección:<label> $seccion</label></td>
            <td width='5%'>&nbsp;</td>
            <td width='15%'>Tipo de Ingreso:<label> $tipo_ingreso</label></td>
            <td width='10%'><label id='tipo_ingreso'></label></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
		<table width='100%' border='0' id='oferta_academica'>
        </table>		
		----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
      </tr>
      <tr>
        <td><div align='center'>
          <table width='100%' border='0'>
            <tr>
              <td colspan='8'><div align='center'><strong>DATOS DEL ESTUDIANTE </strong></div></td>
            </tr>
            <tr>
              <td width='24%'>Apellidos y Nombres: </td>
              <td colspan='7'><label id='nom_ape_alumno'>$nom_ape_alum</label></td>
            </tr>
            <tr>
              <td>C.I/Escolar:</td>
              <td width='18%'><label id='ci'>$ced_estudiante</label></td>
              <td width='18%'>Fech. Nacimiento: </td>
              <td width='14%'><label id='fecnac'>$fec_nac_est</label></td>
              <td width='8%'>Edad:              </td>
              <td width='9%'><label id='edad'>$edad_est</label></td>
              <td width='7%'>Sexo:</td>
              <td width='2%'><label id='sexo'>$sexo</label></td>
            </tr>
            <tr>
              <td>Lugar de Nacimiento: </td>
              <td colspan='2'><label id='lug_nac'>$lugar_nacimiento_est</label></td>
              <td>Entidad Federal:</td>
              <td colspan='4'><label id='ent_fed'>$entidad_federal_alumno</label></td>
            </tr>
            <tr>
              <td width='30%'>Dirección:</td>
              <td colspan='7' width='50%'>
				<label id='direcc'>$direc_est</label>
			</td>
            </tr>
            <tr>
              <td>Telf. Hogar: </td>
              <td><label id='telf_hogar'>$telf_hogar_est</label></td>
              <td>Celular:</td>
              <td><label id='telf_celular'>$telf_celular_est</label></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Vive con: </td>
              <td><label id='vive_con'>$vive_con</label></td>
              <td>Forma de Traslado: </td>
              <td><label id='forma_traslado'>$forma_traslado</label></td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
        </div></td>
      </tr>
      <tr>
        <td>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
      </tr>
      <tr>
        <td><table width='80%' border='0'>
          <tr>
            <td colspan='4' class='tablas'><strong>TIENE HERMANO(S) EN OTRO(S) GRADO(S) </strong></td>
            <td width='16%' class='tablas'><strong><label>SI:<label id='hermano_si'>$hermano_si</label></label></strong></td>
            <td width='24%' class='tablas'><strong><label >NO:<label id='hermano_no'>$hermano_no</label></label></strong></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
      </tr>
      <tr>
        <td><table width='100%' border='0'>
          <tr>
            <td><div align='center'><strong>DATOS DE LA MADRE </strong></div></td>
            <td><div align='center'><strong>DATOS DEL PADRE </strong></div></td>
          </tr>
          <tr>
            <td><table width='100%' border='0'>
              <tr>
                <td>Apellidos y Nombres: </td>
                <td>
					<label id='nomape_madre'>$nom_ape_madre</label>
					<label id='nomape_madre'>$nom_ape_madre2</label>							
				</td>
              </tr>
              <tr>
                <td>C.I:</td>
                <td><label id='ci_madre'>$ced_madre</label></td>
              </tr>
              <tr>
                <td>Ocupación:</td>
                <td><label id='prof_madre'>$prof_madre</label>
											$prof_madre2
				</td>
              </tr>
              <tr width='30%'>
                <td width='30%'>Dirección:</td>
                <td width='50%' >
					$dir_hab_madre
					$dir_hab_madre2
					$dir_hab_madre3
				</td>
              </tr>
              <tr>
                <td>Telf. Hogar: </td>
                <td><label id='telf_casa'>$telf_casa_madre</label></td>
              </tr>
              <tr>
                <td>Telf. Trabajo: </td>
                <td><label id='telf_trabajo_madre'>$telf_trabajo_madre</label></td>
              </tr>
              <tr>
                <td>Celular:</td>
                <td><label id='cel_madre'>$telf_celular_madre</label></td>
              </tr>
            </table></td>
            <td><table width='100%' border='0'>
              <tr>
                <td>Apellidos y Nombres: </td>
                <td>
					<label id='nomape_padre'>$nom_ape_padre</label>
					<label id='nomape_padre'>$nom_ape_padre2</label>
				</td>
              </tr>
              <tr>
                <td>C.I:</td>
                <td><label id='ci_padre'>$ced_padre</label></td>
              </tr>
              <tr>
                <td>Ocupación:</td>
                <td>
					<label id='prof_padre'>$prof_padre</label>
					<label id='prof_padre'>$prof_padre2</label>					
				</td>
              </tr>
              <tr>
                <td  width='30%'>Dirección:</td>
                <td width='50%'>
					 $dir_hab_padre
					 $dir_hab_padre2
				</td>
              </tr>
              <tr>
                <td>Telf. Hogar: </td>
                <td><label id='telf_hogar_padre'>$telf_casa_padre</label></td>
              </tr>
              <tr>
                <td>Telf. Trabajo padre: </td>
                <td><label id='telff_trabajo_padre'>$telf_trab_padre</label></td>
              </tr>
              <tr>
                <td>Celular:</td>
                <td><label id='telf_celular_padre'>$telf_cel_padre</label></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
      </tr>
      <tr>
        <td><table width='100%' border='0'>
          <tr>
            <td><div align='center'><strong>DATOS DE REPRESENTANTE LEGAL </strong></div>              <div align='center'></div></td>
          </tr>
          <tr>
            <td><table width='100%' border='0'>
              <tr>
                <td width='25%'>Apellidos y Nombres: </td>
                <td colspan='4'><label id='ape_nom_repre'>$nom_ape_repre</label>
														  $nom_ape_repre2
				</td>
              </tr>
              <tr>
                <td>C.I:</td>
                <td width='12%'><label id='ci_repre'>$ced_repre</label></td>
                <td width='25%'>Parentesco con el (la) alumno(a):</td>
                <td colspan='2'><label id='parentesco_alumno'>$parentesco_repre</label></td>
              </tr>
              <tr>
                <td>Ocupación:</td>
                <td colspan='4'><label id='prof_repre'>$prof_repre</label></td>
              </tr>
              <tr>
                <td width='30%'>Dirección:</td>
                <td colspan='4' width='50%'><label id='dir_repre'>$dir_repre</label></td>
              </tr>
              <tr>
                <td>Telf. Hogar: </td>
                <td><label id='telf_hogar_repre'>$telf_casa_repre</label></td>
                <td>Celular:</td>
                <td width='17%'>$telf_cel_repre</td>
                <td width='30%'><label id='cel_repre'></label></td>
              </tr>

              <tr>
                <td colspan='5'>----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------</td>
                </tr>
              <tr>
                <td colspan='5'><strong>EN CASO DE EMERGENCIA LLAMAR A:</strong> </td>
                </tr>
              <tr>
                <td colspan='5'><label id='nom_telf_emer'>$nom_telf_emer</label></td>
                </tr>
              <tr>
                <td><strong>CONDICIÓN DE INGRESO: </strong></td>
                <td colspan='4'><label id='tipo_ingreso_otravez'>$cond_ingreso</label></td>
              </tr>
              <tr>
                <td colspan='5'><table width='100%' border='0'>
				  <tr>
                    <td align='center'></td>
                    <td align='center'>&nbsp;</td>
                  </tr>
				  <tr>
                    <td align='center'></td>
                    <td align='center'>&nbsp;</td>
                  </tr>
				  <tr>
                    <td align='center'></td>
                    <td align='center'>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align='center'>______________________________________</td>
                    <td align='center'>______________________________________</td>
                  </tr>
                  <tr>
                    <td><div align='center'>FIRMA DEL REPRESENTANTE </div></td>
                    <td><div align='center'>POR LA INSTITUCIÓN </div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
				  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>Fecha/ Hora de Inscripción: $fec_horinsc </td>
                  </tr>
                </table></td>
                </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
  </table>
</form>
</div>";


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
$html =$htmlInscripcion;
$mPDF = new mPDF('UTF-8','LETTER'); /*Instanciamos un objeto de la clase mPDF, definimos los valores que queremos por defecto al constructor */
$mPDF->WriteHTML($html); /*La funcion WriteHTML crea el documento pdf con la variable html*/
$mPDF->Output("Planilla.pdf","I"); 
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