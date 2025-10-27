<?php
require_once __DIR__ . "/../modelos/modeloMembresias.php";

$modelo = new modeloMembresias($conexion);

function subirRutinas($file) {
  if ($file['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    if (strtolower($ext) === 'pdf') {
      $nombreArchivo = uniqid('rutinas_') . '.pdf';
      $rutaDestino = __DIR__ . '/../vistas/public/files/rutinas/' . $nombreArchivo;
      if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
        return $nombreArchivo;
      }
    }
  }
  return null;
}

// crear nueva membresía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['btn-guardar'])) {
    $datos = [
      'nombre' => $_POST['nombre'],
      'meses' => intval($_POST['meses']),
      'modalidad' => $_POST['modalidad'],
      'precio' => floatval($_POST['precio']),
      'rutinas' => null
    ];

    if (isset($_FILES['rutinas']) && $_FILES['rutinas']['size'] > 0) {
      $archivo = subirRutinas($_FILES['rutinas']);
      if ($archivo) {
        $datos['rutinas'] = $archivo;
      }
    }

    $modelo->crearMembresia($datos);
    header("location:" . BASE_URL . "vistas/membresias.php");
    exit();
  }

  // editar membresía existente por id
  if (isset($_POST['btn-editar'])) {
    $id = intval($_POST['id']);
    $membresiaActual = $modelo->obtenerMembresiaPorId($id);

    $datos = [
      'id' => $id,
      'nombre' => $_POST['nombre'],
      'meses' => intval($_POST['meses']),
      'modalidad' => $_POST['modalidad'],
      'precio' => floatval($_POST['precio']),
      'rutinas' => $membresiaActual['rutinas']
    ];

    if (isset($_FILES['rutinas']) && $_FILES['rutinas']['size'] > 0) { // reemplaza el anterior pdf si se sube uno nuevo
      $archivo = subirRutinas($_FILES['rutinas']);
      if ($archivo) {
        if ($membresiaActual['rutinas']) {
          $rutaAnterior = __DIR__ . '/../vistas/public/files/rutinas/' . $membresiaActual['rutinas'];
          if (file_exists($rutaAnterior)) unlink($rutaAnterior);
        }
        $datos['rutinas'] = $archivo;
      }
    }

    $modelo->actualizarMembresia($datos);
    header("location:" . BASE_URL . "vistas/membresias.php");
    exit();
  }

  // eliminar membresía existente por id
  if (isset($_POST['btn-eliminar'])) {
    $id = intval($_POST['id']);
    $membresiaActual = $modelo->obtenerMembresiaPorId($id);

    if ($membresiaActual && $membresiaActual['rutinas']) {
      $rutaPDF = __DIR__ . '/../vistas/public/files/rutinas/' . $membresiaActual['rutinas'];
      if (file_exists($rutaPDF)) unlink($rutaPDF);
    }

    $modelo->borrarMembresia($id);
    header("location:" . BASE_URL . "vistas/membresias.php");
    exit();
  }
}
?>