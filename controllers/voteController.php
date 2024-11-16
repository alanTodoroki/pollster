<?php
session_start();
require_once '../models/voteModel.php';

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $estado = $_POST['estado'];
    $id_usuario = 1; // Asegúrate de obtener el ID del usuario de manera adecuada
    $texto_pregunta = $_POST['texto_pregunta'];
    $opciones = $_POST['opciones'];

    $resultado = crearVotacionCompleta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario, $texto_pregunta, $opciones);

    echo $resultado;
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    switch ($action) {
        case 'crearVotacion':
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];
            $opciones = $_POST['opciones'];

            $resultado = crearVotacionCompleta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $opciones);
            echo $resultado;
            break;

        case 'eliminarVotacion':
            $id_votacion = $_POST['id_votacion'];
            $resultado = eliminarVotacion($id_votacion);
            echo $resultado;
            break;

        case 'actualizarVotacion':
            $id_votacion = $_POST['id_votacion'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $estado = $_POST['estado'];

            $resultado = actualizarVotacion($id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);
            echo $resultado;
            break;

        case 'agregarVoto':
            $id_usuario = $_SESSION['id_usuario']; // Obtener el ID del usuario de la sesión
            $id_opcion = $_POST['id_opcion'];
            $tipo_opcion = $_POST['tipo_opcion'];
            $cantidad = $_POST['cantidad'];

            $resultado = agregarVoto($id_usuario, $id_opcion, $tipo_opcion, $cantidad);
            echo $resultado;
            break;

        default:
            echo "Acción no válida";
            break;
    }
}

function crearVotacionCompleta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $opciones)
{
    $votacionModel = new VotacionModel();
    $id_votacion = $votacionModel->crearVotacion($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

    if (!$id_votacion) {
        return "Error al crear la votación";
    }

    foreach ($opciones as $opcion) {
        $votacionModel->agregarOpcionVotacion($id_votacion, $opcion);
    }

    return "Votación creada exitosamente";
}

function eliminarVotacion($id_votacion)
{
    $votacionModel = new VotacionModel();
    $resultado = $votacionModel->eliminarVotacion($id_votacion);

    if ($resultado === true) {
        return "Votación eliminada exitosamente";
    } else {
        return "Error al eliminar la votación: " . $resultado;
    }
}

function actualizarVotacion($id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
{
    $votacionModel = new VotacionModel();
    $resultado = $votacionModel->actualizarVotacion($id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

    if ($resultado === true) {
        return "Votación actualizada exitosamente";
    } else {
        return "Error al actualizar la votación: " . $resultado;
    }
}

function agregarVoto($id_usuario, $id_opcion, $tipo_opcion, $cantidad)
{
    $votacionModel = new VotacionModel();
    $resultado = $votacionModel->agregarVoto($id_usuario, $id_opcion, $tipo_opcion, $cantidad);

    if ($resultado === true) {
        return "Voto agregado exitosamente";
    } else {
        return "Error al agregar el voto: " . $resultado;
    }
}
