<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../conexionMysql.php';

header('Content-Type: application/json; charset=utf-8');

$res = $conexion->query("SELECT id, nombre, apellido, foto FROM Miembros ORDER BY id DESC LIMIT 200");
$rows = [];
while ($r = $res->fetch_assoc()) {
  $foto = $r['foto'];
  $path = __DIR__ . '/../vistas/public/files/miembros/' . $foto;
  $exists = $foto && file_exists($path);
  $rows[] = [
    'id' => $r['id'],
    'nombre' => $r['nombre'],
    'apellido' => $r['apellido'],
    'foto' => $foto,
    'file_exists' => $exists,
    'expected_url' => ($foto ? BASE_URL . 'vistas/public/files/miembros/' . $foto : null)
  ];
}

echo json_encode(['count' => count($rows), 'rows' => $rows], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
