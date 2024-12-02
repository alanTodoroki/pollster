<?php
require_once '../../config/db.php';
require_once '../../models/userModel.php';
require_once '../../models/configurationModel.php';
require_once '../../models/pollModel.php';
require_once '../../models/voteModel.php';

session_start();
$database = new Database();
$db = $database->getConnection();

// Verificar si hay una sesión activa
$usuario = null;
if (isset($_SESSION['id_usuario'])) {
    $usuarioModel = new UsuarioModel($db);
    $usuario = $usuarioModel->obtenerUsuario($_SESSION['id_usuario']);
}

$encuestaModel = new EncuestaModel($db);
$votacionModel = new VotacionModel($db);

// Obtener todas las encuestas y votaciones activas de todos los usuarios
$encuestas = $encuestaModel->obtenerEncuestasActivas();
$votaciones = $votacionModel->obtenerVotacionesActivas();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Encuestas y Votaciones</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        /* Estilos de perfil y generales */
        .profile-pic-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-pic-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .logo-small {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
        }

        h2 {
            font-size: 1rem;
            color: #888;
        }

        /* Estilos específicos de cards */
        .card-votaciones {
            border: 2px solid #99C24D;
        }

        .card-votaciones .card-title {
            color: #99C24D;
        }

        .card-encuestas {
            border: 2px solid #F18F01;
        }

        .card-encuestas .card-title {
            color: #F18F01;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../components/navbar.php'; ?>

    <div class="container mt-4">
        <!-- Perfil de usuario si está logueado -->
        <?php if ($usuario): ?>
            <div class="text-center mb-4">
                <img src="../../public/img/logo.png" alt="Logo" class="profile-pic-large">
                <h1 class="text-dark fw-bold">Pollster</h1>


            </div>
        <?php endif; ?>

        <!-- Feed de Encuestas y Votaciones -->
        <div class="row">
            <!-- Encuestas -->
            <?php if (!empty($encuestas)): ?>
                <?php foreach ($encuestas as $encuesta): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card card-encuestas">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="../../public/img/avatar.png" alt="User profile picture" class="rounded-circle me-2 profile-pic-small">
                                    <div>
                                        <div class="fw-bold"><?= $encuesta['nombre_usuario'] ?></div>
                                        <small class="text-muted">Encuesta</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-share"></i></a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3"><?= $encuesta['titulo'] ?></h5>
                                <p class="card-text"><?= $encuesta['descripcion'] ?></p>
                                <a href="participar_encuesta.php?id=<?= $encuesta['id_encuesta'] ?>" class="btn btn-light mt-3">Participar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay encuestas disponibles.</p>
            <?php endif; ?>

            <!-- Votaciones -->
            <?php if (!empty($votaciones)): ?>
                <?php foreach ($votaciones as $votacion): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card card-votaciones">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="../../public/img/avatar.png" alt="User profile picture" class="rounded-circle me-2 profile-pic-small">
                                    <div>
                                        <div class="fw-bold"><?= $votacion['nombre_usuario'] ?></div>
                                        <small class="text-muted">Votación</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="bi bi-share"></i></a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3"><?= $votacion['titulo'] ?></h5>
                                <p class="card-text"><?= $votacion['descripcion'] ?></p>
                                <a href="participar_votacion.php?id=<?= $votacion['id_votacion'] ?>" class="btn btn-light mt-3">Participar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No hay votaciones disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>