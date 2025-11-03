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
require_once __DIR__ . "/../modelos/modeloMembresias.php";
$modeloMembresias = new modeloMembresias($conexion);
$membresias = $modeloMembresias->obtenerMembresia();
$editando = isset($_GET['editar']) ? intval($_GET['editar']) : null;
$membresiaEditar = null;
if ($editando) {
  $membresiaEditar = $modeloMembresias->obtenerMembresiaPorId($editando);
}
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
                  <th>Meses</th>
                  <th>Modalidad</th>
                  <th>Precio (Q)</th>
                  <th>Rutinas (PDF)</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($membresias)): ?>
                  <?php foreach ($membresias as $membresia): ?>
                    <?php if ($editando && $membresia['id'] == $editando): ?>
                      <!-- FORMULARIO DE EDICIÓN INLINE -->
                      <tr>
                        <form method="POST" action="<?php echo BASE_URL; ?>controladores/controladorMembresias.php" enctype="multipart/form-data">
                          <td>
                            <?php echo htmlspecialchars($membresia['id']); ?>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($membresia['id']); ?>">
                          </td>
                          <td>
                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($membresia['nombre']); ?>" required class="edit-input">
                          </td>
                          <td>
                            <input type="number" name="meses" value="<?php echo htmlspecialchars($membresia['meses']); ?>" min="1" required class="edit-input">
                          </td>
                          <td>
                            <select name="modalidad" required class="edit-input">
                              <option value="diario" <?php if($membresia['modalidad']=='diario') echo 'selected'; ?>>Diario</option>
                              <option value="finde" <?php if($membresia['modalidad']=='finde') echo 'selected'; ?>>Fines de semana</option>
                              <option value="personalizado" <?php if($membresia['modalidad']=='personalizado') echo 'selected'; ?>>Personalizado</option>
                            </select>
                          </td>
                          <td>
                            <input type="number" name="precio" value="<?php echo htmlspecialchars($membresia['precio']); ?>" min="0" required class="edit-input">
                          </td>
                          <td>
                            <?php if ($membresia['rutinas']): ?>
                              <a href="<?php echo BASE_URL . 'vistas/public/files/rutinas/' . $membresia['rutinas']; ?>" target="_blank" class="option-btn" style="margin-bottom: 6px; display: inline-block;">Ver PDF</a><br>
                            <?php endif; ?>
                            <input type="file" name="rutinas" accept="application/pdf" class="edit-file-input">
                          </td>
                          <td>
                            <div class="edit-btn-group">
                              <input type="submit" name="btn-editar" class="option-btn edit-btn" value="Guardar">
                              <a href="<?php echo BASE_URL; ?>vistas/membresias.php" class="option-btn cancel-btn">Cancelar</a>
                            </div>
                          </td>
                        </form>
                      </tr>
                    <?php else: ?>
                      <tr>
                        <td><?php echo htmlspecialchars($membresia['id']); ?></td>
                        <td><?php echo htmlspecialchars($membresia['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($membresia['meses']); ?></td>
                        <td><?php echo htmlspecialchars(ucfirst($membresia['modalidad'])); ?></td>
                        <td>Q<?php echo htmlspecialchars($membresia['precio']); ?></td>
                        <td>
                          <?php if ($membresia['rutinas']): ?>
                            <a href="<?php echo BASE_URL . 'vistas/public/files/rutinas/' . $membresia['rutinas']; ?>" target="_blank" class="option-btn">Ver PDF</a>
                          <?php else: ?>
                            <span style="color:#888;">No disponible</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="<?php echo BASE_URL; ?>vistas/membresias.php?editar=<?php echo $membresia['id']; ?>" class="option-btn">Editar</a>
                          <form method="POST" action="<?php echo BASE_URL; ?>controladores/controladorMembresias.php" style="display:inline;" onsubmit="return confirm('¿Está seguro que desea eliminar esta membresía?');">
                            <input type="hidden" name="id" value="<?php echo $membresia['id']; ?>">
                            <input type="submit" name="btn-eliminar" class="option-btn" value="Eliminar" style="background:#e74c3c;">
                          </form>
                        </td>
                      </tr>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="7" style="text-align:center;">No hay membresías registradas.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>

        <!-- REGISTRO DE NUEVAS MEMBRESÍAS -->
        <section id="registroMembresia" style="display: none;">
          <h2>Registro de Membresías</h2>
          <form method="POST" action="<?php echo BASE_URL; ?>controladores/controladorMembresias.php" enctype="multipart/form-data" autocomplete="off">
            <div class="registro-grid">
              <div class="form-row">
                  <div class="form-group">
                    <label for="nombre">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Nombre de la membresía" required>
                  </div>
                  <div class="form-group">
                    <label for="meses">Meses *</label>
                    <input type="number" name="meses" id="meses" min="1" placeholder="Cantidad de meses" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="modalidad">Modalidad *</label>
                      <select name="modalidad" id="modalidad" required>
                        <option value="">Seleccione...</option>
                        <option value="diario">Diario</option>
                        <option value="finde">Fines de semana</option>
                        <option value="personalizado">Personalizado</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label for="precio">Precio (Q) *</label>
                    <input type="number" name="precio" id="precio" min="0" placeholder="Precio en quetzales" required>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group">
                    <label for="rutinas">Rutinas (PDF)</label>
                    <input type="file" name="rutinas" id="rutinas" accept="application/pdf">
                  </div>
                </div>
              <div class="form-actions">
                <button type="button" class="back-btn" id="btnAtras">Atrás</button>
                <input type="submit" name="btn-guardar" class="save-btn" value="Guardar">
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