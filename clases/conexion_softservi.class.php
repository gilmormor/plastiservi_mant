<?php 
session_start();
error_reporting(E_ALL & ~E_NOTICE);  
/* Clase encargada de gestionar las conexiones a la base de datos */
class Db
{
//--------------------------------------------------------

	public $servidor='190.9.42.167';
	public $usuario='softserv_softbd';
	public $password='SYSCA3125';
	public $database="softserv_softservica";

	public $linki;
	public $valor;
	public $stmt;
	public $resultado;
	public $filas;
	static $_instance;
//---------------------------------------------------------
	/*La función construct es privada para evitar que el objeto pueda ser creado mediante new*/
	public function __construct()
	{
	$this->conectar($this->servidor, $this->usuario, $this->password);
	}
	/*Evitamos el clonaje del objeto. Patrón Singleton*/
	public function __clone(){}
//---------------------------------------------------------
	
//---------------------------------------------------------
	/*Función encargada de crear, si es necesario, el objeto.  Esta es la función que debemos llamar desde 
	fuera de la clase para instanciar  el objeto, y así, poder utilizar sus métodos*/
	public static function getInstance()
	{
		if (!(self::$_instance instanceof self))
		{
			self::$_instance=new self();
		}
		return self::$_instance;
	}
//---------------------------------------------------------
	/*Realiza la conexión a la base de datos.*/
	public function conectar($servidor,$usuario,$password)
	{
		$this->valor=@mysql_connect($servidor,$usuario,$password);
		@mysql_query("SET NAMES 'utf8'"); /*arreglo problemas de los registros con acentos y ñ */
		@mysql_select_db($this->database);
		echo $valor;
	}
//--------------------------------------------------------- 
	function mysql_fetch_all($res)
	{
		$data = array();
		while ($row = @mysql_fetch_array($res))
		   $data[] = $row;
		return $data;
	} 
//---------------------------------------------------------        
   /*Método para ejecutar una sentencia sql de tipo select y retorna el arreglo con los datos*/
	public function ejecutar($sql)
	{
		$this->stmt= @mysql_query($sql,$this->valor) or die ( @mysql_error());
		$data = array();
		while ($row = @mysql_fetch_array($this->stmt))
		$data[] = $row;
		return $data;
	}
//---------------------------------------------------------        
   /*Método para ejecutar una sentencia sql de tipo select y retorna el objeto */
	public function ejecutarQuery($sql)
	{
		$this->stmt= @mysql_query($sql,$this->valor) or die ( @mysql_error());
		return $this->stmt;
		/*
		$data = array();
		while ($row = @mysql_fetch_array($this->stmt))
		$data[] = $row;
		return $data;
		*/
	}
//---------------------------------------------------------     
/*metodo que retorna el numero de filas de una consulta*/
	public function filas($sql)
	{
		$this->stmt=@mysql_query($sql,$this->valor) or die ( @mysql_error());
		$this->filas=@mysql_num_rows($this->stmt);
		return $this->filas;
	}
//---------------------------------------------------------   
	/*Metodo para ejecutar una sentencia de tipo insert o update   */
	public function guardar($sql)
	{
		$this->stmt= @mysql_query($sql,$this->valor) or die ( @mysql_error());
		//$this->resultado=mysql_affected_rows($this->stmt);
		//return $this->resultado;
		return $this->stmt;
	}
//---------------------------------------------------------   
	/*Metodo para ejecutar una sentencia de tipo insert o update   */
	public function eliminar($sql)
	{
		$this->stmt= @mysql_query($sql,$this->valor) or die ( @mysql_error());
		//$this->resultado=mysql_affected_rows($this->stmt);
		//return $this->resultado;
		return $this->stmt;
	}
//---------------------------------------------------------
	public function objeto($sql)
	{
		$this->stmt=@mysql_query($sql,$this->valor) or die ( @mysql_error());
		$this->objeto=@mysql_fetch_object($this->stmt);
		return $this->objeto;
	}
//---------------------------------------------------------	
	public function eliminar_clave_provicional($id)
	{
		$sql_eliminar="delete from control_verificacion where control_id_usuario='$id'";
		$eliminar_clave_pro=$this->guardar($sql_eliminar);
		return 1;
	}
//---------------------------------------------------------
// funcion que guarda que hace cada usuario
	public function auitoria ($nro_control,$seccion,$cod_materia,$nota,$cedula,$c)
	{
		$sql_consulta="select eva_usuarios.usu_exp as id, eva_usuarios.usu_tipo as tipo from eva_usuarios where usu_cod='$nro_control'";
		$objeto=$this->objeto($sql_consulta);
		$id=$objeto->id;
		$tipo=$objeto->tipo;
		$des='al estudainte '.$cedula.' se ingreso la nota de '.$nota.' en el cote '.$c.' en la materia '.$cod_materia.' de la seccion '.$seccion;
		$sql_insert="insert into auditoria_notas (audi_id,audi_tipo, audi_secc,audi_mat,audi_des,auid_fecha) values ('$id','$tipo','$seccion','$cod_materia','$des',(SELECT NOW()))";
		$guardar=$this->guardar($sql_insert);
		return 1;
	} 
//---------------------------------------------------------	
}
//---------------------------------------------------------------------------------------------------------------
?>