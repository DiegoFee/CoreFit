<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";

class ModeloMensajes {
  private $conexion;

  public function __construct($conexion) {
    $this->conexion = $conexion;
    // Crear tablas necesarias si no existen
    $sql1 = "CREATE TABLE IF NOT EXISTS MensajesPlantillas (
      id INT AUTO_INCREMENT PRIMARY KEY,
      nombre VARCHAR(100) NOT NULL,
      texto TEXT NOT NULL,
      dias_antes INT DEFAULT NULL,
      cada_x_dias INT DEFAULT NULL,
      habilitado TINYINT(1) DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $this->conexion->query($sql1);

    $sql2 = "CREATE TABLE IF NOT EXISTS MensajesLogs (
      id INT AUTO_INCREMENT PRIMARY KEY,
      telefono VARCHAR(50),
      texto TEXT,
      fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      estado VARCHAR(50)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $this->conexion->query($sql2);

    // Tabla para guardar receptores seleccionados (una fila con JSON de ids)
    $sql3 = "CREATE TABLE IF NOT EXISTS MensajesReceptores (
      id INT AUTO_INCREMENT PRIMARY KEY,
      miembros TEXT DEFAULT NULL,
      fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    $this->conexion->query($sql3);
  }

  public function obtenerPlantillas() {
    $sql = "SELECT * FROM MensajesPlantillas ORDER BY id";
    $res = $this->conexion->query($sql);
    $rows = [];
    while ($r = $res->fetch_assoc()) {
      $rows[] = $r;
    }
    return $rows;
  }

  // Guardar plantilla reemplaza todo el conjunto (al final ya no se usa)
  public function guardarPlantillas($plantillas) {
    $this->conexion->begin_transaction();
    try {
      $this->conexion->query("TRUNCATE TABLE MensajesPlantillas");
      $stmt = $this->conexion->prepare("INSERT INTO MensajesPlantillas (nombre, texto, dias_antes, cada_x_dias, habilitado) VALUES (?, ?, ?, ?, ?)");
      foreach ($plantillas as $p) {
        $nombre = isset($p->name) ? $p->name : (isset($p->type) ? $p->type : '');
        $texto = isset($p->message) ? $p->message : '';
        $dias_antes = isset($p->days_before) && $p->days_before !== '' ? $p->days_before : null;
        $cada_x = isset($p->every_x_days) && $p->every_x_days !== '' ? $p->every_x_days : null;
        $habilitado = isset($p->enabled) && $p->enabled ? 1 : 0;
        $stmt->bind_param('ssiii', $nombre, $texto, $dias_antes, $cada_x, $habilitado);
        $stmt->execute();
      }
      $this->conexion->commit();
      return true;
    } catch (Exception $e) {
      $this->conexion->rollback();
      return false;
    }
  }

  // Guardar y obtener receptores seleccionados (almacena array de ids como JSON)
  public function guardarReceptores($miembros) {
    $json = json_encode(array_values($miembros));
    $this->conexion->query("TRUNCATE TABLE MensajesReceptores");
    $stmt = $this->conexion->prepare("INSERT INTO MensajesReceptores (miembros) VALUES (?)");
    $stmt->bind_param('s', $json);
    return $stmt->execute();
  }

  public function obtenerReceptores() {
    $sql = "SELECT miembros FROM MensajesReceptores ORDER BY id DESC LIMIT 1";
    $res = $this->conexion->query($sql);
    if ($res && $row = $res->fetch_assoc()) {
      return json_decode($row['miembros'], true) ?: [];
    }
    return [];
  }

  public function registrarEnvio($telefono, $texto, $estado = 'ok') {
    $stmt = $this->conexion->prepare("INSERT INTO MensajesLogs (telefono, texto, estado) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $telefono, $texto, $estado);
    return $stmt->execute();
  }
}

?>
