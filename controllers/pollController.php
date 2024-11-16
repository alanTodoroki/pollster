<?php
require_once '../models/pollModel.php';

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $estado = $_POST['estado'];
    $id_usuario = 1; // Asegúrate de obtener el ID del usuario de manera adecuada
    $preguntas = $_POST['preguntas'];

    $resultado = crearEncuestaCompleta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario, $preguntas);

    echo $resultado;
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    switch ($action) {
        case 'crearEncuesta':
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];
            $id_usuario = 1; // Asegúrate de obtener el ID del usuario de manera adecuada
            $preguntas = $_POST['preguntas'];

            $resultado = crearEncuestaCompleta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario, $preguntas);
            echo $resultado;
            break;

        case 'eliminarEncuesta':
            $id_encuesta = $_POST['id_encuesta'];
            $resultado = eliminarEncuesta($id_encuesta);
            echo $resultado;
            break;

        case 'actualizarEncuesta':
            $id_encuesta = $_POST['id_encuesta'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];

            $resultado = actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);
            echo $resultado;
            break;

        default:
            echo "Acción no válida";
            break;
    }
}

function crearEncuestaCompleta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario, $preguntas)
{
    $encuestaModel = new EncuestaModel();
    $id_encuesta = $encuestaModel->crearEncuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario);

    if (!$id_encuesta) {
        return "Error al crear la encuesta";
    }

    foreach ($preguntas as $pregunta) {
        $id_pregunta = $encuestaModel->agregarPregunta($id_encuesta, $pregunta['texto_pregunta'], $pregunta['tipo_pregunta']);

        if ($pregunta['tipo_pregunta'] === 'multiple_choice' && !empty($pregunta['opciones'])) {
            foreach ($pregunta['opciones'] as $opcion) {
                $encuestaModel->agregarOpcion($id_pregunta, $opcion);
            }
        }
    }

    return "Encuesta creada exitosamente";
}

/*function eliminarEncuesta($id_encuesta)
{
    $encuestaModel = new EncuestaModel();
    return $encuestaModel->eliminarEncuesta($id_encuesta);
}*/
function eliminarEncuesta($id_encuesta)
{
    $encuestaModel = new EncuestaModel();
    $resultado = $encuestaModel->eliminarEncuesta($id_encuesta);

    if ($resultado === true) {
        return "Encuesta eliminada exitosamente";  //Aquí se va a modificar para que lleve a otra pagina despues
    } else {
        return "Error al eliminar la encuesta: " . $resultado;
    }
}


/*function actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
{
    $encuestaModel = new EncuestaModel();
    return $encuestaModel->actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);
}*/

function actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
{
    $encuestaModel = new EncuestaModel();
    $resultado = $encuestaModel->actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

    if ($resultado === true) {
        return "Encuesta actualizada exitosamente";
    } else {
        return "Error al actualizar la encuesta: " . $resultado;
    }
}
