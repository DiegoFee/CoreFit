<?php

//----- INICIO DE SESIÓN -----
session_start();
//importaciones necesarias
require_once __DIR__ . "/../config.php";
require __DIR__ . "/../conexionMysql.php";

//variables locales
$mensaje = "";

if (!empty($_POST["btn-ingresar"])) {
  if (empty($_POST["usuario"] || empty($_POST["contraseña"]))) {
    $_SESSION['mensaje'] = "Los campos están vacíos";
    header("location:" . BASE_URL . "vistas/login.php");
    exit();
  } else {
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];
    // consulta de verificación en mysql
    $sql = $conexion -> query("select * from usuarios where usuario='$usuario' and contraseña='$contraseña'");
    if ($datos = $sql -> fetch_object()) {
        $_SESSION['usuario'] = $usuario;
        header("location:" . BASE_URL . "vistas/inicio.php");
        exit();
    } else {  
      $_SESSION['mensaje'] = "Las credenciales son incorrectas";
      header("location:" . BASE_URL . "vistas/login.php");
      exit();
    }
  } 
}

//mostrar mensaje si existe en sesión
if (!empty($_SESSION['mensaje'])) {
  $mensaje = $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
}

//-----CIERRE DE SESIÓN -----
if (isset($_GET['logout'])) {
  session_unset();
  session_destroy();
  header("Location:" . BASE_URL . "vistas/login.php");
  exit();
}

?>