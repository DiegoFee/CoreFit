<?php
require_once __DIR__ . '/../modelos/modeloAcerca.php';

$modelo = new modeloAcerca($conexion);

function subirLogo($file) {
  if ($file['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombreArchivo = 'logo-gimnasio.' . $ext;
    $rutaDestino = __DIR__ . '/../vistas/public/files/logos/' . $nombreArchivo;
    if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
      return $nombreArchivo;
    }
  }
  return null;
}

// manejo de los campos de texto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $accion = $_POST['accion'] ?? '';
  $datos = [
    'nombre' => $_POST['nombre'] ?? null,
    'contacto' => $_POST['contacto'] ?? null,
    'dueno' => $_POST['dueno'] ?? null,
    'correo' => $_POST['correo'] ?? null,
    'logo' => null
  ];

  // manejo de logo
  $acercaActual = $modelo->obtenerAcerca();
  $restaurarLogo = isset($_POST['restaurar_logo']);

  if ($restaurarLogo) {
    // borra el logo personalizado de las carpetas del sistema
    if ($acercaActual && $acercaActual['logo'] && $acercaActual['logo'] !== 'logo-novacorp.jpg') {
      $rutaLogo = __DIR__ . '/../vistas/public/files/logos/' . $acercaActual['logo'];
      if (file_exists($rutaLogo)) {
          unlink($rutaLogo);
      }
    }
    $datos['logo'] = 'logo-novacorp.jpg';
  } elseif (isset($_FILES['logo']) && $_FILES['logo']['size'] > 0) {
    $logoSubido = subirLogo($_FILES['logo']);
    if ($logoSubido) {
      $datos['logo'] = $logoSubido;
    } else {
      $datos['logo'] = $acercaActual['logo'] ?? 'logo-novacorp.jpg';
    }
  } else {
    $datos['logo'] = $acercaActual['logo'] ?? 'logo-novacorp.jpg';
  }

  if ($accion === 'crear' || !$acercaActual) {
    $modelo->crearAcerca($datos);
  } elseif ($accion === 'actualizar' && isset($_POST['id'])) {
    $datos['id'] = $_POST['id'];
    $modelo->actualizarAcerca($datos);
  }
  header("location:" . BASE_URL . "vistas/acerca.php");
  exit();
} else {
    $acerca = $modelo->obtenerAcerca();
}
?>