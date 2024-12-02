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
}
