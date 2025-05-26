<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Cambiar a JSON
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

// Leer el ID del usuario desde la solicitud
$id_usuario = $_POST['id_usuario'] ?? '';

if (empty($id_usuario)) {
    http_response_code(400);
    echo json_encode(["error" => "ID de usuario no proporcionado"]);
    exit;
}

// Consultar la variable de cafés validados del usuario
$query = "SELECT CafesValidados FROM Registro WHERE ID = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error]);
    exit;
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

$fila = $resultado->fetch_assoc();
$cafes_validados = $fila['CafesValidados'];

// Convertir la cadena de CafesValidados en un array
$cafes_array = str_split($cafes_validados);

// Verificar si todos los valores son '1'
if (in_array('0', $cafes_array)) {
    $cafeterias_restantes = array_count_values($cafes_array)['0'];
    echo json_encode(["info" => "Te faltan $cafeterias_restantes cafeterías por visitar"]);
} else {
    // Reiniciar a todos ceros
    $nuevoEstado = str_repeat('0', count($cafes_array));
    $update = "UPDATE Registro SET CafesValidados = ? WHERE ID = ?";
    $stmt2 = $mysqli->prepare($update);
    if (!$stmt2) {
        http_response_code(500);
        echo json_encode(["error" => "Error al preparar la actualización: " . $mysqli->error]);
        exit;
    }
    $stmt2->bind_param("si", $nuevoEstado, $id_usuario);
    $stmt2->execute();

    echo json_encode(["success" => "¡Premio validado! Tu pasaporte se reinició."]);
}

$stmt->close();
$mysqli->close();
?>