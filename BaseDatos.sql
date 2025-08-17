CREATE DATABASE IF NOT EXISTS finanzas_seguras;
USE finanzas_seguras;
-- Tabla de clientes
CREATE TABLE clientes (
 id_cliente INT AUTO_INCREMENT PRIMARY KEY,
 identificacion VARCHAR(20) NOT NULL UNIQUE,
 apellidos VARCHAR(100) NOT NULL,
 nombre VARCHAR(100) NOT NULL,
 telefono_personal VARCHAR(20),
 direccion_personal VARCHAR(255),
 email VARCHAR(150) UNIQUE,
 lugar_trabajo VARCHAR(150),
 direccion_trabajo VARCHAR(255),
 telefono_trabajo VARCHAR(20),
 usuario VARCHAR(50) NOT NULL UNIQUE,
 contrasena VARCHAR(255) NOT NULL
);
-- Tabla de prestamos
CREATE TABLE prestamos (
 id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
 id_cliente INT NOT NULL,
 monto_total DECIMAL(10,2) NOT NULL,
 fecha_inicio DATE NOT NULL,
 estado ENUM('Activo','Cancelado') DEFAULT 'Activo',
 FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente) ON
DELETE CASCADE
);
-- Tabla de pagos
CREATE TABLE pagos (
 id_pago INT AUTO_INCREMENT PRIMARY KEY,
 id_prestamo INT NOT NULL,
 monto_pagado DECIMAL(10,2) NOT NULL,
 fecha_pago DATE NOT NULL,
 numero_deposito VARCHAR(50) NOT NULL,
 FOREIGN KEY (id_prestamo) REFERENCES prestamos(id_prestamo) ON
DELETE CASCADE
);
-- Insertar usuario de prueba
INSERT INTO clientes (
  identificacion, apellidos, nombre, telefono_personal,
  direccion_personal, email,
  lugar_trabajo, direccion_trabajo, telefono_trabajo, usuario,
  contrasena
) VALUES
(
  '12345678', 'Pérez', 'Juan', '8888-8888', 'San José, Costa Rica', 'juan.perez@example.com',
  'Banco Nacional', 'San José Centro', '2222-2222', 'juanp',
  '$2y$10$pruebahash1234567890123456789012345678901234567890'
),
(
  '23456789', 'Rodríguez', 'María', '8999-1234', 'Alajuela, Costa Rica', 'maria.rodriguez@example.com',
  'Hospital México', 'La Uruca', '2233-4455', 'mariar',
  '$2y$10$hashmaria9876543210987654321098765432109876543210'
),
(
  '34567890', 'González', 'Carlos', '8777-5678', 'Cartago, Costa Rica', 'carlos.gonzalez@example.com',
  'ICE', 'Cartago Centro', '2244-5566', 'carlosg',
  '$2y$10$hashcarlos1234567890123456789012345678901234567890'
),
(
  '45678901', 'Ramírez', 'Ana', '8655-4321', 'Heredia, Costa Rica', 'ana.ramirez@example.com',
  'Universidad Nacional', 'Heredia Campus', '2255-6677', 'anar',
  '$2y$10$hashana0987654321098765432109876543210987654321'
),
('123', 'Sanchez Molina', 'Steven', '60664678', 'El Roble, Alajuela', 'steven1@gmail.com', 
'Banco Nacional', 'El Roble', '2424242424', '123', 
'$2y$10$PQ6QErlzH9dl.Tyx6gWbz.n7RY9NmljdtSsmdVBcNB.ParsOVsnRi'),
(
  '56789012', 'Jiménez', 'Luis', '8544-8765', 'Puntarenas, Costa Rica', 'luis.jimenez@example.com',
  'Recope', 'Barranca', '2266-7788', 'luisj',
  '$2y$10$hashluis5678901234567890123456789012345678901234'
);

-- Insertar préstamo de prueba
INSERT INTO prestamos (id_cliente, monto_total, fecha_inicio, estado)
VALUES
(1, 5000.00, '2025-01-15', 'Activo'),
(2, 7500.00, '2025-02-10', 'Activo'),
(3, 3200.00, '2025-03-05', 'Cancelado'),
(4, 10000.00, '2025-04-20', 'Activo'),
(5, 4500.00, '2025-05-12', 'Cancelado'),
(1, 2500.00, '2025-06-01', 'Activo'),
(3, 6000.00, '2025-07-18', 'Cancelado'),
(2, 1500.00, '2025-08-10', 'Activo');