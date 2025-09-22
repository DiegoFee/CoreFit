<div class="contenedor">
  <img src="<?php echo BASE_URL;?>vistas/public/images/access.png" alt="Acceso denegado" title="Acceso denegado">
  <h2>Acceso denegado</h2>
  <p>Se requiere autentificación del sistema para acceder a los contenidos de la página. Si ves este mensaje probablemente accediste por otros medios que NO sean el login. </p>
  <p>No tiene permiso para ver este apartado.</p>
</div>

<style>
  .contenedor{
    width: 100vw;
    height: 100vh;
    background-color: var(--paua);
    position: fixed;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: var(--space-md);

    overflow-x: hidden;
    overflow-y: hidden;
    overflow: hidden;
    scrollbar-width: none;
  }
  .contenedor>img{
    width: 100px;
    height: 100px;
    margin-bottom: var(--space-lg);
  }
  .contenedor h2, .contenedor p{
    color: var(--blanco);
    margin-bottom: var(--space-sm);
  }
  .contenedor h2{
    font-size: 2rem;
    margin-bottom: var(--space-md);
  }
  .contenedor p{
    font-size: 1.1rem;
    max-width: 600px;
    line-height: 1.6;
  }

  /* ==============================
  DISEÑO RESPONSIVE
  ============================== */
  @media (max-width: 768px) {
    .contenedor>img{
      width: 80px;
      height: 80px;
    }
    .contenedor h2{
      font-size: 1.5rem;
    }
    .contenedor p{
      font-size: 1rem;
    }
  }

  @media (max-width: 480px) {
    .contenedor{
      padding: var(--space-sm);
    }
    .contenedor>img{
      width: 60px;
      height: 60px;
    }
    .contenedor h2{
      font-size: 1.3rem;
    }
    .contenedor p{
      font-size: 0.9rem;
    }
  }
</style>