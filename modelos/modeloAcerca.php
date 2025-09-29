<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . '/../conexionMysql.php';

class modeloAcerca {
  private $conexion;

  public function __construct($conexion) {
    $this -> conexion = $conexion;
  }

  // funciones directas a la bd
  public function obtenerAcerca() {
    $sql = "SELECT * FROM Acerca LIMIT 1";
    $resultado = $this->conexion->query($sql);
    return $resultado ? $resultado->fetch_assoc() : null;
  }

  public function crearAcerca($datos) {
    $stmt = $this->conexion->prepare("INSERT INTO Acerca (nombre, contacto, dueno, correo, logo) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $datos['nombre'], $datos['contacto'], $datos['dueno'], $datos['correo'], $datos['logo']);
    return $stmt->execute();
  }

  public function actualizarAcerca($datos) {
    $stmt = $this->conexion->prepare("UPDATE Acerca SET nombre=?, contacto=?, dueno=?, correo=?, logo=? WHERE id=?");
    $stmt->bind_param("sssssi", $datos['nombre'], $datos['contacto'], $datos['dueno'], $datos['correo'], $datos['logo'], $datos['id']);
    return $stmt->execute();
  }
}

?>