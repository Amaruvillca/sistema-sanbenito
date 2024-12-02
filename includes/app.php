<?php
require 'funciones.php';
require 'config/db.php';
require __DIR__.'/../vendor/autoload.php';
require 'mensajes.php';

//conectar ha la base de datos
$db = conectarDb();
use App\ActiveRecord;
date_default_timezone_set('America/La_Paz');

setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'es');

ActiveRecord::setDb($db);
