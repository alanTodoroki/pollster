<?php
session_start();
include_once '../models/configurationModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasenia = $_POST['contrasenia'];
    $foto_perfil = $_FILES['foto_perfil'];
    $id_usuario = $_SESSION['id_usuario']; // Asegúrate de obtener el ID del usuario de la sesión

    $resultado = actualizarConfiguracion($id_usuario, $nombre, $correo, $contrasenia, $foto_perfil);
    echo $resultado;
}

function actualizarConfiguracion($id_usuario, $nombre, $correo, $contrasenia, $foto_perfil)
{
    $usuarioModel = new UsuarioModel();
    $resultado = $usuarioModel->actualizarConfiguracion($id_usuario, $nombre, $correo, $contrasenia, $foto_perfil);

    if ($resultado === true) {
        return "Configuración actualizada exitosamente";
    } else {
        return "Error al actualizar la configuración: " . $resultado;
    }
}
