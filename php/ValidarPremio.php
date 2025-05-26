<?php
session_start();
require_once "conexion.php"; // Asegúrate de tener este archivo con la conexión a tu base de datos

// Verificar que haya sesión activa
if (!isset($_SESSION['correo'])) {
    echo json_encode(["error" => "Usuario no autenticado."]);
    exit;
}

$correo = $_SESSION['correo'];

// Consultar la variable de sellos del usuario
$query = "SELECT cafes_validados FROM usuarios WHERE correo = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo json_encode(["error" => "Usuario no encontrado."]);
    exit;
}

$fila = $resultado->fetch_assoc();
$cafes_validados = $fila['cafes_validados'];

// Verificar si tiene los 13 sellos (todos en 1)
if ($cafes_validados === str_repeat('1', 13)) {
    // Aquí puedes ejecutar un webhook, redireccionar, o realizar una acción
    file_get_contents("https://cafecompass.free.nf/?i=3");

    // Reiniciar los sellos a 0
    $nuevoEstado = str_repeat('0', 13);
    $update = "UPDATE usuarios SET cafes_validados = ? WHERE correo = ?";
    $stmt2 = $conexion->prepare($update);
    $stmt2->bind_param("ss", $nuevoEstado, $correo);
    $stmt2->execute();

    echo json_encode(["success" => "Premio validado y sellos reiniciados."]);
} else {
    echo json_encode(["info" => "Aún no has completado los 13 sellos."]);
}
?>
