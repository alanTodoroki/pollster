<?php
session_start();
include_once '../../config/db.php';
include_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();
$pollModel = new EncuestaModel($db);

$id_encuesta = $_GET['id'] ?? null; // ID de encuesta recibido por GET
if (!$id_encuesta) {
    die('ID de encuesta no especificado.');
}

// Obtener preguntas asociadas a la encuesta
$preguntas = $pollModel->obtenerPreguntas($id_encuesta);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['preguntas'] as $id_pregunta => $pregunta) {
        // Validar que la clave texto_pregunta esté definida
        if (!isset($pregunta['texto_pregunta'])) {
            echo "Error: La pregunta con ID $id_pregunta no tiene texto definido.<br>";
            continue;
        }

        // Actualizar texto de la pregunta
        $pollModel->actualizarPregunta($id_pregunta, $pregunta['texto_pregunta']);

        // Validar si hay opciones asociadas
        if (isset($pregunta['opciones']) && is_array($pregunta['opciones'])) {
            foreach ($pregunta['opciones'] as $id_opcion => $texto_opcion) {
                $pollModel->actualizarOpcion($id_opcion, $texto_opcion);
            }
        }
    }
    header("Location: ../../views/polls/seePoll.php?id=$id_encuesta");

    exit;
}



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Encuesta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #5BC0EB;
            --secondary-color: #3693B5;
            --accent-color: #E1F4FB;
        }

        body {
            background-color: #ffffff;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: var(--primary-color);
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .btn-custom {
            background-color: var(--primary-color);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background-color: var(--secondary-color);
            transform: scale(1.05);
        }

        .form-control {
            border-radius: 8px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(91, 192, 235, 0.5);
            /* Sombra de color azul claro */
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center">Actualizar Encuesta</h1>

        <a href="seePoll.php?id=<?php echo $id_encuesta; ?>" class="btn btn-custom mb-3">Regresar</a>

        <form method="POST">
            <?php foreach ($preguntas as $pregunta): ?>
                <div class="mb-3">
                    <label class="form-label">Pregunta:</label>
                    <input type="text"
                        name="preguntas[<?php echo $pregunta['id_pregunta']; ?>][texto_pregunta]"
                        class="form-control"
                        value="<?php echo htmlspecialchars($pregunta['texto_pregunta']); ?>">

                    <?php if ($pregunta['tipo_pregunta'] === 'multiple_choice'): ?>
                        <div>
                            <h5>Opciones:</h5>
                            <?php
                            $opciones = $pollModel->obtenerOpciones($pregunta['id_pregunta']);
                            foreach ($opciones as $opcion): ?>
                                <div class="mb-2">
                                    <input type="text"
                                        name="preguntas[<?php echo $pregunta['id_pregunta']; ?>][opciones][<?php echo $opcion['id_opcion']; ?>]"
                                        class="form-control"
                                        value="<?php echo htmlspecialchars($opcion['texto_opcion']); ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Esta es una pregunta abierta, no requiere opciones.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-custom mt-3">Guardar Cambios</button>
        </form>
    </div>

</body>

</html>