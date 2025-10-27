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
require_once __DIR__ . "/../modelos/modeloPagos.php";
require_once __DIR__ . "/../modelos/modeloMiembros.php";
$modeloPagos = new modeloPagos($conexion);
$modeloMiembros = new modeloMiembros($conexion);
$miembrosConPagos = $modeloPagos->obtenerMiembrosConPagos();
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
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/pagos.css">
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
      echo '<style> .menu-item:nth-child(3) { background: var(--lime); color: var(--paua); } </style>';
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

        <section class="content">
          <h1 class="content-title">Pagos efectuados por miembros</h1>

          <!-- CONTROLES PARA FUTUROS FILTROS -->
          <div class="controls-row">
            <div class="left-controls">
            </div>
            <div class="right-controls">
              <input id="searchGlobal" type="search" class="search-bar" placeholder="Buscar por nombre, apellido o estado...">
            </div>
          </div>

          <!-- TABLA DE PAGOS EFECTUADOS -->
          <div class="table-responsive">
            <table id="tablaPagos" class="members-table" aria-label="Tabla de pagos">
              <thead>
                <tr>
                  <th>Id</th>
                  <th>Nombre</th>
                  <th>Apellido</th>
                  <th>Membresía</th>
                  <th>Desde</th>
                  <th>Hasta</th>
                  <th>Actividad</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
              </thead>

              <!-- Ejemplo: filas con atributos data para poblar modal -->
              <tbody>
                <?php if (!empty($miembrosConPagos)): ?>
                  <?php foreach ($miembrosConPagos as $miembro): ?>
                    <?php
                      $fechaHasta = new DateTime($miembro['fecha_hasta']);
                      $fechaActual = new DateTime();
                      $esActivo = $fechaHasta >= $fechaActual;
                      $estaAlDia = $miembro['total_pagado'] >= $miembro['precio_total'];
                      $debe = $miembro['precio_total'] - $miembro['total_pagado'];
                    ?>
                    <tr data-id="<?php echo $miembro['id']; ?>" 
                        data-nombre="<?php echo htmlspecialchars($miembro['nombre']); ?>" 
                        data-apellido="<?php echo htmlspecialchars($miembro['apellido']); ?>" 
                        data-telefono="<?php echo htmlspecialchars($miembro['telefono'] ?? 'No registrado'); ?>" 
                        data-membresia="<?php echo htmlspecialchars($miembro['membresia_nombre']); ?>" 
                        data-precio="<?php echo $miembro['precio_total']; ?>" 
                        data-desde="<?php echo $miembro['fecha_desde']; ?>" 
                        data-hasta="<?php echo $miembro['fecha_hasta']; ?>" 
                        data-actividad="<?php echo $esActivo ? 'Activo' : 'Inactivo'; ?>" 
                        data-estado="<?php echo $estaAlDia ? 'Al día' : 'Moroso'; ?>" 
                        data-pago="<?php echo $miembro['total_pagado']; ?>"
                        data-debe="<?php echo $debe; ?>"
                        data-foto="<?php echo htmlspecialchars($miembro['foto'] ?? ''); ?>">
                      <td><?php echo $miembro['id']; ?></td>
                      <td><?php echo htmlspecialchars($miembro['nombre']); ?></td>
                      <td><?php echo htmlspecialchars($miembro['apellido']); ?></td>
                      <td><?php echo htmlspecialchars($miembro['membresia_nombre']); ?></td>
                      <td><?php echo date('d-m-y', strtotime($miembro['fecha_desde'])); ?></td>
                      <td><?php echo date('d-m-y', strtotime($miembro['fecha_hasta'])); ?></td>
                      <td>
                        <span class="badge <?php echo $esActivo ? 'badge-active' : 'badge-inactive'; ?>">
                          <?php echo $esActivo ? 'Activo' : 'Inactivo'; ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge <?php echo $estaAlDia ? 'badge-ok' : 'badge-danger'; ?>">
                          <?php echo $estaAlDia ? 'Al día' : 'Moroso'; ?>
                        </span>
                      </td>
                      <td class="td-actions">
                        <button class="option-btn pagar-btn <?php echo $estaAlDia ? 'disabled' : ''; ?>" 
                                title="Registrar pago" 
                                <?php echo $estaAlDia ? 'disabled' : ''; ?>>
                          <i class="fa fa-credit-card"></i> Pagar
                        </button>
                        <button class="option-btn detalles-btn" title="Ver detalles">
                          <i class="fa fa-eye"></i> Detalles
                        </button>
                      </td>
                    </tr>
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
      </main>
    </div>
  </div>

  <!-- MODAL PARA LA OPCIÓN DE PAGAR -->
  <div id="modalPagar" class="modal-window" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalPagarTitle">
      <button class="close-btn modal-close" aria-label="Cerrar">&times;</button>
      <h2 id="modalPagarTitle" class="modal-title">Pago de membresía — <span id="pagarNombre"></span></h2>

      <div class="data-grid">
        <div class="data-section">
          <h3>Datos del miembro</h3>
          <div id="miembroDatosPago" class="member-data"></div>
        </div>

        <div class="data-section">
          <h3>Datos del pago</h3>
          <form id="formPago" method="POST" action="<?php echo BASE_URL; ?>controladores/controladorPagos.php" class="payment-form" autocomplete="off">
            <input type="hidden" id="miembroIdPago" name="miembro_id">
            <div class="form-row">
              <label>Membresía <input id="pagarMembresia" type="text" readonly></label>
              <label>Precio (Q) <input id="pagarPrecio" type="number" readonly></label>
            </div>
            <div class="form-row">
              <label>Cantidad que debe <input id="pagarDebe" type="number" readonly></label>
              <label>Cantidad a pagar <input id="pagarCantidad" name="monto" type="number" min="0" step="0.01" required></label>
            </div>
            <div class="form-row">
              <!-- Se utiliza siempre efectivo; lo enviamos oculto desde el formulario -->
              <input type="hidden" name="metodo_pago" value="efectivo">
              <input type="hidden" name="observaciones" value="">
            </div>

            <div class="form-actions">
              <button type="button" class="btn-secondary modal-close">Atrás</button>
              <input type="submit" name="btn-guardar-pago" class="save-btn" value="Guardar pago">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL PARA LA OPCIÓN DE DETALLES -->
  <div id="modalDetalles" class="modal-window" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="modalDetallesTitle">
      <button class="close-btn modal-close" aria-label="Cerrar">&times;</button>
      <h2 id="modalDetallesTitle" class="modal-title">Detalles del miembro — <span id="detallesNombre"></span></h2>

      <div class="data-section">
        <h3>Datos del miembro</h3>
        <div id="miembroDatosDetalles" class="member-data"></div>
        <div class="form-actions">
          <button class="option-btn" id="verFotoBtn"><i class="fa fa-image"></i> Ver foto</button>
          <button class="option-btn" id="imprimirRegistroBtn"><i class="fa fa-print"></i> Imprimir registro de actividad (PDF)</button>
        </div>
      </div>

      <div class="data-section">
        <h3>Calendario / Estadísticas</h3>
        <canvas id="chartPagos" style="max-height:260px;"></canvas>
      </div>

      <div class="data-section">
        <h3>Transacciones</h3>
        <div class="controls-row">
          <input id="searchTrans" class="search-bar" placeholder="Buscar transacción...">
        </div>

        <!-- tabla de transacciones que complementa a los ejemplos estáticos -->
        <div class="table-responsive">
          <table id="tablaTrans" class="members-table">
            <thead>
              <tr>
                <th>Id</th><th>Día</th><th>Membresía</th><th>Meses</th><th>Modalidad</th><th>Precio (Q)</th><th>Fecha inicio</th><th>Fecha final</th><th>Pagado</th><th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>1</td><td>Lunes</td><td>Normal</td><td>1</td><td>Mensual</td><td>200</td><td>01-08-25</td><td>31-08-25</td><td>Q200</td><td>Al día</td></tr>
            </tbody>
          </table>
        </div>

        <div class="form-actions">
          <button id="imprimirHistoriaBtn" class="save-btn"><i class="fa fa-print"></i> Imprimir historial de pagos (PDF)</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL PARA LA OPCIÓN DE VER FOTO -->
  <div id="modalFoto" class="modal-window" aria-hidden="true">
    <div class="modal-content" role="dialog" aria-modal="true">
      <button class="close-btn modal-close" aria-label="Cerrar">&times;</button>
      <div class="data-section" style="align-items:center;">
        <h3>Foto del miembro</h3>
        <img id="fotoImagen" src="/public/icons/usuario.ico" alt="Foto" style="max-width:320px;border-radius:8px;">
      </div>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE Y CONFIGURACIÓN DE VENTANAS MODALES -->
  <script src="<?php echo BASE_URL;?>vistas/public/scripts/pagos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>