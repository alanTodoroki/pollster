<?php
session_start();
require_once '../../config/database.php';

// Removemos la verificación de sesión aquí - cualquiera puede acceder al signup
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        !empty($_POST['correo_electronico']) && !empty($_POST['contraseña']) &&
        !empty($_POST['confirmar_contraseña']) && !empty($_POST['nombre']) &&
        !empty($_POST['apellido'])
    ) {
        if ($_POST['contraseña'] !== $_POST['confirmar_contraseña']) {
            $message = 'Las contraseñas no coinciden.';
        } else {
            // Verificar si el correo ya existe
            $check_email = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE correo_electronico = ?");
            $check_email->bind_param("s", $_POST['correo_electronico']);
            $check_email->execute();
            $result = $check_email->get_result();

            if ($result->num_rows > 0) {
                $message = 'El correo electrónico ya está registrado.';
            } else {
                $sql = "INSERT INTO Usuarios (nombre, apellido, correo_electronico, contraseña, rol) VALUES (?, ?, ?, ?, 'usuario')";
                $stmt = $conn->prepare($sql);
                $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
                $stmt->bind_param(
                    "ssss",
                    $_POST['nombre'],
                    $_POST['apellido'],
                    $_POST['correo_electronico'],
                    $contraseña
                );

                if ($stmt->execute()) {
                    // Si el registro es exitoso, cerramos cualquier sesión existente
                    session_unset();
                    session_destroy();
                    $message = 'Usuario creado con éxito. <a href="login.php">Iniciar sesión</a>';
                } else {
                    if ($conn->errno === 1062) { // Código de error para entrada duplicada
                        $message = 'El correo electrónico ya está registrado.';
                    } else {
                        $message = 'Error al crear la cuenta: ' . $conn->error;
                    }
                    //$message = 'Error al crear la cuenta: ' . $conn->error;
                }
            }
        }
    } else {
        $message = 'Por favor completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <h1>Registro</h1>
    <span>o <a href="login.php">Iniciar Sesión</a></span>

    <form action="signup.php" method="POST">
        <input name="nombre" type="text" placeholder="Nombre" required>
        <input name="apellido" type="text" placeholder="Apellido" required>
        <input name="correo_electronico" type="email" placeholder="Correo electrónico" required>
        <input name="contraseña" type="password" placeholder="Contraseña" required>
        <input name="confirmar_contraseña" type="password" placeholder="Confirmar contraseña" required>
        <input type="submit" value="Registrarse">
    </form>
</body>

</html>