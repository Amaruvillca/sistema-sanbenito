<?php
define('TEMPLATES_URL', __DIR__ . '/templates');
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/${nombre}.php";
}
function noMostrar()
{
    if ($_SESSION['rol'] === 'Veterinario')
        echo 'no-mostrar';
}
function estadoAutenticado(mysqli $db)
{
    session_start();
    //ver si inicio sesion
    if (!$_SESSION['login']) {
        header('Location:/sistema-sanbenito/login.php');
        exit;
    } else {
        $verestado = verSiEstaActivo($db, $_SESSION['email']);
        //comprobar si la cuenta esta activa
        if (!$verestado) {
            header('Location:/sistema-sanbenito/error/403.php?mensaje=1');
            exit;
        }
    }
}
//ver el estado
function verificaAcceso()
{
    $acceso = $_SESSION['rol'] === 'Veterinario' ? false : true;
    if (!$acceso):
        header('Location:/sistema-sanbenito/error/403.php?mensaje=3');
    endif;
}
//verificar si el usuario esta activo
function verSiEstaActivo(mysqli $db, $email): bool
{
    // Utilizando sentencias preparadas para evitar inyección SQL
    $query = "SELECT estado FROM usuario WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($estado);
    $stmt->fetch();
    $stmt->close();

    // Verificando si el estado es 0 (inactivo)

    return $estado === 1 ? true : false;
}
//mostrar todas las caracteristicas de un registro de una tabla
function mostrarTabla(mysqli $db, $id_usuario, $tabla): array
{
    // Verifica si el valor de $tabla es seguro para usar en la consulta
    $tabla = mysqli_real_escape_string($db, $tabla);
    // Consulta SQL usando un parámetro para evitar inyecciones SQL
    $query = "SELECT * FROM `$tabla` WHERE id_usuario = ?";
    if ($stmt = $db->prepare($query))
        $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    // Fetch assoc rows as an associative array
    $perfil = $result->fetch_assoc() ?: [];
    // Cierra la sentencia
    $stmt->close();
    return $perfil;
}
function debuguear($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}
function s($html):String{
$s=htmlspecialchars($html);
return $s;
}
function encryptData($data) {
    $encryption_key = "clave_secreta"; // Asegúrate de usar una clave segura
    $cipher = "AES-128-CTR";
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_length);

    // Encriptar los datos
    $encrypted = openssl_encrypt($data, $cipher, $encryption_key, 0, $iv);
    
    // Combina el texto encriptado con el IV para luego desencriptarlo
    return urlencode(base64_encode($encrypted . "::" . $iv));
}
function decryptData($encrypted_data) {
    $encryption_key = "clave_secreta";  // Debe ser la misma clave que usaste para encriptar
    $cipher = "AES-128-CTR";

    // Separar el texto encriptado del IV
    list($encrypted, $iv) = explode("::", base64_decode($encrypted_data), 2);
    
    // Desencriptar los datos
    return openssl_decrypt($encrypted, $cipher, $encryption_key, 0, $iv);
}
