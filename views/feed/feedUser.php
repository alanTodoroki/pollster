<?php
require_once '../../config/db.php';
require_once '../../models/userModel.php';
require_once '../../models/configurationModel.php';
require_once '../../models/pollModel.php';
require_once '../../models/voteModel.php';

session_start();
$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../views/users/login.php");
}

$usuarioModel = new UsuarioModel($db);
$encuestaModel = new EncuestaModel($db);
$votacionModel = new VotacionModel($db);

$usuario = $usuarioModel->obtenerUsuario($_SESSION['id_usuario']);
$encuestas = $encuestaModel->obtenerEncuestas($_SESSION['id_usuario']);
$votaciones = $votacionModel->obtenerVotaciones($_SESSION['id_usuario']);
//var_dump($usuario);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed del Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Estilos personalizados */
        .btn-custom {
            background-color: #99C24D;
            color: white;
        }

        .btn-custom:hover {
            background-color: #638729;
            color: white;
        }

        .btn-participar {
            background-color: #006E90;
            /* Cambia este color al que desees */
            color: white;
            border: none;
        }

        .card {
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .card-body {
            background-color: #ffffff;
        }

        .card-title {
            color: #638729;
        }

        .text-muted {
            font-size: 0.9em;
        }

        /* Imagen grande */
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
            width: 20px;
            height: 20px;
            object-fit: contain;
            margin-right: 10px;
        }

        /* Estilo para alinear nombre de usuario y logo */
        h1 {
            font-size: 2.5rem;
            /* Hacer el nombre más grande */
            color: #333;
            /* Color más oscuro para el nombre */
        }

        h2 {
            font-size: 1rem;
            /* Hacer el nombre de usuario más pequeño */
            color: #888;
            /* Color más claro para el nombre de usuario */
        }

        .logo-small {
            width: 30px;
            /* Hacer el logo un poco más grande */
            height: 30px;
            object-fit: contain;
        }

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

        .text-center p {
            font-size: 1.2em;
            color: #555;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include '../components/navbar.php'; ?>

    <div class="container mt-4">
        <!-- User Profile -->
        <div class="text-center mb-4">
            <img src="../../public/img/avatar.png" alt="Foto de perfil" class="profile-pic-large">
            <h1 class="text-dark fw-bold"><?= isset($usuario['nombre']) && isset($usuario['apellido']) ? $usuario['nombre'] . ' ' . $usuario['apellido'] : 'Nombre no disponible' ?></h1>

            <div class="d-flex justify-content-center align-items-center mb-2">
                <img src="../../public/img/logo.png" alt="Logo" class="logo-small me-2">
                <h2 class="text-muted fs-5"><?= isset($usuario['nombre_usuario']) ? $usuario['nombre_usuario'] : 'Nombre de usuario no disponible' ?></h2>
            </div>

            <p class="text-secondary"><?= $usuario['correo_electronico'] ?></p>

            <button class="btn btn-custom" onclick="redirectToSettings()">Configuración</button>
        </div>

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
                                        <div class="fw-bold"><?= $usuario['nombre_usuario'] ?></div>
                                        <small class="text-muted">Encuesta</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="../polls/seePoll.php?id=<?php echo $encuesta['id_encuesta']; ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('encuesta', <?= $encuesta['id_encuesta'] ?>)"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3"><?= $encuesta['titulo'] ?></h5>
                                <p class="card-text"><?= $encuesta['descripcion'] ?></p>
                                <a href="../polls/participatePoll.php?id=<?php echo $encuesta['id_encuesta']; ?>" class="btn btn-participar mt-3">Participar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center my-4">
                    <img src="../../public/img/person.svg" alt="No hay encuestas" class="img-fluid" style="max-width: 200px;">
                    <p class="text-center mt-2">No hay encuestas disponibles.</p>
                </div> <?php endif; ?>

            <!-- Votaciones -->
            <?php if (!empty($votaciones)): ?>
                <?php foreach ($votaciones as $votacion): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card card-votaciones">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <img src="../../public/img/avatar.png" alt="User profile picture" class="rounded-circle me-2 profile-pic-small">
                                    <div>
                                        <div class="fw-bold"><?= $usuario['nombre_usuario'] ?></div>
                                        <small class="text-muted">Votación</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="../votes/seeVote.php?id=<?php echo $votacion['id_votacion']; ?>" class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('votacion', <?= $votacion['id_votacion'] ?>, event)"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title mb-3"><?= $votacion['titulo'] ?></h5>
                                <p class="card-text"><?= $votacion['descripcion'] ?></p>
                                <a href="../votes/participateVote.php?id=<?= $votacion['id_votacion'] ?>" class="btn btn-participar mt-3">Participar</a>
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
    <script>
        function confirmDelete(type, id) {
            if (confirm("¿Estás seguro de querer eliminar esta " + (type == 'encuesta' ? "Encuesta" : "Votación") + "?")) {
                // Redirigir al archivo de eliminación correspondiente
                if (type == 'encuesta') {
                    window.location.href = '../../views/polls/deletePoll.php?id=' + id;
                } else if (type == 'votacion') {
                    window.location.href = '../../views/votes/deleteVote.php?id=' + id;
                }
            }
        }
    </script>

</body>

</html>