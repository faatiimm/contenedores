-- Active: 1767719574796@@127.0.0.1@3307@retrogaming_hub
/**
 * Script de creación de la Base de Datos: retrogaming_hub
 * Proyecto: Foro de preservación y discusión de videojuegos clásicos.
 */

-- Creación del contenedor de datos con soporte para caracteres especiales (tildes, eñes, emojis)
CREATE DATABASE IF NOT EXISTS retrogaming_hub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE retrogaming_hub;

/**
 * Tabla: usuarios
 * Almacena la información de identidad y credenciales de acceso.
 * Requisito: Gestión de inicio de sesión mediante DB.
 */
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY, -- Identificador único autoincremental
    nombre VARCHAR(50) NOT NULL,               -- Nombre de visualización en el foro
    email VARCHAR(100) NOT NULL UNIQUE,        -- Correo único para evitar registros duplicados
    password VARCHAR(255) NOT NULL,            -- Hash de la contraseña (almacenamiento seguro)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha automática de alta
) ENGINE=InnoDB;

/**
 * Tabla: posts
 * Almacena las publicaciones o hilos creados por los usuarios.
 * Relación: Muchos a Uno (Un usuario puede tener muchos posts).
 */
CREATE TABLE posts (
    id_post INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    contenido TEXT NOT NULL,
    categoria VARCHAR(50) NOT NULL,             -- Campo utilizado para el filtrado en el Feed
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,                   -- Clave foránea que vincula con el autor
    -- Restricción de Integridad: Si se borra un usuario, se eliminan sus posts automáticamente
    FOREIGN KEY (id_usuario)
        REFERENCES usuarios(id_usuario)
        ON DELETE CASCADE
) ENGINE=InnoDB;

/**
 * Tabla: comentarios
 * Almacena las respuestas dentro de cada hilo (Thread).
 * Relación: Vincula usuarios con posts específicos.
 */
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    contenido TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_usuario INT NOT NULL,                   -- Autor del comentario
    id_post INT NOT NULL,                      -- Hilo al que pertenece el comentario
    -- Integridad Referencial:
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_post) REFERENCES posts(id_post) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- Datos de Inicialización (Seeders)
-- =========================================
INSERT INTO usuarios (nombre, email, password)
VALUES ('Admin', 'admin@retrohub.com', '$2y$10$xyz...'); -- Ejemplo de registro administrativo

INSERT INTO posts (titulo, contenido, categoria, id_usuario)
VALUES ('Mi primer juego retro', 'Hablemos de clásicos de la NES...', 'Nintendo', 1);