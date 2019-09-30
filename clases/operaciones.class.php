<?php
require_once("seguridad.class.php");
class operaciones extends seguridad //extends es para heredar las cosas de otra clase (Hay que hacer un require once de esa clase que deseamos heredar)
{

 function seleccionar_operaciones($con,$correo)
{
	echo '<link rel="stylesheet" type="text/css" href="../css/estilos.css">
		<script src="../javascript/validaciones.js" type="text/javascript" language="javascript"></script>';
	 
   $permisos=$this->buscar_permisos($con,$correo);//aqui llamamos la funcion que hicimos en seguridad (buscar_permisos)

   //$sql="select * from operaciones order by orden_ope";
	$sql="select operaciones.*,modulo.nom_mod from operaciones inner join modulo on fk_mod=id_mod order by orden,orden_ope";
   $ok=$con->ejecutarQuery($sql);

   echo "<table width='60%' align='center' class='formulario1'>";
   
   echo "<tr>
        <td colspan='3' align='right'>Seleccionar Todos <input type='checkbox' id='todos' onclick='seleccionar()'></td>
        </tr>";
   
   echo "<tr class='titulo'>
   			<td>Modulo</td>
         	<td>Operación</td>
		 	<td>Acción</td>
		 </tr>";
		 
	      
   while(($datos=mysql_fetch_assoc($ok))>0)
   {
   	/*
    $nombre=explode("_",$datos["nom_ope"]);	 
	$accion=ucfirst($nombre[0])." ".ucfirst($nombre[1]);  
	*/
	$aux_modulo=$datos["nom_mod"];
	$accion=$datos["nom_ope"];
	 
	if(@in_array($datos["id_ope"],$permisos)) //esta funcion in_array me sirve para poder buscar si un valor existe dentro del array, se coloca el arroba al principio para que no me muestre los warnings que arrojara debido a que el usuario no tiene los permisos.
	{
		$checked='checked';
	}else
	{ $checked='';
	}		
	
	$name="name='$datos[id_ope]'";
      echo "<tr>
      			<td>$aux_modulo</td>
	           <td>$accion</td>
			   <td align='center'><input type='checkbox' $checked $name></td>
	       </tr>";	   
   }
   echo"<tr><td colspan='2' align='center'><input type='submit' value='Guardar Permisos'></td></tr>";
   echo "</table>"; 	 
	 
 }
	function buscar_operaciones($conexion,$ide_ope)
	{
		$sql="select * from operaciones where id_ope='$ide_ope'";
		//echo $sql;
		//$ok=mysql_query($sql,$conexion);
		$ok=$conexion->ejecutarQuery($sql);

		$datos=mysql_fetch_assoc($ok);
		if($datos["id_ope"]==$ide_ope)
			echo "$datos[nom_ope]#$datos[url]#$datos[fk_mod]#$datos[est_ope]";
		else
			echo "no Encontrado";
	}	
	
}
?>