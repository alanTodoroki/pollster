<?php
session_start(); // Asegúrate de que la sesión está iniciada
// Cargar datos de la votación
require_once '../../config/db.php';
require_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();
$model = new VotacionModel($db);

$id_votacion = $_GET['id']; // ID de la votación pasada como parámetro
$id_usuario = $_SESSION['id_usuario'];

if (!$id_votacion || !$id_usuario) {
    die('ID de votación o usuario no especificado.');
}

$votacion = $model->obtenerVotacionPorId($id_votacion);
if (!$votacion) {
    die('La votación no existe.');
}
$opciones = $model->obtenerOpcionesPorVotacion($id_votacion);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participar en Votación</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        h1 {
            color: #41BBD9;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .description-box {
            background-color: #f9f9f9;
            border-left: 6px solid #41BBD9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 1.1rem;
            color: #333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            border: none;
            padding: 10px 20px;
            background-color: #f9f9f9;
            transition: transform 0.2s, box-shadow 0.2s, background-color 0.2s;
            cursor: pointer;
            text-align: center;
            font-weight: bold;
            color: #333;
            border-radius: 8px;
        }

        .list-group-item:hover {
            background-color: #e9f7fb;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .list-group-item.active {
            background-color: #d0f0f6 !important;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            color: #005b6e;
        }

        .btn-primary {
            background-color: #41BBD9;
            border: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary:hover {
            background-color: #35A3BE;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-cancel {
            background-color: #006E90;
            border: none;
            color: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-cancel:hover {
            background-color: #005a7d;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1><?php echo $votacion['titulo']; ?></h1>
        <div class="description-box">
            <?php echo $votacion['descripcion']; ?>
        </div>
        <form id="voteForm" method="POST" action="../../controllers/vote/voteController.php">
            <input type="hidden" name="action" value="participarVotacion">
            <input type="hidden" name="id_votacion" value="<?php echo $id_votacion; ?>">
            <div class="list-group">
                <?php foreach ($opciones as $opcion): ?>
                    <div class="list-group-item" data-option-id="<?php echo $opcion['id_opcion']; ?>">
                        <?php echo $opcion['texto_opcion']; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Hidden input for selected option -->
            <input type="hidden" id="respuesta" name="respuesta" value="">

            <button type="submit" class="btn btn-primary mt-3">Enviar Votación</button>
        </form>

        <!-- Botón Cancelar -->
        <a href="../feed/feedUser.php" class="btn btn-cancel mt-3">Cancelar</a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const items = document.querySelectorAll('.list-group-item');
            let selectedOption = null;

            items.forEach(item => {
                item.addEventListener('click', (event) => {
                    // Remove active class from all items
                    items.forEach(el => el.classList.remove('active'));

                    // Add active class to clicked item
                    event.currentTarget.classList.add('active');

                    // Update the hidden input value with the selected option
                    selectedOption = event.currentTarget.dataset.optionId;
                    document.getElementById('respuesta').value = selectedOption;
                });
            });
        });

        document.getElementById('voteForm').addEventListener('submit', function(event) {
            const selectedOption = document.getElementById('respuesta').value;

            if (!selectedOption) {
                event.preventDefault();
                alert('Por favor, selecciona una opción antes de enviar tu voto.');
            }
        });
    </script>
</body>

</html>