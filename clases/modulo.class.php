<?php
class opciones
{
	function agregar_opciones($conexion,$nom_mod,$est_mod)
	{
		$sql="insert into modulo(nom_mod,est_mod) values('$nom_mod','$est_mod')"; //fk_mod va sin comillas porque es numerico
		//echo $sql;
		$ok=$conexion->guardar($sql);
		return $ok;
	}
}

?>