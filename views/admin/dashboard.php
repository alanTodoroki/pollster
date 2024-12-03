<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/voteModel.php';
require_once '../../models/pollModel.php';
require_once '../../models/userModel.php';

$database = new Database();
$db = $database->getConnection();

$voteModel = new VotacionModel($db);
$pollModel = new EncuestaModel($db);
$userModel = new UsuarioModel($db);

// Obtener datos para el dashboard
$usuarios = $userModel->obtenerTodosLosUsuarios();
$totalUsuarios = count($usuarios);
$totalEncuestasActivas = count($pollModel->obtenerEncuestasActivas(true));
$totalVotacionesActivas = count($voteModel->obtenerVotacionesActivas(true));
$totalNotificaciones = obtenerTotalNotificaciones($db);

// Obtener encuestas y votaciones
$encuestas = $pollModel->obtenerTodasLasEncuestas();
$votaciones = $voteModel->obtenerTodasLasVotaciones();

// Función para obtener total de notificaciones (mantener como estaba)
function obtenerTotalNotificaciones($db)
{
    $query = "SELECT COUNT(*) as total FROM notificaciones";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    return $row['total'];
}

// Procesar eliminación de usuario si se envía por GET
if (isset($_GET['eliminar_usuario']) && isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    if ($userModel->eliminarUsuario($id_usuario)) {
        $_SESSION['mensaje'] = "Usuario eliminado exitosamente";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "No se pudo eliminar el usuario";
    }
}
?>

<!-- El resto del código HTML permanece igual -->

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Administrador</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .card-dashboard {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 10px;
            overflow: hidden;
        }

        .card-dashboard:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-dashboard .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-dashboard .card-icon {
            font-size: 3rem;
            opacity: 0.7;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
        }

        #statsChart {
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-chart-line me-2"></i>Dashboard Administrador
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-users me-2"></i>Usuarios
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="usuariosDropdown">
                            <li><a class="dropdown-item" href="manageUsers.php">
                                    <i class="fas fa-list me-2"></i>Gestionar Usuarios
                                </a></li>
                            <li><a class="dropdown-item" href="createUser.php">
                                    <i class="fas fa-user-plus me-2"></i>Crear Usuario
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="encuestasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-poll me-2"></i>Encuestas
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="encuestasDropdown">
                            <li><a class="dropdown-item" href="managePoll.php">
                                    <i class="fas fa-list me-2"></i>Gestionar Encuestas
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="votacionesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-vote-yea me-2"></i>Votaciones
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="votacionesDropdown">
                            <li><a class="dropdown-item" href="manageVote.php">
                                    <i class="fas fa-list me-2"></i>Gestionar Votaciones
                                </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../users/logOut.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card card-dashboard text-primary">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Usuarios Totales</h5>
                            <p class="card-text display-6"><?php echo $totalUsuarios; ?></p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dashboard text-success">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Encuestas Activas</h5>
                            <p class="card-text display-6"><?php echo $totalEncuestasActivas; ?></p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-poll"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dashboard text-warning">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Votaciones Activas</h5>
                            <p class="card-text display-6"><?php echo $totalVotacionesActivas; ?></p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-vote-yea"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-dashboard text-danger">
                    <div class="card-body">
                        <div>
                            <h5 class="card-title">Notificaciones</h5>
                            <p class="card-text display-6"><?php echo $totalNotificaciones; ?></p>
                        </div>
                        <div class="card-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#usuarios" data-bs-toggle="tab">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#encuestas" data-bs-toggle="tab">Encuestas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#votaciones" data-bs-toggle="tab">Votaciones</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body tab-content">
                        <div class="tab-pane active" id="usuarios">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Correo Electrónico</th>
                                            <th>Rol</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($usuarios as $usuario): ?>
                                            <tr>
                                                <td><?php echo $usuario['id_usuario']; ?></td>
                                                <td><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></td>
                                                <td><?php echo $usuario['correo_electronico']; ?></td>
                                                <td><?php echo $usuario['rol']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="encuestas">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Descripción</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($encuestas as $encuesta): ?>
                                            <tr>
                                                <td><?php echo $encuesta['id_encuesta']; ?></td>
                                                <td><?php echo $encuesta['titulo']; ?></td>
                                                <td><?php echo $encuesta['descripcion']; ?></td>
                                                <td><?php echo $encuesta['fecha_inicio']; ?></td>
                                                <td><?php echo $encuesta['fecha_fin']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="votaciones">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Descripción</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Fin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($votaciones as $votacion): ?>
                                            <tr>
                                                <td><?php echo $votacion['id_votacion']; ?></td>
                                                <td><?php echo $votacion['titulo']; ?></td>
                                                <td><?php echo $votacion['descripcion']; ?></td>
                                                <td><?php echo $votacion['fecha_inicio']; ?></td>
                                                <td><?php echo $votacion['fecha_fin']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Estadísticas
                    </div>
                    <div class="card-body">
                        <canvas id="statsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('statsChart').getContext('2d');
        const statsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Usuarios', 'Encuestas', 'Votaciones', 'Notificaciones'],
                datasets: [{
                    data: [<?php echo $totalUsuarios; ?>, <?php echo $totalEncuestasActivas; ?>, <?php echo $totalVotacionesActivas; ?>, <?php echo $totalNotificaciones; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(54, 162, 235, 0.8)'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Datos'
                    }
                }
            }
        });
    </script>
</body>

</html>