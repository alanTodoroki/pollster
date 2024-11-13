<?php

session_start();
require_once '../../models/userModel..php';
require_once '../../models/configurationModel.php';
require_once '../../models/pollModel.php';
require_once '../../models/voteModel.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../views/users/login.php");
}

$usuarioModel = new UsuarioModel();
$encuestaModel = new EncuestaModel();
$votacionModel = new VotacionModel();

$usuario = $usuarioModel->obtenerUsuario($_SESSION['id_usuario']);
/*$encuestas = $encuestaModel->obtenerEncuestasPorUsuario($_SESSION['id_usuario']);
$votaciones = $votacionModel->obtenerVotacionesPorUsuario($_SESSION['id_usuario']);
*/
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <title>Feed del Usuario</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .poll {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .poll-header {
            display: flex;
            align-items: center;
        }

        .poll-header i {
            font-size: 40px;
            margin-right: 10px;
        }

        .poll-header .info {
            flex-grow: 1;
        }

        .poll-header .icon {
            margin-left: auto;
        }

        .poll-body h3 {
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
        <h1>Nombre de Usuario</h1>
        <p>Correo Electrónico</p>
        <button onclick="redirectToSettings()">Configuración</button>
    </div>

    <div class="feed">
        <!-- Ejemplo de Encuesta -->
        <div class="poll">
            <div class="poll-header">
                <i class="fas fa-user-circle"></i>
                <div class="info">
                    <h2>Nombre de Usuario</h2>
                    <p>3 semanas atrás</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
            <div class="poll-body">
                <h3>Título de la Encuesta</h3>
                <p>Descripción de la encuesta.</p>
                <button>Participar en esta encuesta</button>
            </div>
        </div>

        <!-- Ejemplo de Votación -->
        <div class="poll">
            <div class="poll-header">
                <i class="fas fa-user-circle"></i>
                <div class="info">
                    <h2>Nombre de Usuario</h2>
                    <p>3 semanas atrás</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
            <div class="poll-body">
                <h3>¿Cuál es tu tipo de mascota favorita?</h3>
                <p>Ayúdanos a entender tus preferencias.</p>
                <ul class="poll-options">
                    <li><span>Perro</span><span>45%</span></li>
                    <li><span>Gato</span><span>35%</span></li>
                    <li><span>Pez</span><span>12%</span></li>
                    <li><span>Pájaro</span><span>8%</span></li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function redirectToSettings() {
            window.location.href = 'configuration.php';
        }
    </script>
</body>

</html>