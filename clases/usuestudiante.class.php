<?php
require_once("seguridad.class.php");
class estudiante extends seguridad //extends es para heredar las cosas de otra clase (Hay que hacer un require once de esa clase que deseamos heredar)
{
	function modificar_usuestudiante($conexion,$usu_cod,$usu_nombre,$usu_contrasena,$usu_email)
	{
		$sql="update eva_usuarios set usu_nombre='$usu_nombre',usu_contrasena='$usu_contrasena',usu_email='$usu_email' where usu_cod='$usu_cod'"; //fk_mod va sin comillas porque es numerico
		//echo $sql;
		$ok=mysql_query($sql,$conexion);
		return $ok;
	}

	function buscar_usuestudiante($conexion,$usu_cod)
		{
			$sql="select * from eva_usuarios where usu_cod='$usu_cod'";
			//echo $sql;
			$ok=mysql_query($sql,$conexion);
			$datos=mysql_fetch_assoc($ok);
			if($datos["usu_cod"]==$usu_cod)
				echo "$datos[usu_nombre]#$datos[usu_contrasena]#$datos[usu_email]";
			else
				echo "no Encontrado";
		}	

	}

?>