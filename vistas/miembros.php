<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="NovaCorp">
  <meta name="description" content="Sistema de gestión de gimnasios 'CoreFit' creado por 'NovaCorp'">
  <title>Miembros - CoreFit</title>
  <link rel="stylesheet" href="public/styles/inicio.css">
  <link rel="stylesheet" href="public/styles/miembros.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="public/icons/favicon.ico" type="image/ico">
</head>

<body>
  <div class="container">
    <!-- SIDEBAR (IMPORTADO DESDE MODULOS) -->
    <?php
    echo '<aside class="sidebar" aria-label="Navegación principal">';
      include "public/modulos/aside.php";
      echo '<style> .menu-item:nth-child(2) { background: var(--lime); color: var(--paua); } </style>';
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
        <section class="content" id="miembrosLista" style="display:block;">
          <h1 class="content-title">Lista de Miembros Registrados</h1>

          <!-- TABLA DE MIEMBROS REGISTRADOS -->
          <div class="controls">
            <button class="register-btn">+ Registrar miembro</button>
            <input type="text" placeholder="Buscar miembro" class="search-bar">
          </div>
          <div class="table-responsive">
            <table class="members-table">
              <thead>
                <tr>
                  <th>Id</th>
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
                <tr>
                  <td>1</td>
                  <td>Luis</td>
                  <td>Gómez</td>
                  <td>111 1111</td>
                  <td>Membresía normal</td>
                  <td>01-01-25</td>
                  <td>01-02-25</td>
                  <td>1</td>
                  <td><button class="option-btn">Editar | Eliminar RFID</button></td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>María</td>
                  <td>Pérez</td>
                  <td>222 2222</td>
                  <td>Membresía premium</td>
                  <td>05-01-25</td>
                  <td>05-02-25</td>
                  <td>5</td>
                  <td><button class="option-btn">Editar | Eliminar RFID</button></td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- FORMULARIO DE INGRESO DE NUEVO MIEMBRO -->
        <section class="content content-form" id="registroMiembro" style="display:none;">
          <h2>Registro de Miembros</h2>
          <form class="registro-grid">
            <div class="form-row">
              <div class="form-group">
                <label for="membresia">Membresía *</label>
                <select id="membresia" name="membresia" required>
                  <option value="" disabled selected>Seleccionar membresía</option>
                  <option value="normal">Membresía normal</option>
                  <option value="premium">Membresía premium</option>
                </select>
              </div>
              <div class="form-group">
                <label for="precio">Precio (Q) *</label>
                <input type="number" id="precio" name="precio" placeholder="Precio" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="desde">Desde *</label>
                <input type="date" id="desde" name="desde" required>
              </div>
              <div class="form-group">
                <label for="hasta">Hasta *</label>
                <input type="date" id="hasta" name="hasta" required>
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
                <label for="telefono">Teléfono *</label>
                <input type="tel" id="telefono" name="telefono" placeholder="Teléfono" required>
              </div>
              <div class="form-group">
                <label for="foto">Foto *</label>
                <input type="file" id="foto" name="foto" accept="image/png, image/jpeg" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="pago">Pago *</label>
                <input type="number" id="pago" name="pago" placeholder="Pago" required>
              </div>
              <div class="form-group form-actions">
                <button type="button" class="back-btn">Atrás</button>
                <button type="submit" class="save-btn">Guardar</button>
              </div>
            </div>
          </form>
        </section>
      </main>
    </div>
  </div>

  <!-- JS PARA DESPLIEGUE DE REGISRO DE NUEVO MIEMBRO -->
  <script src="public/scripts/miembros.js">

  </script>
</body>
</html>