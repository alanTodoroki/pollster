<?php
class VotacionModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Funciones para la creación de votaciones con sus opciones

    public function crearVotacion($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario)
    {
        $query = "INSERT INTO Votaciones (titulo, descripcion, fecha_inicio, fecha_fin, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // Regresa el ID de la votación creada
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarOpcionVotacion($id_votacion, $texto_opcion)
    {
        // $query = "INSERT INTO Opciones_Votacion (id_votacion, texto_opcion) VALUES ('{$id_votacion}', '{$texto_opcion}')";
        // $query = "INSERT INTO Opciones_Votacion (id_votacion, texto_opcion) VALUES (':id_votacion', ':texto_opcion');
        //$query = "INSERT INTO Opciones_Votacion (id_votacion, texto_opcion) VALUES ('{$id_votacion}', '{$texto_opcion}')";
        $query = "INSERT INTO Opciones_Votacion (id_votacion, texto_opcion) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        //var_dump($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("is", $id_votacion, $texto_opcion);
        return $stmt->execute();
    }

    //Funciones para obtener datos de votaciones y opciones



    //Funciones para la actualizacion de las votaciones con sus opciones

    public function actualizarVotacion($id_votacion, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        $query = "UPDATE Votaciones SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id_votacion = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_votacion);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function actualizarOpcionesVotacion($id_votacion, $opciones)
    {
        // Primero eliminar las opciones existentes
        $this->eliminarOpcionesVotacion($id_votacion);

        // Luego agregar las nuevas opciones
        foreach ($opciones as $opcion) {
            $this->agregarOpcionVotacion($id_votacion, $opcion);
        }

        return true;
    }

    //Funciones para eliminar datos de votaciones

    public function eliminarVotacion($id_votacion)
    {
        // Primero elimina las opciones asociadas a la votación
        $this->eliminarOpcionesVotacion($id_votacion);


        $query = "DELETE FROM Votaciones WHERE id_votacion = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
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
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_votacion);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    //Funciones para el feed del usuario

    public function obtenerVotaciones($id_usuario)
    {
        $query = "SELECT * FROM votaciones WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario); // "i" indica que es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        $votaciones = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $votaciones;
    }

    public function obtenerVotacionesActivas()
    {
        $query = "SELECT e.*, u.nombre_usuario 
                  FROM Votaciones e 
                  JOIN Usuarios u ON e.id_usuario = u.id_usuario 
                  WHERE e.estado = 'activa'";
        // Resto de la implementación

        $result = $this->conn->query($query);

        $votaciones = [];
        while ($row = $result->fetch_assoc()) {
            $votaciones[] = $row;
        }

        return $votaciones;
    }

    //Funciones para registrar los votos y usarlos en las estadisticas

    public function obtenerVotacionPorId($id_votacion, $id_usuario)
    {
        $query = "SELECT * FROM Votaciones WHERE id_votacion = ? AND id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id_votacion, $id_usuario); // "ii" indica dos enteros
        $stmt->execute();
        $result = $stmt->get_result();
        $votacion = $result->fetch_assoc();
        $stmt->close();
        return $votacion;
    }


    public function obtenerOpcionesPorVotacion($id_votacion)
    {
        $query = "SELECT * FROM Opciones_Votacion WHERE id_votacion = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_votacion);
        $stmt->execute();
        $result = $stmt->get_result();

        $opciones = [];
        while ($row = $result->fetch_assoc()) {
            $opciones[] = $row;
        }

        return $opciones;
    }

    // Método para registrar un voto para una votación
    public function registrarVoto($id_usuario, $id_opcion)
    {
        $query = "INSERT INTO Votos (id_usuario, id_opcion, tipo_opcion) VALUES (?, ?, 'votacion')";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("ii", $id_usuario, $id_opcion);

        return $stmt->execute();
    }

    // Método para obtener los resultados de una votación
    public function obtenerResultadosVotacion($id_votacion)
    {
        $query = "SELECT o.texto_opcion, COUNT(v.id_opcion) AS votos 
                  FROM Opciones_Votacion o
                  LEFT JOIN Votos v ON o.id_opcion = v.id_opcion AND v.tipo_opcion = 'votacion'
                  WHERE o.id_votacion = ?
                  GROUP BY o.id_opcion";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_votacion);
        $stmt->execute();

        $result = $stmt->get_result();
        $resultados = [];

        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }

        return $resultados;
    }
}
