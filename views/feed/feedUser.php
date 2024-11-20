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
$encuestaModel = new EncuestaModel();
$votacionModel = new VotacionModel($db);

$usuario = $usuarioModel->obtenerUsuario($_SESSION['id_usuario']);
/*$encuestas = $encuestaModel->obtenerEncuestasPorUsuario($_SESSION['id_usuario']);
$votaciones = $votacionModel->obtenerVotacionesPorUsuario($_SESSION['id_usuario']);
*/
$encuestas = $encuestaModel->obtenerEncuestasActivas();  // Necesitarás crear este método en tu modelo
$votaciones = $votacionModel->obtenerVotacionesActivas();  // Necesitarás crear este método en tu modelo

if (empty($encuestas)) {
    echo "No hay encuestas disponibles."; // Mostrar mensaje si no hay encuestas
}

if (empty($votaciones)) {
    echo "No hay votaciones disponibles."; // Mostrar mensaje si no hay votaciones
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed del Usuario</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../public/css/votes.css">
    <style>
        .user-profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-profile img {
            border-radius: 50%;
        }

        .user-profile button {
            margin-top: 10px;
        }

        .feed {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px;
        }

        .poll {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
            flex: 1 1 calc(33.333% - 20px);
            /* Ajusta el ancho según tus necesidades */
            box-sizing: border-box;
        }

        .poll-header {
            display: flex;
            align-items: center;
        }

        .poll-header img {
            border-radius: 50%;
            margin-right: 10px;
        }

        .poll-header .info {
            flex-grow: 1;
        }

        .poll-body h2 {
            margin-top: 0;
        }

        .poll-options li {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="user-profile">
        <img src="foto_de_perfil.jpg" alt="Foto de perfil" width="50" height="50">
        <h1><?= isset($usuario['nombre_usuario']) ? $usuario['nombre_usuario'] : 'Nombre de usuario no disponible' ?></h1>
        <h2><?= isset($usuario['nombre']) && isset($usuario['apellido']) ? $usuario['nombre'] . ' ' . $usuario['apellido'] : 'Nombre no disponible' ?></h2>
        <p><?= $usuario['correo_electronico'] ?></p>
        <button onclick="redirectToSettings()">Configuración</button>
    </div>

    <div class="feed">
        <!-- Mostrar encuestas -->
        <?php foreach ($encuestas as $encuesta): ?>
            <div class="poll">
                <div class="poll-header">
                    <img alt="User profile picture" src="https://storage.googleapis.com/a1aa/image/b2Vv6krDftUKL6H1O90cRD6TFT2DEkLAXXmfobXXGBoZ21rTA.jpg" width="40" height="40" />
                    <div class="info">
                        <div class="name"><?= $usuario['nombre_usuario'] ?></div>
                        <div class="time">3 semanas atrás</div>
                    </div>
                </div>
                <div class="poll-body">
                    <h2><?= $encuesta['titulo'] ?></h2>
                    <p><?= $encuesta['descripcion'] ?></p>
                    <button onclick="window.location.href='participar_encuesta.php?id=<?= $encuesta['id_encuesta'] ?>'">Participar en esta encuesta</button>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Mostrar votaciones -->
        <?php foreach ($votaciones as $votacion): ?>
            <div class="poll">
                <div class="poll-header">
                    <img alt="User profile picture" src="https://storage.googleapis.com/a1aa/image/b2Vv6krDftUKL6H1O90cRD6TFT2DEkLAXXmfobXXGBoZ21rTA.jpg" width="40" height="40" />
                    <div class="info">
                        <div class="name"><?= $usuario['nombre_usuario'] ?></div>
                        <div class="time">3 semanas atrás</div>
                    </div>
                </div>
                <div class="poll-body">
                    <h2><?= $votacion['titulo'] ?></h2>
                    <p><?= $votacion['descripcion'] ?></p>
                    <ul class="poll-options">
                        <?php
                        // Obtener las opciones de la votación
                        $opciones = $votacionModel->obtenerOpcionesVotacion($votacion['id_votacion']);
                        foreach ($opciones as $opcion):
                        ?>
                            <li><span><?= $opcion['texto_opcion'] ?></span><span>45%</span></li> <!-- Aquí puedes calcular el porcentaje -->
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <script>
        function redirectToSettings() {
            window.location.href = 'configuration.php';
        }
    </script>
</body>

</html>