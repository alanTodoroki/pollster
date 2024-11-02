<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "pollster";

//Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

//Checamos la conexión
if ($conn->connect_error) {
    die("Conexíon fallida: " . $conn->connect_error);
}
echo "Conexión exitosa";
