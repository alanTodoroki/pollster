<?php
require_once '../../config/db.php';
require_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id_votacion = $_GET['id'];

    // Obtener los detalles de la votación
    $votacionModel = new VotacionModel($db);
    $votacion = $votacionModel->obtenerVotacion($id_votacion);

    if ($votacion) {
        // Generar un token único
        $token = generarToken($id_votacion); // Aquí debes utilizar la función que genera el token

        // Actualizar el token en la base de datos
        $votacionModel->actualizarToken($id_votacion, $token);

        // Mostrar el enlace para compartir
        echo "<h2>Comparte este enlace para acceder a la votación:</h2>";
        echo "<p><a href='shareVote.php?token=$token' target='_blank'>Compartir Votación</a></p>";
        echo "<p>Enlace directo a la votación: <strong>https://tusitio.com/shareVote.php?token=$token</strong></p>";
    } else {
        echo "Votación no encontrada.";
    }
} else {
    echo "No se ha proporcionado un ID de votación.";
}

// Función para generar el token único
function generarToken($id_votacion)
{
    return substr(md5($id_votacion . time()), 0, 8); // Genera un token de 8 caracteres
}
