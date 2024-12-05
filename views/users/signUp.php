<?php
session_start();
require_once '../../config/db.php';
require_once '../../controllers/auth/signUpController.php';

$message = '';

$db = new Database();
$conn = $db->getConnection();
$signUpController = new SignUpController($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message = $signUpController->handleSignUp($_POST);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: url('../../public/img/fondo.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-left: 20px;
        }

        .container {
            padding: 40px;
            text-align: center;
            width: 100%;
            max-width: 400px;
            background: none;
            box-sizing: border-box;
            border-radius: 8px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 80px;
            height: auto;
        }

        .logo-container h2 {
            margin: 10px 0 0;
            font-size: 22px;
            color: black;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: black;
        }

        span {
            display: block;
            margin-bottom: 20px;
            font-size: 14px;
        }

        span a {
            color: #006E90;
            text-decoration: none;
        }

        span a:hover {
            text-decoration: underline;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #006E90;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #005f6b;
        }

        p {
            color: red;
            font-size: 14px;
        }

        .message {
            color: green;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo y nombre -->
        <div class="logo-container">
            <img src="../../public/img/logo.png" alt="Logo">
            <h2>Pollster</h2>
        </div>

        <?php if (!empty($message)): ?>
            <p class="<?= strpos($message, 'éxito') !== false ? 'message' : '' ?>">
                <?= $message ?>
            </p>
        <?php endif; ?>

        <h1>Registro</h1>
        <span>o <a href="login.php">Iniciar Sesión</a></span>

        <form action="signup.php" method="POST">
            <input name="nombre" type="text" placeholder="Nombre" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            <input name="apellido" type="text" placeholder="Apellido" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
            <input name="nombre_usuario" type="text" placeholder="Nombre de usuario" required value="<?= htmlspecialchars($_POST['nombre_usuario'] ?? '') ?>">
            <input name="correo_electronico" type="email" placeholder="Correo electrónico" required value="<?= htmlspecialchars($_POST['correo_electronico'] ?? '') ?>">
            <input name="contrasenia" type="password" placeholder="Contraseña" required>
            <input name="confirmar_contrasenia" type="password" placeholder="Confirmar contraseña" required>
            <input type="submit" value="Registrarse">
        </form>
    </div>
</body>

</html>