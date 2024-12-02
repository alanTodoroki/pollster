<?php
require_once '../../config/db.php';
require_once '../../models/pollModel.php'; // Modelo para las encuestas

session_start();
$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../views/users/login.php");
    exit;
}

$encuestaModel = new EncuestaModel($db); // Instanciamos el modelo de Encuestas

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_encuesta = $_GET['id'];

    // Eliminar la encuesta
    if ($encuestaModel->eliminarEncuesta($id_encuesta)) {
        header("Location: ../feed/feedUser.php?message=Encuesta eliminada con éxito");
    } else {
        echo "Error al eliminar la encuesta.";
    }
} else {
    echo "ID de encuesta no válido.";
}
