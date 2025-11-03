<?php
// Importes generales
require __DIR__ . "/../config.php";
require __DIR__ . "/../controladores/controladorLogin.php";
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// Ajustes para que se muestre el logo personalizado
require_once __DIR__ . "/../conexionMysql.php";
require_once __DIR__ . "/../modelos/modeloAcerca.php";
$modeloAcerca = new ModeloAcerca($conexion);
$acerca = $modeloAcerca->obtenerAcerca();
$logoAside = ($acerca && $acerca['logo']) ? $acerca['logo'] : 'logo-novacorp.jpg';

// Importes para el MVC
require_once __DIR__ . "/../modelos/modeloInicio.php";
$modeloInicio = new modeloInicio($conexion);
$estadisticas = $modeloInicio->obtenerEstadisticas();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="NovaCorp">
  <meta name="description" content="Sistema de gestión de gimnasios 'CoreFit' creado por 'NovaCorp'">
  <title>CoreFit</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/inicio.css">
  <link rel="icon" href="<?php echo BASE_URL;?>vistas/public/icons/favicon.ico" type="image/ico">
</head>

<body>
  <div class="container">
    <script>window.CoreFitBaseUrl = '<?php echo BASE_URL; ?>';</script>
    <!-- SIDEBAR (IMPORTADO DESDE MODULOS) -->
    <?php
      if ($usuario == "Administrador") {
        include __DIR__ . "/public/modulos/aside.php";
      } elseif ($usuario == "Recepcionista") {
        include __DIR__ . "/public/modulos/aside2.php";
      } else {
        include __DIR__ . "/public/modulos/permission.php";
        exit();
      }
      echo '<style> .menu-item:nth-child(1) { background: var(--lime); color: var(--paua); } </style>';
    ?>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-content-wrapper">
      <!-- HEADER (IMPORTADO DESDE MODULOS) -->
      <?php
        if ($usuario == "Administrador") {
          include __DIR__ . "/public/modulos/header.php";
        } elseif ($usuario == "Recepcionista") {
          include __DIR__ . "/public/modulos/header2.php";
        } else {
          echo "No tiene permiso para ver este apartado";
        }
      ?>

      <!-- CONTENIDO -->
      <main class="main-content-scroll">
        <section class="content content-dashboard">
          <h1 class="content-title">Panel de Control</h1>

          <!-- CARTAS DE CONTENIDO -->
          <div class="stats">
            <div class="card">
              <div class="card-icon"><i class="fa fa-users"></i></div>
              <div class="card-title">Cantidad de miembros</div>
              <div class="card-value"><?php echo $estadisticas['total_miembros']; ?></div>
              <div class="card-sub">Actualizado hace <span id="tiempoActualizacion">1</span> min</div>
            </div>
            <div class="card">
              <div class="card-icon"><i class="fa fa-calendar-check"></i></div>
              <div class="card-title">Asistencias del día</div>
              <div class="card-value"><?php echo $estadisticas['asistencias_hoy']; ?></div>
              <div class="card-sub">Actualizado hace <span id="tiempoActualizacion2">1</span> min</div>
            </div>
            <div class="card">
              <div class="card-icon"><i class="fa fa-dumbbell"></i></div>
              <div class="card-title">Miembros activos</div>
              <div class="card-value"><?php echo $estadisticas['miembros_activos']; ?></div>
              <div class="card-sub">Actualizado hace <span id="tiempoActualizacion3">1</span> min</div>
            </div>
            <div class="card">
              <div class="card-icon"><i class="fa fa-dollar-sign"></i></div>
              <div class="card-title">Pagos pendientes</div>
              <div class="card-value"><?php echo $estadisticas['miembros_morosos']; ?></div>
              <div class="card-sub">Actualizado hace <span id="tiempoActualizacion4">1</span> min</div>
            </div>
          </div>

          <!-- REGISTRO DE ASISTENCIA -->
          <div class="attendance">
            <h2>Registro de asistencia</h2>
            <form class="attendance-form" id="formAsistencia">
              <input type="text" id="rfid_input" name="rfid_input" placeholder="Ingrese ID manualmente o por tarjeta" required>
              <button type="submit" class="confirm-btn">Confirmar asistencia</button>
            </form>
            
            <!-- Área para mostrar mensajes -->
            <div id="mensajeAsistencia" class="mensaje-asistencia" style="display: none;">
              <div id="contenidoMensaje"></div>
            </div>
          </div>

        </section>
      </main>
    </div>
  </div>
  
  <!-- JS PARA CONTROL DE ASISTENCIA Y ACTUALIZACIÓN DINÁMICA -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/inicio.js"></script>
</body>
</html>
