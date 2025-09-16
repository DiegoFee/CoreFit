        <!-- CONTENIDO DEL CONTENEDOR <HEADER> -->
        <div class="app-icon">
          <img src="public/icons/favicon.ico" alt="Icono de la aplicación">
        </div>
        <div class="user-box">
          <span class="user-type">Administrador</span>
          <button class="logout-btn">Cerrar sesión</button>
          <div class="user-icon">
            <img src="public/icons/usuario.ico" alt="Icono de usuario">
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
        </style>