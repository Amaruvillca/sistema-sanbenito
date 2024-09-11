<?php
require 'funciones.php';
require 'config/db.php';
require __DIR__.'/../vendor/autoload.php';
require 'mensajes.php';

//conectar ha la base de datos
$db = conectarDb();
use App\ActiveRecord;

ActiveRecord::setDb($db);
