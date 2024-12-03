<?php
session_start();
require_once '../../config/db.php';
require_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();

$voteModel = new VotacionModel($db);

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
            $id_usuario = $_SESSION['id_usuario'];

            $resultado = crearVotacionCompleta($db, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $opciones, $id_usuario);
            echo $resultado;
            break;

        case 'eliminarVotacion':
            $id_votacion = $_POST['id_votacion'];
            $resultado = eliminarVotacion($db, $id_votacion);
            echo $resultado;
            break;

        case 'actualizarVotacion':
            $id_votacion = $_POST['id_votacion'];
            $titulo = $_POST['titulo']; // Título que se actualizará
            $descripcion = $_POST['descripcion']; // Descripción que se actualizará
            $opciones = $_POST['opciones']; // Opciones que se actualizarán

            // Actualizar la votación (título y descripción)
            $query_votacion = $db->prepare("UPDATE votaciones SET titulo = ?, descripcion = ? WHERE id_votacion = ?");
            $query_votacion->bind_param("ssi", $titulo, $descripcion, $id_votacion);
            $query_votacion->execute();

            // Actualizar las opciones
            foreach ($opciones as $id_opcion => $texto_opcion) {
                // Solo actualizamos las opciones que tienen un nuevo valor
                if (!empty($texto_opcion)) {
                    $query_opciones = $db->prepare("UPDATE opciones_votacion SET texto_opcion = ? WHERE id_opcion = ?");
                    $query_opciones->bind_param("si", $texto_opcion, $id_opcion);
                    $query_opciones->execute();
                }
            }

            // Redirigir después de la actualización
            header("Location: ../../votes/seeVote.php?id=<?php echo $id_votacion;");
            exit;
            break;

            // Otros casos aquí...

        default:
            // Si la acción no coincide con ningún caso
            echo "Acción no válida";
            break;

        case 'participarVotacion':
            $id_votacion = $_POST['id_votacion'];
            $id_usuario = $_SESSION['id_usuario'];
            $id_opcion = $_POST['respuesta']; // La opción seleccionada

            if ($voteModel->registrarVoto($id_usuario, $id_opcion, 'votacion')) {
                // Redirige al feed del usuario si el voto fue exitoso
                header("Location: ../../views/feed/feedUser.php");
                exit();
            } else {
                echo "Error al registrar el voto.";
            }
            break;

            foreach ($opciones as $id_opcion => $texto_opcion) {
                // Solo actualizamos las opciones que tienen un nuevo valor
                if (!empty($texto_opcion)) {
                    $query = $db->prepare("UPDATE opciones_votacion SET texto_opcion = ? WHERE id_opcion = ?");
                    $query->bind_param("si", $texto_opcion, $id_opcion);
                    $query->execute();
                }
            }
    }
}

function crearVotacionCompleta($db, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $opciones, $id_usuario)
{
    $votacionModel = new VotacionModel($db);
    $id_votacion = $votacionModel->crearVotacion($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario);

    if (!$id_votacion) {
        return "Error al crear la votación";
    }

    foreach ($opciones as $opcion) {
        print_r($opcion);
        //$votacionModel->agregarOpcionVotacion($id_votacion, $opcion);

        /*$resultado = $votacionModel->agregarOpcionVotacion($id_votacion, $opcion);
        if (!$resultado) {
            return "Error al agregar la opción: " . $opcion;
        }*/
        if (!$votacionModel->agregarOpcionVotacion($id_votacion, $opcion)) {
            return "Error al agregar opción: " . $opcion; // Agregar un mensaje de error
        }
    }

    return "Votación creada exitosamente";
}

function eliminarVotacion($db, $id_votacion)
{
    $votacionModel = new VotacionModel($db);
    $resultado = $votacionModel->eliminarVotacion($id_votacion);

    if ($resultado === true) {
        return "Votación eliminada exitosamente";
    } else {
        return "Error al eliminar la votación: " . $resultado;
    }
}

function actualizarVotacion($db, $id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $opciones = null)
{
    $votacionModel = new VotacionModel($db);

    // Primero actualizar los datos de la votación
    $resultado = $votacionModel->actualizarVotacion($id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

    // Si se proporcionan opciones, actualizar también las opciones
    if ($opciones !== null) {
        $votacionModel->actualizarOpcionesVotacion($id_votacion, $opciones);
    }

    if ($resultado === true) {
        return "Votación actualizada exitosamente";
    } else {
        return "Error al actualizar la votación: " . $resultado;
    }
}
