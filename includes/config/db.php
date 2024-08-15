<?php
function conectarDb():mysqli{
    $db= new mysqli("localhost","root","root","ventas");
    if(!$db){
        echo "error no se pudo conectar";
        exit;
    }
    return $db;
}