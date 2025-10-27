<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";

class modeloPagos {
  private $conexion;

  public function __construct($conexion) {
    $this -> conexion = $conexion;
  }

  public function crearPago($datos) {
    $stmt = $this->conexion->prepare("INSERT INTO Pagos (miembro_id, monto, metodo_pago, observaciones) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $datos['miembro_id'], $datos['monto'], $datos['metodo_pago'], $datos['observaciones']);
    return $stmt->execute();
  }

  public function obtenerPagos() {
    $sql = "SELECT p.*, m.nombre, m.apellido, m.tarjeta_rfid, m.fecha_desde, m.fecha_hasta, m.precio_total, m.pagado,
                   mem.nombre as membresia_nombre, mem.modalidad
            FROM Pagos p 
            JOIN Miembros m ON p.miembro_id = m.id 
            JOIN Membresias mem ON m.membresia_id = mem.id
            ORDER BY p.fecha_pago DESC";
    $resultado = $this->conexion->query($sql);
    $pagos = [];
    while ($fila = $resultado->fetch_assoc()) {
      $pagos[] = $fila;
    }
    return $pagos;
  }

  public function obtenerPagosPorMiembro($miembroId) {
    $stmt = $this->conexion->prepare("SELECT * FROM Pagos WHERE miembro_id=? ORDER BY fecha_pago DESC");
    $stmt->bind_param("i", $miembroId);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $pagos = [];
    while ($fila = $resultado->fetch_assoc()) {
      $pagos[] = $fila;
    }
    return $pagos;
  }

  public function obtenerTotalPagadoPorMiembro($miembroId) {
    $stmt = $this->conexion->prepare("SELECT SUM(monto) as total FROM Pagos WHERE miembro_id=?");
    $stmt->bind_param("i", $miembroId);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    return $fila['total'] ?? 0;
  }

  public function actualizarPagadoMiembro($miembroId, $nuevoPagado) {
    $stmt = $this->conexion->prepare("UPDATE Miembros SET pagado=? WHERE id=?");
    $stmt->bind_param("di", $nuevoPagado, $miembroId);
    return $stmt->execute();
  }

  public function obtenerMiembrosConPagos() {
    $sql = "SELECT m.*, mem.nombre as membresia_nombre, mem.modalidad,
                   COALESCE(SUM(p.monto), 0) as total_pagado
            FROM Miembros m 
            JOIN Membresias mem ON m.membresia_id = mem.id
            LEFT JOIN Pagos p ON m.id = p.miembro_id
            GROUP BY m.id
            ORDER BY m.fecha_registro DESC";
    $resultado = $this->conexion->query($sql);
    $miembros = [];
    while ($fila = $resultado->fetch_assoc()) {
      $miembros[] = $fila;
    }
    return $miembros;
  }

  public function obtenerAsistenciasPorSemana($miembroId) {
    // Query para contar asistencias agrupadas por día de la semana (0=Domingo, 1=Lunes, ..., 6=Sábado)
    $sql = "SELECT 
              WEEKDAY(fecha_asistencia) as dia_semana, 
              COUNT(*) as total_asistencias
            FROM Asistencias 
            WHERE miembro_id = ?
            GROUP BY WEEKDAY(fecha_asistencia)
            ORDER BY dia_semana";
    
    $stmt = $this->conexion->prepare($sql);
    $stmt->bind_param("i", $miembroId);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // Inicializar array con ceros (Lun=0, Mar=1, ..., Dom=6)
    $asistencias = array_fill(0, 7, 0);
    
    // Llenar los días que tienen asistencias
    while ($fila = $resultado->fetch_assoc()) {
      $dia = $fila['dia_semana'];
      // MySQL WEEKDAY() retorna 0=Lunes, ..., 6=Domingo
      // Necesitamos ajustar el índice para que 0=Lunes, 6=Domingo
      $indice = $dia == 6 ? 0 : $dia + 1; // Mover domingo al inicio
      $asistencias[$indice] = (int)$fila['total_asistencias'];
    }
    
    return ['data' => $asistencias];
  }

  public function obtenerEstadisticasPagos() {
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
    
    return [
      'total_miembros' => $fila['total_miembros'],
      'miembros_activos' => $fila['miembros_activos'],
      'miembros_inactivos' => $fila['miembros_inactivos'],
      'miembros_al_dia' => $filaPagos['miembros_al_dia'],
      'miembros_morosos' => $filaPagos['miembros_morosos']
    ];
  }
}

?>
