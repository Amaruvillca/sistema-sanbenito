drop database sanbenito;
create database sanbenito;
use sanbenito;
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    email VARCHAR(60) NOT NULL,
    password CHAR(60) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE, 
    rol VARCHAR(30)
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
email varchar(60) not null,
direccion varchar(100) not null,
fecha_registro date not null,
id_personal int,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal)
);
ALTER TABLE propietario
MODIFY COLUMN email varchar(60) NULL;

ALTER TABLE propietario
ADD COLUMN num_celular_secundario varchar(20) AFTER num_celular;

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
drop table mascota;
create table vacuna(
id_vacuna int auto_increment primary key not null,
contra varchar(30) not null,
nom_vac varchar(30) not null,
costo decimal(3,3) not null,
fecha_vacuna date not null,
proxima_vacuna date not null,
id_mascota int,
id_personal int,
FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL
);
ALTER TABLE vacuna MODIFY COLUMN costo DECIMAL(10,2);

drop table vacuna;
create table desparasitacion(
id_desparasitacion int auto_increment primary key not null,
producto varchar(30) not null,
tipo_desparasitacion varchar(30) not null,
principio_activo varchar(30) not null,
via varchar(30) not null,
costo DECIMAL(10,2) not null,
fecha_aplicacion date not null,
proxima_desparasitacion date not null,
id_mascota int,
id_personal int,
FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL
);

drop table servicios;
CREATE TABLE cirugias (
    id_cirugia INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_cirugia VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro DATE NOT NULL, 
    id_personal INT , 
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal)
);
CREATE TABLE servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_servicio VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro DATE NOT NULL , 
    id_personal INT NOT NULL,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) 
);
INSERT INTO servicios (nombre_servicio, descripcion, estado, fecha_registro, id_personal)
VALUES ('Limpieza dental', 'Servicio de limpieza dental', TRUE, '2024-09-19', 1);

INSERT INTO cirugias (nombre_cirugia, descripcion, estado, fecha_registro, id_personal)
VALUES ('Esterilización', 'Cirugía para esterilizar a mascotas', TRUE, '2024-09-19', 1);
drop table atiende_servicio;

CREATE TABLE atiende_servicio (
    id_atencion_servicio INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    observaciones VARCHAR(255) NOT NULL,
    costo DECIMAL(10,2) NOT NULL,
    fecha_servicio DATE NOT NULL,
    id_mascota INT NOT NULL,
    id_personal INT NOT NULL,
    id_servicio INT NOT NULL,
    FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio) ON DELETE SET NULL
);
select * from vacuna;
SELECT v.*, 
       m.nombre AS nombre_mascota, 
       m.imagen_mascota AS imagen_mascota, 
       p.nombres AS nombre_propietario
FROM vacuna v
JOIN mascota m ON v.id_mascota = m.id_mascota
JOIN propietario p ON m.id_propietario = p.id_propietario
WHERE v.id_vacuna = 12;

SELECT v.*, 
       m.nombre AS nombre_mascota, 
       m.imagen_mascota AS imagen_mascota, 
       p.nombres AS nombre_propietario,
       p.apellido_paterno AS apellidopa_propietario,
       p.apellido_materno AS apellidoma_propietario,
       vet.nombres AS nombre_veterinario,
       vet.apellido_paterno AS apellidopa_veterinario,
       vet.apellido_materno AS apellidoma_veterinario
FROM vacuna v
JOIN mascota m ON v.id_mascota = m.id_mascota
JOIN propietario p ON m.id_propietario = p.id_propietario
JOIN personal vet ON v.id_personal = vet.id_personal
WHERE v.id_vacuna = 11;



