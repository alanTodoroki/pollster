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
                <div>
                    <button class="btn btn-custom me-2" onclick="goBack()">
                        <i class="fas fa-arrow-left me-2"></i>Regresar
                    </button>
                    <button class="btn btn-custom me-2" onclick="window.location.href='exportVote.php?id=<?php echo $id_votacion; ?>'">
                        <i class="fas fa-file-export me-2"></i>Exportar
                    </button>

                    <button class="btn btn-custom" onclick="editVotation()">
                        <i class="fas fa-edit me-2"></i>Editar
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


        function exportResults() {
            alert('Funcionalidad de exportación próximamente');
            // Aquí puedes agregar la lógica real de exportación
        }

        function editVotation() {
            alert('Funcionalidad de edición próximamente');
            // Aquí puedes agregar la lógica real de edición
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</body>

</html>