<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="NovaCorp">
  <meta name="description" content="Sistema de gestión de gimnasios 'CoreFit' creado por 'NovaCorp'">
  <title>Inicio - CoreFit</title>
  <link rel="stylesheet" href="public/styles/inicio.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" href="public/icons/favicon.ico" type="image/ico">
</head>

<body>
  <div class="container">
    <!-- SIDEBAR (IMPORTADO DESDE MODULOS) -->
    <?php
    echo '<aside class="sidebar" aria-label="Navegación principal">';
      include "public/modulos/aside.php";
      echo '<style> .menu-item:nth-child(1) { background: var(--lime); color: var(--paua); } </style>';
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
        <section class="content content-dashboard">
          <h1 class="content-title">Panel de Control</h1>

          <!-- CARTAS DE CONTENIDO -->
          <div class="stats">
            <div class="card">
              <div class="card-icon"><i class="fa fa-users"></i></div>
              <div class="card-title">Cantidad de miembros</div>
              <div class="card-value">0</div>
              <div class="card-sub">Actualizado hace 1 min</div>
            </div>
            <div class="card">
              <div class="card-icon"><i class="fa fa-calendar-check"></i></div>
              <div class="card-title">Asistencias del día</div>
              <div class="card-value">0</div>
              <div class="card-sub">Actualizado hace 1 min</div>
            </div>
            <div class="card">
              <div class="card-icon"><i class="fa fa-dumbbell"></i></div>
              <div class="card-title">Cantidad de membresías</div>
              <div class="card-value">0</div>
              <div class="card-sub">Actualizado hace 1 min</div>
            </div>
            <div class="card">
              <div class="card-icon"><i class="fa fa-dollar-sign"></i></div>
              <div class="card-title">Pagos pendientes</div>
              <div class="card-value">0</div>
              <div class="card-sub">Actualizado hace 1 min</div>
            </div>
          </div>

          <!-- REGISTRO DE ASISTENCIA -->
          <div class="attendance">
            <h2>Registro de asistencia</h2>
            <form class="attendance-form">
              <input type="text" placeholder="Ingrese ID o nombre">
              <button type="submit" class="confirm-btn">Confirmar asistencia</button>
            </form>
          </div>

        </section>
      </main>
    </div>
  </div>
</body>
</html>
