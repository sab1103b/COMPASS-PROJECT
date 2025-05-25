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

file_put_contents('debug_premio.txt', print_r($_POST, true), FILE_APPEND);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos (ajusta los nombres según el atributo "name" de tus inputs)
    $nombre = isset($_POST['nombre_premio']) ? trim($_POST['nombre_premio']) : '';
    $descripcion = isset($_POST['descripcion_premio']) ? trim($_POST['descripcion_premio']) : '';

    $errores = [];
    if (empty($nombre)) $errores[] = 'El nombre del premio es obligatorio.';
    if (empty($descripcion)) $errores[] = 'La descripción del premio es obligatoria.';

    if (!$errores) {
        $stmt = $mysqli->prepare("INSERT INTO premios (nombre, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Premio agregado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al guardar el premio en la base de datos."]);
        }
        $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(["error" => implode(" ", $errores)]);
    }
    exit;
}

http_response_code(405);
echo json_encode(["error" => "Método no permitido"]);
exit;
?>