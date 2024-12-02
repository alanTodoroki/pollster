<?php
session_start();
include_once '../../config/db.php';
include_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();
$votacionModel = new VotacionModel($db);

$id_votacion = $_GET['id'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_votacion || !$id_usuario) {
    die('ID de votación o usuario no especificado.');
}

// Obtener los resultados de la votación
$resultados = $votacionModel->obtenerResultadosVotacion($id_votacion);
$csv_filename = "resultados_votacion_{$id_votacion}.csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $csv_filename . '"');

$output = fopen('php://output', 'w');

// Si quieres añadir el BOM (opcional, para Excel):
fwrite($output, "\xEF\xBB\xBF"); // Esto fuerza UTF-8 en Excel
fputcsv($output, ['Opción', 'Votos', 'Porcentaje']);

$total_votos = array_sum(array_column($resultados, 'votos'));
foreach ($resultados as $resultado) {
    $porcentaje = $total_votos > 0 ? round(($resultado['votos'] / $total_votos) * 100, 1) : 0;
    fputcsv($output, [$resultado['texto_opcion'], $resultado['votos'], $porcentaje . '%']);
}

fclose($output);
