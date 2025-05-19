<?php
$getJSON = file_get_contents('php://input');
$dataJson = json_decode($getJSON);

// Mostrar todos los datos recibidos del formulario de registro
echo "<pre>";
print_r($dataJson);
echo "</pre>";

// Ejemplo de toma de datos individuales:
if (isset($dataJson->correo)) {
    $correo = $dataJson->correo;
    echo "Correo: $correo<br>";
}
if (isset($dataJson->contrasena)) {
    $contrasena = $dataJson->contrasena;
    echo "Contraseña: $contrasena<br>";
}
if (isset($dataJson->nombre)) {
    $nombre = $dataJson->nombre;
    echo "Nombre: $nombre<br>";
}
if (isset($dataJson->celular)) {
    $celular = $dataJson->celular;
    echo "Celular: $celular<br>";
}
if (isset($dataJson->{'verificacion-contrasena'})) {
    $verif = $dataJson->{'verificacion-contrasena'};
    echo "Verificación contraseña: $verif<br>";
}

// base de datos
$mysqli = new mysqli("localhost", "root", "", "prueba"); // base de datos locales
// se crea la base de datos para el online

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} else {
    echo "Conectado a la base de datos";
}

$sql = "INSERT INTO 'usuario' ('id', 'correo', 'contrasena') VALUES (NULL, '".$correo."', '".$contrasena."')";
if ($mysqli->query($sql) === TRUE) {
    echo "Nuevo registro creado correctamente";
} else {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
}


?>