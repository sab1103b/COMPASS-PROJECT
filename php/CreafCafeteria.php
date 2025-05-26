<?php

file_put_contents('debug_crear_cafeteria.txt', print_r($_FILES, true), FILE_APPEND);


// Procesa el formulario de creación de cafetería (solo lógica, sin HTML)
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
header('Content-Type: application/json');
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre_cafeteria']) ? $mysqli->real_escape_string(trim($_POST['nombre_cafeteria'])) : '';
    $direccion = isset($_POST['direccion_cafeteria']) ? $mysqli->real_escape_string(trim($_POST['direccion_cafeteria'])) : '';
    $telefono = isset($_POST['telefono_cafeteria']) ? $mysqli->real_escape_string(trim($_POST['telefono_cafeteria'])) : '';
    $bebida = isset($_POST['bebida_propuesta']) ? $mysqli->real_escape_string(trim($_POST['bebida_propuesta'])) : '';
    $imagen = $_FILES['imagen_sello'] ?? null;

    $errores = [];

    // Validaciones
    if (empty($nombre)) $errores[] = 'El nombre de la cafetería es obligatorio.';
    if (empty($direccion)) $errores[] = 'La dirección es obligatoria.';
    if (empty($telefono)) $errores[] = 'El teléfono es obligatorio.';
    if (empty($bebida)) $errores[] = 'El nombre de la bebida propuesta es obligatorio.';
    if (!$imagen || $imagen['error'] !== UPLOAD_ERR_OK) $errores[] = 'Debes subir una imagen para el sello.';

    $contenidoImagen = null;

    // Procesar imagen si no hay errores
    if (!$errores && $imagen) {
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $nombreArchivo = basename($imagen['name']);
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        $errores[] = 'La imagen debe ser JPG, JPEG, PNG o GIF.';
    } else {
        // Leer el contenido binario de la imagen
        $contenidoImagen = file_get_contents($imagen['tmp_name']);
        if ($contenidoImagen === false) {
            $errores[] = 'Error al leer la imagen.';
        }
    }
}

    // Insertar en la base de datos si no hay errores
    if (!$errores) {
    $stmt = $mysqli->prepare("INSERT INTO cafeterias (nombre, direccion, telefono, bebida, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $direccion, $telefono, $bebida, $contenidoImagen);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Cafetería creada exitosamente."]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Error al guardar la cafetería en la base de datos."]);
    }
    $stmt->close();
    } else {
        http_response_code(400);
        echo json_encode(["error" => implode(" ", $errores)]);
    }
} 

// Si no es POST, no hace nada
http_response_code(405);
echo json_encode(["error" => "Método no permitido"]);
exit;
?>