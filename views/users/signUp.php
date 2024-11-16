<?php
session_start();
require_once '../../config/db.php';

$message = '';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if (
        !empty($_POST['correo_electronico']) && !empty($_POST['contrasenia']) &&
        !empty($_POST['confirmar_contrasenia']) && !empty($_POST['nombre']) &&
        !empty($_POST['apellido'])
    ) {
        if ($_POST['contrasenia'] !== $_POST['confirmar_contrasenia']) {
            $message = 'Las contraseñas no coinciden.';
        } else {
            // Verificar si el correo ya existe
            $check_email = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE correo_electronico = ?");
            /*Aqui agregué*/
            $check_email->bind_param("s", $_POST['correo_electronico']);
            $check_email->execute();
            $result = $check_email->get_result();

            /*if ($check_email === false) {
                die('Error en la preparación de la consulta: ' . $conn->error);
            }

            $check_email->bind_param("s", $_POST['correo_electronico']);
            $check_email->execute();
            $result = $check_email->get_result();
            */
            if ($result->num_rows > 0) {
                $message = 'El correo electrónico ya está registrado.';
            } else {
                // Verificar si el nombre de usuario ya existe
                $check_username = $conn->prepare("SELECT id_usuario FROM Usuarios WHERE nombre_usuario = ?");
                $check_username->bind_param("s", $_POST['nombre_usuario']);
                $check_username->execute();
                $result_username = $check_username->get_result();

                if ($result_username->num_rows > 0) {
                    $message = 'El nombre de usuario ya está registrado.';
                } else {

                    $sql = "INSERT INTO Usuarios (nombre, apellido, correo_electronico, contrasenia, rol, nombre_usuario) VALUES (?, ?, ?, ?, 'usuario',?)";
                    $stmt = $conn->prepare($sql);

                    /*if ($stmt === false) {
                    die('Error en la preparación de la consulta: ' . $conn->error);
                }*/

                    $contrasenia = password_hash($_POST['contrasenia'], PASSWORD_BCRYPT);
                    $stmt->bind_param(
                        "sssss",
                        $_POST['nombre'],
                        $_POST['apellido'],
                        $_POST['correo_electronico'],
                        $contrasenia,
                        $_POST['nombre_usuario']
                    );

                    if ($stmt->execute()) {
                        session_unset();
                        session_destroy();
                        $message = 'Usuario creado con éxito. <a href="login.php">Iniciar sesión</a>';
                    } else {
                        if ($conn->errno === 1062) {
                            $message = 'El correo electrónico ya está registrado.';
                        } else {
                            $message = 'Error al crear la cuenta: ' . $conn->error;
                        }
                    }
                }
            }
        }
    } else {
        $message = 'Por favor completa todos los campos.';
    }
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
            /* Fondo que se mantiene cubriendo la pantalla */
            display: flex;
            justify-content: flex-start;
            /* Coloca el formulario a la izquierda */
            align-items: center;
            height: 100vh;
            margin: 0;
            padding-left: 20px;
            /* Espacio a la izquierda para que no quede pegado al borde */
        }

        .container {
            padding: 20px;
            text-align: center;
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        span {
            display: block;
            margin-bottom: 20px;
            font-size: 14px;
        }

        span a {
            color: #007b8a;
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
            background-color: #007b8a;
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