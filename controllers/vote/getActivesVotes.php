<?php
require_once '../../config/db.php';
require_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();

$votacionModel = new VotacionModel($db);
$votaciones = $votacionModel->obtenerVotacionesActivasConOpciones();

header('Content-Type: application/json');
echo json_encode($votaciones);
