<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";

class modeloMiembros {
  private $conexion;

  public function __construct($conexion) {
    $this -> conexion = $conexion;
  }

  public function crearMiembro($datos) {
    $stmt = $this->conexion->prepare("INSERT INTO Miembros (tarjeta_rfid, nombre, apellido, telefono, foto, membresia_id, fecha_desde, fecha_hasta, precio_total, pagado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssissdd", $datos['tarjeta_rfid'], $datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['foto'], $datos['membresia_id'], $datos['fecha_desde'], $datos['fecha_hasta'], $datos['precio_total'], $datos['pagado']);
    return $stmt->execute();
  }

  public function obtenerMiembros() {
    $sql = "SELECT m.*, mem.nombre as membresia_nombre, mem.meses, mem.modalidad, mem.precio as membresia_precio 
            FROM Miembros m 
            JOIN Membresias mem ON m.membresia_id = mem.id 
            ORDER BY m.fecha_registro DESC";
    $resultado = $this->conexion->query($sql);
    $miembros = [];
    while ($fila = $resultado->fetch_assoc()) {
      $miembros[] = $fila;
    }
    return $miembros;
  }

  public function obtenerMiembroPorId($id) {
    $stmt = $this->conexion->prepare("SELECT m.*, mem.nombre as membresia_nombre, mem.meses, mem.modalidad, mem.precio as membresia_precio 
                                      FROM Miembros m 
                                      JOIN Membresias mem ON m.membresia_id = mem.id 
                                      WHERE m.id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
  }

  public function obtenerMiembroPorRFID($rfid) {
    $stmt = $this->conexion->prepare("SELECT m.*, mem.nombre as membresia_nombre, mem.meses, mem.modalidad, mem.precio as membresia_precio 
                                      FROM Miembros m 
                                      JOIN Membresias mem ON m.membresia_id = mem.id 
                                      WHERE m.tarjeta_rfid=?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
  }

  public function actualizarMiembro($datos) {
    $stmt = $this->conexion->prepare("UPDATE Miembros SET tarjeta_rfid=?, nombre=?, apellido=?, telefono=?, foto=?, membresia_id=?, fecha_desde=?, fecha_hasta=?, precio_total=?, pagado=? WHERE id=?");
    $stmt->bind_param("ssssssissdi", $datos['tarjeta_rfid'], $datos['nombre'], $datos['apellido'], $datos['telefono'], $datos['foto'], $datos['membresia_id'], $datos['fecha_desde'], $datos['fecha_hasta'], $datos['precio_total'], $datos['pagado'], $datos['id']);
    return $stmt->execute();
  }

  public function borrarMiembro($id) {
    $stmt = $this->conexion->prepare("DELETE FROM Miembros WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }

  public function verificarRFIDExistente($rfid, $excluirId = null) {
    if ($excluirId) {
      $stmt = $this->conexion->prepare("SELECT COUNT(*) as count FROM Miembros WHERE tarjeta_rfid=? AND id!=?");
      $stmt->bind_param("si", $rfid, $excluirId);
    } else {
      $stmt = $this->conexion->prepare("SELECT COUNT(*) as count FROM Miembros WHERE tarjeta_rfid=?");
      $stmt->bind_param("s", $rfid);
    }
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
    return $fila['count'] > 0;
  }

  public function obtenerEstadisticas() {
    $sql = "SELECT 
              COUNT(*) as total_miembros,
              COUNT(CASE WHEN fecha_hasta >= CURDATE() THEN 1 END) as miembros_activos,
              COUNT(CASE WHEN pagado >= precio_total THEN 1 END) as miembros_al_dia,
              COUNT(CASE WHEN pagado < precio_total THEN 1 END) as miembros_morosos
            FROM Miembros";
    $resultado = $this->conexion->query($sql);
    return $resultado->fetch_assoc();
  }
}

?>
