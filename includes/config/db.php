<?php
function conectarDb():mysqli{
    $db= new mysqli("localhost","root","root","sanbenito2");
    if(!$db){
        echo "error no se pudo conectar";
        exit;
    }
    return $db;
}