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

$votacion = $votacionModel->obtenerVotacionPorId($id_votacion, $id_usuario);
$resultados = $votacionModel->obtenerResultadosVotacion($id_votacion);

$total_votos = array_sum(array_column($resultados, 'votos'));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de la Votación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Tus estilos aquí */
    </style>
</head>

<body>
    <div class="container mt-5" id="report">
        <div class="text-center mb-4">
            <h1>Informe de Votación</h1>
            <p><strong>Título:</strong> <?php echo htmlspecialchars($votacion['titulo']); ?></p>
            <p><strong>Período:</strong> <?php echo $votacion['fecha_inicio']; ?> - <?php echo $votacion['fecha_fin']; ?></p>
        </div>
        <div class="mb-4">
            <h2>Gráfico de Resultados</h2>
            <canvas id="chartResultados" style="max-width: 600px; margin: auto;"></canvas>
        </div>
        <div>
            <h2>Detalle de Resultados</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Opción</th>
                        <th>Votos</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $resultado):
                        $porcentaje = $total_votos > 0 ? round(($resultado['votos'] / $total_votos) * 100, 1) : 0; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resultado['texto_opcion']); ?></td>
                            <td><?php echo $resultado['votos']; ?></td>
                            <td><?php echo $porcentaje; ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <button class="btn btn-primary mt-4" id="downloadReport">Descargar Informe en PDF</button>
    <script>
        // Configuración del gráfico
        const etiquetas = <?php echo json_encode(array_column($resultados, 'texto_opcion')); ?>;
        const datos = <?php echo json_encode(array_column($resultados, 'votos')); ?>;
        const ctx = document.getElementById('chartResultados').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: etiquetas,
                datasets: [{
                    data: datos,
                    backgroundColor: ['#FFC107', '#007BFF', '#28A745', '#DC3545', '#6C757D'],
                }]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => `${tooltipItem.raw} votos (${((tooltipItem.raw / datos.reduce((a, b) => a + b, 0)) * 100).toFixed(1)}%)`
                        }
                    }
                }
            }
        });

        // Exportar a PDF
        document.getElementById('downloadReport').addEventListener('click', () => {
            const reportElement = document.getElementById('report');
            html2canvas(reportElement, {
                scale: 2
            }).then(canvas => {
                const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                const imgData = canvas.toDataURL('image/png');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                pdf.save('informe_votacion.pdf');
            });
        });
    </script>
</body>

</html>