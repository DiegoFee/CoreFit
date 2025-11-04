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
$stmt = $conexion->prepare('SELECT fecha_asistencia, hora_entrada FROM Asistencias WHERE miembro_id=? ORDER BY fecha_asistencia DESC, hora_entrada DESC');
$stmt->bind_param('i', $miembroId);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
  $asistencias[] = $row;
}

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
    .member-info h3 {
        color: #1a1a1a;
        font-size: 14px;
        margin-top: 0;
        margin-bottom: 15px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }
    .member-info p {
        margin: 5px 0;
        color: #444;
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
    strong {
        color: #1a1a1a;
    }
    ";
}

function generarTablaAsistencias($asistencias) {
    $html = "<table><thead><tr><th>Fecha</th><th>Hora de Entrada</th></tr></thead><tbody>";
    foreach ($asistencias as $asistencia) {
        $html .= sprintf(
            "<tr><td>%s</td><td>%s</td></tr>",
            date('d-m-Y', strtotime($asistencia['fecha_asistencia'])),
            $asistencia['hora_entrada']
        );
    }
    return $html . "</tbody></table>";
}

function generarHTML($miembro, $asistencias) {
    $html = "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>" . getStyles() . "</style></head><body>";
    
    // Header
    $html .= "<div class='header'>
        <h1>REGISTRO DE ACTIVIDAD</h1>
        <h2>CoreFit - Sistema de Gestión de Gimnasios</h2>
    </div>";
    
    // Información del miembro
    $html .= "<div class='member-info'>
        <h3>Información del Miembro</h3>
        <p><strong>Nombre:</strong> {$miembro['nombre']} {$miembro['apellido']}</p>
        <p><strong>Teléfono:</strong> " . ($miembro['telefono'] ?? 'No registrado') . "</p>
        <p><strong>Membresía:</strong> {$miembro['membresia_nombre']}</p>
        <p><strong>Período:</strong> {$miembro['fecha_desde']} - {$miembro['fecha_hasta']}</p>
        <p><strong>Fecha de registro:</strong> " . date('d-m-Y H:i', strtotime($miembro['fecha_registro'])) . "</p>
    </div>";
    
    // Tabla de asistencias
    $html .= "<h3>Historial de Asistencias</h3>";
    $html .= generarTablaAsistencias($asistencias);
    
    // Footer
    $html .= "<div class='footer'>
        <p>Reporte generado el " . date('d-m-Y H:i') . "</p>
        <p>Total de asistencias: " . count($asistencias) . "</p>
    </div>";
    
    return $html . "</body></html>";
}

$html = generarHTML($miembro, $asistencias);

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('registro_actividad_' . $miembro['id'] . '.pdf', ['Attachment' => false]);
?>
