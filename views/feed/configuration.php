<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/configurationModel.php';
require_once '../../controllers/configuration/configurationController.php';

// Verificar autenticación
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../login.php');
    exit();
}

// Crear instancias
$database = new Database();
$configuracionModel = new ConfiguracionModel($db);
$configuracionController = new ConfiguracionController();

// Obtener datos del usuario
$usuario = $configuracionModel->obtenerConfiguracion($_SESSION['id_usuario']);

// Manejar mensajes de estado
$mensaje = isset($_GET['mensaje']) ? urldecode($_GET['mensaje']) : null;
$tipo_mensaje = isset($_GET['tipo']) ? $_GET['tipo'] : null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos previos mantenidos */
        .error-input {
            border-color: red;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Mostrar mensajes de estado -->
        <?php if ($mensaje): ?>
            <div class="alert alert-<?= $tipo_mensaje == 'error' ? 'danger' : 'success' ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-4">
                <!-- Tarjeta de Perfil (código anterior mantenido) -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title mt-3">
                            <?= htmlspecialchars($usuario['nombre']) ?>
                        </h5>
                        <p class="card-text text-muted">
                            <?= htmlspecialchars($usuario['correo_electronico']) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Configuración de Perfil</div>
                    <div class="card-body">
                        <form id="formulario-configuracion" novalidate>
                            <!-- Campos de información personal -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text"
                                        class="form-control"
                                        id="nombre"
                                        name="nombre"
                                        value="<?= htmlspecialchars($usuario['nombre']) ?>"
                                        required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese su nombre.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                                    <input type="text"
                                        class="form-control"
                                        id="nombre_usuario"
                                        name="nombre_usuario"
                                        value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>"
                                        required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese un nombre de usuario.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label">Correo Electrónico</label>
                                <input type="email"
                                    class="form-control"
                                    id="correo"
                                    name="correo"
                                    value="<?= htmlspecialchars($usuario['correo_electronico']) ?>"
                                    required>
                                <div class="invalid-feedback">
                                    Por favor ingrese un correo electrónico válido.
                                </div>
                            </div>

                            <!-- Sección de cambio de contraseña -->
                            <div class="card mt-4">
                                <div class="card-header">Cambiar Contraseña</div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="contrasenia_actual" class="form-label">Contraseña Actual</label>
                                        <input type="password"
                                            class="form-control"
                                            id="contrasenia_actual"
                                            name="contrasenia_actual">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nueva_contrasenia" class="form-label">Nueva Contraseña</label>
                                            <input type="password"
                                                class="form-control"
                                                id="nueva_contrasenia"
                                                name="nueva_contrasenia"
                                                minlength="8">
                                            <div class="form-text">
                                                Mínimo 8 caracteres
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="confirmar_contrasenia" class="form-label">Confirmar Nueva Contraseña</label>
                                            <input type="password"
                                                class="form-control"
                                                id="confirmar_contrasenia"
                                                name="confirmar_contrasenia"
                                                minlength="8">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist /js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formulario = document.getElementById('formulario-configuracion');

            // Manejar el envío del formulario
            formulario.addEventListener('submit', function(event) {
                event.preventDefault();
                if (formulario.checkValidity() === false) {
                    event.stopPropagation();
                } else {
                    const formData = new FormData(formulario);
                    fetch('../../controllers/configuration/configurationController.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            const mensaje = encodeURIComponent(data.message);
                            const tipo = data.success ? 'success' : 'error';
                            window.location.href = `?mensaje=${mensaje}&tipo=${tipo}`;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
                formulario.classList.add('was-validated');
            });
        });
    </script>
</body>

</html>