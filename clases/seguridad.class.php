<?php
class seguridad 
{
/*******************************************************************************************/			
function crear_menu_bootstrap($conexion,$usuario)
 {
	$sql="select * from seguridad,operaciones,modulo where fk_usu='$usuario' and fk_ope=id_ope and  fk_mod=id_mod order by orden,orden_ope";
	$ok = $conexion->ejecutarQuery($sql);
	//$ok=mysql_query($sql,$con_bd);
	return $ok;
}

function crear_menu($con_bd,$usuario)
 {
	$sql="select * from seguridad,operaciones,modulo where fk_usu='$usuario' and fk_ope=id_ope and fk_mod=id_mod order by id_mod";
	$ok=$con_bd->ejecutarQuery($sql);

	echo "<ul>";
	$modulo="";
	while(($datos=mysql_fetch_assoc($ok))>0)
	{
		/*
		$accion=explode("_",$datos["nom_ope"]);
		$nombre=ucfirst($accion[0])." ".ucfirst($accion[1]);
		*/
		$nombre=$datos["nom_ope"];
		
		if($datos["id_mod"]!=$modulo)
		{
		  echo "<li class='titulo'>$datos[nom_mod]</li>";
 		 $modulo=$datos["id_mod"];
		}
	
		echo "<li><a href='$datos[url]' target='contenedor'>$nombre</a></li>";
	}
		echo "</ul>";
 }
/*******************************************************************************************/	
function validarAcceso($con_bd,$usuario,$operacion)
{
	if($operacion==0)
	{
		$sql="select * from seguridad where fk_usu='$usuario'";
	}
	else
	{
		$sql="select * from seguridad where fk_usu='$usuario' and fk_ope='$operacion'";
	}
	//echo $sql;
	//$ok=mysql_query($sql,$con_bd);
    //$filas=mysql_num_rows($ok);
    $filas = $con_bd->filas($sql);
    if($filas>0){
    	$ok=$con_bd->ejecutarQuery($sql);
    	$datos=mysql_fetch_assoc($ok);
		$_SESSION["staDelete"] = $datos["staDelete"];
		$_SESSION["staInsert"] = $datos["staInsert"];
		$_SESSION["staUpdate"] = $datos["staUpdate"];
    }
    if($_SESSION["statusdepositosok"] == false)
    {
    	//si el estatus viene en falso quiere decir que faltan depositos. 
    	$filas = 0;
    }
	return $filas;
}
/*******************************************************************************************/	
function buscar_permisos($con_bd,$email)
{
	$sql="select * from seguridad where fk_usu='$email'"; //Seleccionamos todas las columnas de la tabla seguridad donde fk_usu sea igual al email que me pasaron por parametro
	$ok=$con_bd->ejecutarQuery($sql);
	$i=0;
	$permisos="";
	while(($datos=mysql_fetch_assoc($ok))>0)
	{
		$permisos[$i]=$datos["fk_ope"];
		$i++;	
	}
	return $permisos;
}
/*******************************************************************************************/			
function crear_permiso($con_bd,$id_ope,$email)
{
	$sql="insert into seguridad(fk_usu,fk_ope) values('$email',$id_ope)";
	$ok=$con_bd->guardar($sql);
	return $ok;
}
/*******************************************************************************************/
function borrar_permisos($con_bd,$email)
{
	$sql="delete from seguridad where fk_usu='$email'";
	$ok=$con_bd->eliminar($sql);
	
}
/*******************************************************************************************/			

function devolverPermisosPantalla()
{
	//Estos permisos ya fueron almacenados en la funcion validarAcceso() que se ejecuta en todas las pantallas al inicio
	//Esta funcion la voy a llamar desde principal.js que lo llamo en todas las pantallas
	$respuesta = array();
	$respuesta['staDelete'] = $_SESSION["staDelete"];
	$respuesta['staInsert'] = $_SESSION["staInsert"];
	$respuesta['staUpdate'] = $_SESSION["staUpdate"];
 	echo json_encode($respuesta);
}

}
?>