<?php 
require '../../includes/app.php';
use App\Cuenta;
$cuenta = new Cuenta($_POST['cuenta']);
$resultado=$cuenta->actualizar($cuenta->id_cuenta);
if ($resultado) {
    $id_propietario = $cuenta->id_propietario;
    $data = "id_propietario=$id_propietario";
    $encryptedData = encryptData($data);
    header('Location:/sistema-sanbenito/home/vermas/propietarios.php?data='.$encryptedData);
    
}