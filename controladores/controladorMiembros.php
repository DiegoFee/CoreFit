<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";
require_once __DIR__ . "/../modelos/modeloMiembros.php";
require_once __DIR__ . "/../modelos/modeloMembresias.php";
require_once __DIR__ . "/../modelos/modeloPagos.php";

$modeloMiembros = new modeloMiembros($conexion);
$modeloMembresias = new modeloMembresias($conexion);
$modeloPagos = new modeloPagos($conexion);

function subirFoto($file) {
  if ($file['error'] === UPLOAD_ERR_OK) {
    $directorioDestino = __DIR__ . '/../vistas/public/files/miembros/';
    if (!file_exists($directorioDestino)) {
      mkdir($directorioDestino, 0777, true);
    }
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('miembro_') . '.' . $ext;
    $rutaDestino = $directorioDestino . $nombreArchivo;
    if (move_uploaded_file($file['tmp_name'], $rutaDestino)) {
      return $nombreArchivo;
    }
  }
  return null;
}

function calcularFechaHasta($fechaDesde, $meses) {
  $fecha = new DateTime($fechaDesde);
  $fecha->add(new DateInterval('P' . $meses . 'M'));
  return $fecha->format('Y-m-d');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['btn-guardar'])) {
    $datos = [
      'tarjeta_rfid' => $_POST['tarjeta_rfid'],
      'nombre' => $_POST['nombre'],
      'apellido' => $_POST['apellido'],
      'telefono' => $_POST['telefono'] ?? null,
      'foto' => '',  // Cambiamos null por string vacío
      'membresia_id' => intval($_POST['membresia_id']),
      'fecha_desde' => $_POST['fecha_desde'],
      'fecha_hasta' => $_POST['fecha_hasta'],
      'precio_total' => floatval($_POST['precio_total']),
      'pagado' => floatval($_POST['pagado'] ?? 0)
    ];

    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
      $archivo = subirFoto($_FILES['foto']);
      if ($archivo) {
        $datos['foto'] = $archivo;
      }
    }

    if ($modeloMiembros->verificarRFIDExistente($datos['tarjeta_rfid'])) {
      $_SESSION['error'] = "La tarjeta RFID ya está registrada";
    } else {
      // Validar que el pago inicial no exceda el precio total de la membresía
      if (!empty($datos['pagado']) && floatval($datos['pagado']) > floatval($datos['precio_total'])) {
        // Mostrar mensaje instructivo y redirigir (el usuario deberá corregir el valor)
        $_SESSION['error'] = "Por favor, introduzca un valor igual o menor al precio de la membresía.";
        header("location:" . BASE_URL . "vistas/miembros.php");
        exit();
      }
      // Crear miembro
      $exito = $modeloMiembros->crearMiembro($datos);
      if ($exito) {
        // Obtener id insertado
        $miembroId = $conexion->insert_id;
        // Si hubo un pago inicial, registrar en tabla Pagos y actualizar campo pagado en Miembros
        if (!empty($datos['pagado']) && floatval($datos['pagado']) > 0) {
          $pagoData = [
            'miembro_id' => $miembroId,
            'monto' => floatval($datos['pagado']),
            'metodo_pago' => 'efectivo',
            'observaciones' => 'Pago inicial al registrar miembro'
          ];
          $modeloPagos->crearPago($pagoData);
          // Actualizar campo pagado en Miembros
          $modeloPagos->actualizarPagadoMiembro($miembroId, floatval($datos['pagado']));
        }
        $_SESSION['success'] = "Miembro registrado exitosamente";
      } else {
        // Si la inserción falló por clave duplicada de la tarjeta rfid informa que la tarjeta ya está en uso
        if ($conexion->errno == 1062) {
          $_SESSION['error'] = "La tarjeta RFID ya está en uso. Por favor use otra tarjeta.";
        } else {
          $_SESSION['error'] = "Error al crear el miembro";
        }
      }
    }
    
    header("location:" . BASE_URL . "vistas/miembros.php");
    exit();
  }

  if (isset($_POST['btn-editar'])) {
    $id = intval($_POST['id']);
    $miembroActual = $modeloMiembros->obtenerMiembroPorId($id);

    $datos = [
      'id' => $id,
      'tarjeta_rfid' => $_POST['tarjeta_rfid'],
      'nombre' => $_POST['nombre'],
      'apellido' => $_POST['apellido'],
      'telefono' => $_POST['telefono'] ?? null,
      'foto' => null,
      'membresia_id' => intval($_POST['membresia_id']),
      'fecha_desde' => $_POST['fecha_desde'],
      'fecha_hasta' => $_POST['fecha_hasta'],
      'precio_total' => floatval($_POST['precio_total']),
      'pagado' => floatval($_POST['pagado'] ?? 0)
    ];

    if (isset($_FILES['foto']) && $_FILES['foto']['size'] > 0) {
      $archivo = subirFoto($_FILES['foto']);
      if ($archivo) {
        if ($miembroActual['foto'] && $miembroActual['foto'] !== '0') {
          $rutaAnterior = __DIR__ . '/../vistas/public/files/miembros/' . $miembroActual['foto'];
          if (file_exists($rutaAnterior)) unlink($rutaAnterior);
        }
        $datos['foto'] = $archivo;
      } else {
        $datos['foto'] = null;
      }
    } else {
      $datos['foto'] = $miembroActual['foto'] !== '0' ? $miembroActual['foto'] : null;
    }

    if ($modeloMiembros->verificarRFIDExistente($datos['tarjeta_rfid'], $id)) {
      $_SESSION['error'] = "La tarjeta RFID ya está registrada";
    } else {
      $modeloMiembros->actualizarMiembro($datos);
      $_SESSION['success'] = "Miembro actualizado exitosamente";
    }
    
    header("location:" . BASE_URL . "vistas/miembros.php");
    exit();
  }

  if (isset($_POST['btn-eliminar'])) {
    $id = intval($_POST['id']);
    $miembroActual = $modeloMiembros->obtenerMiembroPorId($id);

    if ($miembroActual && $miembroActual['foto']) {
      $rutaFoto = __DIR__ . '/../vistas/public/files/miembros/' . $miembroActual['foto'];
      if (file_exists($rutaFoto)) unlink($rutaFoto);
    }

    $modeloMiembros->borrarMiembro($id);
    $_SESSION['success'] = "Miembro eliminado exitosamente";
    header("location:" . BASE_URL . "vistas/miembros.php");
    exit();
  }
}
?>
