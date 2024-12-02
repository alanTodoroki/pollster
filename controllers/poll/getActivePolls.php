<?php
require_once '../../config/db.php';
require_once '../../models/pollModel.php';

header('Content-Type: application/json');

$database = new Database();
$db = $database->getConnection();

$encuestaModel = new EncuestaModel($db);

try {
    $encuestas = $encuestaModel->obtenerEncuestasActivas();
    echo json_encode($encuestas); // Envía las encuestas como JSON
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]); // Manejo de errores
}
