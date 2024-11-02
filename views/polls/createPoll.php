<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Encuesta</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Nueva Encuesta</h1>
        <form action="guardar_encuesta.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre de la Encuesta</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" class="form-control"></textarea>
            </div>

            <div id="preguntas-container">
                <div class="form-group pregunta">
                    <label>Pregunta</label>
                    <input type="text" name="preguntas[0][texto]" class="form-control" placeholder="Tu pregunta" required>
                    <label>Tipo de respuesta:</label>
                    <select name="preguntas[0][tipo]" class="form-control">
                        <option value="unica">Única</option>
                        <option value="multiple">Múltiple</option>
                    </select>
                    <div class="opciones mt-3">
                        <label>Opciones:</label>
                        <input type="text" name="preguntas[0][opciones][]" class="form-control mt-2" placeholder="Opción 1">
                        <button type="button" class="btn btn-sm btn-secondary add-opcion mt-2">Añadir opción</button>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary add-pregunta mt-4">Añadir Pregunta</button>
            <button type="submit" class="btn btn-success mt-4">Guardar Encuesta</button>
        </form>

        <form action="http://localhost/pollster/controllers/encuestaController.php" method="post">
            <label for="titulo">Título de la encuesta:</label>
            <input type="text" name="titulo" id="titulo" required><br>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required></textarea><br>

            <button type="submit">Crear Encuesta</button>
        </form>
    </div>
    <script src="../../public/js/crearOps.js"></script>
</body>

</html>