<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Configuración de la conexión a la base de datos
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
if ($mysqli->connect_errno) {
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}
$mysqli->set_charset("utf8");

// Leer el ID del usuario desde la solicitud
$data = json_decode(file_get_contents("php://input"), true);
$id_usuario = $data['id'] ?? '';

if (empty($id_usuario)) {
    echo json_encode(["error" => "ID de usuario no proporcionado"]);
    exit;
}

// Consultar los datos del usuario en la base de datos
$query = "SELECT Nombre, Correo, Celular FROM Registro WHERE ID = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(["error" => "Error al preparar la consulta: " . $mysqli->error]);
    exit;
}
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

$fila = $resultado->fetch_assoc();

// Registrar en un archivo de depuración los datos del usuario encontrado
file_put_contents('debug_usuario.txt', print_r($fila, true), FILE_APPEND);

// Devolver los datos del usuario en formato JSON
echo json_encode([
    "nombre" => $fila['Nombre'],
    "correo" => $fila['Correo'],
    "celular" => $fila['Celular']
]);

$stmt->close();
$mysqli->close();
?>