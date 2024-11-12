<!DOCTYPE html>
<html lang="es">

<head>
    <title>Crear Encuesta</title>
    <script src="../../public/js/createPoll.js"></script>
</head>

<body>
    <form id="crearEncuestaForm" action="../../controllers/pollController.php" method="post">
        <input type="hidden" name="action" value="crearEncuesta">
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required><br>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea><br>

        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" required><br>

        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" required><br>

        <label for="estado">Estado:</label>
        <select name="estado" required>
            <option value="activa">Activa</option>
            <option value="inactiva">Inactiva</option>
        </select><br>

        <div id="preguntasContainer">
            <button type="button" onclick="agregarPregunta()">Añadir Pregunta</button>
        </div>

        <input type="submit" value="Crear Encuesta">
    </form>

    <form id="eliminarEncuestaForm" action="../../controllers/pollController.php" method="post">
        <input type="hidden" name="action" value="eliminarEncuesta">
        <label for="id_encuesta">ID de la Encuesta:</label>
        <input type="text" name="id_encuesta" required><br>
        <input type="submit" value="Eliminar Encuesta">
    </form>

    <form id="actualizarEncuestaForm" action="../../controllers/pollController.php" method="post">
        <input type="hidden" name="action" value="actualizarEncuesta">
        <label for="id_encuesta">ID de la Encuesta:</label>
        <input type="text" name="id_encuesta" required><br>
        <label for="titulo">Título:</label>
        <input type="text" name="titulo" required><br>
        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" required></textarea><br>
        <label for="fecha_inicio">Fecha de Inicio:</label>
        <input type="date" name="fecha_inicio" required><br>
        <label for="fecha_fin">Fecha de Fin:</label>
        <input type="date" name="fecha_fin" required><br>
        <label for="estado">Estado:</label>
        <select name="estado" required>
            <option value="activa">Activa</option>
            <option value="inactiva">Inactiva</option>
        </select><br>
        <input type="submit" value="Actualizar Encuesta">
    </form>
</body>

</html>