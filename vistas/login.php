<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="NovaCorp">
  <meta name="description" content="Sistema de gestión de gimnasios 'CoreFit' creado por 'NovaCorp'">
  <title>Iniciar sesión - CoreFit</title>
  <link rel="stylesheet" href="public/styles/login.css">
  <link rel="stylesheet" href="public/fonts/fonts.css">
  <link rel="icon" href="public/icons/favicon.ico" type="image/ico">
</head>

<body>
  <main class="contenedor-inicio">
    <div class="contenedor-inicio-imagen">
      <video autoplay muted loop>
        <source src="public/multimedia/sponsor720.mp4" type="video/mp4">
      </video>
    </div>

    <section class="contenedor-inicio-formulario">
      <form class="contenedor-inicio-formulario-parte1" action="#" method="POST" autocomplete="off">
        <h1>Bienvenido a CoreFit</h1>

        <label for="nombre">Usuario</label>
        <input id="nombre" name="usuario" type="text" placeholder="Nombre de usuario" required>

        <label for="contraseña">Contraseña</label>
        <input id="contraseña" name="contraseña" type="password" placeholder="Contraseña" required>

        <button type="submit">Acceder</button>
      </form>

      <div class="contenedor-inicio-formulario-parte2">
        <p>¿Necesita ayuda?</p>
        <a href="#" class="btn-ayuda">Clic aquí</a>
      </div>
    </section>
  </main>    
</body>
</html>
