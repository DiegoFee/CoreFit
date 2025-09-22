      <header class="header">  
        <!-- CONTENIDO DEL CONTENEDOR <HEADER> -->
        <div class="app-icon">
          <img src="<?php echo BASE_URL;?>vistas/public/icons/favicon.ico" alt="Icono de la aplicación">
        </div>
        <div class="user-box">
          <span class="user-type">Recepcionista</span>
            <a href="<?php echo BASE_URL; ?>controladores/controladorLogin.php?logout=1" class="logout-btn">Cerrar sesión</a>          
          <div class="user-icon">
            <img src="<?php echo BASE_URL;?>vistas/public/icons/usuario2.ico" alt="Icono de usuario">
          </div>
        </div>

        <!-- ESTILOS DE TODO <HEADER> -->
        <style>
          .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--paua);
            color: var(--white);
            padding: var(--space-sm) var(--space-lg);
            box-shadow: var(--shadow);
            z-index: 10;

            position: fixed;
            left: 240px;
            width: calc(100% - 240px);
          }
          .main-content-scroll {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding-top: 90px;
          }

          .app-icon img {
            width: 50px;
            height: 50px;
          }
          .user-box {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
          }
          .logout-btn {
            background: var(--dodger-blue);
            border: none;
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius);
            color: var(--white);
            cursor: pointer;
            font-weight: 500;
            transition: background var(--transition);
            text-decoration: none;
          }
          .logout-btn:hover {
            background: var(--lime);
            color: var(--paua);
          }
          .user-icon img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--dodgerblue);
          }

          /* ==============================
          DISEÑO RESPONSIVE
          ============================== */
          @media (max-width: 1024px) {
            .header {
              left: 0;
              width: 100%;
              top: 80px;
            }
            .main-content-scroll {
              padding-top: 170px;
            }
            .app-icon img {
              width: 40px;
              height: 40px;
            }
            .user-icon img {
              width: 35px;
              height: 35px;
            }
            .logout-btn {
              padding: 0.4rem 0.8rem;
              font-size: 0.9rem;
            }
          }

          @media (max-width: 768px) {
            .header {
              top: 70px;
              padding: var(--space-xs) var(--space-sm);
            }
            .main-content-scroll {
              padding-top: 150px;
            }
            .app-icon img {
              width: 35px;
              height: 35px;
            }
            .user-icon img {
              width: 30px;
              height: 30px;
            }
            .user-box {
              gap: 0.5rem;
            }
            .user-type {
              font-size: 0.9rem;
            }
          }

          @media (max-width: 480px) {
            .header {
              flex-direction: column;
              gap: var(--space-xs);
              padding: var(--space-xs);
              top: 60px;
            }
            .main-content-scroll {
              padding-top: 140px;
            }
            .app-icon img {
              width: 30px;
              height: 30px;
            }
            .user-icon img {
              width: 25px;
              height: 25px;
            }
            .logout-btn {
              padding: 0.3rem 0.6rem;
              font-size: 0.8rem;
            }
            .user-type {
              font-size: 0.8rem;
            }
          }
        </style>
      </header>