<?php
$getJSON = file_get_contents('php://input');

$dataJson = json_decode($getJSON);

// ejemplo de toma de datos del json: 
$correo = $dataJson -> {'correo'};
$contrasena = $dataJson -> {'contrasena'};

echo $getJSON;


// base de datos
$mysqli = new mysqli("localhost", "root", "", "prueba");
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