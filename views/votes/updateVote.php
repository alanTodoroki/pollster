<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();
$voteModel = new VotacionModel($db);

$id_votacion = $_GET['id'] ?? null; // Asegurarnos de que el ID de la votación se pasa correctamente
$id_usuario = $_SESSION['id_usuario'] ?? null;

if (!$id_votacion || !$id_usuario) {
    die('ID de votación o usuario no especificado.');
}

// Consultar los datos de la votación
$votacion_query = $db->prepare("SELECT * FROM votaciones WHERE id_votacion = ?");
$votacion_query->bind_param("i", $id_votacion);
$votacion_query->execute();
$votacion = $votacion_query->get_result()->fetch_assoc();

// Consultar las opciones asociadas
$opciones_query = $db->prepare("SELECT * FROM opciones_votacion WHERE id_votacion = ?");
$opciones_query->bind_param("i", $id_votacion);
$opciones_query->execute();
$opciones = $opciones_query->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Votación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #9BC53D;
            --secondary-color: #A3C680;
            --accent-color: #E6F0D6;
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
            box-shadow: 0 0 0 0.25rem rgba(155, 197, 61, 0.5);
            /* Sombra de color verde claro */
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center">Editar Votación</h1>

        <a href="seeVote.php?id=<?php echo $id_votacion; ?>" class="btn btn-custom mb-2">Regresar</a>

        <form action="../../controllers/vote/voteController.php" method="POST">
            <input type="hidden" name="action" value="actualizarVotacion">
            <input type="hidden" name="id_votacion" value="<?= $votacion['id_votacion'] ?>">

            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($votacion['titulo']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" required><?= htmlspecialchars($votacion['descripcion']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Opciones</label>
                <?php while ($opcion = $opciones->fetch_assoc()) { ?>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="opciones[<?= $opcion['id_opcion'] ?>]" value="<?= htmlspecialchars($opcion['texto_opcion']) ?>" required>
                    </div>
                <?php } ?>
            </div>

            <button type="submit" class="btn btn-custom me-2">Guardar Cambios</button>
        </form>
    </div>

</body>

</html>