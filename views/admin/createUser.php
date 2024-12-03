<?php
session_start();
require_once '../../config/db.php';
require_once '../../controllers/user/userController.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header('Location: ../users/login.php');
    exit();
}

$message = '';
$database = new Database();
$db = $database->getConnection();
$createUserController = new CreateUserController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $createUserController->handleCreateUser($_POST);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Pollster</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        .form-control,
        .form-select {
            border-radius: 5px;
            border: 1px solid #E0E0E0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #586F6B;
            box-shadow: 0 0 0 0.25rem rgba(88, 111, 107, 0.25);
            /* Sombra de enfoque */
        }

        .btn-primary {
            background-color: #586F6B;
            border-color: #586F6B;
        }

        .btn-primary:hover {
            background-color: #475C4F;
            /* Color de hover */
            border-color: #475C4F;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h2 class="text-center mb-4">
                        <i class="fas fa-user-plus me-2"></i>Crear Nuevo Usuario
                    </h2>

                    <!-- Mostrar mensaje si existe -->
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= strpos($message, 'éxito') !== false ? 'alert-success' : 'alert-danger' ?>">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    <div class="d-grid mb-3">
                        <a href="manageUsers.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Regresar
                        </a>
                    </div>
                    <form method="POST" action="createUser.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido"
                                    value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="correo_electronico" name="correo_electronico"
                                value="<?= htmlspecialchars($_POST['correo_electronico'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario"
                                value="<?= htmlspecialchars($_POST['nombre_usuario'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="contrasenia" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contrasenia" name="contrasenia" required>
                        </div>

                        <div class="mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="">Selecciona un rol</option>
                                <option value="usuario" <?= isset($_POST['rol']) && $_POST['rol'] === 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                <option value="administrador" <?= isset($_POST['rol']) && $_POST['rol'] === 'administrador' ? 'selected' : '' ?>>Administrador</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>