# CoreFit - Sistema de Gestión de Gimnasios

Sistema completo para la gestión de gimnasios pequeños que funciona con tarjetas RFID, desarrollado con PHP puro, CSS, JavaScript y MySQL usando el patrón MVC.

## Características Principales

### 🏋️ Gestión de Miembros
- Registro de nuevos miembros con tarjetas RFID
- Carga de fotos de miembros
- Asignación automática de membresías
- Cálculo automático de fechas de vencimiento
- Edición y eliminación de miembros

### 💳 Gestión de Pagos
- Registro de pagos por efectivo, transferencia o tarjeta
- Control de estado de pagos (Al día / Moroso)
- Historial completo de pagos
- Generación de PDFs de historial de pagos
- Generación de PDFs de registro de actividad

### 🎯 Control de Asistencia
- Registro de asistencia por tarjeta RFID
- Verificación automática de membresías activas
- Control de pagos pendientes
- Estadísticas en tiempo real
- Mensajes automáticos según el estado del miembro

### 📊 Dashboard Dinámico
- Estadísticas actualizadas en tiempo real
- Contador de miembros activos
- Asistencias del día
- Pagos pendientes
- Actualización automática cada 5 minutos

## Instalación

### Requisitos
- XAMPP (Apache + MySQL + PHP)
- PHP 7.4 o superior
- MySQL 5.7 o superior

### Pasos de Instalación

1. **Clonar o copiar el proyecto**
   ```bash
   # Copiar la carpeta CoreFit a C:\xampp\htdocs\
   ```

2. **Configurar la base de datos**
   - Abrir phpMyAdmin (http://localhost/phpmyadmin)
   - Ejecutar las consultas del archivo `corefit.sql` para crear la base de datos y tablas

3. **Configurar la conexión**
   - Verificar que `conexionMysql.php` tenga las credenciales correctas
   - Por defecto: usuario `root`, sin contraseña, puerto `3306`

4. **Configurar permisos de carpetas**
   ```bash
   # Asegurar que las siguientes carpetas tengan permisos de escritura:
   CoreFit/vistas/public/files/logos/
   CoreFit/vistas/public/files/miembros/
   CoreFit/vistas/public/files/rutinas/
   CoreFit/vistas/public/files/pdf/
   ```

5. **Acceder al sistema**
   - URL: http://localhost/CoreFit/vistas/login.php
   - Usuario: `Administrador` / Contraseña: `admin12345678`
   - Usuario: `Recepcionista` / Contraseña: `recep12345678`

## Estructura del Proyecto

```
CoreFit/
├── config.php                 # Configuración general
├── conexionMysql.php          # Conexión a base de datos
├── corefit.sql               # Scripts de base de datos
├── controladores/            # Lógica de negocio
│   ├── controladorAcerca.php
│   ├── controladorLogin.php
│   ├── controladorMembresias.php
│   ├── controladorMiembros.php
│   ├── controladorPagos.php
│   └── controladorInicio.php
├── modelos/                  # Acceso a datos
│   ├── modeloAcerca.php
│   ├── modeloMembresias.php
│   ├── modeloMiembros.php
│   ├── modeloPagos.php
│   └── modeloInicio.php
└── vistas/                   # Interfaz de usuario
    ├── acerca.php
    ├── inicio.php
    ├── login.php
    ├── membresias.php
    ├── miembros.php
    ├── pagos.php
    └── public/
        ├── files/           # Archivos subidos
        ├── scripts/         # JavaScript
        └── styles/          # CSS
```

## Uso del Sistema

### 1. Configurar Empresa
- Ir a "Acerca" para configurar datos de la empresa
- Subir logo personalizado
- Configurar información de contacto

### 2. Crear Membresías
- Ir a "Membresías" para crear tipos de membresías
- Definir precio, duración y modalidad
- Subir PDFs de rutinas (opcional)

### 3. Registrar Miembros
- Ir a "Miembros" para registrar nuevos miembros
- Ingresar datos personales y asignar tarjeta RFID
- Seleccionar membresía (precio y fechas se calculan automáticamente)
- Opcional: realizar pago inicial

### 4. Gestionar Pagos
- Ir a "Pagos" para ver estado de todos los miembros
- Registrar pagos parciales o completos
- Generar PDFs de historial de pagos
- Ver detalles y estadísticas de cada miembro

### 5. Control de Asistencia
- En "Inicio" usar el formulario de asistencia
- Pasar tarjeta RFID o ingresar ID manualmente
- El sistema mostrará mensajes según el estado del miembro
- Las estadísticas se actualizan automáticamente

## Funcionalidades RFID

El sistema está preparado para trabajar con lectores de tarjetas RFID que:
- Envían el ID de la tarjeta seguido de un Enter
- Se pueden simular escribiendo el ID manualmente y presionando Enter
- El sistema valida automáticamente el estado del miembro

## Estados del Sistema

### Miembro Activo/Inactivo
- **Activo**: La membresía no ha expirado
- **Inactivo**: La membresía ha expirado

### Estado de Pago
- **Al día**: Ha pagado el total de su membresía
- **Moroso**: Tiene pagos pendientes

### Mensajes de Asistencia
- **Bienvenida**: Miembro activo y al día
- **Recordatorio de pago**: Miembro activo pero moroso
- **Membresía expirada**: Miembro inactivo
- **No encontrado**: Tarjeta RFID no registrada

## Personalización

### Modificar Estilos
- Los archivos CSS están en `vistas/public/styles/`
- Cada módulo tiene su propio archivo CSS

### Agregar Funcionalidades
- Seguir el patrón MVC existente
- Crear modelo, controlador y vista correspondientes
- Actualizar la base de datos si es necesario

### Configurar URLs
- Modificar `BASE_URL` en `config.php` para producción
- Cambiar de `http://localhost/CoreFit/` a la URL de producción

## Solución de Problemas

### Error de Conexión a Base de Datos
- Verificar que MySQL esté ejecutándose
- Revisar credenciales en `conexionMysql.php`
- Confirmar que la base de datos `corefit` existe

### Archivos No Se Suben
- Verificar permisos de escritura en carpetas `files/`
- Revisar configuración de PHP para uploads

### JavaScript No Funciona
- Verificar que las rutas en `BASE_URL` sean correctas
- Revisar la consola del navegador para errores

### Estadísticas No Se Actualizan
- Verificar que el JavaScript esté cargando correctamente
- Revisar la consola del navegador para errores de AJAX

## Soporte

Para soporte técnico o consultas sobre el sistema CoreFit, contactar al equipo de desarrollo de NovaCorp (este GitHub).

---

**Desarrollado por NovaCorp** - Sistema de Gestión de Gimnasios CoreFit
