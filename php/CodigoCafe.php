<?php
file_put_contents('debug_cafe.txt', print_r($_POST, true), FILE_APPEND);
session_start(); // Inicia la sesión

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/plain'); // Cambia el tipo de contenido a texto plano
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    echo "OPTIONS request";
    exit();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401); // No autorizado
    echo "Usuario no autenticado";
    exit;
}

$id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario autenticado

// Conexión a la base de datos
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "Error de conexión: " . $mysqli->connect_error;
    exit;
}
$mysqli->set_charset("utf8");

// Leer datos del formulario
$cafeteria = $_POST['cafeteria-select'] ?? '';
$codigo = $_POST['codigo'] ?? '';

if (empty($cafeteria) || empty($codigo)) {
    http_response_code(400);
    echo "Debes seleccionar una cafetería y proporcionar un código";
    exit;
}

// Verificar si el código es válido
$codigoValido = "Cod123"; // Código válido para la comparación
if ($codigo !== $codigoValido) {
    http_response_code(403);
    echo "El código ingresado no es válido";
    exit;
}

// Obtener la cadena actual de CafesValidados
$sql = "SELECT CafesValidados FROM Registro WHERE ID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cafes = $row['CafesValidados'];

    // Convertir la cadena a un array para modificar
    $cafes_array = str_split($cafes);

    // Validar el índice de la cafetería
    $indice_cafe = intval($cafeteria) - 1; // Convertir la cafetería seleccionada a índice (0 a 12)
    if ($indice_cafe < 0 || $indice_cafe >= count($cafes_array)) {
        http_response_code(400);
        echo "Índice de cafetería no válido";
        exit;
    }

    // Marcar como validado (1)
    $cafes_array[$indice_cafe] = '1';

    // Reconstruir la cadena
    $nuevo_cafes = implode('', $cafes_array);

    // Actualizar en la base de datos
    $update_sql = "UPDATE Registro SET CafesValidados = ? WHERE ID = ?";
    $update_stmt = $mysqli->prepare($update_sql);
    $update_stmt->bind_param("si", $nuevo_cafes, $id_usuario);

    if ($update_stmt->execute()) {
        echo "Café validado correctamente. Nueva cadena de validación: " . $nuevo_cafes;
    } else {
        http_response_code(500);
        echo "Error al actualizar la base de datos: " . $update_stmt->error;
    }
    $update_stmt->close();
} else {
    http_response_code(404);
    echo "Usuario no encontrado";
}

$stmt->close();
$mysqli->close();
?>