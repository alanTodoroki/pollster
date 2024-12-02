<?php
session_start();
include_once '../../config/db.php';
include_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();
$pollModel = new EncuestaModel($db);

$id_encuesta = $_GET['id'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_encuesta || !$id_usuario) {
    die('ID de encuesta o usuario no especificado.');
}

// Obtener los resultados de la encuesta
$respuestas = $pollModel->obtenerRespuestas($id_encuesta);
$respuestasPorPregunta = [];
foreach ($respuestas as $respuesta) {
    $pregunta = $respuesta['texto_pregunta'];
    if (!isset($respuestasPorPregunta[$pregunta])) {
        $respuestasPorPregunta[$pregunta] = [];
    }
    $respuestasPorPregunta[$pregunta][] = $respuesta['texto_respuesta'];
}

$csv_filename = "resultados_encuesta_{$id_encuesta}.csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $csv_filename . '"');

$output = fopen('php://output', 'w');

// Si quieres añadir el BOM (opcional, para Excel):
fwrite($output, "\xEF\xBB\xBF"); // Esto fuerza UTF-8 en Excel
fputcsv($output, ['Pregunta', 'Respuesta', 'Votos']);

foreach ($respuestasPorPregunta as $pregunta => $respuestas) {
    $conteoRespuestas = array_count_values($respuestas);
    foreach ($conteoRespuestas as $respuesta => $votos) {
        fputcsv($output, [$pregunta, $respuesta, $votos]);
    }
}

fclose($output);
