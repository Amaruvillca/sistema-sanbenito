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
INSERT INTO personal (
    imagen_personal,
    nombres,
    apellido_paterno,
    apellido_materno,
    num_celular,
    direccion,
    num_carnet,
    profesion,
    especialidad,
    matricula_profesional,
    fecha_registro,
    id_usuario
) VALUES (
    'veterinario.jpg',
    'Amaru lino',
    'Villca',
    'Alanez',
    '123456789',
    'Calle Falsa 123',
    '9874713',
    'General',
    'General',
    'MAT-2023-001',
    '2024-10-20',
    1
);
select * from usuario;


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
CREATE TABLE cuenta(
    id_cuenta int auto_increment primary key not null,
    nombre_completo varchar(255) not null,
    num_carnet varchar(30),
    estado ENUM('nopagada', 'pagada', 'adelanto') NOT NULL DEFAULT 'nopagada',
    saldo_total decimal(11,2) not null,
    monto_pagado decimal(11,2) not null,
    fecha_apertura date not null,
    fecha_pago date,
    id_personal int,
    id_propietario int,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
    FOREIGN KEY (id_propietario) REFERENCES propietario(id_propietario) ON DELETE SET NULL
);
create table vacuna(
id_vacuna int auto_increment primary key not null,
contra varchar(30) not null,
nom_vac varchar(30) not null,
costo decimal(11,3) not null,
fecha_vacuna date not null,
proxima_vacuna date not null,
id_mascota int,
id_personal int,
id_cuenta int,
FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta) ON DELETE SET NULL
);
create table desparasitacion (
id_desparasitacion int auto_increment primary key not null,
producto varchar(30) not null,
tipo_desparasitacion varchar(30) not null,
principio_activo varchar(30) not null,
via varchar(30) not null,
costo DECIMAL(11,2) not null,
fecha_aplicacion date not null,
proxima_desparasitacion date not null,
id_mascota int,
id_personal int,
id_cuenta int,
FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta) ON DELETE SET NULL
);

CREATE TABLE cirugias (
    id_cirugia INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    nombre_cirugia VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    estado BOOLEAN NOT NULL DEFAULT TRUE,
    fecha_registro DATE NOT NULL, 
    frecuencia ENUM('unica_vez', 'multiples_veces') NOT NULL,
    id_personal INT, 
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
CREATE TABLE cirugia_programada (
    id_cirugia_programada INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    estado ENUM('pendiente', 'cancelada', 'concluida') NOT NULL DEFAULT 'pendiente',
    fecha_programada DATETIME NOT NULL,
    fecha_creacion DATE NOT NULL,
    id_cirugia INT,
    id_personal INT,
    id_mascota INT,
    FOREIGN KEY (id_cirugia) REFERENCES cirugias(id_cirugia) ON DELETE SET NULL,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
    FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL
);
#drop table cirugia_realizada;
#drop table cirugia_programada;

CREATE TABLE cirugia_realizada (
    id_cirugia_realizada INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    fecha_cirugia DATE NOT NULL,
    mucosa VARCHAR(100) NOT NULL,
    tiempo_de_llenado_capilar DECIMAL(3,1) NOT NULL,
    frecuencia_cardiaca DECIMAL(5,2) NOT NULL,
    frecuencia_respiratoria DECIMAL(5,2) NOT NULL,
    peso DECIMAL(5,2) NOT NULL,
    pulso DECIMAL(5,2) NOT NULL,
    observaciones varchar(255) not null,
    costo DECIMAL(11,2) NOT NULL,
    id_cirugia_programada INT,
    id_personal INT,
    id_cuenta INT,
    FOREIGN KEY (id_cirugia_programada) REFERENCES cirugia_programada(id_cirugia_programada) ON DELETE CASCADE,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
    FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta) ON DELETE SET NULL
);
ALTER TABLE cirugia_realizada
ADD temperatura DECIMAL(11,2) NOT NULL;





CREATE TABLE atiende_servicio (
    id_atencion_servicio INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    observaciones VARCHAR(255) NOT NULL,
    costo DECIMAL(11,2) NOT NULL,
    fecha_servicio DATE NOT NULL,
    id_mascota INT,
    id_personal INT,
    id_servicio INT,
    id_cuenta INT,
    FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio) ON DELETE SET NULL,
    FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta) ON DELETE SET NULL
);

