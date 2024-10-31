# Sistema de Gestión de Biblioteca

## Descripción
Este es un sistema simple de gestión de biblioteca desarrollado en PHP que permite gestionar libros, usuarios y préstamos. El sistema está implementado en dos versiones: una utilizando **MySQLi** y otra utilizando **PDO**.

## Estructura de Archivos
TALLER_8/
├── mysqli/
│   ├── config.php
│   ├── libros.php
│   ├── usuarios.php
│   ├── prestamos.php
│   └── index.php
├── pdo/
│   ├── config.php
│   ├── libros.php
│   ├── usuarios.php
│   ├── prestamos.php
│   └── index.php
└── README.md


## Instrucciones de Configuración

1. **Clona el repositorio** o descarga los archivos del proyecto.

2. **Crea una base de datos** en tu servidor MySQL. Puedes hacerlo utilizando phpMyAdmin o una herramienta similar en este caso se uso MySQL-Workbench.

3. **Ejecuta los siguientes scripts SQL** para crear las tablas necesarias en tu base de datos:

   ```sql
   CREATE TABLE usuarios_p (
       id INT PRIMARY KEY AUTO_INCREMENT,
       nombre VARCHAR(100) NOT NULL,
       email VARCHAR(100) NOT NULL UNIQUE,
       password VARCHAR(255) NOT NULL
   );

   CREATE TABLE libros (
       id INT PRIMARY KEY AUTO_INCREMENT,
       titulo VARCHAR(150) NOT NULL,
       autor VARCHAR(100) NOT NULL,
       isbn VARCHAR(20) NOT NULL UNIQUE,
       year INT,
       cantidad INT NOT NULL
   );

   CREATE TABLE prestamos (
       id INT PRIMARY KEY AUTO_INCREMENT,
       usuario_id INT,
       libro_id INT,
       fecha_prestamo DATETIME DEFAULT CURRENT_TIMESTAMP,
       fecha_devolucion DATETIME,
       FOREIGN KEY (usuario_id) REFERENCES usuarios_p(id),
       FOREIGN KEY (libro_id) REFERENCES libros(id)
   );

4. **Ejecuta los siguientes URL para ir a las pantalla index**
PDO: http://localhost/TALLERES/TALLER_8/ejercicio_final/pdo/index.php
mysqli: http://localhost/TALLERES/TALLER_8/ejercicio_final/mysqli/index.php