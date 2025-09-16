<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="NovaCorp">
  <meta name="description" content="Sistema de gestión de gimnasios 'CoreFit' creado por 'NovaCorp'">
  <title>Pagos - CoreFit</title>
  <link rel="stylesheet" href="public/styles/inicio.css">
  <link rel="stylesheet" href="public/styles/pagos.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="public/icons/favicon.ico" type="image/ico">
</head>

<body>
  <div class="container">
    <!-- SIDEBAR (IMPORTADO DESDE MODULOS) -->
    <?php
    echo '<aside class="sidebar" aria-label="Navegación principal">';
      include "public/modulos/aside.php";
      echo '<style> .menu-item:nth-child(3) { background: var(--lime); color: var(--paua); } </style>';
    echo '</aside>';
    ?>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="main-content-wrapper">
      <!-- HEADER (IMPORTADO DESDE MODULOS) -->
      <?php
      echo '<header class="header">';
        include "public/modulos/header.php";
      echo '</header>';
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
                <tr data-id="1" data-nombre="Luis" data-apellido="Gómez" data-telefono="111 1111" data-membresia="Normal" data-precio="200" data-desde="2025-01-01" data-hasta="2025-01-31" data-actividad="Activo" data-estado="Al día" data-pago="Q200">
                  <td>1</td>
                  <td>Luis</td>
                  <td>Gómez</td>
                  <td>Normal</td>
                  <td>01-01-25</td>
                  <td>31-01-25</td>
                  <td><span class="badge badge-active">Activo</span></td>
                  <td><span class="badge badge-ok">Al día</span></td>
                  <td class="td-actions">
                    <button class="option-btn pagar-btn" title="Registrar pago"><i class="fa fa-credit-card"></i> Pagar</button>
                    <button class="option-btn detalles-btn" title="Ver detalles"><i class="fa fa-eye"></i> Detalles</button>
                  </td>
                </tr>

                <tr data-id="2" data-nombre="María" data-apellido="Pérez" data-telefono="222 2222" data-membresia="Premium" data-precio="350" data-desde="2025-01-05" data-hasta="2025-02-05" data-actividad="Inactivo" data-estado="Moroso" data-pago="Q0">
                  <td>2</td>
                  <td>María</td>
                  <td>Pérez</td>
                  <td>Premium</td>
                  <td>05-01-25</td>
                  <td>05-02-25</td>
                  <td><span class="badge badge-inactive">Inactivo</span></td>
                  <td><span class="badge badge-danger">Moroso</span></td>
                  <td class="td-actions">
                    <button class="option-btn pagar-btn" title="Registrar pago"><i class="fa fa-credit-card"></i> Pagar</button>
                    <button class="option-btn detalles-btn" title="Ver detalles"><i class="fa fa-eye"></i> Detalles</button>
                  </td>
                </tr>

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
          <form id="formPago" class="payment-form" autocomplete="off">
            <div class="form-row">
              <label>Membresía <input id="pagarMembresia" type="text" readonly></label>
              <label>Precio (Q) <input id="pagarPrecio" type="number" readonly></label>
            </div>
            <div class="form-row">
              <label>Cantidad que debe <input id="pagarDebe" type="number" readonly></label>
              <label>Cantidad a pagar <input id="pagarCantidad" type="number" min="0" step="0.01" required></label>
            </div>

            <div class="form-actions">
              <button type="button" class="btn-secondary modal-close">Atrás</button>
              <button type="submit" class="save-btn"><i class="fa fa-save"></i> Guardar pago</button>
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
          <button class="option-btn" id="imprimirRegistroBtn"><i class="fa fa-print"></i> Imprimir registro</button>
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
          <button id="imprimirHistoriaBtn" class="save-btn"><i class="fa fa-file-pdf"></i> Imprimir historia de pagos (PDF)</button>
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
  <script src="public/scripts/pagos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>