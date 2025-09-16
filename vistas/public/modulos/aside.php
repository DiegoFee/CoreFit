      <div class="logo-box">
        <img src="public/images/logo-novacorp.jpg" alt="Logo NovaCorp" class="logo-img">
      </div>
      <nav class="menu">
        <a href="/COREFIT/vistas/inicio.php" target="_self" class="menu-item"><i class="fa fa-home"></i> <span>Inicio</span></a>
        <a href="/COREFIT/vistas/miembros.php" target="_self" class="menu-item"><i class="fa fa-users"></i> <span>Miembros</span></a>
        <a href="/COREFIT/vistas/pagos.php" target="_self" class="menu-item"><i class="fa fa-dollar-sign"></i> <span>Pagos</span></a>
        <a href="/COREFIT/vistas/membresias.php" class="menu-item"><i target="_self" class="fa fa-dumbbell"></i> <span>Membresías</span></a>
        <a href="/COREFIT/vistas/whatsapp.html" class="menu-item"><i target="_self" class="fa fa-comment-dots"></i> <span>Whatsapp</span></a>
        <a href="/COREFIT/vistas/acerca.html" class="menu-item"><i target="_self" class="fa fa-info-circle"></i> <span>Acerca de</span></a>
      </nav>

      <style>
        /* contenedor de logo de novacorp */
        .container {
          display: flex;
          min-height: 100vh;
          flex-direction: row;
        }
        .logo-box {
          margin-bottom: var(--space-xl);
        }
        .logo-img {
          height: 60px;
          border-radius: var(--radius);
          object-fit: contain;
        }

        /* elementos dentro del sidebar */
        .menu {
          display: flex;
          flex-direction: column;
          width: 100%;
          gap: var(--space-xs);
        }
        .menu-item {
          display: flex;
          align-items: center;
          gap: var(--space-sm);
          padding: var(--space-sm) var(--space-md);
          text-decoration: none;
          color: var(--white);
          font-size: 1.05rem;
          border-radius: var(--radius);
          transition: background var(--transition), color var(--transition);
        }
        .menu-item i {
          min-width: 22px;
          text-align: center;
          font-size: 1.2rem;
        }
        .menu-item:hover,
        .menu-item.active {
          background: var(--lime);
          color: var(--paua);
        }

        /* contenedor principal sibebar */
        .sidebar {
          width: 240px;
          background: var(--paua);
          color: var(--white);
          display: flex;
          flex-direction: column;
          align-items: center;
          padding: var(--space-md) 0;
          position: sticky;
          top: 0;
          min-height: 100vh;
          z-index: 2;
          box-shadow: var(--shadow);
        }

        /* contenedor principal de todo */
        .main-content-wrapper {
          flex: 1;
          display: flex;
          flex-direction: column;
          min-height: 100vh;
        }
      </style>