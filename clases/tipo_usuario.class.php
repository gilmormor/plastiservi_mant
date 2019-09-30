<?php
require_once("utilidades.class.php");
require_once("../clases/conexion.class.php");
class tipo_usuario extends utilidades
{
	function agregar_t_Usuario($con_bd,$for_usu,$est_tip_usu)
	{
		$sql="insert into tipo_usuario(nom_tip_usu,est_tip_usu) values ('$nom_tip_usu',$for_usu','$est_tip_usu')";
		//echo $sql;
		$ok=$con_bd->ejecutarQuery($sql);
		if($ok==true){
			echo "tipo de usuario guardado correcamente";
			}else
			{
				$this->validarErrores($con_bd,"tipo de usuario");
			}
	}
	
	function seleccionar_tipo($con_bd)
	
	{
		$sql="select * from tipo_usuario";
		$ok=$con_bd->ejecutarQuery($sql);
		
		echo "<select name='fk_tip_usu' id='fk_tip_usu' onClick='validarTipoUsuario()' class='form-control'> ";
		echo"<option>Seleccione...</option>";
		while(($datos=mysql_fetch_assoc($ok))>0)
		{
			echo"<option value='$datos[id_tip_usu]'>$datos[nom_tip_usu]</option>";
		}
		
		echo"</select>";
		
		
	}
	function modificar_fpago($con_bd,$f_pago,$est_for)
	{
		$sql="update forma_pago set nom_for='$f_pago',est_for='$est_for' where nom_for='$f_pago'";
		echo $sql;
		$ok=$con_bd->ejecutarQuery($sql,$con_bd);
		if($ok==true){
		$afectadas=mysql_affected_rows($con_bd);
		if($afectadas>0)
			$this->listar_fpago($con_bd,$f_pago);
			else
			echo "No se Modifico ninguna Forma de Pago";
		
	
	}
	
	
	}
	function borrar_fpago($con_bd,$f_pago)
	{
		$sql="delete from forma_pago where nom_for='$f_pago'";
		$afectadas=mysql_affected_rows($con_bd);
		if($afectadas>0)
		$this->listar_fpago($con_bd,"");
		else
		echo "Error al eliminar Forma de Pago";
	}
	
	function listar_fpago($con_bd,$f_pago)
	{
		$sql="select * from forma_pago";
		$ok=mysql_query($sql,$con_bd);
	
		?>
        <link href="../css/estilos.css" rel="stylesheet" type="text/css">
        <table class="formulario1" align="center"> 	
        <tr  align="center" class="titulo">
        <td>Codigo</td>
        <td>Nombre</td>
        <td>Estatus</td>
        <td>Editar</td>
        <td>Borrar</td>
        </tr>  
       <?php
	   
	   if($datos["nom_for"]=='$f_pago')
	   $clase="class='buscado'";
	   
	   else 
	   $clase="";
	   
	   while($datos=mysql_fetch_assoc($ok)>0)
	   {
		   echo '<tr  align="center" class=$clase>
        		<td>$datos[cod_for]</td>
        		<td>$datos[nom_for]</td>
        		<td>$datos[est_for]</td>
        		<td><a href="../controladores/controlador_forma_pago.php?accion=filtrar_fpago&f_pago=$datos[nom_for]"><img src="../imagenes/Iconos Facturas/editar.jpg" height=15px width=20px> </a></td>
		<td><a href="../controladores/controlador_forma_pago.php?accion=borrar_fpago&f_pago=$datos[nom_for]"><img src="../imagenes/Iconos Facturas/borrar.jpg" height=15px width=20px> </a></td>
        		</tr>';
	   }
	   echo "</table>";
	}	
	function buscar_fpago()
	{
	}
	function filtrar_fpago($con_bd,$f_pago)
	{
		$sql="select * from forma_pago where nom_for='$f_pago'";
		$ok=mysql_query($sql,$con_bd);
		$seleccion=mysql_fetch_assoc($ok);
		if($seleccion["nom_for"]=="")
			echo "Forma de Pago no Encontrada";
			else
			header("Location:../pantallas/modificar_fpago.php?nom=$f_pago&est=$seleccion[est_for]&cod=$seleccion[cod_for]");
		
	
	
	}


}




?>