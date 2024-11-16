<?php
//require_once '../config/db.php';
//require_once 'C:/wamp64/www/pollster/config/db.php';
//include_once __DIR__ . '/../config/database.php';
class VotacionModel
{
    private $db;

    public function __construct()
    {
        /*require_once 'C:/wamp64/www/pollster/config/db.php';
        //global $conn;
        //$this->db = $conn;
        $this->db = new Database();
        //$database = new Database();
        $this->db = $database->getConnection(); // Ahora es un objeto mysqli
        */

        try {
            require_once 'C:/wamp64/www/pollster/config/db.php';
            $database = new Database();
            $this->db = $database->getConnection();

            if (!$this->db) {
                throw new Exception("No se pudo establecer la conexión a la base de datos");
            }
        } catch (Exception $e) {
            die("Error de conexión: " . $e->getMessage());
        }
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
    public function obtenerVotacionesActivas()
    {
        $query = "SELECT * FROM Votaciones WHERE estado = 'activa' AND fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE()";
        $result = $this->db->query($query);

        $votaciones = [];
        while ($row = $result->fetch_assoc()) {
            $votaciones[] = $row;
        }

        return $votaciones;
    }

    public function obtenerOpcionesVotacion($id_votacion)
    {
        /*$query = "SELECT o.texto_opcion 
                  FROM Opciones o
                  INNER JOIN Preguntas p ON o.id_pregunta = p.id_pregunta
                  INNER JOIN Votaciones v ON p.id_votacion = v.id_votacion
                  WHERE v.id_votacion = ?";
        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bind_param("i", $id_votacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $opciones = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $opciones;
        */
        $query = "SELECT ov.texto_opcion 
                  FROM Opciones_Votacion ov
                  WHERE ov.id_votacion = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            die("Error preparando la consulta: " . $this->db->error);
        }

        $stmt->bind_param("i", $id_votacion);
        $stmt->execute();
        $result = $stmt->get_result();
        $opciones = [];

        while ($row = $result->fetch_assoc()) {
            $opciones[] = $row;
        }

        $stmt->close();
        return $opciones;
    }
}
