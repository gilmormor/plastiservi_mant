<?php
require_once("../clases/conexion.class.php");/*llama a el archivo conexionnew.class.php que contiene la clase de coneccion y los metodos para manipular la BD*/
$conexion=Db::getInstance();/*instancia la clase*/


/* ahora evaluaremos Polizonte*/

$sql = "insert into eva_matinscritas(periodo,ced_alum,cot_mat,cod_carre,cod_sec,cond_materia) 
select eva_matersemestre.matersem_codlapso,eva_matersemestre.matersem_cedula,
eva_matersemestre.matersem_codmat,eva_matersemestre.matersem_codcarr,
eva_matersemestre.matersem_codsec,eva_matersemestre.matersem_condicion 
from eva_matersemestre";
echo $sql;
$ok1 = $conexion->guardar($sql);

?>