<?php
session_start();
include '../../config/db.php'; // Archivo de conexión a la base de datos
include '../../models/userModel.php'; // Asegúrate de que esta ruta sea correcta

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userModel = new UsuarioModel($db);

$id_usuario = $_SESSION['id_usuario'];

// Obtener información actual del usuario
$consulta = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$consulta->bind_param("i", $id_usuario);
$consulta->execute();
$resultado = $consulta->get_result();
$usuario = $resultado->fetch_assoc();

// Procesar actualización de perfil
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nombre_usuario = $_POST['nombre_usuario'];

    // Manejar la foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $carpeta_fotos = 'profilePhoto/';

        // Crear carpeta si no existe
        if (!file_exists($carpeta_fotos)) {
            mkdir($carpeta_fotos, 0777, true);
        }

        // Generar nombre único para la foto
        $nombre_archivo = $id_usuario . '_' . time() . '_' . $_FILES['foto_perfil']['name'];
        $ruta_completa = $carpeta_fotos . $nombre_archivo;

        // Subir archivo
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $ruta_completa)) {
            // Actualizar ruta de foto en base de datos
            $actualizar_foto = $db->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
            $actualizar_foto->bind_param("si", $ruta_completa, $id_usuario);
            $actualizar_foto->execute();
        }
    }

    // Actualizar información del usuario
    $actualizar = $db->prepare("UPDATE usuarios SET nombre = ?, apellido = ?, nombre_usuario = ? WHERE id_usuario = ?");
    $actualizar->bind_param("sssi", $nombre, $apellido, $nombre_usuario, $id_usuario);

    if ($actualizar->execute()) {
        $mensaje = "Perfil actualizado exitosamente";
        // Recargar información del usuario
        $consulta = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $consulta->bind_param("i", $id_usuario);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_assoc();
    } else {
        $error = "Error al actualizar el perfil";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Configuración de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fb;
            font-family: 'Arial', sans-serif;
        }

        .form-container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin-top: 60px;
        }

        .perfil-foto {
            max-width: 150px;
            max-height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #006E90;
            margin-bottom: 15px;
        }

        h1 {
            color: #006E90;
            font-weight: 700;
        }

        .btn-primary {
            background-color: #006E90;
            border: none;
        }

        .btn-primary:hover {
            background-color: #005377;
        }

        .btn-secondary {
            background-color: #dee2e6;
            color: #495057;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #ced4da;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        input.form-control {
            border: 1px solid #ced4da;
        }

        input.form-control:focus {
            border-color: #006E90;
            box-shadow: 0 0 0 0.2rem rgba(0, 110, 144, 0.25);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="form-container">
                    <h1 class="text-center mb-4">Configuración de Perfil</h1>

                    <?php
                    if (isset($mensaje)) {
                        echo "<div class='alert alert-success'>$mensaje</div>";
                    }
                    if (isset($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                    ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <?php if (!empty($usuario['foto_perfil'])): ?>
                                <img src="<?php echo htmlspecialchars($usuario['foto_perfil']); ?>" alt="Foto de Perfil" class="perfil-foto">
                            <?php else: ?>
                                <img src="default-profile.png" alt="Foto de Perfil" class="perfil-foto">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="foto_perfil" class="form-label">Cambiar Foto de Perfil</label>
                                <input type="file" class="form-control" name="foto_perfil" id="foto_perfil" accept="image/jpeg,image/png,image/gif">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="apellido" id="apellido"
                                value="<?php echo htmlspecialchars($usuario['apellido']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario"
                                value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <a href="../feed/feedUser.php" class="btn btn-secondary">Inicio</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>