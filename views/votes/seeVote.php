<?php
session_start();
include_once '../../config/db.php';
include_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();
$votacionModel = new VotacionModel($db);

$id_votacion = $_GET['id'] ?? null; // Verifica que el ID de la votación se pasa correctamente
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_votacion || !$id_usuario) {
    die('ID de votación o usuario no especificado.');
}
// Obtener la información de la votación 
$votacion = $votacionModel->obtenerVotacionPorId($id_votacion, $id_usuario);
$opciones = $votacionModel->obtenerOpcionesPorVotacion($id_votacion);
$resultados = $votacionModel->obtenerResultadosVotacion($id_votacion);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de la Votación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Cargar html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- Cargar jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <style>
        :root {
            --primary-color: #C9DCB3;
            --secondary-color: #A3C680;
            --accent-color: #E6F0D6;
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><?php echo htmlspecialchars($votacion['titulo']); ?></h1>
                <div class="no-print">
                    <button class="btn btn-custom me-2" onclick="goBack()">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <button class="btn btn-custom me-2" onclick="exportToPDF()">
                        <i class="fa-solid fa-file-pdf"></i>
                    </button>
                    <button class="btn btn-custom me-2" onclick="window.location.href='exportVote.php?id=<?php echo $id_votacion; ?>'">
                        <i class="fas fa-file-export"></i>
                    </button>
                    <button class="btn btn-custom me-2" onclick="window.location.href='updateVote.php?id=<?php echo $id_votacion; ?>'">
                        <i class="fas fa-edit"></i>
                    </button>

                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h4 class="mb-0">Descripción de la Votación</h4>
            </div>
            <div class="card-body">
                <p><?php echo htmlspecialchars($votacion['descripcion']); ?></p>
                <p class="text-muted">
                    <strong>Período:</strong>
                    <?php echo $votacion['fecha_inicio']; ?> -
                    <?php echo $votacion['fecha_fin']; ?>
                </p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">Gráfico de Resultados</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoVotacion"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">Detalle de Resultados</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Opción</th>
                                        <th>Votos</th>
                                        <th>Porcentaje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_votos = array_sum(array_column($resultados, 'votos'));
                                    foreach ($resultados as $resultado):
                                        $porcentaje = $total_votos > 0 ?
                                            round(($resultado['votos'] / $total_votos) * 100, 1) : 0;
                                    ?>
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
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preparar datos para Chart.js 
        const etiquetas = <?php echo json_encode(array_column($resultados, 'texto_opcion')); ?>;
        const datos = <?php echo json_encode(array_column($resultados, 'votos')); ?>;

        const ctx = document.getElementById('graficoVotacion').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: etiquetas,
                datasets: [{
                    data: datos,
                    backgroundColor: [
                        '#C9DCB3', // Color principal
                        '#A3C680', // Variación más oscura
                        '#E6F0D6', // Variación más clara
                        '#8AB369', // Otra variación
                        '#D1E7B6' // Última variación
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => `${tooltipItem.raw} votos (${((tooltipItem.raw / datos.reduce((a,b) => a+b, 0)) * 100).toFixed(1)}%)`
                        }
                    }
                }
            }
        });

        function goBack() {
            window.location.href = '../feed/feedUser.php';
        }

        function editVotation() {
            alert('Funcionalidad de edición próximamente');
        }

        function exportToPDF() {
            // Selecciona el contenedor principal del informe
            const reportElement = document.querySelector('.container');

            // Usa html2canvas para capturar el contenido como imagen 
            html2canvas(reportElement, {
                scale: 2
            }).then(canvas => {
                const pdf = new jspdf.jsPDF('p', 'mm', 'a4'); // Orientación vertical, unidades en mm, tamaño A4
                const imgData = canvas.toDataURL('image/png'); // Convierte el canvas a imagen
                const pdfWidth = pdf.internal.pageSize.getWidth(); // Ancho del PDF
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width; // Ajusta el alto proporcional

                // Agrega la imagen al PDF
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

                // Guarda el archivo PDF con un nombre personalizado
                pdf.save('informe.pdf');
            });
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>

</html>