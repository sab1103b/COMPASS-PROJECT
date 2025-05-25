<?php
// CreafCafeteria.php
// Procesa el formulario de creación de cafetería

// Conexión a la base de datos
$mysqli = new mysqli("sql213.infinityfree.com", "if0_39018712", "NRS1qInNPpD", "if0_39018712_cafe_compass");
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $mysqli->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("[CrearCafeteria] POST recibido", 0);

    // Recoger y limpiar datos del formulario
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

    $rutaImagen = '';

    // Procesar imagen si no hay errores
    if (!$errores && $imagen) {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $nombreArchivo = basename($imagen['name']);
        $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

        if (!in_array($extension, $extensionesPermitidas)) {
            $errores[] = 'La imagen debe ser JPG, JPEG, PNG o GIF.';
        } else {
            $directorioDestino = '../IMAGENES/';
            $nombreUnico = uniqid('sello_', true) . '.' . $extension;
            $rutaImagen = $directorioDestino . $nombreUnico;

            if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
                $errores[] = 'Error al guardar la imagen.';
            }
        }
    }

    // Insertar en la base de datos si no hay errores
    if (!$errores) {
        $stmt = $mysqli->prepare("INSERT INTO cafeterias (nombre, direccion, telefono, bebida, imagen) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $direccion, $telefono, $bebida, $rutaImagen);

        if ($stmt->execute()) {
            error_log("[CrearCafeteria] Cafetería guardada en la base de datos", 0);
            echo '<div style="color:green;font-weight:bold;font-size:1.2rem;margin-bottom:1rem;background:#e6ffe6;padding:1rem 2rem;border-radius:1rem;text-align:center;">Cafetería creada exitosamente.</div>';
        } else {
            error_log("[CrearCafeteria] Error al guardar en la base de datos: " . $stmt->error, 0);
            echo '<div style="color:red;">Error al guardar la cafetería en la base de datos.</div>';
        }

        $stmt->close();
    } else {
        foreach ($errores as $error) {
            echo '<div style="color:red;">' . htmlspecialchars($error) . '</div>';
        }
    }
}
?>
<form class="crear-cafeteria-form" method="POST" enctype="multipart/form-data" style="max-width:500px; margin:auto; background:#d3bebe; padding:2rem; border-radius:2rem; box-shadow:0 2px 10px #0001;">
    <input type="text" name="nombre_cafeteria" placeholder="Nombre Cafetería" required style="width:100%;margin-bottom:1.2rem;padding:1.2rem 1.5rem;font-size:1.5rem;border-radius:1.2rem;border:none;box-shadow:0 2px 6px #0001;outline:none;">
    <input type="text" name="direccion_cafeteria" placeholder="Dirección Cafetería" required style="width:100%;margin-bottom:1.2rem;padding:1.2rem 1.5rem;font-size:1.5rem;border-radius:1.2rem;border:none;box-shadow:0 2px 6px #0001;outline:none;">
    <input type="tel" name="telefono_cafeteria" placeholder="Teléfono Cafetería" required style="width:100%;margin-bottom:1.2rem;padding:1.2rem 1.5rem;font-size:1.5rem;border-radius:1.2rem;border:none;box-shadow:0 2px 6px #0001;outline:none;">
    <input type="text" name="bebida_propuesta" placeholder="Nombre bebida propuesta" required style="width:100%;margin-bottom:1.2rem;padding:1.2rem 1.5rem;font-size:1.5rem;border-radius:1.2rem;border:2px solid #222;box-shadow:0 2px 6px #0001;outline:none;">
    <div class="imagen-sello" style="display:flex;align-items:center;gap:2rem;margin-top:1.5rem;">
        <label for="imagen-sello" class="custom-file-upload" style="background:#bfa7a7;padding:1.2rem 2.5rem;border-radius:1.2rem;cursor:pointer;box-shadow:0 2px 6px #0001;font-size:1.3rem;display:flex;align-items:center;gap:0.7rem;">
            <i class="fas fa-upload"></i> Subir Imagen Sello
        </label>
        <input type="file" id="imagen-sello" name="imagen_sello" accept="image/*" required style="display:none;">
        <button type="submit" id="btn-crear-cafeteria" style="background:#7c6868;color:#fff;padding:1.2rem 2.5rem;border:none;border-radius:1.2rem;font-size:1.3rem;cursor:pointer;box-shadow:0 2px 6px #0001;">PUBLICAR</button>
    </div>
</form>
<script>
// Mostrar nombre de archivo seleccionado (opcional, mejora UX)
document.getElementById('imagen-sello').addEventListener('change', function(e) {
    const label = document.querySelector('label[for="imagen-sello"]');
    if (this.files && this.files[0]) {
        label.innerHTML = '<i class="fas fa-upload"></i> ' + this.files[0].name;
    } else {
        label.innerHTML = '<i class="fas fa-upload"></i> Subir Imagen Sello';
    }
});
</script>
