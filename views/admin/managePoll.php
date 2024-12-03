<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();
$pollModel = new EncuestaModel($db);

// Obtener todas las encuestas
$encuestas = $pollModel->obtenerTodasLasEncuestas();

// Procesar eliminación de encuesta
if (isset($_GET['eliminar']) && $_GET['eliminar'] == 'true' && isset($_GET['id'])) {
    $id_encuesta = $_GET['id'];
    if ($pollModel->eliminarEncuesta($id_encuesta)) {
        header("Location: gestionEncuestas.php?mensaje=Encuesta eliminada exitosamente");
        exit();
    } else {
        $error = "No se pudo eliminar la encuesta";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Encuestas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #FFFFFF;
            color: #586F6B;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        h1 {
            text-align: center;
            color: #586F6B;
            font-weight: bold;
        }

        .card {
            border: none;
            background: #F8F9FA;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #586F6B;
            color: #FFFFFF;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header a {
            background-color: #586F6B;
            color: #FFFFFF;
        }

        .card-header a:hover {
            background-color: #404F4D;
        }

        .table thead {
            background-color: #586F6B;
            color: #FFFFFF;
        }

        .table tbody tr:hover {
            background-color: rgba(88, 111, 107, 0.1);
        }

        .btn-eliminar {
            display: block;
            margin: 0 auto;
            background-color: #FFC9C9;
            /* Rojo claro */
            color: #721C24;
            /* Texto rojo oscuro */
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-align: center;
        }

        .btn-eliminar:hover {
            background-color: #FF9999;
            /* Rojo más oscuro */
            color: #721C24;
            /* Texto rojo oscuro */
        }

        .table td {
            text-align: center;
        }

        .estado-activo {
            background-color: #bdd2c7;
            color: #7FC29B;
            /* Verde */
        }

        .estado-inactivo {
            background-color: #956c69;
            color: #721C24;
            /* Rojo oscuro */
        }

        .dataTables_filter input,
        .dataTables_length select {
            border: 1px solid #586F6B;
            border-radius: 4px;
        }

        .dataTables_filter input:focus,
        .dataTables_length select:focus {
            outline: none;
            border-color: #404F4D;
            box-shadow: 0 0 4px rgba(64, 79, 77, 0.8);
        }

        .alert {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="btn-container">
            <a href="../admin/dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
            <h1 class="mb-0">Gestión de Encuestas</h1>
        </div>

        <?php if (isset($_GET['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_GET['mensaje']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <span>Lista de Encuestas</span>
            </div>
            <div class="card-body">
                <table id="encuestasTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Fecha Inicio</th>
                            <th>Fecha Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($encuestas as $encuesta): ?>
                            <tr class="<?php echo $encuesta['estado'] == 'activa' ? 'estado-activo' : 'estado-inactivo'; ?>">
                                <td><?php echo $encuesta['id_encuesta']; ?></td>
                                <td><?php echo htmlspecialchars($encuesta['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($encuesta['descripcion']); ?></td>
                                <td><?php echo $encuesta['fecha_inicio']; ?></td>
                                <td><?php echo $encuesta['fecha_fin']; ?></td>
                                <td><?php echo htmlspecialchars($encuesta['estado']); ?></td>
                                <td>
                                    <a href="?eliminar=true&id=<?php echo $encuesta['id_encuesta']; ?>"
                                        class="btn btn-eliminar btn-sm"
                                        onclick="return confirm('¿Estás seguro de eliminar esta encuesta?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#encuestasTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json',
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ entradas",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                    infoEmpty: "No hay entradas disponibles",
                    infoFiltered: "(filtrado de _MAX_ entradas en total)",
                    paginate: {
                        previous: "Anterior",
                        next: "Siguiente"
                    }
                }
            });
        });
    </script>
</body>

</html>