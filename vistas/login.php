<?php
require __DIR__ . "/../config.php";
require __DIR__ . "/../controladores/controladorLogin.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="NovaCorp">
  <meta name="description" content="Sistema de gestión de gimnasios 'CoreFit' creado por 'NovaCorp'">
  <title>Iniciar sesión - CoreFit</title>
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/styles/login.css">
  <link rel="stylesheet" href="<?php echo BASE_URL;?>vistas/public/fonts/fonts.css">
  <link rel="icon" href="<?php echo BASE_URL;?>vistas/public/icons/favicon.ico" type="image/ico">
</head>

<body>
  <main class="contenedor-inicio">
    <div class="contenedor-inicio-imagen">
      <video autoplay muted loop>
        <source src="<?php echo BASE_URL;?>vistas/public/multimedia/sponsor720.mp4" type="video/mp4">
      </video>
    </div>

    <section class="contenedor-inicio-formulario">
      <form class="contenedor-inicio-formulario-parte1" method="POST" autocomplete="off">
        <h1>Bienvenido a CoreFit</h1>
        <label for="nombre">Usuario</label>
        <input name="usuario" id="nombre" type="text" placeholder="Nombre de usuario" required>

        <label for="contraseña">Contraseña</label>
        <input name="contraseña" id="contraseña" type="password" placeholder="Contraseña" required>

        <input type="submit" name="btn-ingresar" id="btn-ingresar" value="Acceder">
        
        <?php
          if (!empty($mensaje)) {
            echo "<div class='mensaje-error'>$mensaje</div>";
          }
        ?>  
      </form>

      <div class="contenedor-inicio-formulario-parte2">
        <p>¿Necesita ayuda?</p>
        <a href="https://github.com/DiegoFee" class="btn-ayuda" target="_self">Clic aquí</a>
      </div>
    </section>
  </main>    
</body>
</html>
