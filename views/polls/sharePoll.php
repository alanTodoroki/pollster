<?php
require_once '../../config/db.php';
require_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id_encuesta = $_GET['id'];

    // Obtener los detalles de la encuesta
    $encuestaModel = new EncuestaModel($db);
    $encuesta = $encuestaModel->obtenerEncuesta($id_encuesta);

    if ($encuesta) {
        // Generar un token único
        $token = generarToken($id_encuesta); // Aquí debes utilizar la función que genera el token

        // Actualizar el token en la base de datos
        $encuestaModel->actualizarToken($id_encuesta, $token);

        // Mostrar el enlace para compartir
        echo "<h2>Comparte este enlace para acceder a la encuesta:</h2>";
        echo "<p><a href='sharePoll.php?token=$token' target='_blank'>Compartir Encuesta</a></p>";
        echo "<p>Enlace directo a la encuesta: <strong>https://tusitio.com/sharePoll.php?token=$token</strong></p>";
    } else {
        echo "Encuesta no encontrada.";
    }
} else {
    echo "No se ha proporcionado un ID de encuesta.";
}

// Función para generar el token único
function generarToken($id_encuesta)
{
    return substr(md5($id_encuesta . time()), 0, 8); // Genera un token de 8 caracteres
}
