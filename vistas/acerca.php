<?php
// importes generales
require __DIR__ . "/../config.php";
require __DIR__ . "/../controladores/controladorLogin.php";
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// ajustes para que se muestre el logo personalizado
require_once __DIR__ . "/../modelos/modeloAcerca.php";
require_once __DIR__ . "/../conexionMysql.php";
$modelo = new ModeloAcerca($conexion);
$acerca = $modelo->obtenerAcerca();
$logo = ($acerca && $acerca['logo']) ? $acerca['logo'] : 'logo-novacorp.jpg';
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
        $logoAside = $logo;
        include __DIR__ . "/public/modulos/aside.php";
      } elseif ($usuario == "Recepcionista") {
        $logoAside = $logo;
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

          <form class="card-inner datos-empresa" method="POST" action="<?php echo BASE_URL; ?>controladores/controladorAcerca.php" enctype="multipart/form-data">
            <input type="hidden" name="accion" value="<?php echo $acerca ? 'actualizar' : 'crear'; ?>">
            <?php if ($acerca): ?>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($acerca['id']); ?>">
            <?php endif; ?>

            <div class="logo-section">
              <div class="logo-preview">
                <img id="company-logo"
                  src="<?php echo BASE_URL . 'vistas/public/files/logos/' . $logo; ?>"
                  data-default="<?php echo BASE_URL . 'vistas/public/images/logo-novacorp.jpg'; ?>"
                  alt="Logo de la empresa">
              </div>
              <div class="logo-actions">
                <input type="file" name="logo" id="logo-input" accept="image/*">
                <label style="display:block;margin-top:8px;">
                  <input type="checkbox" name="restaurar_logo" id="restaurar-logo">
                  Restaurar logo por defecto
                </label>
              </div>
            </div>

            <label>Nombre de la empresa:</label>
            <input type="text" name="nombre" value="<?php echo $acerca ? htmlspecialchars($acerca['nombre']) : ''; ?>" placeholder="Nombre de la empresa cliente">

            <label>Teléfono de la empresa:</label>
            <input type="text" name="contacto" value="<?php echo $acerca ? htmlspecialchars($acerca['contacto']) : ''; ?>" placeholder="Teléfono de la empresa cliente">

            <label>Dueño de la empresa:</label>
            <input type="text" name="dueno" value="<?php echo $acerca ? htmlspecialchars($acerca['dueno']) : ''; ?>" placeholder="Nombre del dueño">

            <label>Correo de la empresa:</label>
            <input type="email" name="correo" value="<?php echo $acerca ? htmlspecialchars($acerca['correo']) : ''; ?>" placeholder="Correo de la empresa cliente">

            <div class="action-buttons">
              <input type="reset" class="delete-btn" value="Borrar Cambios">
              <input type="submit" class="save-btn" value="Guardar Cambios">
            </div>
          </form>
        </section>
      </main>
    </div>
  </div>
  
  <!-- JS PARA EL CONTROL DEL LOGO PERSONALIZADO EN EL ASIDE -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/acerca.js"></script>
</body>
</html>
