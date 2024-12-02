<?php
require_once '../../config/db.php';
require_once '../../models/voteModel.php'; // Modelo para las votaciones

session_start();
$database = new Database();
$db = $database->getConnection();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../../views/users/login.php");
    exit;
}

$votacionModel = new VotacionModel($db); // Instanciamos el modelo de Votaciones

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_votacion = $_GET['id'];

    // Eliminar la votación
    if ($votacionModel->eliminarVotacion($id_votacion)) {
        header("Location: ../feed/feedUser.php?message=Votación eliminada con éxito");
    } else {
        echo "Error al eliminar la votación.";
    }
} else {
    echo "ID de votación no válido.";
}
