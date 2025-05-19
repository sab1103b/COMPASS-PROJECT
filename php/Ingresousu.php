<?php
// filepath: d:\COMPASS\php\Ingresousu.php
file_put_contents('debug_login.txt', $getJSON . PHP_EOL, FILE_APPEND);

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
$codigo = $dataJson->codigo ?? ''; // Solo para admin

if (empty($correo) || empty($contrasena)) {
    http_response_code(400);
    echo json_encode(["error" => "Correo y contraseña son obligatorios"]);
    exit;
}

// Si hay código, es intento de ingreso de administrador
if (!empty($codigo)) {
    // Buscar en tabla de administradores
    $stmt = $mysqli->prepare("SELECT Contrasena FROM Administradores WHERE Correo = ? AND Codigo = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error]);
        exit;
    }
    $stmt->bind_param("ss", $correo, $codigo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        // Comparación directa
        if ($contrasena === $hashedPassword) {
            echo json_encode(["success" => "Administrador autenticado correctamente"]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Contraseña incorrecta"]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Administrador no encontrado o código incorrecto"]);
    }
    $stmt->close();
} else {
    // Buscar en tabla de usuarios normales
    $stmt = $mysqli->prepare("SELECT Contrasena FROM Registro WHERE Correo = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error]);
        exit;
    }
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($hashedPassword);
        $stmt->fetch();
        // Comparación directa
        if ($contrasena === $hashedPassword) {
            echo json_encode(["success" => "Usuario autenticado correctamente"]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Contraseña incorrecta"]);
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Usuario no encontrado"]);
    }
    $stmt->close();
}

$mysqli->close();
?>