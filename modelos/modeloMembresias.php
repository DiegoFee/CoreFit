<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";

class modeloMembresias {
  private $conexion;

  public function __construct($conexion) {
    $this -> conexion = $conexion;
  }

  // Funciones directas a la bd
  public function crearMembresia($datos) {
    $stmt = $this->conexion->prepare("INSERT INTO Membresias (nombre, meses, modalidad, precio, rutinas) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisis", $datos['nombre'], $datos['meses'], $datos['modalidad'], $datos['precio'], $datos['rutinas']);
    return $stmt->execute();
  }

  public function obtenerMembresia() { // Para mostrar los datos en una lista
    $sql = "SELECT * FROM Membresias";
    $resultado = $this->conexion->query($sql);
    $membresias = [];
    while ($fila = $resultado->fetch_assoc()) {
      $membresias[] = $fila;
    }
    return $membresias;
  }

  public function obtenerMembresiaPorId($id) { // Para editar datos específicos
    $stmt = $this->conexion->prepare("SELECT * FROM Membresias WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
  }

  public function actualizarMembresia($datos) {
    $stmt = $this->conexion->prepare("UPDATE Membresias SET nombre=?, meses=?, modalidad=?, precio=?, rutinas=? WHERE id=?");
    $stmt->bind_param("sisisi", $datos['nombre'], $datos['meses'], $datos['modalidad'], $datos['precio'], $datos['rutinas'], $datos['id']);
    return $stmt->execute();
  }

  public function borrarMembresia($id) {
    $stmt = $this->conexion->prepare("DELETE FROM Membresias WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
  }
}

?>