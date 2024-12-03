<?php
session_start();
require_once '../../config/db.php';
require_once '../../controllers/auth/loginController.php';

$database = new Database();
$db = $database->getConnection();
$loginController = new LoginController($db);

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['correo_electronico']) || empty($_POST['contrasenia'])) {
        $message = 'Por favor, llena todos los campos.';
    } else {
        $correo_o_usuario = $_POST['correo_electronico'];
        $contrasenia = $_POST['contrasenia'];
        $message = $loginController->login($correo_o_usuario, $contrasenia);
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
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

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: black;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .checkbox-container input {
            margin-right: 10px;
        }

        .btn {
            background-color: #006E90;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .btn:hover {
            background-color: #005f6b;
        }

        .links {
            margin-top: 20px;
            font-size: 14px;
        }

        .links a {
            color: #006E90;
            text-decoration: none;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .logo-title-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-large {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
            object-fit: contain;
        }

        .pollster-title {
            font-size: 24px;
            font-weight: bold;
            color: #006E90;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-title-wrapper mb-4">
            <img src="../../public/img/logo.png" alt="Logo de Pollster" class="logo-large">
            <h1 class="pollster-title">Pollster</h1>
        </div>
        <h1>Inicio de sesión</h1>
        <?php if (!empty($message)): ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="../users/login.php" method="POST">
            <input name="correo_electronico" type="text" placeholder="Correo electrónico o nombre de usuario" required autocomplete="off" value="">
            <input name="contrasenia" type="password" placeholder="Contraseña" required>
            <div class="checkbox-container">
                <input type="checkbox" id="show-password">
                <label for="show-password">Mostrar contraseña</label>
            </div>
            <button type="submit" class="btn">Iniciar sesión</button>
        </form>
        <div class="links">
            <p>¿Olvidaste tu <a href="#">Contraseña?</a></p>
            <p>¿No tienes cuenta? <a href="signup.php">Regístrate</a>.</p>
        </div>
    </div>
    <script>
        document.getElementById('show-password').addEventListener('change', function() {
            const passwordInput = document.querySelector('input[name="contrasenia"]');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
    </script>
</body>

</html>