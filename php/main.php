<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Obtener datos del cuerpo JSON
$getJSON = file_get_contents('php://input');
$dataJson = json_decode($getJSON);

if (!$dataJson) {
    http_response_code(400);
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Obtener campos
$correo = $dataJson->correo ?? '';
$contrasena = $dataJson->contrasena ?? '';
$nombre = $dataJson->nombre ?? '';
$celular = $dataJson->celular ?? '';
$verif = $dataJson->{'verificacion-contrasena'} ?? '';

// Conexión a base de datos (usa tus datos reales del hosting aquí)
$mysqli = new mysqli("sqlXXX.infinityfree.com", "ep_tuusuario", "tucontraseña", "ep_nombredb");

if ($mysqli->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Conexión fallida: " . $mysqli->connect_error]);
    exit;
}

// Insertar datos
$sql = "INSERT INTO usuario (correo, contrasena) VALUES ('$correo', '$contrasena')";
if ($mysqli->query($sql) === TRUE) {
    echo json_encode(["success" => "Registro creado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error SQL: " . $mysqli->error]);
}
?>
