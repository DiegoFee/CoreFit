    <aside class="sidebar" aria-label="Navegación principal">
      <div class="logo-box">
        <img src="<?php echo BASE_URL;?>vistas/public/images/logo-novacorp.jpg" alt="Logo NovaCorp" class="logo-img">
      </div>
      <nav class="menu">
        <a href="<?php echo BASE_URL;?>vistas/inicio.php" target="_self" class="menu-item"><i class="fa fa-home"></i> <span>Inicio</span></a>
        <a href="<?php echo BASE_URL;?>vistas/miembros.php" target="_self" class="menu-item"><i class="fa fa-users"></i> <span>Miembros</span></a>
        <a href="<?php echo BASE_URL;?>vistas/pagos.php" target="_self" class="menu-item"><i class="fa fa-dollar-sign"></i> <span>Pagos</span></a>
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
          padding-top: 10vh;
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
          justify-content:start;
          padding: var(--space-md) 0;
          top: 0;
          z-index: 2;
          box-shadow: var(--shadow);

          position: fixed;
          left: 0;
          height: 100vh;
        }

        /* contenedor principal de todo */
        .main-content-wrapper {
          flex: 1;
          display: flex;
          flex-direction: column;
          min-height: 100vh;
          margin-left: 240px;
        }

        /* ==============================
        DISEÑO RESPONSIVE
        ============================== */
        @media (max-width: 1024px) {
          .container {
            flex-direction: column;
          }
          .sidebar {
            width: 100%;
            height: auto;
            flex-direction: row;
            overflow-x: auto;
            padding: var(--space-xs) 0;
            position: fixed;
            top: 0;
            z-index: 1000;
            justify-content: flex-start;
          }
          .menu {
            flex-direction: row;
            gap: var(--space-xs);
            width: auto;
            padding: 0 var(--space-sm);
            padding-top: 0;
          }
          .menu-item {
            flex-direction: column;
            padding: var(--space-xs);
            font-size: 0.9rem;
            white-space: nowrap;
            min-width: 80px;
          }
          .menu-item span {
            font-size: 0.8rem;
          }
          .logo-box {
            margin-bottom: 0;
            margin-right: var(--space-sm);
          }
          .logo-img {
            height: 40px;
          }
          .main-content-wrapper {
            margin-left: 0;
            margin-top: 80px;
          }
        }

        @media (max-width: 768px) {
          .sidebar {
            height: 70px;
          }
          .menu-item {
            padding: var(--space-xs) 0.5rem;
            min-width: 60px;
          }
          .menu-item i {
            font-size: 1rem;
          }
          .menu-item span {
            font-size: 0.7rem;
          }
          .logo-img {
            height: 35px;
          }
          .main-content-wrapper {
            margin-top: 70px;
          }
        }

        @media (max-width: 480px) {
          .menu-item {
            padding: 0.3rem;
            min-width: 50px;
          }
          .menu-item span {
            display: none;
          }
          .logo-img {
            height: 30px;
          }
        }
      </style>
    </aside>