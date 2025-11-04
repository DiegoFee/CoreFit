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

function getStyles() {
    return "
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');
    body { 
        font-family: 'Open Sans', Arial, sans-serif; 
        margin: 30px;
        font-size: 12px;
        color: #333;
        line-height: 1.6;
    }
    .header { 
        text-align: center; 
        margin-bottom: 40px;
        border-bottom: 2px solid #e0e0e0;
        padding-bottom: 20px;
    }
    .header h1 {
        font-size: 24px;
        color: #1a1a1a;
        margin: 0 0 10px 0;
        font-weight: 600;
        letter-spacing: 1px;
    }
    .header h2 {
        font-size: 16px;
        color: #666;
        margin: 0;
        font-weight: normal;
    }
    .member-info {
        background: #f8f8f8;
        margin-bottom: 30px;
        padding: 20px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .summary {
        background: #fff;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .summary h3 {
        color: #1a1a1a;
        font-size: 14px;
        margin-top: 0;
        margin-bottom: 15px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    th {
        background-color: #f0f0f0;
        color: #333;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 8px;
        border: 1px solid #ddd;
    }
    td {
        padding: 10px 8px;
        border: 1px solid #ddd;
        color: #444;
        font-size: 11px;
        background: #fff;
    }
    tr:nth-child(even) td {
        background: #fafafa;
    }
    .footer {
        margin-top: 40px;
        text-align: center;
        font-size: 10px;
        color: #888;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    .total {
        font-weight: 600;
        color: #1a1a1a;
    }
    strong {
        color: #1a1a1a;
    }
    h3 {
        color: #1a1a1a;
        font-size: 14px;
        margin: 20px 0 15px 0;
    }
    ";
}

function generarTablaPagos($pagos) {
    $html = "<table><thead><tr><th>ID</th><th>Fecha de Pago</th><th>Monto</th><th>Método</th></tr></thead><tbody>";
    if (!empty($pagos)) {
        foreach ($pagos as $pago) {
            $html .= sprintf(
                "<tr><td>%s</td><td>%s</td><td>Q%s</td><td>%s</td></tr>",
                $pago['id'],
                date('d-m-Y H:i', strtotime($pago['fecha_pago'])),
                $pago['monto'],
                ucfirst($pago['metodo_pago'])
            );
        }
    } else {
        $html .= "<tr><td colspan='4' style='text-align: center;'>No hay pagos registrados</td></tr>";
    }
    return $html . "</tbody></table>";
}

function generarHTML($miembro, $pagos, $totalPagado) {
    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>" . getStyles() . "</style></head><body>";
    
    // Header
    $html .= "<div class='header'>
        <h1>HISTORIAL DE PAGOS</h1>
        <h2>CoreFit - Sistema de Gestión de Gimnasios</h2>
    </div>";
    
    // Información del miembro
    $html .= "<div class='member-info'>
        <h3>Información del Miembro</h3>
        <p><strong>Nombre:</strong> {$miembro['nombre']} {$miembro['apellido']}</p>
        <p><strong>Teléfono:</strong> " . ($miembro['telefono'] ?? 'No registrado') . "</p>
        <p><strong>Membresía:</strong> {$miembro['membresia_nombre']}</p>
        <p><strong>Período:</strong> {$miembro['fecha_desde']} - {$miembro['fecha_hasta']}</p>
    </div>";
    
    // Resumen de pagos
    $saldoPendiente = $miembro['precio_total'] - $totalPagado;
    $html .= "<div class='summary'>
        <h3>Resumen de Pagos</h3>
        <p><strong>Precio total de la membresía:</strong> Q{$miembro['precio_total']}</p>
        <p><strong>Total pagado:</strong> Q{$totalPagado}</p>
        <p><strong>Saldo pendiente:</strong> Q{$saldoPendiente}</p>
        <p><strong>Estado:</strong> " . ($totalPagado >= $miembro['precio_total'] ? 'Al día' : 'Moroso') . "</p>
    </div>";
    
    // Tabla de pagos
    $html .= "<h3>Detalle de Pagos</h3>";
    $html .= generarTablaPagos($pagos);
    
    // Footer
    $html .= "<div class='footer'>
        <p>Reporte generado el " . date('d-m-Y H:i') . "</p>
        <p>Total de pagos: " . count($pagos) . "</p>
    </div>";
    
    return $html . "</body></html>";
}

$html = generarHTML($miembro, $pagos, $totalPagado);

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('historial_pagos_' . $miembro['id'] . '.pdf', ['Attachment' => false]);
?>
