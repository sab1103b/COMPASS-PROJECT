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
    echo json_encode(["debug" => "OPTIONS request"]);
    exit();
}

// Conexión a la base de datos
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error, "debug" => "DB connect fail"]);
    exit;
}
$mysqli->set_charset("utf8");

// Leer datos del cuerpo JSON
$getJSON = file_get_contents('php://input');
$dataJson = json_decode($getJSON);

if (!$dataJson) {
    http_response_code(400);
    echo json_encode(["error" => "JSON inválido", "debug" => "JSON decode fail"]);
    exit;
}

// Obtener campos
$correo = $dataJson->correo ?? '';
$contrasena = $dataJson->contrasena ?? '';
$nombre = $dataJson->nombre ?? '';
$celular = $dataJson->celular ?? '';
$verif = $dataJson->{'verificacion-contrasena'} ?? '';
$codigo = $dataJson->codigo ?? '';

if (empty($nombre) || empty($correo) || empty($celular) || empty($contrasena) || empty($verif)) {
    http_response_code(400);
    echo json_encode(["error" => "Todos los campos son obligatorios", "debug" => "Campos vacíos"]);
    exit;
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "El correo no es válido", "debug" => "Correo inválido"]);
    exit;
}

if ($contrasena !== $verif) {
    http_response_code(400);
    echo json_encode(["error" => "Las contraseñas no coinciden", "debug" => "Contraseñas no coinciden"]);
    exit;
}

$hashedPassword = $contrasena;

// Validar que celular sea numérico para usuarios normales
if (empty($codigo)) {
    if (!is_numeric($celular)) {
        http_response_code(400);
        echo json_encode(["error" => "El celular debe ser solo números", "debug" => $celular]);
        exit;
    }
}

if (!empty($codigo)) {
    // Consultar códigos válidos desde la base de datos
    $codigosAdmin = [];
    $result = $mysqli->query("SELECT CodigoAdm FROM CodigoAdmin");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $codigosAdmin[] = $row['CodigoAdm'];
        }
        $result->free();
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al consultar códigos de administrador: " . $mysqli->error, "debug" => "Consulta codigos admin"]);
        exit;
    }

    if (!in_array($codigo, $codigosAdmin)) {
        http_response_code(403);
        echo json_encode(["error" => "El código de administrador no es válido", "debug" => "Código admin no válido"]);
        exit;
    }

    // Insertar en tabla de administradores
    $stmt = $mysqli->prepare("INSERT INTO Administradores (Nombre, Correo, Celular, Contrasena, Codigo) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error, "debug" => "Prepare admin"]);
        exit;
    }
    $stmt->bind_param("sssss", $nombre, $correo, $celular, $hashedPassword, $codigo);

    if ($stmt->execute()) {
        // Eliminar el código usado para que sea de único uso
        $del = $mysqli->prepare("DELETE FROM CodigoAdmin WHERE CodigoAdm = ?");
        if ($del) {
            $del->bind_param("s", $codigo);
            $del->execute();
            $del->close();
        }
        echo json_encode(["success" => "Administrador registrado correctamente", "debug" => "Admin registrado y código eliminado"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al registrar administrador: " . $stmt->error, "debug" => "Execute admin"]);
    }
    $stmt->close();
} else {
    // Registro de usuario normal
    $stmt = $mysqli->prepare("INSERT INTO Registro (Nombre, Correo, Celular, Contrasena) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error, "debug" => "Prepare user"]);
        exit;
    }
    $stmt->bind_param("ssis", $nombre, $correo, $celular, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Usuario registrado correctamente", "debug" => "Usuario registrado"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al registrar usuario: " . $stmt->error, "debug" => "Execute user"]);
    }
    $stmt->close();
}

$mysqli->close();
file_put_contents('debug_registro.txt', "Llego al final\n", FILE_APPEND);
?>