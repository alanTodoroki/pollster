<?php
session_start();
include_once '../../config/db.php';
include_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();
$pollModel = new EncuestaModel($db);

$id_encuesta = $_GET['id'] ?? null; // Verifica que el ID de la votación se pasa correctamente
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_encuesta || !$id_usuario) {
    die('ID de votación o usuario no especificado.');
}
// Obtener gráficas de preguntas de opción múltiple
$graficasMultiples = $pollModel->generarGraficasPreguntasMultiples($id_encuesta);
$encuesta = $pollModel->obtenerEncuestaPorId($id_encuesta, $id_usuario);
$preguntas = $pollModel->obtenerPreguntas($id_encuesta);
$respuestas = $pollModel->obtenerRespuestas($id_encuesta);

// Agrupar respuestas por pregunta
$respuestasPorPregunta = [];
foreach ($respuestas as $respuesta) {
    $pregunta = $respuesta['texto_pregunta'];
    if (!isset($respuestasPorPregunta[$pregunta])) {
        $respuestasPorPregunta[$pregunta] = [];
    }
    $respuestasPorPregunta[$pregunta][] = $respuesta['texto_respuesta'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #ADCAD6;
            --secondary-color: #8CAEB8;
            --accent-color: #E6F0F3;
        }

        body {
            background-color: var(--accent-color);
            font-family: 'Arial', sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            background-color: white;
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--secondary-color);
            transform: scale(1.05);
        }

        .accordion-button {
            background-color: var(--accent-color);
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--primary-color);
            color: white;
        }

        #graficoPregunta {
            max-height: 300px;
            max-width: 300px;
        }
    </style>
</head>


<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><?php echo htmlspecialchars($encuesta['titulo']); ?></h1>
                <div>
                    <button class="btn btn-custom me-2" onclick="goBack()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="btn btn-custom me-2" onclick="exportToPDF()">
                        <i class="fa-solid fa-file-pdf"></i>
                    </button>
                    <button class="btn btn-custom me-2" onclick="window.location.href='exportPoll.php?id=<?php echo $id_encuesta; ?>'">
                        <i class="fas fa-file-export"></i>
                    </button>
                    <button class="btn btn-custom me-2" onclick="window.location.href='updatePoll.php?id=<?php echo $id_encuesta; ?>'">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php if (!empty($graficasMultiples)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Opción múltiple</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($graficasMultiples as $grafica): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-header" style="background-color: var(--primary-color); color: white;">
                                                <h5 class="card-title mb-0"><?php echo htmlspecialchars($grafica['pregunta']); ?></h5>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="grafica-<?php echo md5($grafica['pregunta']); ?>"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Resumen General</h4>
                    </div>
                    <div class="card-body text-center">
                        <canvas id="graficoPregunta" class="mx-auto"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Detalle de Respuestas</h4>
                    </div>
                    <div class="card-body">
                        <div class="accordion" id="accordionPreguntas">
                            <?php
                            $counter = 1;
                            foreach ($respuestasPorPregunta as $pregunta => $respuestas):
                                // Contar ocurrencias de cada respuesta
                                $conteoRespuestas = array_count_values($respuestas);
                            ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?php echo $counter; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $counter; ?>" aria-expanded="false" aria-controls="collapse<?php echo $counter; ?>">
                                            <?php echo htmlspecialchars($pregunta); ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?php echo $counter; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $counter; ?>">
                                        <div class="accordion-body">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Respuesta</th>
                                                        <th>Votos</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($conteoRespuestas as $respuesta => $votos): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($respuesta); ?></td>
                                                            <td><?php echo $votos; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                $counter++;
                            endforeach;
                            ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preparar datos globales para el gráfico
        const respuestasTotales = <?php
                                    $totalRespuestas = [];
                                    foreach ($respuestasPorPregunta as $pregunta => $respuestas) {
                                        $totalRespuestas[$pregunta] = count($respuestas);
                                    }
                                    echo json_encode($totalRespuestas);
                                    ?>;

        const ctx = document.getElementById('graficoPregunta').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(respuestasTotales),
                datasets: [{
                    data: Object.values(respuestasTotales),
                    backgroundColor: [
                        '#ADCAD6', '#8CAEB8', '#E6F0F3',
                        '#6D9BAC', '#C1D6DC'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                const total = Object.values(respuestasTotales).reduce((a, b) => a + b, 0);
                                const value = tooltipItem.raw;
                                return `${value} votos (${((value / total) * 100).toFixed(1)}%)`;
                            }
                        }
                    }
                }
            }
        });

        function goBack() {
            window.location.href = '../feed/feedUser.php';
        }

        function exportResults() {
            alert('Funcionalidad de exportación próximamente');
        }

        function editPoll() {
            alert('Funcionalidad de edición próximamente');
        }

        // Renderizar gráficas de preguntas múltiples
        <?php foreach ($graficasMultiples as $grafica): ?>
            new Chart(document.getElementById('grafica-<?php echo md5($grafica['pregunta']); ?>'), {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode($grafica['labels']); ?>,
                    datasets: [{
                        data: <?php echo json_encode($grafica['votos']); ?>,
                        backgroundColor: <?php echo json_encode($grafica['colores']); ?>
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `${context.label}: ${value} votos (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        <?php endforeach; ?>
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>

</html>