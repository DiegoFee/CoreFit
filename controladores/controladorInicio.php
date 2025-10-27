<?php
if (session_status() == PHP_SESSION_NONE) session_start();
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";
require_once __DIR__ . "/../modelos/modeloInicio.php";
require_once __DIR__ . "/../modelos/modeloMiembros.php";

$modeloInicio = new modeloInicio($conexion);
$modeloMiembros = new modeloMiembros($conexion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['rfid_input'])) {
    $rfid = trim($_POST['rfid_input']);
    
    if (empty($rfid)) {
      echo json_encode(['success' => false, 'message' => 'Por favor ingrese un ID válido']);
      exit();
    }
    
    // Buscar miembro por RFID
    $miembro = $modeloInicio->obtenerMiembroPorRFID($rfid);
    
    if (!$miembro) {
      echo json_encode(['success' => false, 'message' => 'Miembro no encontrado', 'type' => 'not_found']);
      exit();
    }
    
    // Verificar si la membresía está activa
    $fechaHasta = new DateTime($miembro['fecha_hasta']);
    $fechaActual = new DateTime();
    $esActivo = $fechaHasta >= $fechaActual;
    
    // Verificar estado de pago
    $estaAlDia = $miembro['total_pagado'] >= $miembro['precio_total'];
    
    if (!$esActivo) {
      echo json_encode([
        'success' => false, 
        'message' => 'Su membresía ha expirado. Por favor renueve su membresía.',
        'type' => 'expired',
        'miembro' => $miembro
      ]);
      exit();
    }
    
    // Registrar asistencia
    $resultadoAsistencia = $modeloInicio->registrarAsistencia($miembro['id']);
    
    if ($resultadoAsistencia['success']) {
      $mensaje = "¡Bienvenido {$miembro['nombre']} {$miembro['apellido']}!";
      if (!$estaAlDia) {
        $mensaje .= " Recuerde que tiene pagos pendientes.";
      }
      // Obtener estadísticas actualizadas (incluye asistencias del día)
      $estadisticasActualizadas = $modeloInicio->obtenerEstadisticas();
      echo json_encode([
        'success' => true,
        'message' => $mensaje,
        'type' => 'success',
        'miembro' => $miembro,
        'esta_al_dia' => $estaAlDia,
        'estadisticas' => $estadisticasActualizadas,
        'received_post' => $_POST,
        'resultadoAsistencia' => $resultadoAsistencia
      ]);
    } else {
      echo json_encode([
        'success' => false,
        'message' => $resultadoAsistencia['message'],
        'type' => 'already_registered',
        'miembro' => $miembro,
        'received_post' => $_POST,
        'resultadoAsistencia' => $resultadoAsistencia
      ]);
    }
    exit();
  }
}

// Manejar peticiones GET para obtener estadísticas
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax'])) {
  $estadisticas = $modeloInicio->obtenerEstadisticas();
  echo json_encode(['success' => true, 'estadisticas' => $estadisticas]);
  exit();
}

// Obtener estadísticas para mostrar en el dashboard
$estadisticas = $modeloInicio->obtenerEstadisticas();
?>
