<?php
require("clases/conexion.class.php");
require("clases/seguridad.class.php");

$objSeguridad=new seguridad;
//$objConexion=new conexion;
//$con=$objConexion->conectar();
$conexion=Db::getInstance();/*instancia la clase*/

$operacion=0; /*Se envia cero (0) a la funcion para que solo filtre por el login de Usuario*/
$retorno=$objSeguridad->validarAcceso($conexion,@$_SESSION["usuario"],$operacion);
if ($retorno>=1)
{
?>
<!DOCTYPE html>

<html lang="es">

<head>
<title id="titulo" name="titulo">Plastiservi</title>
<meta charset="utf-8" />
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
<link rel="stylesheet" href="bootstrap_iugc/css/bootstrap-select.css">
<link rel="stylesheet" href="bootstrap_iugc/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="bootstrap_iugc/css/alertify.min.css">
<link rel="stylesheet" href="bootstrap_iugc/css/bootstrap-datepicker.min.css"/>

<link rel="stylesheet" href="css/estilos_nivel2.css">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link rel="shortcut icon" href="imagenes/logoShor.png">
</head>
<body>
<header style="display:none">
  <p class="text-header" id="titulosist" name="titulosist">Plastiservi</p>
</header>
<nav class="navbar navbar-default" role="navigation" style="margin-bottom: 1px;"  name="menu" id="menu">
  <div class="container-fluit"> <!--div ajustable -->
    <div class="navbar-header"> 
      <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#opciones"> 
        <span class="icon-bar"> </span>
        <span class="icon-bar"> </span>
        <span class="icon-bar"> </span>
      </button>
      <a href="" class="navbar-brand" style="margin-right: -170px;"><img src="imagenes/LOGO-PLASTISERVI.png" style="max-width:50%;width:auto;height:auto;"></a> 
    </div>
    <div class="collapse navbar-collapse" id="opciones">  
      <ul class="nav navbar-nav">
      <?php
        $ok=$objSeguridad->crear_menu_bootstrap($conexion,$_SESSION["usuario"]);

        $bandera=0;
        $modulo="";
        while(($datos=mysql_fetch_assoc($ok))>0)
        {

          if($modulo!=$datos["nom_mod"])
          {
            if($bandera==1)
            {
              echo "</ul>";
              echo "</li>";
            }
            echo "<li class='dropdown'><a class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>$datos[nom_mod]<span class='caret'></span></a>";
            echo "<ul class='dropdown-menu' role='menu'>";
            $bandera=1;
          }
          echo "<li><a href=$datos[url] target='central' id='op$datos[id_ope]' name='op$datos[id_ope]'>$datos[nom_ope]</a></li>";
          $modulo=$datos["nom_mod"];
        }
        echo "</ul>";
        echo "</li>";
      ?>
<!--
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Cerrar Sesion<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="index.php?salir=1">Cerrar Sesión</a></li>
          </ul>
        </li>
-->
      </ul>
    <ul class="nav navbar-nav navbar-right" id='mensaje1' name='mensaje1'>
      <li><a href="#" id='IDusuario' name='IDusuario'></a>

        <ul class='dropdown-menu' role='menu'>
          <li>
              <a href=pantallas/cambiar_claveusuario.php target='central'>Cambiar Clave  <span class='glyphicon glyphicon glyphicon-retweet'></a>
          </li>
        </ul>
      </li>

      <li><a href="index.php?salir=1"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
    </ul>
<!--
      <div id='mensaje1' name='mensaje1' style='display:none;'>
        <p id='mensaje2' style='line-height: 2.1;  text-indent: 5em; text-align:right; margin-top: 10px;'><font color="red"></font></p>-->
      </div>
    </div>
  </div>
</nav>

<div class="row" style="margin-left: 0px;margin-right: 0px;">
  <div class="centro col-md-12 col-sm-12">
  <!--<div class="centro"> -->
    <iframe src="" frameborder="0" id="central" name="central" width="100%"></iframe>
  
  </div>
</div>


  <!-- Modal -->
  <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title" id="exampleModalLongTitle" name="exampleModalLongTitle">Trabajos terminados sin Validar</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
              <div class="row" id="porValidar">
              </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"  data-toggle='tooltip' title="Cerrar">Cerrar</button>
        </div>
      </div>
    </div>
  </div>


<footer id="piepag" name="piepag">
  <p class="text-footer">Sistema Desarrollado por: Gilmer Moreno, C.A. <a href="http://www.plastiservi.cl" TARGET="_new">www.plastiservi.cl | </a><a id="mespiepag" name="mespiepag" align="right"></a></p>
</footer>




<!--
  <div id="dialog_carga" title="Cargando..." style="display:none;" align="center">
  <img src="imagenes/cargando.gif" alt="q" width="50" height="500px"></div>
-->

  <div id="dialog_carga" title="Cargando..."align="center">
  <div class="loader"></div></div>

  <script src="bootstrap/js/jquery-3.1.1.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="bootstrap/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
  <script src="bootstrap_iugc/js/jquery.dataTables.min.js"></script>
  <script src="bootstrap_iugc/js/dataTables.bootstrap.min.js"></script>
  <script src="bootstrap_iugc/js/alertify.js"></script>
  <script src="bootstrap_iugc/js/jquery.numeric.min.js"></script>
  <script src="bootstrap_iugc/js/bootstrap-datepicker.min.js"></script>
  <script src="bootstrap_iugc/js/bootstrap-datepicker.es.min.js"></script>


  <script src="bootstrap/js/sessvars.js"></script>
  <script src="javascript/menu_principal.js" language="javascript" type="text/javascript"> </script>
  <script src="javascript/principal.js" language="javascript" type="text/javascript"> </script>
</body>
</html>
<?php
}
else
{
    echo "<script>
      alert('El usuario no tiene privilegion para entrar.');
      location.replace('index.php');
    </script>";
}
?>