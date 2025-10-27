<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
use Dompdf\Dompdf;
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../conexionMysql.php';
require_once __DIR__ . '/../../../../modelos/modeloMiembros.php';

$miembroId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($miembroId <= 0) {
  die('ID de miembro inválido');
}

$modeloMiembros = new modeloMiembros($conexion);
$miembro = $modeloMiembros->obtenerMiembroPorId($miembroId);
if (!$miembro) {
  die('Miembro no encontrado');
}

$asistencias = [];
$stmt = $conexion->prepare('SELECT fecha_asistencia, hora_entrada, hora_salida FROM Asistencias WHERE miembro_id=? ORDER BY fecha_asistencia DESC, hora_entrada DESC');
$stmt->bind_param('i', $miembroId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $asistencias[] = $row;
}

$html = "<html><head><meta charset='UTF-8'><style>
body { font-family: Arial, sans-serif; margin: 20px; }
.header { text-align: center; margin-bottom: 30px; }
.member-info { margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
.footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
</style></head><body>";
$html .= "<div class='header'><h1>REGISTRO DE ACTIVIDAD</h1><h2>CoreFit - Sistema de Gestión de Gimnasios</h2></div>";
$html .= "<div class='member-info'><h3>Información del Miembro</h3><p><strong>Nombre:</strong> {$miembro['nombre']} {$miembro['apellido']}</p><p><strong>Teléfono:</strong> " . ($miembro['telefono'] ?? 'No registrado') . "</p><p><strong>Membresía:</strong> {$miembro['membresia_nombre']}</p><p><strong>Período:</strong> {$miembro['fecha_desde']} - {$miembro['fecha_hasta']}</p><p><strong>Fecha de registro:</strong> " . date('d-m-Y H:i', strtotime($miembro['fecha_registro'])) . "</p></div>";
$html .= "<h3>Historial de Asistencias</h3><table><thead><tr><th>Fecha</th><th>Hora de Entrada</th><th>Hora de Salida</th><th>Duración</th></tr></thead><tbody>";
foreach ($asistencias as $asistencia) {
  $entrada = new DateTime($asistencia['fecha_asistencia'] . ' ' . $asistencia['hora_entrada']);
  $salida = !empty($asistencia['hora_salida']) ? new DateTime($asistencia['fecha_asistencia'] . ' ' . $asistencia['hora_salida']) : null;
  $duracion = $salida ? $entrada->diff($salida) : null;
  $html .= "<tr><td>" . date('d-m-Y', strtotime($asistencia['fecha_asistencia'])) . "</td><td>{$asistencia['hora_entrada']}</td><td>" . ($asistencia['hora_salida'] ?? '-') . "</td><td>" . ($duracion ? $duracion->format('%h horas %i minutos') : '-') . "</td></tr>";
}
$html .= "</tbody></table><div class='footer'><p>Reporte generado el " . date('d-m-Y H:i') . "</p><p>Total de asistencias: " . count($asistencias) . "</p></div></body></html>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('registro_actividad_' . $miembro['id'] . '.pdf', ['Attachment' => false]);
?>
