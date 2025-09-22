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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/inicio.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/membresias.css">
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
      echo '<style> .menu-item:nth-child(4) { background: var(--lime); color: var(--paua); } </style>';
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
        <section class="content" id="membresiasLista" style="display:block;">

          <!-- LISTA DE MEMBRESÍAS CREADAS -->
          <h1 class="content-title">Lista de Membresías Creadas</h1>
          <div class="controls">
            <button class="register-btn" id="btnRegistrar"><i class="fas fa-plus"></i> Registrar Membresía</button>
            <input type="text" class="search-bar" placeholder="Buscar membresías...">
          </div>

          <div class="table-responsive">
            <table class="members-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <!-- <th>Tipo</th> -->
                  <th>Meses</th>
                  <th>Modalidad</th>
                  <th>Precio (Q)</th>
                  <th>Rutinas (PDF)</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>001</td>
                  <td>Membresía Básica</td>
                  <!-- <td>Normal</td> -->
                  <td>3</td>
                  <td>Mensual</td>
                  <td>Q300</td>
                  <td><button class="option-btn">Ver PDF</button></td>

                  <td>
                    <button class="option-btn">Editar</button>
                    <button class="option-btn">Eliminar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- REGISTRO DE NUEVAS MEMBRESÍAS -->
        <section id="registroMembresia" style="display: none;">
          <h2>Registro de Membresías</h2>
          <form>
            <div class="registro-grid">
              <div class="form-row">
                  <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" id="nombre" placeholder="Nombre de la membresía" required>
                  </div>
                  <div class="form-group">
                    <label for="meses">Meses *</label>
                    <input type="number" id="meses" min="1" placeholder="Cantidad de meses" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="modalidad">Modalidad *</label>
                      <select id="modalidad" required>
                        <option value="">Seleccione...</option>
                        <option value="diario">Diario</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label for="precio">Precio (Q) *</label>
                    <input type="number" id="precio" min="0" placeholder="Precio en quetzales" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="rutinas">Rutinas (PDF)</label>
                    <input type="file" id="rutinas" accept="application/pdf">
                  </div>
                </div>
              <div class="form-actions">
                <button type="button" class="back-btn" id="btnAtras">Atrás</button>
                <button type="submit" class="save-btn">Guardar</button>
              </div>
            </div>
          </form>
        </section>
        </section>
      </main>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE DE REGISRO DE NUEVA MEMBRESIA -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/membresias.js"></script>
</body>
</html>