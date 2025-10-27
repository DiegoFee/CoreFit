<?php
require_once __DIR__ . '/../../../../vendor/autoload.php';
use Dompdf\Dompdf;
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../conexionMysql.php';
require_once __DIR__ . '/../../../../modelos/modeloMiembros.php';
require_once __DIR__ . '/../../../../modelos/modeloPagos.php';

$miembroId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($miembroId <= 0) {
  die('ID de miembro inválido');
}

$modeloMiembros = new modeloMiembros($conexion);
$modeloPagos = new modeloPagos($conexion);

$miembro = $modeloMiembros->obtenerMiembroPorId($miembroId);
if (!$miembro) {
  die('Miembro no encontrado');
}

$pagos = $modeloPagos->obtenerPagosPorMiembro($miembroId);
$totalPagado = $modeloPagos->obtenerTotalPagadoPorMiembro($miembroId);

$html = "<html><head><meta charset='UTF-8'><style>
body { font-family: Arial, sans-serif; margin: 20px; }
.header { text-align: center; margin-bottom: 30px; }
.member-info { margin-bottom: 20px; }
.summary { background-color: #f9f9f9; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
th { background-color: #f2f2f2; }
.footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
.total { font-weight: bold; font-size: 16px; }
</style></head><body>";
$html .= "<div class='header'><h1>HISTORIAL DE PAGOS</h1><h2>CoreFit - Sistema de Gestión de Gimnasios</h2></div>";
$html .= "<div class='member-info'><h3>Información del Miembro</h3><p><strong>Nombre:</strong> {$miembro['nombre']} {$miembro['apellido']}</p><p><strong>Teléfono:</strong> " . ($miembro['telefono'] ?? 'No registrado') . "</p><p><strong>Membresía:</strong> {$miembro['membresia_nombre']}</p><p><strong>Período:</strong> {$miembro['fecha_desde']} - {$miembro['fecha_hasta']}</p></div>";
$html .= "<div class='summary'><h3>Resumen de Pagos</h3><p><strong>Precio total de la membresía:</strong> Q{$miembro['precio_total']}</p><p><strong>Total pagado:</strong> Q{$totalPagado}</p><p><strong>Saldo pendiente:</strong> Q" . ($miembro['precio_total'] - $totalPagado) . "</p><p><strong>Estado:</strong> " . ($totalPagado >= $miembro['precio_total'] ? 'Al día' : 'Moroso') . "</p></div>";
$html .= "<h3>Detalle de Pagos</h3><table><thead><tr><th>ID</th><th>Fecha de Pago</th><th>Monto</th><th>Método</th></tr></thead><tbody>";
if (!empty($pagos)) {
  foreach ($pagos as $pago) {
    $html .= "<tr><td>{$pago['id']}</td><td>" . date('d-m-Y H:i', strtotime($pago['fecha_pago'])) . "</td><td>Q{$pago['monto']}</td><td>" . ucfirst($pago['metodo_pago']) . "</td></tr>";
  }
} else {
  $html .= "<tr><td colspan='4' style='text-align: center;'>No hay pagos registrados</td></tr>";
}
$html .= "</tbody></table><div class='footer'><p>Reporte generado el " . date('d-m-Y H:i') . "</p><p>Total de pagos: " . count($pagos) . "</p></div></body></html>";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('historial_pagos_' . $miembro['id'] . '.pdf', ['Attachment' => false]);
?>
