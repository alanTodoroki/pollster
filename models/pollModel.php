<?php
class EncuestaModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Funciones para la creacion de encuestas con sus preguntas y opciones

    public function crearEncuesta($titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario)
    {
        $query = "INSERT INTO Encuestas (titulo, descripcion, fecha_inicio, fecha_fin, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_usuario);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // Regresa el ID de la encuesta creada
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarPregunta($id_encuesta, $texto_pregunta, $tipo_pregunta)
    {
        $db_tipo = ($tipo_pregunta === 'opciones') ? 'multiple_choice' : 'abierto';
        $query = "INSERT INTO Preguntas (id_encuesta, texto_pregunta, tipo_pregunta) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("iss", $id_encuesta, $texto_pregunta, $db_tipo);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // ID de la pregunta creada
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function agregarOpcion($id_pregunta, $texto_opcion)
    {
        $query = "INSERT INTO Opciones (id_pregunta, texto_opcion) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("is", $id_pregunta, $texto_opcion);
        return $stmt->execute();
    }

    //Funciones para la eliminacion de encuestas sus preguntas y opciones

    public function eliminarEncuesta($id_encuesta)
    {

        // Primero elimina las preguntas asociadas a la encuesta
        $this->eliminarPreguntasEncuesta($id_encuesta);

        $query = "DELETE FROM Encuestas WHERE id_encuesta = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
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
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
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
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_encuesta);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    //Funciones para actualizar las encuestas sus preguntas y funciones

    public function actualizarEncuesta($id_encuesta, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado)
    {
        $query = "UPDATE Encuestas SET titulo = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id_encuesta = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("sssssi", $titulo, $descripcion, $fecha_inicio, $fecha_fin, $estado, $id_encuesta);

        if ($stmt->execute()) {
            return true;
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }

    public function obtenerOpciones($id_pregunta)
    {
        $query = "SELECT * FROM opciones WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query); // $this->conn es la conexión a la base de datos
        $stmt->bind_param('i', $id_pregunta); // Vinculamos el parámetro de la consulta
        $stmt->execute(); // Ejecutamos la consulta
        $result = $stmt->get_result(); // Obtenemos el resultado
        return $result->fetch_all(MYSQLI_ASSOC); // Devolvemos un arreglo asociativo con los resultados
    }


    public function actualizarPregunta($id_pregunta, $texto_pregunta)
    {
        $query = "UPDATE preguntas SET texto_pregunta = ? WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $texto_pregunta, $id_pregunta);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function actualizarOpcion($id_opcion, $texto_opcion)
    {
        $query = "UPDATE opciones SET texto_opcion = ? WHERE id_opcion = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('si', $texto_opcion, $id_opcion);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }


    //Funciones para consultar las encuestas y meterlas en cards para el feed y el home

    public function obtenerEncuestas($id_usuario)
    {
        $query = "SELECT * FROM encuestas WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario); // "i" indica que es un entero
        $stmt->execute();
        $result = $stmt->get_result();
        $encuestas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $encuestas;
    }

    public function obtenerEncuestasActivas($soloActivas = false, $id_usuario = null)
    {
        $query = "SELECT e.*, u.nombre_usuario 
              FROM Encuestas e
              JOIN Usuarios u ON e.id_usuario = u.id_usuario";

        $conditions = [];
        if ($soloActivas) {
            $conditions[] = "e.estado = 'activa'";
        }

        if ($id_usuario !== null) {
            $conditions[] = "e.id_usuario = ?";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        if ($id_usuario !== null) {
            $stmt->bind_param("i", $id_usuario);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $encuestas = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        return $encuestas;
    }

    //Funciones para contestar la encuesta

    public function registrarVoto($id_usuario, $id_opcion, $tipo = 'encuesta')
    {
        // Log para verificar los datos de entrada
        error_log("Intentando registrar voto: id_usuario={$id_usuario}, id_opcion={$id_opcion}, tipo_opcion={$tipo}");

        // Verificar si ya existe un voto similar para prevenir duplicados
        $query_check = "SELECT * FROM Votos 
                    WHERE id_usuario = ? 
                    AND id_opcion = ? 
                    AND tipo_opcion = ?";
        $stmt_check = $this->conn->prepare($query_check);
        if ($stmt_check === false) {
            error_log("Error al preparar query_check: " . $this->conn->error);
            return false;
        }

        $stmt_check->bind_param("iis", $id_usuario, $id_opcion, $tipo);
        if (!$stmt_check->execute()) {
            error_log("Error al ejecutar query_check: " . $stmt_check->error);
            return false;
        }

        $result_check = $stmt_check->get_result();
        if ($result_check->num_rows > 0) {
            error_log("Voto ya existente para id_usuario={$id_usuario}, id_opcion={$id_opcion}, tipo_opcion={$tipo}");
            return true; // Ya existe el voto
        }

        // Insertar nuevo voto
        $query = "INSERT INTO Votos (id_usuario, id_opcion, tipo_opcion) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            error_log("Error al preparar query de inserción: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("iis", $id_usuario, $id_opcion, $tipo);

        if (!$stmt->execute()) {
            error_log("Error al ejecutar query de inserción: " . $stmt->error);
            return false;
        }

        // Confirmación de éxito
        error_log("Voto registrado exitosamente: id_usuario={$id_usuario}, id_opcion={$id_opcion}, tipo_opcion={$tipo}");
        return true;
    }

    public function registrarRespuestaAbierta($id_usuario, $id_pregunta, $respuesta)
    {
        // Inserta la respuesta abierta en la tabla Respuestas_Abiertas
        $query = "INSERT INTO Respuestas_Abiertas (id_usuario, id_pregunta, texto_respuesta) 
                      VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iis", $id_usuario, $id_pregunta, $respuesta);  // 'i' para enteros, 's' para string

        // Ejecutamos la consulta y verificamos si se inserta correctamente
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    //Funciones para registrar los votos y usarlos en las estadisticas

    public function obtenerEncuestaPorId($id_encuesta, $id_usuario = null)
    {
        $query = "SELECT e.*, u.nombre_usuario, u.id_usuario
              FROM Encuestas e
              JOIN Usuarios u ON e.id_usuario = u.id_usuario
              WHERE e.id_encuesta = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_encuesta);
        $stmt->execute();
        $result = $stmt->get_result();

        $encuesta = $result->fetch_assoc();
        $stmt->close();

        // Si no se encuentra la encuesta, devolver null o lanzar un error
        if (!$encuesta) {
            return null;
        }

        return $encuesta;
    }


    public function obtenerPreguntasPorEncuesta($id_encuesta)
    {
        $query = "SELECT * FROM Preguntas WHERE id_encuesta = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_encuesta);
        $stmt->execute();
        $result = $stmt->get_result();

        $preguntas = [];
        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }

        return $preguntas;
    }

    public function obtenerOpcionesPorPregunta($id_pregunta)
    {
        $query = "SELECT * FROM Opciones WHERE id_pregunta = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();
        $result = $stmt->get_result();

        $opciones = [];
        while ($row = $result->fetch_assoc()) {
            $opciones[] = $row;
        }

        return $opciones;
    }

    public function obtenerResultadosPregunta($id_pregunta)
    {
        $query = "SELECT o.texto_opcion, COUNT(v.id_opcion) AS votos
                  FROM Opciones o
                  LEFT JOIN Votos v ON o.id_opcion = v.id_opcion AND v.tipo_opcion = 'encuesta'
                  WHERE o.id_pregunta = ?
                  GROUP BY o.id_opcion";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();

        $result = $stmt->get_result();
        $resultados = [];

        while ($row = $result->fetch_assoc()) {
            $resultados[] = $row;
        }

        return $resultados;
    }

    // Método para obtener respuestas abiertas de una pregunta
    public function obtenerRespuestasAbiertas($id_pregunta)
    {
        $query = "SELECT texto_respuesta, COUNT(texto_respuesta) AS total
                FROM respuestas_abiertas
                WHERE id_pregunta = ?
                GROUP BY texto_respuesta";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        // Asociar el parámetro `id_pregunta`
        $stmt->bind_param("i", $id_pregunta);

        // Ejecutar la consulta
        $stmt->execute();

        $result = $stmt->get_result();
        $respuestas = [];

        // Recopilar los resultados en un array asociativo
        while ($row = $result->fetch_assoc()) {
            $respuestas[] = $row;
        }

        return $respuestas; // Retornar las respuestas agrupadas con sus totales
    }




    //Funciones para visualizar mis datos
    public function obtenerPreguntas($id_encuesta)
    {
        $query = "SELECT id_pregunta, texto_pregunta, tipo_pregunta FROM Preguntas WHERE id_encuesta = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_encuesta);
        $stmt->execute();
        $result = $stmt->get_result();

        $preguntas = [];
        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }

        return $preguntas;
    }



    public function obtenerRespuestas($id_encuesta)
    {
        $query = "SELECT 
                ra.id_respuesta, 
                p.texto_pregunta, 
                ra.texto_respuesta, 
                ra.id_usuario
              FROM respuestas_abiertas ra
              JOIN preguntas p ON ra.id_pregunta = p.id_pregunta
              WHERE p.id_encuesta = ?";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_encuesta);
        $stmt->execute();
        $result = $stmt->get_result();

        $respuestas = [];
        while ($row = $result->fetch_assoc()) {
            $respuestas[] = $row;
        }

        return $respuestas;
    }
    public function obtenerOpcionesYVotos($id_pregunta)
    {
        $query = "SELECT o.texto_opcion, COUNT(v.id_opcion) AS votos
              FROM Opciones o
              LEFT JOIN Votos v ON o.id_opcion = v.id_opcion
              WHERE o.id_pregunta = ?
              GROUP BY o.id_opcion";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_pregunta);
        $stmt->execute();

        $result = $stmt->get_result();
        $opcionesYVotos = [];

        while ($row = $result->fetch_assoc()) {
            $opcionesYVotos[] = $row; // Guarda cada opción con su número de votos
        }

        return $opcionesYVotos; // Retorna todas las opciones y votos
    }

    // Funcion para generar token para compartir

    public function generarTokenEncuesta($id_encuesta)
    {
        // Generar token corto (8 caracteres al azar)
        $token = substr(bin2hex(random_bytes(4)), 0, 8);

        // Insertar token en la tabla de encuestas
        $query = "UPDATE encuestas SET token = ? WHERE id_encuesta = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("si", $token, $id_encuesta);
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }

    public function obtenerEncuesta($token)
    {
        $query = "SELECT * FROM encuestas WHERE token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token); // Usamos "s" ya que el token es una cadena
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false; // No se encontró la encuesta
        }
    }

    public function actualizarToken($id_encuesta, $token)
    {
        $query = "UPDATE encuestas SET token = ? WHERE id_encuesta = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $token, $id_encuesta); // "s" para cadena y "i" para entero
        return $stmt->execute();
    }

    public function obtenerDatosEncuesta($id_encuesta)
    {
        $query = "
            SELECT 
                p.texto_pregunta AS pregunta,
                o.texto_opcion AS opcion,
                COUNT(v.id_voto) AS votos
            FROM preguntas p
            LEFT JOIN opciones o ON p.id_pregunta = o.id_pregunta
            LEFT JOIN votos v ON o.id_opcion = v.id_opcion AND v.tipo_opcion = 'encuesta'
            WHERE p.id_encuesta = ?
            GROUP BY p.id_pregunta, o.id_opcion
            ORDER BY p.id_pregunta;
        ";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error preparando la consulta SQL: " . $this->conn->error);
        }

        $stmt->bind_param('i', $id_encuesta); // 'i' para entero
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            die("Error ejecutando la consulta: " . $stmt->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerDatosParaGraficas($id_encuesta)
    {
        $preguntas = $this->obtenerPreguntasPorEncuesta($id_encuesta);
        $datosParaGraficas = [];

        foreach ($preguntas as $pregunta) {
            if ($pregunta['tipo_pregunta'] === 'multiple_choice') {
                $opcionesYVotos = $this->obtenerOpcionesYVotos($pregunta['id_pregunta']);
                $datosParaGraficas[] = [
                    'pregunta' => $pregunta['texto_pregunta'],
                    'opcionesYVotos' => $opcionesYVotos
                ];
            }
        }

        return $datosParaGraficas;
    }

    public function generarGraficasPreguntasMultiples($id_encuesta)
    {
        $preguntas = $this->obtenerPreguntasPorEncuesta($id_encuesta);
        $graficas = [];

        foreach ($preguntas as $pregunta) {
            // Solo procesamos preguntas de opción múltiple
            if ($pregunta['tipo_pregunta'] === 'multiple_choice') {
                $resultados = $this->obtenerResultadosPregunta($pregunta['id_pregunta']);

                // Preparar datos para la gráfica
                $labels = [];
                $votos = [];
                $colores = $this->generarColoresAleatorios(count($resultados));

                foreach ($resultados as $resultado) {
                    $labels[] = $resultado['texto_opcion'];
                    $votos[] = $resultado['votos'];
                }

                $graficas[] = [
                    'pregunta' => $pregunta['texto_pregunta'],
                    'labels' => $labels,
                    'votos' => $votos,
                    'colores' => $colores
                ];
            }
        }

        return $graficas;
    }

    private function generarColoresAleatorios($cantidad)
    {
        $colores = [];
        $paletaBase = [
            'rgba(54, 162, 235, 0.7)',   // Azul
            'rgba(155, 197, 61, 0.7)',   // 9BC53D - Verde
            'rgba(255, 99, 132, 0.7)',   // Rojo
            'rgba(91, 192, 235, 0.7)',   // 5BC0EB - Azul claro
            'rgba(75, 192, 192, 0.7)',   // Verde aguamarina 
            'rgba(255, 206, 86, 0.7)',   // Amarillo
            'rgba(153, 102, 255, 0.7)',  // Morado
            'rgba(255, 159, 64, 0.7)',   // Naranja
            'rgba(253, 231, 76, 0.7)',   // FDE74C - Amarillo
            'rgba(195, 66, 63, 0.7)',
        ];

        for ($i = 0; $i < $cantidad; $i++) {
            $colores[] = $paletaBase[$i % count($paletaBase)];
        }

        return $colores;
    }

    public function obtenerTodasLasEncuestas()
    {
        $query = "SELECT * FROM Encuestas";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
