<?php
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../conexionMysql.php";
require_once __DIR__ . "/../modelos/modeloMensajes.php";
session_start();

$modelo = new ModeloMensajes($conexion);

// Acepta el JSON desde el body
$input = file_get_contents('php://input');
$data = json_decode($input);

header('Content-Type: application/json; charset=utf-8');

// Router por action en payload
$action = isset($_GET['action']) ? $_GET['action'] : ($data->action ?? null);

if ($action === 'guardar_config') {
  $templates = $data->templates ?? [];
  $receptores = $data->receptores ?? null;
  $ok = $modelo->guardarPlantillas($templates);
  $ok2 = true;
  if ($receptores !== null) {
    $ok2 = $modelo->guardarReceptores($receptores);
  }
  echo json_encode(['success' => ($ok && $ok2)]);
  exit;
}

if ($action === 'enviar_prueba') {
  $phones = $data->phones ?? [];
  $message = $data->message ?? '';

  // Verifica las credenciales twilio en config.php
  if (!defined('TWILIO_SID') || !defined('TWILIO_AUTH_TOKEN') || !defined('TWILIO_FROM')) {
    echo json_encode(['success' => false, 'error' => 'Twilio credentials not configured. Define TWILIO_SID, TWILIO_AUTH_TOKEN and TWILIO_FROM in config.php']);
    exit;
  }

  $sid = TWILIO_SID;
  $token = TWILIO_AUTH_TOKEN;
  $from = TWILIO_FROM;

  $results = [];
  foreach ($phones as $phone) {
    $res = sendSms($sid, $token, $from, $phone, $message);
    $results[] = $res;
    // Registra los errores en logs
    $estado = isset($res['error']) ? 'error' : 'ok';
    $modelo->registrarEnvio($phone, $message, $estado);
  }

  echo json_encode(['success' => true, 'results' => $results]);
  exit;
}

// Muestra las plantillas actuales
 $plantillas = $modelo->obtenerPlantillas();
 $receptores_guardados = $modelo->obtenerReceptores();
 echo json_encode(['success' => true, 'templates' => $plantillas, 'receptores' => $receptores_guardados]);

function sendSms($sid, $token, $from, $to, $body) {
  $url = "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json";
  $data = http_build_query([
    'From' => $from,
    'To' => $to,
    'Body' => $body
  ]);

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  $response = curl_exec($ch);
  $err = curl_error($ch);
  curl_close($ch);

  if ($err) {
    return ['error' => $err];
  }

  $decoded = json_decode($response, true);
  return $decoded ?: ['raw' => $response];
}

?>
