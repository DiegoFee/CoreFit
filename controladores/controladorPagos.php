<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";
require_once __DIR__ . "/../modelos/modeloPagos.php";
require_once __DIR__ . "/../modelos/modeloMiembros.php";

$modeloPagos = new modeloPagos($conexion);
$modeloMiembros = new modeloMiembros($conexion);

// Manejador GET para obtener asistencias por semana
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'asistencias_por_semana') {
  header('Content-Type: application/json');
  if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID de miembro no proporcionado']);
    exit();
  }

  $miembroId = intval($_GET['id']);
  $asistencias = $modeloPagos->obtenerAsistenciasPorSemana($miembroId);
  echo json_encode($asistencias);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['btn-guardar-pago'])) {
    $miembroId = intval($_POST['miembro_id']);
    $monto = floatval($_POST['monto']);
    $metodoPago = $_POST['metodo_pago'] ?? 'efectivo';
    $observaciones = $_POST['observaciones'] ?? null;

    // Obtener información del miembro
    $miembro = $modeloMiembros->obtenerMiembroPorId($miembroId);
    if (!$miembro) {
      $_SESSION['error'] = "Miembro no encontrado";
      header("location:" . BASE_URL . "vistas/pagos.php");
      exit();
    }

    // Verificar que no se exceda el precio total
    $totalPagado = $modeloPagos->obtenerTotalPagadoPorMiembro($miembroId);
    $nuevoTotal = $totalPagado + $monto;
    
    if ($nuevoTotal > $miembro['precio_total']) {
      $_SESSION['error'] = "El pago excede el precio total de la membresía";
      header("location:" . BASE_URL . "vistas/pagos.php");
      exit();
    }

    // Crear el pago
    $datosPago = [
      'miembro_id' => $miembroId,
      'monto' => $monto,
      'metodo_pago' => $metodoPago,
      'observaciones' => $observaciones
    ];

    if ($modeloPagos->crearPago($datosPago)) {
      // Actualizar el campo pagado del miembro
      $modeloPagos->actualizarPagadoMiembro($miembroId, $nuevoTotal);
      $_SESSION['success'] = "Pago registrado exitosamente";
    } else {
      $_SESSION['error'] = "Error al registrar el pago";
    }
    
    header("location:" . BASE_URL . "vistas/pagos.php");
    exit();
  }
}
?>
