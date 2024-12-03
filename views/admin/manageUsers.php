<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/userModel.php';

// Verificar permisos de administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página";
    header('Location: ../users/login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$userModel = new UsuarioModel($db);

// Verificar si se ha proporcionado un ID de usuario
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

// Manejar eliminación de usuario
if (isset($_GET['action']) && $_GET['action'] === 'delete' && $id) {
    if ($userModel->eliminarUsuario($id)) {
        $_SESSION['mensaje'] = "Usuario eliminado exitosamente";
        header('Location: manageUsers.php');
        exit();
    } else {
        $_SESSION['error'] = "No se pudo eliminar el usuario";
    }
}

// Obtener lista de usuarios
$usuarios = $userModel->obtenerTodosLosUsuarios();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
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

        .btn {
            background-color: #586F6B;
            color: #FFFFFF;
        }

        .btn:hover {
            background-color: #404F4D;
            color: #FFFFFF;
        }

        .btn-eliminar {
            display: block;
            margin: 0 auto;
            /* Centrar horizontalmente */
            background-color: #FFC9C9;
            color: #721C24;
        }

        .btn-eliminar:hover {
            background-color: #FF9999;
            color: #721C24;
        }

        .table td {
            text-align: center;
            /* Centrar contenido de las celdas */
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
            <h1 class="mb-0">Gestión de Usuarios</h1>
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
                <span>Lista de Usuarios</span>
                <a href="createUser.php" class="btn">
                    <i class="fas fa-plus"></i> Crear Nuevo Usuario
                </a>
            </div>
            <div class="card-body">
                <table id="usuariosTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo Electrónico</th>
                            <th>Nombre de Usuario</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['apellido']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['correo_electronico']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                                <td>
                                    <a href="?action=delete&id=<?php echo $usuario['id_usuario']; ?>"
                                        class="btn btn-sm btn-eliminar"
                                        data-nombre="<?php echo htmlspecialchars($usuario['nombre']); ?>">
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
            $('#usuariosTable').DataTable({
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

            // Confirmación de eliminación
            $('.btn-eliminar').on('click', function(e) {
                e.preventDefault();
                const nombre = $(this).data('nombre');
                const href = $(this).attr('href');

                if (confirm(`¿Estás seguro de eliminar al usuario ${nombre}?`)) {
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