CREATE DATABASE `corefit`;
USE `corefit`;

--------------- login -----------------
CREATE TABLE Usuarios (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `usuario` VARCHAR(50) NOT NULL,
  `contraseña` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO Usuarios (`usuario`, `contraseña`) VALUES ("Administrador", "admin12345678");
INSERT INTO Usuarios (`usuario`, `contraseña`) VALUES ("Recepcionista", "recep12345678");

--------------- acerca -----------------
CREATE TABLE Acerca (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(100) DEFAULT NULL,
    `contacto` VARCHAR(50) DEFAULT NULL,
    `dueno` VARCHAR(100) DEFAULT NULL,
    `correo` VARCHAR(100) DEFAULT NULL,
    `logo` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

----------------- membresias -----------------
CREATE TABLE Membresias (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nombre` VARCHAR(100) NOT NULL,
  `meses` INT NOT NULL,
  `modalidad` ENUM('diario', 'finde', 'personalizado') NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `rutinas` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

----------------- miembros -----------------
CREATE TABLE Miembros (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `tarjeta_rfid` VARCHAR(50) NOT NULL UNIQUE,
  `nombre` VARCHAR(100) NOT NULL,
  `apellido` VARCHAR(100) NOT NULL,
  `telefono` VARCHAR(20) DEFAULT NULL,
  `foto` VARCHAR(255) DEFAULT NULL,
  `membresia_id` INT NOT NULL,
  `fecha_desde` DATE NOT NULL,
  `fecha_hasta` DATE NOT NULL,
  `precio_total` DECIMAL(10,2) NOT NULL,
  `pagado` DECIMAL(10,2) DEFAULT 0.00,
  `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`membresia_id`) REFERENCES Membresias(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

----------------- pagos -----------------
CREATE TABLE Pagos (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `miembro_id` INT NOT NULL,
  `monto` DECIMAL(10,2) NOT NULL,
  `fecha_pago` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `metodo_pago` ENUM('efectivo', 'transferencia', 'tarjeta') DEFAULT 'efectivo',
  `observaciones` TEXT DEFAULT NULL,
  FOREIGN KEY (`miembro_id`) REFERENCES Miembros(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

----------------- inicio -----------------
CREATE TABLE Asistencias (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `miembro_id` INT NOT NULL,
  `fecha_asistencia` DATE NOT NULL,
  `hora_entrada` TIME NOT NULL,
  `hora_salida` TIME DEFAULT NULL,
  FOREIGN KEY (`miembro_id`) REFERENCES Miembros(`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_asistencia` (`miembro_id`, `fecha_asistencia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

----------------- mensajes -----------------
CREATE TABLE `mensajeslogs` (
  `id` INT AUTO_INCREMENT,
  `telefono` VARCHAR(50) DEFAULT NULL,
  `texto` TEXT DEFAULT NULL,
  `fecha_envio` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `mensajesplantillas` (
  `id` INT AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `texto` TEXT NOT NULL,
  `dias_antes` INT DEFAULT NULL,
  `cada_x_dias` INT DEFAULT NULL,
  `habilitado` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `mensajesreceptores` (
  `id` INT AUTO_INCREMENT,
  `miembros` TEXT DEFAULT NULL,
  `fecha_actualizacion` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

----------------- otros -----------------
DROP TABLE usuarios;
SELECT * FROM usuarios;

DROP TABLE Acerca;
SELECT * FROM Acerca;

DROP TABLE Membresias;
SELECT * FROM Membresias;

DROP TABLE Miembros;
SELECT * FROM Miembros;

DROP TABLE Pagos;
SELECT * FROM Pagos;

DROP TABLE Asistencias;
SELECT * FROM Asistencias;

DROP TABLE mensajeslogs;
SELECT * FROM mensajeslogs;

DROP TABLE mensajesplantillas;
SELECT * FROM mensajesplantillas;

DROP TABLE mensajesreceptores;
SELECT * FROM mensajesreceptores;