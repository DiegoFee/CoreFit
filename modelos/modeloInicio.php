<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";

class modeloInicio {
  private $conexion;

  public function __construct($conexion) {
    $this -> conexion = $conexion;
  }

  public function registrarAsistencia($miembroId) {
    $fechaHoy = date('Y-m-d');
    $horaActual = date('H:i:s');
    
    // Verificar si ya existe una asistencia para hoy
    $stmt = $this->conexion->prepare("SELECT id FROM Asistencias WHERE miembro_id=? AND fecha_asistencia=?");
    $stmt->bind_param("is", $miembroId, $fechaHoy);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
      return ['success' => false, 'message' => 'Ya se registró la asistencia de hoy'];
    }
    
    // Registrar nueva asistencia
    $stmt = $this->conexion->prepare("INSERT INTO Asistencias (miembro_id, fecha_asistencia, hora_entrada) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $miembroId, $fechaHoy, $horaActual);
    
    if ($stmt->execute()) {
      return ['success' => true, 'message' => 'Asistencia registrada exitosamente'];
    } else {
      return ['success' => false, 'message' => 'Error al registrar la asistencia'];
    }
  }

  public function obtenerEstadisticas() {
    // Obtener estadísticas básicas
    $sql = "SELECT 
              COUNT(*) as total_miembros,
              COUNT(CASE WHEN fecha_hasta >= CURDATE() THEN 1 END) as miembros_activos,
              COUNT(CASE WHEN fecha_hasta < CURDATE() THEN 1 END) as miembros_inactivos
            FROM Miembros";
    
    $resultado = $this->conexion->query($sql);
    $fila = $resultado->fetch_assoc();
    
    // Obtener estadísticas de pagos
    $sqlPagos = "SELECT 
                   COUNT(CASE WHEN total_pagado >= precio_total THEN 1 END) as miembros_al_dia,
                   COUNT(CASE WHEN total_pagado < precio_total THEN 1 END) as miembros_morosos
                 FROM (
                   SELECT m.id, m.precio_total, COALESCE(SUM(p.monto), 0) as total_pagado
                   FROM Miembros m
                   LEFT JOIN Pagos p ON m.id = p.miembro_id
                   GROUP BY m.id, m.precio_total
                 ) as pagos_resumen";
    
    $resultadoPagos = $this->conexion->query($sqlPagos);
    $filaPagos = $resultadoPagos->fetch_assoc();
    
    // Obtener asistencias de hoy
    $sqlAsistencias = "SELECT COUNT(DISTINCT miembro_id) as asistencias_hoy 
                       FROM Asistencias 
                       WHERE fecha_asistencia = CURDATE()";
    
    $resultadoAsistencias = $this->conexion->query($sqlAsistencias);
    $filaAsistencias = $resultadoAsistencias->fetch_assoc();
    
    return [
      'total_miembros' => $fila['total_miembros'],
      'miembros_activos' => $fila['miembros_activos'],
      'miembros_inactivos' => $fila['miembros_inactivos'],
      'miembros_al_dia' => $filaPagos['miembros_al_dia'],
      'miembros_morosos' => $filaPagos['miembros_morosos'],
      'asistencias_hoy' => $filaAsistencias['asistencias_hoy']
    ];
  }

  public function obtenerAsistenciasHoy() {
    $sql = "SELECT a.*, m.nombre, m.apellido, m.tarjeta_rfid, mem.nombre as membresia_nombre
            FROM Asistencias a
            JOIN Miembros m ON a.miembro_id = m.id
            JOIN Membresias mem ON m.membresia_id = mem.id
            WHERE a.fecha_asistencia = CURDATE()
            ORDER BY a.hora_entrada DESC";
    
    $resultado = $this->conexion->query($sql);
    $asistencias = [];
    while ($fila = $resultado->fetch_assoc()) {
      $asistencias[] = $fila;
    }
    return $asistencias;
  }

  public function obtenerMiembroPorRFID($rfid) {
    $stmt = $this->conexion->prepare("SELECT m.*, mem.nombre as membresia_nombre, mem.modalidad,
                                            COALESCE(SUM(p.monto), 0) as total_pagado
                                     FROM Miembros m 
                                     JOIN Membresias mem ON m.membresia_id = mem.id
                                     LEFT JOIN Pagos p ON m.id = p.miembro_id
                                     WHERE m.tarjeta_rfid = ?
                                     GROUP BY m.id");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
  }
}

?>
