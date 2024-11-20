<?php
require_once '../../config/db.php';
require_once '../../models/voteModel.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Crear Votación</title>
    <script src="../../public/js/createVote.js"></script>
</head>

<body>
    <form id="crearVotacionForm" action="../../controllers/voteController.php" method="post">
        <input type="hidden" name="action" value="crearVotacion">
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

        <div id="opcionesContainer">
            <button type="button" onclick="agregarOpcion()">Añadir Opción</button>
        </div>

        <input type="submit" value="Crear Votación">
    </form>

    <form id="eliminarVotacionForm" action=".../../controllers/voteController.php" method="post">
        <input type="hidden" name="action" value="eliminarVotacion">
        <label for="id_votacion">ID de la Votación:</label>
        <input type="text" name="id_votacion" required><br>
        <input type="submit" value="Eliminar Votación">
    </form>

    <form id="actualizarVotacionForm" action="../../controllers/voteController.php" method="post">
        <input type="hidden" name="action" value="actualizarVotacion">
        <label for="id_votacion">ID de la Votación:</label>
        <input type="text" name="id_votacion" required><br>
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
        <input type="submit" value="Actualizar Votación">
    </form>

    <form id="agregarVotoForm" action="../../controllers/voteController.php" method="post">
        <input type="hidden" name="action" value="agregarVoto">
        <label for="id_opcion">ID de la Opción:</label>
        <input type="text" name="id_opcion" required><br>
        <label for="tipo_opcion">Tipo de Opción:</label>
        <select name="tipo_opcion" required>
            <option value="encuesta">Encuesta</option>
            <option value="votacion">Votación</option>
        </select><br>
        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" value="1" required><br>
        <input type="submit" value="Agregar Voto">
    </form>
</body>

</html>