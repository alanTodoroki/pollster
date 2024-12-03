<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/voteModel.php';

// Verificar permisos de administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página";
    header('Location: ../users/login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$pollModel = new VotacionModel($db);

// Verificar si se ha proporcionado un ID de votación
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Manejar eliminación de votación
if (isset($_GET['action']) && $_GET['action'] === 'delete' && $id) {
    if ($pollModel->eliminarVotacion($id)) {
        $_SESSION['mensaje'] = "Votación eliminada exitosamente";
        header('Location: managePoll.php');
        exit();
    } else {
        $_SESSION['error'] = "No se pudo eliminar la votación";
    }
}

// Obtener lista de votaciones
$votaciones = $pollModel->obtenerTodasLasVotaciones();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Votaciones</title>
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
            color: #721C24;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-align: center;
        }

        .btn-eliminar:hover {
            background-color: #FF9999;
            color: #721C24;
        }

        .table td {
            text-align: center;
        }

        .status-activo {
            color: #bdd2c7;
            font-weight: bold;
        }

        .status-inactivo {
            color: #956c69;
            font-weight: bold;
        }


        .alert {
            font-weight: bold;
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
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="btn-container">
            <a href="../admin/dashboard.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Regresar
            </a>
            <h1 class="mb-0">Gestión de Votaciones</h1>
        </div>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <span>Lista de Votaciones</span>
            </div>
            <div class="card-body">
                <table id="votacionesTable" class="table table-striped table-bordered">
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
                        <?php foreach ($votaciones as $votacion): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($votacion['id_votacion']); ?></td>
                                <td><?php echo htmlspecialchars($votacion['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($votacion['descripcion']); ?></td>
                                <td><?php echo htmlspecialchars($votacion['fecha_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($votacion['fecha_fin']); ?></td>
                                <td>
                                    <?php
                                    $estado = $votacion['estado'];
                                    $claseEstado = ($estado == 'activo') ? 'status-activo' : 'status-inactivo';
                                    echo "<span class='$claseEstado'>" . htmlspecialchars(ucfirst($estado)) . "</span>";
                                    ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="pollDetails.php?id=<?php echo $votacion['id_votacion']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Detalles
                                        </a>
                                        <a href="?action=delete&id=<?php echo $votacion['id_votacion']; ?>"
                                            class="btn btn-sm btn-danger btn-eliminar"
                                            data-titulo="<?php echo htmlspecialchars($votacion['titulo']); ?>">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </a>
                                    </div>
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
            $('#votacionesTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
                }
            });

            // Confirmación de eliminación
            $('.btn-eliminar').on('click', function(e) {
                e.preventDefault();
                const titulo = $(this).data('titulo');
                const href = $(this).attr('href');

                if (confirm(`¿Estás seguro de eliminar la votación "${titulo}"?`)) {
                    window.location.href = href;
                }
            });

            // Cierre automático de alertas
            $(".alert").delay(4000).slideUp(200, function() {
                $(this).alert('close');
            });
        });
    </script>
</body>

</html>