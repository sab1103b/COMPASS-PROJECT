<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Conexión a la base de datos
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}

// Forzar codificación UTF-8 (importante para evitar errores con ñ o tildes)
$mysqli->set_charset("utf8");

// Leer datos del cuerpo JSON
$getJSON = file_get_contents('php://input');
$dataJson = json_decode($getJSON);

// Verificar si el JSON está bien formado
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

// Validar campos vacíos
if (empty($nombre) || empty($correo) || empty($celular) || empty($contrasena) || empty($verif)) {
    http_response_code(400);
    echo json_encode(["error" => "Todos los campos son obligatorios"]);
    exit;
}

// Validar formato de correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "El correo no es válido"]);
    exit;
}

// Validar que las contraseñas coincidan
if ($contrasena !== $verif) {
    http_response_code(400);
    echo json_encode(["error" => "Las contraseñas no coinciden"]);
    exit;
}

// Cifrar la contraseña
$hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);

// Preparar e insertar en la base de datos
$stmt = $mysqli->prepare("INSERT INTO Registro (Nombre, Correo, Celular, Contrasena) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error]);
    exit;
}

$stmt->bind_param("ssis", $nombre, $correo, $celular, $hashedPassword);

if ($stmt->execute()) {
    echo json_encode(["success" => "Usuario registrado correctamente"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Error al registrar usuario: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>
