<?php
session_start(); // Asegúrate de que la sesión está iniciada
// Cargar datos de la encuesta
require_once '../../config/db.php';
require_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();
$pollModel = new EncuestaModel($db);

$id_encuesta = $_GET['id']; // ID de la encuesta pasada como parámetro
$id_usuario = $_SESSION['id_usuario'];

if (!$id_encuesta || !$id_usuario) {
    die('ID de votación o usuario no especificado.');
}

$encuesta = $pollModel->obtenerEncuestaPorId($id_encuesta, $id_usuario);
$preguntas = $pollModel->obtenerPreguntasPorEncuesta($id_encuesta);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participar en Encuesta</title>
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
            background-color: #005a74;
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .open-question {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            width: 100%;
            font-size: 1rem;
            box-sizing: border-box;
            transition: border 0.3s;
        }

        .open-question:focus {
            border: 1px solid #41BBD9;
            outline: none;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1><?php echo $encuesta['titulo']; ?></h1>
        <div class="description-box">
            <?php echo $encuesta['descripcion']; ?>
        </div>
        <form id="pollForm" method="POST" action="../../controllers/poll/pollController.php">
            <input type="hidden" name="action" value="responderEncuesta">
            <input type="hidden" name="id_encuesta" value="<?php echo $id_encuesta; ?>">
            <?php foreach ($preguntas as $pregunta): ?>
                <div class="mb-3">
                    <input type="hidden" name="id_encuesta" value="<?php echo $id_encuesta; ?>">
                    <label class="fw-bold"><?php echo $pregunta['texto_pregunta']; ?></label>
                    <div class="list-group">
                        <?php
                        $opciones = $pollModel->obtenerOpcionesPorPregunta($pregunta['id_pregunta']);
                        if ($pregunta['tipo_pregunta'] === 'multiple_choice') {
                            foreach ($opciones as $opcion): ?>
                                <div class="list-group-item" data-option-id="<?php echo $opcion['id_opcion']; ?>"
                                    data-pregunta-id="<?php echo $pregunta['id_pregunta']; ?>">
                                    <?php echo $opcion['texto_opcion']; ?>
                                </div>
                            <?php endforeach;
                        } else { ?>
                            <!-- Para preguntas abiertas -->
                            <textarea name="respuestas[<?php echo $pregunta['id_pregunta']; ?>]" class="open-question" placeholder="Escribe tu respuesta"></textarea>
                        <?php } ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="btn btn-primary">Finalizar Encuesta</button>
        </form>
        <a href="../feed/feedUser.php" class="btn btn-cancel mt-3">Cancelar</a>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const questionGroups = document.querySelectorAll('.list-group');

            questionGroups.forEach(group => {
                const items = group.querySelectorAll('.list-group-item');
                const idPregunta = group.closest('.mb-3').querySelector('input[name="id_encuesta"]').value;

                items.forEach(item => {
                    item.addEventListener('click', (event) => {
                        // Toggle active class for this specific item
                        event.currentTarget.classList.toggle('active');

                        // Collect all selected options for this question group
                        const selectedItems = group.querySelectorAll('.list-group-item.active');
                        const selectedOptionIds = Array.from(selectedItems).map(item => item.dataset.optionId);

                        // Crear o actualizar el input oculto de forma directa con el nombre esperado por PHP
                        let hiddenInput = group.querySelector(`input[name="respuestas[${idPregunta}][]"]`);
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = `respuestas[${idPregunta}][]`;
                            group.appendChild(hiddenInput);
                        }
                        hiddenInput.value = selectedOptionIds.join(',');
                    });
                });
            });
        });

        document.getElementById('pollForm').addEventListener('submit', function(event) {
            let allAnswered = true;

            document.querySelectorAll('.mb-3').forEach(question => {
                if (question.querySelector('.open-question')) {
                    // Para preguntas abiertas
                    const textarea = question.querySelector('.open-question');
                    if (textarea.value.trim() === '') {
                        allAnswered = false;
                        textarea.style.border = '2px solid red';
                    } else {
                        textarea.style.border = ''; // Reset
                    }
                } else {
                    // Para preguntas de selección
                    const activeOption = question.querySelector('.list-group-item.active');
                    if (!activeOption) {
                        allAnswered = false;
                        question.querySelector('.list-group').style.border = '2px solid red';
                    } else {
                        question.querySelector('.list-group').style.border = ''; // Reset
                    }
                }
            });

            if (!allAnswered) {
                event.preventDefault();
                alert('Por favor, responde todas las preguntas antes de enviar.');
            }
        });
    </script>
    </script>
</body>

</html>