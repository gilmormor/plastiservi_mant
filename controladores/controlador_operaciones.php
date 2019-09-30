<?php
require_once("../clases/conexion.class.php");
require_once("../clases/operaciones.class.php");

$conexion=Db::getInstance();/*instancia la clase*/
$objOperaciones=new operaciones;
$objSeguridad=new seguridad;


switch($_REQUEST["accion"])
{
	case 'seleccionar_operaciones':
	$objOperaciones->seleccionar_operaciones($conexion,$_GET["correo"]); //Todo lo que venga de un hipervinculo o de ajax (javascript) debe ser llamado con GET
	break;
	case 'asignar_permisos':
	$ciclo=0;
	$error=0;
	$objSeguridad->borrar_permisos($conexion,$_POST["fk_usuario"]);
	foreach($_POST as $name => $value) // foreach es un ciclo para arrays(vectores)
	{
	if($ciclo>1)
	{
	$retorno=$objSeguridad->crear_permiso($conexion,$name,$_POST["fk_usuario"]); //mando $name como $id_ope porque le coloque un alias en linea 19

	if($retorno==false)
		{
		$error=1;
		echo "Error al crear permiso";
		} 
	 }
	 $ciclo++;
	}
	if($error==0)
		{
		echo "<script>
	      	alert('Registro guardado correctamente');
	       	parent.location.reload(); 
			</script>"; // parent.location.reload() es para refrescar la pagina de inicio 	
		}	
	break;
	case 'buscar_operacion':
		$objOperaciones->buscar_operaciones($conexion,$_GET["ide_ope"]);
	break;
}



?>