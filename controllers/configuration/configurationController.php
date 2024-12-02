<?php
//session_start();
require_once '../../models/configurationModel.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    switch ($action) {
        case 'actualizarConfiguracion':
            $nombre = $_POST['nombre'];
            $nombre_usuario = $_POST['nombre_usuario'];
            $correo = $_POST['correo'];
            $contrasenia = $_POST['contrasenia'];
            $foto_perfil = $_FILES['foto_perfil'];
            $id_usuario = $_SESSION['id_usuario'];


            $resultado = actualizarConfiguracion($id_usuario, $nombre, $nombre_usuario, $correo, $contrasenia, $foto_perfil);
            echo $resultado;
    }

    // Asegúrate de obtener el ID del usuario de la sesión


}
