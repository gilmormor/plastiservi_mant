<?php
//session_start();
require_once("../clases/utilidades.class.php");
require_once("../clases/conexion.class.php");
require_once("../clases/seguridad.class.php");
require_once("../clases/tipo_usuario.class.php");
require_once("../clases/opciones.class.php");
/*Instanciamos un objeto de la clase Utilidades*/
$objUtilidades=new utilidades;
//$objConexion=new conexion;
$conexion=Db::getInstance();/*instancia la clase*/

$objSeguridad=new seguridad;
$objOpcion = new opciones;

//$conexion=$objConexion->conectar();

$nombre_script=$objUtilidades->ruta_script(); //Averigua el nombre del script que se está ejecutando
//echo $nombre_script;
$num_opc = $objOpcion->devolver_cod_opc($conexion,$nombre_script); //Devuelve el código de la opción asociada al script

$retorno=$objSeguridad->validarAcceso($conexion,$_SESSION["usuario"],$num_opc);
//$retorno = 1;
if($retorno==1)
{


?>
<!DOCTYPE html>
<html lang="es">

<head>
<title> Agregar usuario </title>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/estilos_nivel2.css">
<script src="../javascript/validaciones.js"></script>
</head>
<body>

  <form method="POST" action="../controladores/controlador_usuario.php" id="for_usu">
  <input name="accion" value="agregar_usuario" type="hidden">
    <div class="container">
      <div class="form-group separador-md">
        <div class="bg-primary text-center titulo text-uppercase">Agregar Usuario</div>
      </div>  

      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Email:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="email" class="form-control" maxlength="100" name="ema_usu" id="ema_usu" placeholder="xxxxxxx@dominio.com" onClick="validarEma()">
        </div>
      </div>

      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Clave:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="password" class="form-control" maxlength="80" placeholder="ingrese contraseña" name="cla_usu" id="cla_usu"  onClick="validarClave()">
        </div>
      </div>
      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Confirmar Clave:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="password" class="form-control" maxlength="80" placeholder="ingrese contraseña" name="con_usu" id="con_usu"  onClick="validarClave()">
        </div>
      </div>


      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Cedula:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="text" class="form-control" maxlength="100" placeholder="Ingrese la cedula" name="ced_usu" id="ced_usu"  onClick="validarClave()">
        </div>
      </div>

      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Nombre:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="text" class="form-control" maxlength="100" placeholder="Ingrese el nombre" name="nom_usu" id="nom_usu">
        </div>
      </div>

      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Apellido:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="text" class="form-control" maxlength="100" placeholder="Ingrese el apellido" name="ape_usu" id="ape_usu">
        </div>
      </div>

      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Teléfono:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="text" class="form-control" maxlength="15" placeholder="Teléfono" name="tel_usu" id="tel_usu">
        </div>
      </div>

      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="nom_ope">Tipo de usuario:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          
          <?php $objUtilidades->hacer_lista_desplegable($conexion,$tabla="tipo_usuario",$value="id_tip_usu",$mostrar="nom_tip_usu",$nombre="fk_tip_usu","",""); ?>

        </div>
      </div>
      <div class="form-group separador-md">
        <div class="col-md-3 col-sm-3">
          <label for="estado">Estatus:</label>
        </div>
        <div class="col-md-9 col-sm-9">
          <input type="radio" name="est_usu" id="est_usu" value="A" checked> Activo
          <input type="radio" name="est_usu" id="est_usu2" value="I"> Inactivo
        </div>
      </div>

      <div class="form-group text-center separador-md">
        <div class="bg-default">
          <input type="submit" value="Guardar" class="btn btn-primary" onClick="validar()">
        </div>
      </div>
    </div> 
  </form>

  <script src="../bootstrap/js/bootstrap.min.js"></script>
  <script src="../bootstrap/js/jquery-3.1.1.min.js"></script>

 </body>
</html>
<?php
}else
{
  echo "<script>
      alert('El usuario no tiene privilegios para acceder a esta pagina');
      location.replace('../index.php');
      </script>";
}
?>