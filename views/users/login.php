<?php
session_start();
require_once '../../config/db.php';

$db = new Database();
$conn = $db->getConnection();
// Removemos la verificación de sesión inicial para permitir otros logins
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Cerramos cualquier sesión existente antes de iniciar una nueva
    session_unset();
    session_destroy();
    session_start();

    if (!empty($_POST['correo_electronico']) && !empty($_POST['contrasenia'])) {
        // Preparar la consulta para buscar por correo electrónico o nombre de usuario
        $input = $_POST['correo_electronico']; // Puede ser correo o nombre de usuario
        $stmt = $conn->prepare('SELECT id_usuario, correo_electronico, contrasenia, nombre_usuario, rol FROM Usuarios WHERE correo_electronico = ? OR nombre_usuario = ?');
        //$stmt = $conn->prepare('SELECT id_usuario, correo_electronico, contrasenia, nombre_usuario, rol FROM Usuarios WHERE correo_electronico = ?');
        //$stmt->bind_param('ss', $_POST['correo_electronico']);
        $stmt->bind_param('ss', $input, $input);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($_POST['contrasenia'], $user['contrasenia'])) {
            //$_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['nombre_usuario'] = $user['nombre_usuario']; // Guardar el nombre de usuario en la sesión
            $_SESSION['rol'] = $user['rol']; // Guardar el rol en la sesión

            // Redirigir según el rol del usuario
            if ($user['rol'] === 'administrador') {
                header("Location: ../admin/dashboard.php"); // Redirigir a la página del administrador
            } else {
                header("Location: ../feed/feedUser.php"); // Redirigir a la página del usuario general
            }
            exit();
        } else {
            $message = 'Lo siento, las credenciales no coinciden';
        }
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
            background: none;
            /* Elimina cualquier fondo */
            backdrop-filter: blur(10px);
            /* Solo el efecto de desenfoque */
            padding: 40px;
            border-radius: 8px;
            box-shadow: none;
            /* Elimina la sombra */
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: black;
            /* Color de texto blanco */
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Inicio de sesión</h1>
        <?php if (!empty($message)): ?>
            <p style="color: red;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <input name="correo_electronico" type="text" placeholder="Correo electrónico o nombre de usuario" required value="<?= htmlspecialchars($_POST['correo_electronico'] ?? '') ?>">
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
    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
        <div class="card">
            <div class="card-header p-2 ps-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-0 text-capitalize">Today's Users</p>
                        <h4 class="mb-0">2300</h4>
                    </div>
                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                        <i class="material-symbols-rounded opacity-10">person</i>
                    </div>
                </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
                <p class="mb-0 text-sm"><span class="text-success font-weight-bolder">+3% </span>than last month</p>
            </div>
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