<?php
require __DIR__ . "/../config.php";
require __DIR__ . "/../controladores/controladorLogin.php";
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;
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
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/whatsapp.css">
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
        <section class="content content-whatsapp">
          <h1 class="content-title">Envío de Mensajes Automático</h1>

          <!-- CONTENEDOR DE MENSAJES -->
          <h2 class="subtitle">Mensajes</h2>
          <div class="cards-wrapper">
            <div id="mensajes-card" class="card-inner">
              <div id="mensajes-container" class="mensajes-container">
                <div class="mensaje-card">
                  <h3>Bienvenida</h3>
                  <textarea placeholder="Mensaje de bienvenida...">Bienvenido al gimnasio CoreFit. ¡Esperamos verte pronto!</textarea>
                  <button class="send-btn">Enviar prueba</button>
                </div>
                <div class="mensaje-card">
                  <h3>Aviso de vencimiento</h3>
                  <input type="number" value="3" min="1" title="Días antes">
                  <textarea placeholder="Mensaje personalizado...">Tu membresía vence pronto. Recuerda renovar.</textarea>
                  <button class="send-btn">Enviar prueba</button>
                </div>
                <div class="mensaje-card">
                  <h3>Recordatorio de asistencia</h3>
                  <textarea placeholder="Mensaje de recordatorio...">Te esperamos hoy en el gimnasio. ¡No faltes!</textarea>
                  <button class="send-btn">Enviar prueba</button>
                </div>
              </div>
              <button id="add-message-btn" class="add-btn">+ Agregar mensaje</button>
            </div>

            <!-- CONTENEDOR DE RECEPTORES -->
            <div class="card-inner">
              <h2 class="subtitle">Receptores</h2>
              <div class="search-member">
                <input type="text" placeholder="Buscar miembro...">
                <button class="search-btn"><i class="fa fa-search"></i></button>
              </div>
              <div class="table-wrapper">
                <table class="receptores-table">
                  <thead>
                    <tr>
                      <th>Enviar</th>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Apellido</th>
                      <th>Teléfono</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><button class="send-btn">Enviar</button></td>
                      <td>001</td>
                      <td>Juan</td>
                      <td>Pérez</td>
                      <td>+502 1234 5678</td>
                    </tr>
                    <tr>
                      <td><button class="send-btn">Enviar</button></td>
                      <td>002</td>
                      <td>María</td>
                      <td>Gómez</td>
                      <td>+502 9876 5432</td>
                    </tr>
                    <tr>
                      <td><button class="send-btn">Enviar</button></td>
                      <td>003</td>
                      <td>Carlos</td>
                      <td>López</td>
                      <td>+502 4567 8901</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE DE ACCIONES BÁSICAS -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/whatsapp.js"></script>
</body>
</html>
