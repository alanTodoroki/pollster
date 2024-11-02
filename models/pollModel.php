<?php
/*class Encuesta
{
    private $db; //Conexión a la base de datos

    public function __construct($db)
    {
        $this->db = $db;
    }

    //Metodo para crear una nueva encuesta
    public function crearEncuesta($id_encuesta, $id_usuario_creador, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        $query = "INSERT INTO encuestas (id_encuesta, id_usuario_creador, titulo, descripcion, fecha_inicio, fecha_fin, estado) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query); //Preparamos la consulta
        $stmt->bind_param("ssi", $id_encuesta, $id_usuario_creador, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado); //Enlazamos los parametros
        $stmt->execute(); //Ejecutamos la consulta

        // Retornamos el ID de la encuesta creada si la inserción fue exitosa
        return $stmt->insert_id ? $stmt->insert_id : false;
    }
    // Método para obtener todas las encuestas de la base de datos
    public function obtenerEncuestas()
    {
        $query = "SELECT * FROM encuestas";
        $result = $this->db->query($query); // Ejecutamos la consulta
        return $result->fetch_all(MYSQLI_ASSOC); // Devolvemos el resultado como array asociativo
    }
}*/
class Encuesta
{
    private $db; //Conexión a la base de datos

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Metodo para crear una nueva encuesta
    public function crearEncuesta($id_usuario_creador, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        // Eliminar $id_encuesta porque debe ser autoincremental
        $query = "INSERT INTO encuestas (id_usuario_creador, titulo, descripcion, fecha_inicio, fecha_fin, estado) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query); // Preparamos la consulta

        // Asumimos que el id_usuario_creador es un entero, el resto son cadenas
        $stmt->bind_param("isssss", $id_usuario_creador, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado);

        // Ejecutamos la consulta
        if ($stmt->execute()) {
            // Retornamos el ID de la encuesta creada si la inserción fue exitosa
            return $stmt->insert_id;
        } else {
            return false;
        }
    }

    // Método para obtener todas las encuestas de la base de datos
    public function obtenerEncuestas()
    {
        $query = "SELECT * FROM encuestas";
        $result = $this->db->query($query); // Ejecutamos la consulta
        return $result->fetch_all(MYSQLI_ASSOC); // Devolvemos el resultado como array asociativo
    }
}
