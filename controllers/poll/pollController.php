<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/pollModel.php';

$database = new Database();
$db = $database->getConnection();

$pollModel = new EncuestaModel($db); // Aquí creamos la instancia

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    switch ($action) {
        case 'crearEncuesta':
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];
            $id_usuario = $_SESSION['id_usuario']; // Asegúrate de obtener el ID del usuario de manera adecuada
            $preguntas = $_POST['preguntas'];

            $resultado = crearEncuestaCompleta($db, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario, $preguntas);
            echo $resultado;
            break;

        case 'eliminarEncuesta':
            $id_encuesta = $_POST['id_encuesta'];
            $resultado = eliminarEncuesta($db, $id_encuesta);
            echo $resultado;
            break;

        case 'actualizarEncuesta':
            $id_encuesta = $_POST['id_encuesta'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];

            $resultado = actualizarEncuesta($db, $id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);
            echo $resultado;
            break;

        case 'responderEncuesta':
            $id_encuesta = $_POST['id_encuesta'];
            $id_usuario = $_SESSION['id_usuario']; // Asumo que tienes el ID de usuario en sesión

            // Procesar respuestas
            foreach ($_POST['respuestas'] as $id_pregunta => $respuesta) {
                if (is_array($respuesta)) {
                    // Opciones múltiples (deserializar si necesario)
                    foreach ($respuesta as $id_opcion) {
                        if (is_numeric($id_opcion)) {
                            $pollModel->registrarVoto($id_usuario, $id_opcion);
                        }
                    }
                } else {
                    // Pregunta abierta
                    $pollModel->registrarRespuestaAbierta($id_usuario, $id_pregunta, $respuesta);
                }
            }


            // Redirigir o mostrar mensaje de éxito
            header("Location: ../../views/feed/feedUser.php");
            exit();
            break;
    }
}

function crearEncuestaCompleta($db, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario, $preguntas)
{
    $encuestaModel = new EncuestaModel($db);
    $id_encuesta = $encuestaModel->crearEncuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario);

    if (!$id_encuesta) {
        return "Error al crear la encuesta";
    }

    foreach ($preguntas as $pregunta) {

        $id_pregunta = $encuestaModel->agregarPregunta($id_encuesta, $pregunta['texto'], $pregunta['tipo']);

        if ($pregunta['tipo'] === 'opciones' && !empty($pregunta['opciones'])) {
            foreach ($pregunta['opciones'] as $opcion) {
                $encuestaModel->agregarOpcion($id_pregunta, $opcion);
            }
        }
    }

    return "Encuesta creada exitosamente";
}
function eliminarEncuesta($db, $id_encuesta)
{
    $encuestaModel = new EncuestaModel($db);
    $resultado = $encuestaModel->eliminarEncuesta($id_encuesta);

    if ($resultado === true) {
        return "Encuesta eliminada exitosamente";  //Aquí se va a modificar para que lleve a otra pagina despues
    } else {
        return "Error al eliminar la encuesta: " . $resultado;
    }
}

function actualizarEncuesta($db, $id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
{
    $encuestaModel = new EncuestaModel($db);
    $resultado = $encuestaModel->actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

    if ($resultado === true) {
        return "Encuesta actualizada exitosamente";
    } else {
        return "Error al actualizar la encuesta: " . $resultado;
    }
}
