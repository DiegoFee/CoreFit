<?php
// importes generales
require __DIR__ . "/../config.php";
require __DIR__ . "/../controladores/controladorLogin.php";
$usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : null;

// ajustes para que se muestre el logo personalizado
require_once __DIR__ . "/../conexionMysql.php";
require_once __DIR__ . "/../modelos/modeloAcerca.php";
$modeloAcerca = new ModeloAcerca($conexion);
$acerca = $modeloAcerca->obtenerAcerca();
$logoAside = ($acerca && $acerca['logo']) ? $acerca['logo'] : 'logo-novacorp.jpg';

// importes para el MVC
require_once __DIR__ . "/../modelos/modeloMiembros.php";
require_once __DIR__ . "/../modelos/modeloMembresias.php";
$modeloMiembros = new modeloMiembros($conexion);
$modeloMembresias = new modeloMembresias($conexion);
$miembros = $modeloMiembros->obtenerMiembros();
$membresias = $modeloMembresias->obtenerMembresia();
$editando = isset($_GET['editar']) ? intval($_GET['editar']) : null;
$miembroEditar = null;
if ($editando) {
  $miembroEditar = $modeloMiembros->obtenerMiembroPorId($editando);
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/inicio.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/miembros.css">
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
      echo '<style> .menu-item:nth-child(2) { background: var(--lime); color: var(--paua); } </style>';
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
        <section class="content" id="miembrosLista" style="display:block;">
          <h1 class="content-title">Lista de Miembros Registrados</h1>

          <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error" style="margin:12px 0;padding:10px;border-radius:6px;background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;">
              <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
          <?php elseif (isset($_SESSION['success'])): ?>
            <div class="alert alert-success" style="margin:12px 0;padding:10px;border-radius:6px;background:#d4edda;color:#155724;border:1px solid #c3e6cb;">
              <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
          <?php endif; ?>

          <!-- TABLA DE MIEMBROS REGISTRADOS -->
          <div class="controls">
            <button class="register-btn" id="btnRegistrar">+ Registrar miembro</button>
            <input type="text" placeholder="Buscar miembro" class="search-bar">
          </div>
          <div class="table-responsive">
            <table class="members-table">
              <thead>
                <tr>
                  <th>RFID</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Teléfono</th>
                  <th>Tipo de membresía</th>
                  <th>Desde</th>
                  <th>Hasta</th>
                  <th>Días restantes</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($miembros)): ?>
                  <?php foreach ($miembros as $miembro): ?>
                    <?php if ($editando && $miembro['id'] == $editando): ?>
                      <!-- FORMULARIO DE EDICIÓN INLINE -->
                      <tr>
                        <form method="POST" action="<?php echo BASE_URL; ?>controladores/controladorMiembros.php" enctype="multipart/form-data">
                          <td>
                     <input type="text" name="tarjeta_rfid" value="<?php echo htmlspecialchars($miembro['tarjeta_rfid']); ?>" required class="edit-input">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($miembro['id']); ?>">
                          </td>
                          <td>
                            <input type="text" name="nombre" value="<?php echo htmlspecialchars($miembro['nombre']); ?>" required class="edit-input">
                          </td>
                          <td>
                            <input type="text" name="apellido" value="<?php echo htmlspecialchars($miembro['apellido']); ?>" required class="edit-input">
                          </td>
                          <td>
                            <input type="text" name="telefono" value="<?php echo htmlspecialchars($miembro['telefono']); ?>" class="edit-input">
                          </td>
                          <td>
                            <div class="form-group">
                              <label for="foto">Foto</label>
                              <input type="file" name="foto" id="foto" accept="image/png, image/jpeg" class="edit-input">
                              <?php if (!empty($miembro['foto']) && $miembro['foto'] !== '0'): ?>
                                <div style="margin-top:6px;"><img src="<?php echo BASE_URL;?>vistas/public/files/miembros/<?php echo htmlspecialchars($miembro['foto']); ?>" alt="Foto" style="max-width:80px;border-radius:6px;"></div>
                              <?php endif; ?>
                            </div>
                          </td>
                          <td>
                            <select name="membresia_id" required class="edit-input">
                              <?php foreach ($membresias as $membresia): ?>
                                <option value="<?php echo $membresia['id']; ?>" <?php if($miembro['membresia_id']==$membresia['id']) echo 'selected'; ?>>
                                  <?php echo htmlspecialchars($membresia['nombre']); ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <input type="date" name="fecha_desde" value="<?php echo htmlspecialchars($miembro['fecha_desde']); ?>" required class="edit-input">
                          </td>
                          <td>
                            <!-- Fecha 'Hasta' no editable en modo editar inline. Se marca readonly para que se envíe su valor pero no pueda modificarse. -->
                            <input type="date" name="fecha_hasta" value="<?php echo htmlspecialchars($miembro['fecha_hasta']); ?>" required class="edit-input" readonly>
                          </td>
                          <td>
                            <?php 
                              $fechaHasta = new DateTime($miembro['fecha_hasta']);
                              $fechaActual = new DateTime();
                              $diasRestantes = $fechaActual->diff($fechaHasta)->days;
                              echo $diasRestantes;
                            ?>
                          </td>
                          <td>
                            <div class="edit-btn-group">
                              <!-- Tarjeta RFID editable en el campo superior -->
                              <input type="hidden" name="precio_total" value="<?php echo htmlspecialchars($miembro['precio_total']); ?>">
                              <input type="hidden" name="pagado" value="<?php echo htmlspecialchars($miembro['pagado']); ?>">
                              <input type="submit" name="btn-editar" class="option-btn edit-btn" value="Guardar">
                              <a href="<?php echo BASE_URL; ?>vistas/miembros.php" class="option-btn cancel-btn">Cancelar</a>
                            </div>
                          </td>
                        </form>
                      </tr>
                    <?php else: ?>
                      <tr>
                        <td><?php echo htmlspecialchars($miembro['tarjeta_rfid']); ?></td>
                        <td><?php echo htmlspecialchars($miembro['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($miembro['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($miembro['telefono'] ?? 'No registrado'); ?></td>
                        <td><?php echo htmlspecialchars($miembro['membresia_nombre']); ?></td>
                        <td><?php echo date('d-m-y', strtotime($miembro['fecha_desde'])); ?></td>
                        <td><?php echo date('d-m-y', strtotime($miembro['fecha_hasta'])); ?></td>
                        <td>
                          <?php 
                            $fechaHasta = new DateTime($miembro['fecha_hasta']);
                            $fechaActual = new DateTime();
                            $diasRestantes = $fechaActual->diff($fechaHasta)->days;
                            echo $diasRestantes;
                          ?>
                        </td>
                        <td>
                          <a href="<?php echo BASE_URL; ?>vistas/miembros.php?editar=<?php echo $miembro['id']; ?>" class="option-btn">Editar</a>
                          <form method="POST" action="<?php echo BASE_URL; ?>controladores/controladorMiembros.php" style="display:inline;" onsubmit="return confirm('¿Está seguro que desea eliminar este miembro?');">
                            <input type="hidden" name="id" value="<?php echo $miembro['id']; ?>">
                            <input type="submit" name="btn-eliminar" class="option-btn" value="Eliminar" style="background:#e74c3c;">
                          </form>
                        </td>
                      </tr>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" style="text-align:center;">No hay miembros registrados.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>

        <!-- FORMULARIO DE INGRESO DE NUEVO MIEMBRO -->
        <section class="content content-form" id="registroMiembro" style="display:none;">
          <h2>Registro de Miembros</h2>
          <form method="POST" action="<?php echo BASE_URL; ?>controladores/controladorMiembros.php" enctype="multipart/form-data" class="registro-grid">

            <div class="form-row">
              <div class="form-group">
                <label for="tarjeta_rfid">Tarjeta RFID *</label>
                <input type="text" id="tarjeta_rfid" name="tarjeta_rfid" placeholder="Tarjeta RFID" required>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="membresia_id">Membresía *</label>
                <select id="membresia_id" name="membresia_id" required>
                  <option value="" disabled selected>Seleccionar membresía</option>
                  <?php foreach ($membresias as $membresia): ?>
                    <option value="<?php echo $membresia['id']; ?>" data-precio="<?php echo $membresia['precio']; ?>" data-meses="<?php echo $membresia['meses']; ?>">
                      <?php echo htmlspecialchars($membresia['nombre']); ?> - Q<?php echo $membresia['precio']; ?> (<?php echo $membresia['meses']; ?> meses)
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="precio_total">Precio (Q) *</label>
                <input type="number" id="precio_total" name="precio_total" placeholder="Precio" required readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="fecha_desde">Desde *</label>
                <input type="date" id="fecha_desde" name="fecha_desde" required>
              </div>
              <div class="form-group">
                <label for="fecha_hasta">Hasta *</label>
                <input type="date" id="fecha_hasta" name="fecha_hasta" required readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="nombre">Nombre *</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
              </div>
              <div class="form-group">
                <label for="apellido">Apellido *</label>
                <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Teléfono">
              </div>
              <div class="form-group">
                <label for="foto">Foto</label>
                <input type="file" id="foto" name="foto" accept="image/png, image/jpeg">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="pagado">Pago inicial</label>
                <input type="number" id="pagado" name="pagado" placeholder="Pago inicial" min="0" step="0.01">
              </div>
              <div class="form-group form-actions">
                <button type="button" class="back-btn" id="btnAtras">Atrás</button>
                <input type="submit" name="btn-guardar" class="save-btn" value="Guardar">
              </div>
            </div>

          </form>
        </section>
      </main>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE DE REGISRO DE NUEVO MIEMBRO -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/miembros.js">

  </script>
</body>
</html>