<?php
//importar la bd
require 'includes/config/db.php';
$db = conectarDb();
//crear un usuario y password
$email = "76595194amaru@gmail.com";
$password = "12345678910";
$rol="Administrador";
$passwordhash= password_hash($password, PASSWORD_BCRYPT);
var_dump($passwordhash);

//query
$query = "INSERT INTO usuario ( email ,password , rol) VALUES ('${email}','${passwordhash}','${rol}')";
 echo $query;
mysqli_query($db, $query);