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

$encuesta = $pollModel->obtenerEncuestaPorId($id_encuesta, $id_usuario);
$preguntas = $pollModel->obtenerPreguntas($id_encuesta);
$respuestas = $pollModel->obtenerRespuestas($id_encuesta);

// Agrupar respuestas por pregunta
$respuestasPorPregunta = [];
$totalRespuestas = [];
foreach ($respuestas as $respuesta) {
    $pregunta = $respuesta['texto_pregunta'];
    if (!isset($respuestasPorPregunta[$pregunta])) {
        $respuestasPorPregunta[$pregunta] = [];
        $totalRespuestas[$pregunta] = 0;
    }
    $respuestasPorPregunta[$pregunta][] = $respuesta['texto_respuesta'];
    $totalRespuestas[$pregunta]++;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .btn-download {
            background-color: #ADCAD6;
            color: #fff;
            border: none;
        }

        .btn-download:hover {
            background-color: #8FB5BC;
        }

        .chart-container-detail {
            max-width: 300px;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <!-- Botón de descarga en la parte superior -->
        <div class="d-flex justify-content-end mb-3">
            <button class="btn btn-download" id="downloadReport">Descargar Informe en PDF</button>
        </div>

        <div id="report">
            <div class="text-center mb-4">
                <h1>Informe de Encuesta</h1>
                <p><strong>Título:</strong> <?php echo htmlspecialchars($encuesta['titulo']); ?></p>
                <p><strong>Período:</strong> <?php echo $encuesta['fecha_inicio']; ?> - <?php echo $encuesta['fecha_fin']; ?></p>
            </div>

            <div class="mb-4" style="text-align: center;">
                <h2>Gráfico General</h2>
                <div style="max-width: 400px; margin: auto;">
                    <canvas id="chartGeneral"></canvas>
                </div>
            </div>

            <div>
                <h2>Detalle de Respuestas</h2>
                <?php foreach ($respuestasPorPregunta as $pregunta => $respuestas):
                    $conteoRespuestas = array_count_values($respuestas);
                ?>
                    <div class="mb-4">
                        <h4><?php echo htmlspecialchars($pregunta); ?></h4>
                        <div class="chart-container-detail">
                            <canvas id="chart-<?php echo md5($pregunta); ?>"></canvas>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Respuesta</th>
                                    <th>Votos</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($conteoRespuestas as $respuesta => $votos):
                                    $porcentaje = $totalRespuestas[$pregunta] > 0 ? round(($votos / $totalRespuestas[$pregunta]) * 100, 1) : 0;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($respuesta); ?></td>
                                        <td><?php echo $votos; ?></td>
                                        <td><?php echo $porcentaje; ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Generar gráficos
        const generalLabels = <?php echo json_encode(array_keys($totalRespuestas)); ?>;
        const generalData = <?php echo json_encode(array_values($totalRespuestas)); ?>;

        new Chart(document.getElementById('chartGeneral'), {
            type: 'doughnut',
            data: {
                labels: generalLabels,
                datasets: [{
                    data: generalData,
                    backgroundColor: ['#FFC107', '#007BFF', '#28A745', '#DC3545', '#6C757D'],
                }]
            }
        });

        <?php foreach ($respuestasPorPregunta as $pregunta => $respuestas):
            $conteoRespuestas = array_count_values($respuestas);
        ?>
            new Chart(document.getElementById('chart-<?php echo md5($pregunta); ?>'), {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_keys($conteoRespuestas)); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($conteoRespuestas)); ?>,
                        backgroundColor: ['#FFC107', '#007BFF', '#28A745', '#DC3545', '#6C757D'],
                    }]
                }
            });
        <?php endforeach; ?>

        // Descargar informe como PDF
        // Descargar informe como PDF
        document.getElementById('downloadReport').addEventListener('click', () => {
            const reportElement = document.getElementById('report');
            const pdf = new jspdf.jsPDF('p', 'mm', 'a4');

            // Obtener altura de cada sección y generar imágenes
            html2canvas(reportElement, {
                scale: 2,
                scrollY: 0
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                // Si el contenido supera una página, agregar más páginas
                if (pdfHeight > pdf.internal.pageSize.getHeight()) {
                    let heightLeft = pdfHeight - pdf.internal.pageSize.getHeight();
                    let position = 0;

                    while (heightLeft > 0) {
                        position -= pdf.internal.pageSize.getHeight();
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, pdfWidth, pdfHeight);
                        heightLeft -= pdf.internal.pageSize.getHeight();
                    }
                }

                pdf.save('informe_encuesta.pdf');
            });
        });
    </script>
</body>

</html>