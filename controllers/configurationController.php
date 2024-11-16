<?php
session_start();
require_once '../models/updateConfigurationModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];
    $foto_perfil = $_FILES['foto_perfil'];
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de obtener el ID del usuario de la sesión

    $resultado = actualizarConfiguracion($id_usuario, $nombre, $correo, $contrasenia, $foto_perfil);
    echo $resultado;
}
