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
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/acerca.css">
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
      echo '<style> .menu-item:nth-child(6) { background: var(--lime); color: var(--paua); } </style>';
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
        <section class="content content-acerca">
          <h1 class="content-title">Detalles de la Empresa Cliente</h1>

          <!-- ESPACIO PARA LA SUBIDA DE LOGO -->
          <div class="card-inner logo-section">
            <div class="logo-preview">
              <img id="company-logo" src="public/images/logo-novacorp.jpg" alt="Logo de la empresa">
            </div>
            <div class="logo-actions">
              <input type="file" id="logo-input" accept="image/*">
              <div class="buttons">
                <button id="save-logo-btn" class="save-btn">Guardar Imagen</button>
                <button id="delete-logo-btn" class="delete-btn">Eliminar Imagen</button>
              </div>
            </div>
          </div>

          <!-- ESPACIO PARA LA SUBIDA DE DATOS -->
          <div class="card-inner datos-empresa">
            <label>Nombre de la empresa:</label>
            <input type="text" placeholder="Nombre de la empresa cliente">

            <label>Teléfono de la empresa:</label>
            <input type="text" placeholder="Teléfono de la empresa cliente">

            <label>Dueño de la empresa:</label>
            <input type="text" placeholder="Nombre del dueño">

            <label>Correo de la empresa:</label>
            <input type="email" placeholder="Correo de la empresa cliente">

            <div class="action-buttons">
              <button class="delete-btn">Borrar Cambios</button>
              <button class="save-btn">Guardar Cambios</button>
            </div>
          </div>

        </section>
      </main>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE DE ACCIONES BÁSICAS -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/acerca.js"></script>
</body>
</html>
