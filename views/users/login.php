<?php
session_start();
require_once "../../config/database.php";

// Removemos la verificación de sesión inicial para permitir otros logins
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cerramos cualquier sesión existente antes de iniciar una nueva
    session_unset();
    session_destroy();
    session_start();

    if (!empty($_POST['correo_electronico']) && !empty($_POST['contraseña'])) {
        $stmt = $conn->prepare('SELECT id_usuario, correo_electronico, contraseña FROM Usuarios WHERE correo_electronico = ?');
        $stmt->bind_param('s', $_POST['correo_electronico']);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($_POST['contraseña'], $user['contraseña'])) {
            $_SESSION['id_usuario'] = $user['id_usuario'];
            header("Location: ../../index.php");
            exit();
        } else {
            $message = 'Lo siento, las credenciales no coinciden';
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php if (!empty($message)): ?>
        <p><?= $message ?></p>
    <?php endif; ?>

    <h1>Login</h1>
    <span>or <a href="signup.php">SignUp</a></span>

    <form action="login.php" method="POST">
        <input name="correo_electronico" type="email" placeholder="Introduce tu correo electrónico" required>
        <input name="contraseña" type="password" placeholder="Introduce tu contraseña" required>
        <input type="submit" value="Iniciar Sesión">
    </form>
</body>

</html>