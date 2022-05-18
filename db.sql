CREATE DATABASE polo_tecnologico;
USE polo_tecnologico;

CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    ci CHAR(8) NOT NULL UNIQUE,
    nombre VARCHAR(16) NOT NULL,
    apellido VARCHAR(16) NOT NULL,
    correo VARCHAR(32) NOT NULL,
    telefono CHAR(8), -- sacar telefonos a tabla aparte?
    valido BOOLEAN DEFAULT 0
    -- fecha_registro DATE DEFAULT NOW
);

CREATE TABLE categorias(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(32) UNIQUE
);

CREATE TABLE articulos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(64) NOT NULL,
    cuerpo TEXT NOT NULL, -- varchar hasta 65535 bytes, text 65kb en adelante
    url_imagen VARCHAR(64),
    id_categoria INT NOT NULL REFERENCES categorias(id)
);