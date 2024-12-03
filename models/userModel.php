<?php
class UsuarioModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function verificarUsuario($correo_o_usuario, $contrasenia)
    {
        $query = "SELECT id_usuario, nombre, apellido, nombre_usuario, correo_electronico, contrasenia, rol 
              FROM Usuarios WHERE correo_electronico = ? OR nombre_usuario = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("ss", $correo_o_usuario, $correo_o_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            if (password_verify($contrasenia, $usuario['contrasenia'])) {
                return $usuario;
            }
        }

        return false;
    }

    public function obtenerUsuario($id_usuario)
    {
        $query = "SELECT id_usuario, nombre_usuario, nombre, apellido, correo_electronico, foto_perfil FROM Usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);


        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }
    public function verificarCorreo($correo)
    {
        $query = "SELECT id_usuario FROM Usuarios WHERE correo_electronico = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function verificarNombreUsuario($nombreUsuario)
    {
        $query = "SELECT id_usuario FROM Usuarios WHERE nombre_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $nombreUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->num_rows > 0;
    }

    public function registrarUsuario($nombre, $apellido, $correo, $contrasenia, $nombreUsuario)
    {
        $query = "INSERT INTO Usuarios (nombre, apellido, correo_electronico, contrasenia, rol, nombre_usuario) 
                  VALUES (?, ?, ?, ?, 'usuario', ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssss", $nombre, $apellido, $correo, $contrasenia, $nombreUsuario);

        return $stmt->execute();
    }


    public function registrarUsuarios($nombre, $apellido, $correo_electronico, $contrasenia, $rol, $nombreUsuario)
    {
        try {
            $query = "INSERT INTO usuarios (nombre, apellido, correo_electronico, contrasenia, rol, nombre_usuario) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->conn->error);
            }

            $stmt->bind_param('ssssss', $nombre, $apellido, $correo_electronico, $contrasenia, $rol, $nombreUsuario);

            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }

            return true;
        } catch (Exception $e) {
            die("Error en el modelo UsuarioModel: " . $e->getMessage());
        }
    }

    public function crearUsuario($nombre, $apellido, $correo, $contrasenia, $rol, $nombre_usuario)
    {
        // Hashear la contraseña
        $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);

        $query = "INSERT INTO usuarios (nombre, apellido, correo_electronico, contrasenia, rol, nombre_usuario) 
              VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("ssssss", $nombre, $apellido, $correo, $contrasenia_hash, $rol, $nombre_usuario);

        if ($stmt->execute()) {
            return $this->conn->insert_id; // Regresa el ID del usuario creado
        } else {
            return false; // Regresa false si hay un error
        }
    }



    public function eliminarUsuario($id_usuario)
    {
        $query = "DELETE FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function actualizarUsuario($id_usuario, $nombre, $apellido, $correo, $rol)
    {
        $query = "UPDATE usuarios SET nombre = ?, apellido = ?, correo_electronico = ?, rol = ? WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $rol, $id_usuario);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function obtenerTodosLosUsuarios()
    {
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para obtener usuarios por rol (si no lo tienes ya)
    public function obtenerUsuariosPorRol($rol)
    {
        $query = "SELECT * FROM usuarios WHERE rol = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $rol);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para actualización parcial de usuario
    public function actualizarUsuarioParcial($id, $campos, $params)
    {
        // Construir la consulta dinámica
        $query = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id_usuario = ?";

        $stmt = $this->conn->prepare($query);

        // Determinar los tipos de parámetros dinámicamente
        $tipos = str_repeat("s", count($params));

        // Bind de parámetros
        $stmt->bind_param($tipos, ...$params);

        return $stmt->execute();
    }
    public function obtenerUsuarioPorId($id)
    {
        $query = "SELECT * FROM usuarios WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
