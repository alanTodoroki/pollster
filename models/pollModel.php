<?php
include_once '../config/database.php';

class EncuestaModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function crearEncuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario)
    {
        $query = "INSERT INTO Encuestas (titulo, descripcion, fecha_inicio, fecha_fin, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario);

        if ($stmt->execute()) {
            return $this->db->insert_id; // Regresa el ID de la encuesta creada
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarPregunta($id_encuesta, $texto_pregunta, $tipo_pregunta)
    {
        $query = "INSERT INTO Preguntas (id_encuesta, texto_pregunta, tipo_pregunta) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("iss", $id_encuesta, $texto_pregunta, $tipo_pregunta);

        if ($stmt->execute()) {
            return $this->db->insert_id; // ID de la pregunta creada
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarOpcion($id_pregunta, $texto_opcion)
    {
        $query = "INSERT INTO Opciones (id_pregunta, texto_opcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("is", $id_pregunta, $texto_opcion);
        return $stmt->execute();
    }

    public function eliminarEncuesta($id_encuesta)
    {

        // Primero elimina las preguntas asociadas a la encuesta
        $this->eliminarPreguntasEncuesta($id_encuesta);

        $query = "DELETE FROM Encuestas WHERE id_encuesta = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id_encuesta);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function eliminarPreguntasEncuesta($id_encuesta)
    {
        // Primero elimina las opciones asociadas a las preguntas de la encuesta
        $this->eliminarOpcionesPreguntasEncuesta($id_encuesta);

        // Luego elimina las preguntas de la encuesta
        $query = "DELETE FROM Preguntas WHERE id_encuesta = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id_encuesta);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function eliminarOpcionesPreguntasEncuesta($id_encuesta)
    {
        // Elimina las opciones asociadas a las preguntas de la encuesta
        $query = "DELETE FROM Opciones WHERE id_pregunta IN (SELECT id_pregunta FROM Preguntas WHERE id_encuesta = ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id_encuesta);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }


    public function actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        $query = "UPDATE Encuestas SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id_encuesta = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_encuesta);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }
}
