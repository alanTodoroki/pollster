<?php
session_start();
include_once '../../models/configurationModel.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../views/login.php");
    exit;
}

$usuarioModel = new UsuarioModel();
$usuario = $usuarioModel->obtenerUsuario($_SESSION['id_usuario']);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Configuración del Usuario</title>
    <style>
        .config-form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .config-form input,
        .config-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="config-form">
        <h1>Configuración del Usuario</h1>
        <form id="configForm" action="../../controllers/configurationController.php" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre de Usuario:</label>
            <input type="text" id="nombre" name="nombre" required><br>

            <label for="correo">Correo Electrónico:</label>
            <input type="email" id="correo" name="correo" required><br>

            <label for="contrasenia">Contraseña:</label>
            <input type="password" id="contrasenia" name="contrasenia" required><br>

            <label for="foto_perfil">Foto de Perfil:</label>
            <input type="file" id="foto_perfil" name="foto_perfil" accept="image/*"><br>

            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>

</html>