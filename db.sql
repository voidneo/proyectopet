CREATE DATABASE pet;
USE pet;

CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    ci CHAR(8) NOT NULL UNIQUE,
    nombre VARCHAR(16) NOT NULL,
    apellido VARCHAR(16) NOT NULL,
    correo VARCHAR(32) NOT NULL,
    contrasena CHAR(60) NOT NULL,
    telefono CHAR(8),
    rol CHAR(1) DEFAULT 'e',
    valido BOOLEAN DEFAULT 0,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categorias(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(32) UNIQUE
);

CREATE TABLE articulos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(64) NOT NULL,
    cuerpo TEXT NOT NULL,
    url_imagen VARCHAR(64),
    id_categoria INT NOT NULL REFERENCES categorias(id),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE materias(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(64) NOT NULL UNIQUE
);

CREATE TABLE cursos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(64) NOT NULL UNIQUE,
    duracion VARCHAR(64) NOT NULL,
    id_articulo INT REFERENCES articulos(id)
);

CREATE TABLE materias_curso(
    id_materia INT NOT NULL REFERENCES materias(id),
    id_curso INT NOT NULL REFERENCES cursos(id),
    PRIMARY KEY (id_materia, id_curso)
);

CREATE TABLE inscripciones(
    id INT AUTO_INCREMENT PRIMARY KEY,
    ci_usuario CHAR(8) NOT NULL REFERENCES usuarios(ci),
    id_curso INT NOT NULL REFERENCES cursos(id),
    periodo INT NOT NULL,
    ano TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    valido BOOLEAN DEFAULT 0
);

CREATE TABLE notas(
    id_inscripcion INT NOT NULL REFERENCES inscripciones(id),
    id_materia INT NOT NULL REFERENCES materias(id),
    nota INT NOT NULL,
    PRIMARY KEY (id_inscripcion, id_materia)
);

INSERT INTO usuarios(ci, nombre, apellido, correo, contrasena, telefono, rol, valido) VALUES('00000000', 'Admin', 'istrador', 'admin@polo.edu.uy', '$2y$10$wsiBbw1HfngPIpZQB7.kPetm18jjJLUFMUQVb/JNXY.2j9JumPzRS', '', 'a', 1);

INSERT INTO categorias (nombre) VALUES('noticias');
INSERT INTO categorias (nombre) VALUES('ofertas educativas');
INSERT INTO categorias (nombre) VALUES('ofertas laborales');
INSERT INTO categorias (nombre) VALUES('anuncios');