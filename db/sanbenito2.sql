create database sanbenito2;
use sanbenito2;
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    email VARCHAR(100) NOT NULL,
    password CHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE, 
    rol VARCHAR(50)
);
CREATE TABLE personal(
    id_personal INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    imagen_personal varchar(200),
    nombres VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100),
    apellido_materno VARCHAR(100),
    num_celular VARCHAR(20) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    num_carnet VARCHAR(30) NOT NULL,
    profesion VARCHAR(30) NOT NULL,
    especialidad VARCHAR(30) NOT NULL,
    matricula_profesional VARCHAR(50), 
    fecha_registro DATE NOT NULL,
    id_usuario INT unique not null,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

CREATE TABLE propietario(
id_propietario int auto_increment primary key not null,
nombres varchar(100) not null,
apellido_paterno varchar(100),
apellido_materno varchar(100),
num_carnet varchar(30),
num_celular varchar(20) not null,
num_celular_secundario varchar(20),
email varchar(60),
direccion varchar(100) not null,
fecha_registro date not null,
id_personal int,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal)
);
CREATE TABLE mascota(
id_mascota int auto_increment primary key not null,
codigo_mascota varchar(30) not null unique, 
imagen_mascota varchar(200), 
nombre varchar(100) not null,
especie varchar(30) not null,
sexo varchar(20) not null,
color varchar(60) not null,
raza varchar(60) not null,
fecha_nacimiento date not null,
fecha_registro date not null,
id_propietario int ,
FOREIGN KEY (id_propietario) REFERENCES propietario(id_propietario) ON DELETE SET NULL
);