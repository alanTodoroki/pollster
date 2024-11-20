<?php
class UsuarioModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function verificarUsuario($correo, $contrasenia)
    {
        $query = "SELECT id_usuario, nombre, correo_electronico, contrasenia FROM Usuarios WHERE correo_electronico = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("s", $correo);
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
}
