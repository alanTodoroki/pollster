<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/userModel.php';

// Verificación de permisos de administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página";
    header('Location: ../users/login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userModel = new UsuarioModel($db);

// Verificar si se ha proporcionado un ID de usuario
if (!isset($_GET['id'])) {
    die("ID de usuario no especificado");
}

$id_usuario = $_GET['id'];
$usuario = $userModel->obtenerUsuarioPorId($id_usuario);

// Procesar actualización de usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Preparar los datos a actualizar
    $datosActualizar = [];
    $params = [];

    // Verificar y agregar cada campo solo si ha cambiado
    if ($_POST['nombre'] !== $usuario['nombre']) {
        $datosActualizar[] = "nombre = ?";
        $params[] = $_POST['nombre'];
    }

    if ($_POST['apellido'] !== $usuario['apellido']) {
        $datosActualizar[] = "apellido = ?";
        $params[] = $_POST['apellido'];
    }

    if ($_POST['correo'] !== $usuario['correo_electronico']) {
        $datosActualizar[] = "correo_electronico = ?";
        $params[] = $_POST['correo'];
    }

    if ($_POST['rol'] !== $usuario['rol']) {
        $datosActualizar[] = "rol = ?";
        $params[] = $_POST['rol'];
    }

    // Si hay cambios, actualizar
    if (!empty($datosActualizar)) {
        $params[] = $id_usuario;
        $resultado = $userModel->actualizarUsuarioParcial($id_usuario, $datosActualizar, $params);

        if ($resultado) {
            // Refrescar los datos del usuario después de la actualización
            $usuario = $userModel->obtenerUsuarioPorId($id_usuario);
            $_SESSION['mensaje'] = "Usuario actualizado exitosamente";
        } else {
            $error = "No se pudo actualizar el usuario";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .form-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h1 class="text-center mb-4">Editar Usuario</h1>

                    <?php if (isset($_SESSION['mensaje'])): ?>
                        <div class="alert alert-success">
                            <?php
                            echo $_SESSION['mensaje'];
                            unset($_SESSION['mensaje']);
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre"
                                value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido"
                                value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" name="correo"
                                value="<?php echo htmlspecialchars($usuario['correo_electronico']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rol</label>
                            <select class="form-control" name="rol">
                                <option value="administrador" <?php echo ($usuario['rol'] == 'administrador') ? 'selected' : ''; ?>>
                                    Administrador
                                </option>
                                <option value="usuario" <?php echo ($usuario['rol'] == 'usuario') ? 'selected' : ''; ?>>
                                    Usuario
                                </option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="gestionUsuarios.php" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>