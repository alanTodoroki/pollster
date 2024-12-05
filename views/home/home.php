<?php
require_once '../../config/db.php';
require_once '../../models/userModel.php';
require_once '../../models/configurationModel.php';
require_once '../../models/pollModel.php';
require_once '../../models/voteModel.php';

session_start();
$database = new Database();
$db = $database->getConnection();

// Obtener usuario logueado
$usuario = null;
if (isset($_SESSION['id_usuario'])) {
    $usuarioModel = new UsuarioModel($db);
    $usuario = $usuarioModel->obtenerUsuario($_SESSION['id_usuario']);
}

// Verificar si el usuario está logueado
$id_usuario = $_SESSION['id_usuario'] ?? null;
if (!$id_usuario) {
    die('El usuario no está logueado.');
}

$encuestaModel = new EncuestaModel($db);
$votacionModel = new VotacionModel($db);

// Obtener todas las encuestas y votaciones activas
$encuestas = $encuestaModel->obtenerEncuestasActivas(true);
$votaciones = $votacionModel->obtenerVotacionesActivas(true);
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
        :root {
            --primary-color: #2C5F2D;
            --secondary-color: #608C50;
            --accent-color: #A8C0CC;
            --text-dark: #333;
            --text-light: #666;
            --background-light: #F4F6F7;
        }

        body {
            background-color: var(--background-light);
            font-family: 'Inter', 'Roboto', sans-serif;
            color: var(--text-dark);
        }

        .container {
            max-width: 1200px;
            padding: 2rem;
        }

        /* User Profile Section */
        .profile-section {
            background-color: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            text-align: center;
        }

        .profile-pic-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--secondary-color);
            transition: transform 0.3s ease;
        }

        .profile-pic-large:hover {
            transform: scale(1.05);
        }

        h1 {
            color: var(--primary-color);
            font-weight: 700;
            margin-top: 1rem;
        }

        h2 {
            color: var(--text-light);
            font-weight: 400;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-encuestas {
            border-top: 4px solid #F18F01;
        }

        .card-votaciones {
            border-top: 4px solid var(--primary-color);
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

        .btn-light {
            background-color: #bb4b02;
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: background-color 0.3s ease;
        }

        .card-encuestas .btn-light:hover {
            background-color: #9c3e02;
            color: white;
        }

        .card-votaciones .btn-light {
            background-color: var(--primary-color);
            color: white;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: background-color 0.3s ease;
        }

        .card-votaciones .btn-light:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-light:hover {
            background-color: var(--secondary-color);
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .card-container {
                display: flex;
                flex-direction: column;
            }
        }

        /* Empty State Styles */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .empty-state p {
            color: var(--text-light);
            margin-top: 1rem;
            text-align: center;
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
                                    <a href="sharePoll.php?id=<?= $encuesta['id_encuesta'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-share"></i> Compartir</a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3"><?= $encuesta['titulo'] ?></h5>
                                <p class="card-text"><?= $encuesta['descripcion'] ?></p>
                                <a href="../polls/participatePoll.php?id=<?= $encuesta['id_encuesta'] ?>" class="btn btn-light mt-3">Participar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center my-4">
                    <img src="../../public/img/person.svg" alt="No hay encuestas" class="img-fluid" style="max-width: 200px;">
                    <p class="text-center mt-2">No hay encuestas disponibles.</p>
                </div>
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
                                    <a href="shareVote.php?id=<?= $votacion['id_votacion'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-share"></i> Compartir</a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3"><?= $votacion['titulo'] ?></h5>
                                <p class="card-text"><?= $votacion['descripcion'] ?></p>
                                <a href="../votes/participateVote.php?id=<?= $votacion['id_votacion'] ?>" class="btn btn-light mt-3">Participar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center my-4">
                    <img src="../../public/img/cat.svg" alt="No hay votaciones" class="img-fluid" style="max-width: 200px;">
                    <p class="text-center mt-2">No hay votaciones disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>