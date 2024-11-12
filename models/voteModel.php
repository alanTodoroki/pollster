<?php
include_once '../config/database.php';

class VotacionModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function crearVotacion($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        $query = "INSERT INTO Votaciones (titulo, descripcion, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("sssss", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

        if ($stmt->execute()) {
            return $this->db->insert_id; // Regresa el ID de la votación creada
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarOpcionVotacion($id_votacion, $texto_opcion)
    {
        $query = "INSERT INTO Opciones_Votacion (id_votacion, texto_opcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("is", $id_votacion, $texto_opcion);
        return $stmt->execute();
    }

    public function eliminarVotacion($id_votacion)
    {
        // Primero elimina las opciones asociadas a la votación
        $this->eliminarOpcionesVotacion($id_votacion);


        $query = "DELETE FROM Votaciones WHERE id_votacion = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id_votacion);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function eliminarOpcionesVotacion($id_votacion)
    {
        $query = "DELETE FROM Opciones_Votacion WHERE id_votacion = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id_votacion);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function actualizarVotacion($id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        $query = "UPDATE Votaciones SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id_votacion = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_votacion);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarVoto($id_usuario, $id_opcion, $tipo_opcion, $cantidad = 1)
    {
        $query = "INSERT INTO Votos (id_usuario, id_opcion, tipo_opcion, cantidad) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("iiis", $id_usuario, $id_opcion, $tipo_opcion, $cantidad);

        if ($stmt->execute()) {
            return $this->db->insert_id; // ID del voto creado
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }
}
