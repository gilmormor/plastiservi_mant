<?php
session_start();
class conexion{
	
	function conectar(){
		
		//$conexion=@mysql_connect("ftp.softservica.com","softserv_softbd","SYSCA3125");
		$conexion=@mysql_connect("localhost","root","");
		if($conexion==false)
			echo "Error al conectarse al servidor";	
		else{
			@mysql_query("SET NAMES 'utf8'"); /*arreglo problemas de los registros con acentos y ñ */
			$bd=mysql_select_db("softserv_academia2",$conexion);
				if($bd==false){
					echo "Error al conectarse a la base de datos";	
				}else
					return $conexion;
	
		}
			
	}
	
	function iniciar_sesion($con_bd,$usuario,$clave)
	{
		$clave=md5($clave);
		$sql="select * from usuario where ema_usu='$usuario' and cla_usu='$clave'";
		$ok=mysql_query($sql,$con_bd);
		$datos=mysql_fetch_assoc($ok);
		
		if($datos["est_usu"]=="A")
		{
		  $_SESSION["usuario"]=$usuario;
		  return true;
		}else
		 return false;
				
    }

}
?>