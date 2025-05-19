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

$mysqli->set_charset("utf8");

// Leer datos del cuerpo JSON
$getJSON = file_get_contents('php://input');
$dataJson = json_decode($getJSON);

if (!$dataJson) {
    http_response_code(400);
    echo json_encode(["error" => "JSON inválido"]);
    exit;
}

// Obtener campos
$correo = $dataJson->correo ?? '';
$celular = $dataJson->celular ?? '';

// --- CAMBIO DE CONTRASEÑA ---
if (isset($dataJson->nueva_contrasena) && isset($dataJson->verificacion_contrasena)) {
    $nuevaContra = $dataJson->nueva_contrasena;
    $verifContra = $dataJson->verificacion_contrasena;

    if ($nuevaContra !== $verifContra) {
        http_response_code(400);
        echo json_encode(["error" => "Las contraseñas no coinciden"]);
        exit;
    }

    $actualizado = false;

    // Actualizar en Registro
    if (!empty($correo)) {
        $stmt = $mysqli->prepare("UPDATE Registro SET Contrasena = ? WHERE Correo = ?");
        $stmt->bind_param("ss", $nuevaContra, $correo);
        $stmt->execute();
        if ($stmt->affected_rows > 0) $actualizado = true;
        $stmt->close();
    }
    if (!empty($celular)) {
        $stmt = $mysqli->prepare("UPDATE Registro SET Contrasena = ? WHERE Celular = ?");
        $stmt->bind_param("ss", $nuevaContra, $celular);
        $stmt->execute();
        if ($stmt->affected_rows > 0) $actualizado = true;
        $stmt->close();
    }

    // Actualizar en Administradores
    if (!empty($correo)) {
        $stmt = $mysqli->prepare("UPDATE Administradores SET Contrasena = ? WHERE Correo = ?");
        $stmt->bind_param("ss", $nuevaContra, $correo);
        $stmt->execute();
        if ($stmt->affected_rows > 0) $actualizado = true;
        $stmt->close();
    }
    if (!empty($celular)) {
        $stmt = $mysqli->prepare("UPDATE Administradores SET Contrasena = ? WHERE Celular = ?");
        $stmt->bind_param("ss", $nuevaContra, $celular);
        $stmt->execute();
        if ($stmt->affected_rows > 0) $actualizado = true;
        $stmt->close();
    }

    if ($actualizado) {
        echo json_encode(["success" => "Contraseña actualizada correctamente"]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "No se pudo actualizar la contraseña"]);
    }
    $mysqli->close();
    exit;
}

// --- BÚSQUEDA DE USUARIO ---
if (empty($correo) && empty($celular)) {
    http_response_code(400);
    echo json_encode(["error" => "Debes ingresar correo o celular"]);
    exit;
}

$usuarioEncontrado = false;
$esAdmin = false;
$datosUsuario = null;

// Buscar en tabla de usuarios normales
if (!empty($correo)) {
    $stmt = $mysqli->prepare("SELECT * FROM Registro WHERE Correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $usuarioEncontrado = true;
        $datosUsuario = $row;
    }
    $stmt->close();
}
if (!$usuarioEncontrado && !empty($celular)) {
    $stmt = $mysqli->prepare("SELECT * FROM Registro WHERE Celular = ?");
    $stmt->bind_param("s", $celular);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $usuarioEncontrado = true;
        $datosUsuario = $row;
    }
    $stmt->close();
}

// Si no se encontró en usuarios normales, buscar en administradores
if (!$usuarioEncontrado) {
    if (!empty($correo)) {
        $stmt = $mysqli->prepare("SELECT * FROM Administradores WHERE Correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $usuarioEncontrado = true;
            $esAdmin = true;
            $datosUsuario = $row;
        }
        $stmt->close();
    }
    if (!$usuarioEncontrado && !empty($celular)) {
        $stmt = $mysqli->prepare("SELECT * FROM Administradores WHERE Celular = ?");
        $stmt->bind_param("s", $celular);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $usuarioEncontrado = true;
            $esAdmin = true;
            $datosUsuario = $row;
        }
        $stmt->close();
    }
}

if ($usuarioEncontrado) {
    echo json_encode([
        "success" => "Usuario encontrado. Continúa con el cambio de contraseña.",
        "esAdmin" => $esAdmin,
        "usuario" => $datosUsuario
    ]);
} else {
    http_response_code(404);
    echo json_encode(["error" => "No se encontró ningún usuario con esos datos"]);
}

$mysqli->close();
?>