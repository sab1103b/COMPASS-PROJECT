<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}

// Forzar codificación UTF-8
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
$codigo = $dataJson->codigo ?? '';

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
$hashedPassword = $contrasena;

// Lista de códigos válidos para administradores (puedes cambiarlos si quieres)
$codigosAdmin = [
    "ADM-9X2K7",
    "ADM-4B8Q1",
    "ADM-7M5P3",
    "ADM-2L6R9",
    "ADM-5T1V4"
];

// Si se envía el campo código, es registro de administrador
if (!empty($codigo)) {
    // Validar código de administrador
    if (!in_array($codigo, $codigosAdmin)) {
        http_response_code(403);
        echo json_encode(["error" => "El código de administrador no es válido"]);
        exit;
    }

    // Insertar en tabla de administradores
    $stmt = $mysqli->prepare("INSERT INTO Administradores (Nombre, Correo, Celular, Contrasena, Codigo) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error]);
        exit;
    }
    $stmt->bind_param("ssiss", $nombre, $correo, $celular, $hashedPassword, $codigo);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Administrador registrado correctamente"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al registrar administrador: " . $stmt->error]);
    }
    $stmt->close();
} else {
    // Registro de usuario normal
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
}

$mysqli->close();
?>