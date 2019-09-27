CREATE DATABASE IF NOT EXISTS api_rest_cafeteria;
USE api_rest_cafeteria;

CREATE TABLE usuarios(
    id MEDIUMINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_usuario VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    login VARCHAR(10) not null UNIQUE,
    password VARCHAR(255) NOT NULL,
    correo VARCHAR(50) NOT NULL UNIQUE,
    imagen VARCHAR(255),
    rol VARCHAR(30) NOT NULL,
    creado DATETIME
)ENGINE=InnoDb;

CREATE TABLE categorias_alimentos(
    id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_categoria VARCHAR(20) NOT NULL UNIQUE,
    descripcion_categoria TEXT,
    hora_inicial TIME NOT NULL,
    hora_final TIME NOT NULL
)ENGINE=InnoDb;

CREATE TABLE alimentos(
    id MEDIUMINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_alimento VARCHAR(50) NOT NULL UNIQUE,
    descripcion_alimento TEXT,
    precio REAL(7,2) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    id_categoria_alimento SMALLINT UNSIGNED,
    tiempo_preparacion TIME NOT NULL,
    CONSTRAINT fk_alimento_categoria_alimento FOREIGN KEY(id_categoria_alimento) REFERENCES categorias_alimentos(id)
)ENGINE=InnoDb;

CREATE TABLE ordenes(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_orden DATETIME NOT NULL,
    pago_total REAL(7,2) NOT NULL,
    id_cliente MEDIUMINT UNSIGNED,
    CONSTRAINT fk_orden_usuario FOREIGN KEY(id_cliente) REFERENCES usuarios(id)
)ENGINE=InnoDb;

CREATE TABLE pedidos(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_alimento MEDIUMINT UNSIGNED,
    cantidad TINYINT UNSIGNED NOT NULL,
    precio REAL(7,2) NOT NULL,
    nota TEXT,
    id_orden INT UNSIGNED,
    CONSTRAINT fk_pedido_alimento FOREIGN KEY(id_alimento) REFERENCES alimentos(id),
    CONSTRAINT fk_pedido_orden FOREIGN KEY(id_orden) REFERENCES ordenes(id)
)ENGINE=InnoDb;

CREATE TABLE servicios_comentarios(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    comentario_servicio TEXT NOT NULL,
    id_cliente MEDIUMINT UNSIGNED,
    calificacion_servicio TINYINT UNSIGNED,
    fecha_servicio DATETIME,
    CONSTRAINT fk_servicio_comentario_usuario FOREIGN KEY(id_cliente) REFERENCES usuarios(id)
)ENGINE=InnoDb;

CREATE TABLE alimentos_comentarios(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    comentario_alimento TEXT NOT NULL,
    id_cliente MEDIUMINT UNSIGNED,
    calificacion_alimento TINYINT UNSIGNED,
    id_alimento MEDIUMINT UNSIGNED,
    fecha_alimento DATETIME,
    CONSTRAINT fk_alimento_comentario_usuario FOREIGN KEY(id_cliente) REFERENCES usuarios(id),
    CONSTRAINT fk_alimento_comentario_alimento FOREIGN KEY(id_alimento) REFERENCES alimentos(id)
)ENGINE=InnoDb;

CREATE TABLE datos_cafeteria(
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(30) NOT NULL,
    correo VARCHAR(50) NOT NULL,
    logo VARCHAR(255)
)ENGINE=InnoDb;