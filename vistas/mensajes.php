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
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/mensajes.css">
  <link rel="icon" href="<?php echo BASE_URL;?>vistas/public/icons/favicon.ico" type="image/ico">
</head>
<body>
  <div class="container">
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
      echo '<style> .menu-item:nth-child(5) { background: var(--lime); color: var(--paua); } </style>';
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
        <section class="content content-mensajes">
          <h1 class="content-title">Envío de Mensajes Automático</h1>

          <!-- CONTENEDOR DE MENSAJES -->
          <h2 class="subtitle">Mensajes</h2>
          <div class="cards-wrapper">
            <div id="mensajes-card" class="card-inner">
              <div class="messages-toolbar" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <div></div>
                <div>
                  <!-- <button id="add-message-btn" class="add-btn">+ Agregar mensaje personalizado</button> -->
                </div>
              </div>
              <div id="mensajes-container" class="mensajes-container">
                <div class="mensaje-card">
                  <div class="mensaje-header">
                    <h3>Bienvenida</h3>
                    <label class="switch">
                      <input type="checkbox" class="mensaje-toggle" checked>
                      <span class="slider"></span>
                    </label>
                  </div>
                  <textarea placeholder="Mensaje de bienvenida..." rows="4">Bienvenido al gimnasio CoreFit. ¡Esperamos verte pronto! Estamos para servirte.</textarea>
                  <button class="send-btn">Probar mensaje</button>
                </div>
                <div class="mensaje-card">
                  <div class="mensaje-header">
                    <h3>Aviso de vencimiento</h3>
                    <label class="switch">
                      <input type="checkbox" class="mensaje-toggle" checked>
                      <span class="slider"></span>
                    </label>
                  </div>
                  <div class="days-input">
                    <span>Avisar</span>
                    <input type="number" value="3" min="1" max="30" title="Días antes">
                    <span>días antes</span>
                  </div>
                  <textarea placeholder="Mensaje personalizado..." rows="4">Tu membresía vence en {dias} días. Recuerda renovar para mantener los beneficios de tu membresía.</textarea>
                  <button class="send-btn">Probar mensaje</button>
                </div>
                <div class="mensaje-card">
                  <div class="mensaje-header">
                    <h3>Recordatorio de asistencia</h3>
                    <label class="switch">
                      <input type="checkbox" class="mensaje-toggle">
                      <span class="slider"></span>
                    </label>
                  </div>
                  <div class="days-input">
                    <span>Enviar cada</span>
                    <input type="number" value="7" min="1" max="365" title="Enviar cada X días">
                    <span>días</span>
                  </div>
                  <textarea placeholder="Mensaje de recordatorio..." rows="4">Te extrañamos en el gimnasio. ¡Recuerda mantener tu rutina! Te esperamos pronto.</textarea>
                  <button class="send-btn">Probar mensaje</button>
                </div>
              </div>
            </div>

            <!-- CONTENEDOR DE RECEPTORES -->
            <div class="card-inner">
              <h2 class="subtitle">Receptores</h2>
              <div class="search-member">
                <input type="text" placeholder="Buscar miembro...">
              </div>
              <div class="table-wrapper">
                <table class="receptores-table">
                  <thead>
                    <tr>
                      <th><input type="checkbox" id="select-all-members" title="Seleccionar todos"></th>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Teléfono</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    require_once __DIR__ . "/../modelos/modeloMiembros.php";
                    $modeloMiembros = new ModeloMiembros($conexion);
                    $miembros = $modeloMiembros->obtenerMiembros();

                    foreach($miembros as $miembro) {
                      // Determinar estado según fecha_hasta (Activo o Vencido)
                      $fecha_hasta = isset($miembro['fecha_hasta']) ? $miembro['fecha_hasta'] : null;
                      $estado = '—';
                      if ($fecha_hasta) {
                        $estado = (strtotime($fecha_hasta) >= strtotime(date('Y-m-d'))) ? 'Activo' : 'Vencido';
                      }

                      echo '<tr>';
                      echo '<td><input type="checkbox" class="member-select" data-id="' . $miembro['id'] . '"></td>';
                      // Mostrar tarjeta rfid como identificador
                      echo '<td>' . htmlspecialchars($miembro['tarjeta_rfid']) . '</td>';
                      echo '<td>' . $miembro['nombre'] . '</td>';
                      echo '<td>' . $miembro['apellido'] . '</td>';
                      echo '<td>' . $miembro['telefono'] . '</td>';
                      echo '<td>' . $estado . '</td>';
                      echo '</tr>';
                    }
                  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Botón para guardar cambios -->
          <div class="actions-container">
            <button id="save-settings-btn" class="save-btn fixed">Guardar configuración</button>
          </div>

        </section>
      </main>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE DE ACCIONES BÁSICAS -->
  <script>window.CoreFitBaseUrl = '<?php echo BASE_URL;?>';</script>
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/mensajes.js"></script>
</body>
</html>