#drop table medicacion;
#drop table tratamiento;
#drop table consulta;
CREATE TABLE consulta (
    id_consulta INT AUTO_INCREMENT PRIMARY KEY,
    motivo_consulta VARCHAR(500),
    vac_polivalentes BOOLEAN,
    vac_rabia BOOLEAN,
    desparasitacion BOOLEAN,
    esterelizado BOOLEAN,
    informacion VARCHAR(255),
    mucosa VARCHAR(100) NOT NULL,
    tiempo_de_llenado_capilar DECIMAL(3,1) NOT NULL,
    frecuencia_cardiaca INT NOT NULL,
    frecuencia_respiratoria INT NOT NULL,
    temperatura DECIMAL(5,2) NOT NULL,
    peso DECIMAL(5,2),
    pulso INT NOT NULL,
    turgencia_de_piel VARCHAR(50) NOT NULL,
    actitud VARCHAR(15) NOT NULL,
    ganglios_linfaticos VARCHAR(50) NOT NULL,
    hidratacion VARCHAR(50) NOT NULL,
    Diagnostico_presuntivo VARCHAR(500) NOT NULL,
    costo DECIMAL(11,2) NOT NULL,                    
    estado ENUM('pendiente', 'completada') NOT NULL DEFAULT 'pendiente',
    fecha_consulta date not null,
    id_mascota INT,
    id_personal INT,
    id_cuenta INT,
    FOREIGN KEY (id_mascota) REFERENCES mascota(id_mascota) ON DELETE SET NULL,
    FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL,
    FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta) ON DELETE SET NULL
);
CREATE TABLE programar_tratamiento(
id_programacion_tratamiento INT auto_increment primary key,
dia_tratamiento int(2) not null,
fecha_programada datetime not null,
id_consulta int,
id_personal INT,
FOREIGN KEY (id_consulta) REFERENCES consulta(id_consulta) ON DELETE SET NULL,
FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL
);
CREATE TABLE tratamiento(
	id_tratamiento INT AUTO_INCREMENT PRIMARY KEY,
    fecha_tratamiento date not null,
    peso DECIMAL(11,2) not null,
    temperatura DECIMAL(11,2) NOT NULL,
    observaciones varchar(255) not null,
    id_programacion_tratamiento int,
	id_personal INT,
	FOREIGN KEY (id_programacion_tratamiento) REFERENCES programar_tratamiento(id_programacion_tratamiento) ON DELETE SET NULL,
	FOREIGN KEY (id_personal) REFERENCES personal(id_personal) ON DELETE SET NULL
);
/*drop table tratamiento;
drop table medicacion;
drop table facturas;
drop table categorias;*/
CREATE TABLE medicacion(
	id_mediacion INT AUTO_INCREMENT PRIMARY KEY,
    nombre_medicacion varchar(100) not null,
    via varchar(100) not null,
    costo decimal(11,2) not null,
    fecha_medicacion date not null,
    id_tratamiento int,
    id_cuenta int,
    FOREIGN KEY (id_tratamiento) REFERENCES tratamiento(id_tratamiento) ON DELETE SET NULL,
    FOREIGN KEY (id_cuenta) REFERENCES cuenta(id_cuenta) ON DELETE SET NULL
);
INSERT INTO cuenta (
    nombre_completo, 
    num_carnet, 
    estado, 
    saldo_total, 
    monto_pagado, 
    fecha_apertura, 
    fecha_pago, 
    id_personal, 
    id_propietario
) VALUES (
    'Juan Pérez',        -- nombre_completo
    'ABC123456',        -- num_carnet
    'nopagada',         -- estado
    1000.00,            -- saldo_total
    0.00,               -- monto_pagado
    '2024-10-01',       -- fecha_apertura
'2024-10-01',               -- fecha_pago (puedes usar NULL si no hay fecha de pago)
    1,               -- id_personal (puedes usar NULL si no hay un id personal asociado)
    1                   -- id_propietario (cambia el valor según el propietario correspondiente)
);

