<?php
include_once '../config/database.php';

class UsuarioModel
{
    private $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    public function verificarUsuario($correo, $contrasenia)
    {
        $query = "SELECT id_usuario, nombre, correo_electronico, contraseña FROM Usuarios WHERE correo_electronico = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            if (password_verify($contrasenia, $usuario['contraseña'])) {
                return $usuario;
            }
        }

        return false;
    }

    public function obtenerUsuario($id_usuario)
    {
        $query = "SELECT id_usuario, nombre, correo_electronico, foto_perfil FROM Usuarios WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return false;
    }

    public function actualizarConfiguracion($id_usuario, $nombre, $correo, $contrasenia, $foto_perfil)
    {
        $query = "UPDATE Usuarios SET nombre = ?, correo_electronico = ?, contraseña = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
        $stmt->bind_param("sssi", $nombre, $correo, $contrasenia_hash, $id_usuario);

        if ($stmt->execute()) {
            if ($foto_perfil['error'] == UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $upload_file = $upload_dir . basename($foto_perfil['name']);

                if (move_uploaded_file($foto_perfil['tmp_name'], $upload_file)) {
                    $query = "UPDATE Usuarios SET foto_perfil = ? WHERE id_usuario = ?";
                    $stmt = $this->db->prepare($query);

                    if ($stmt === false) {
                        die('Error en la preparación de la consulta: ' . $this->db->error);
                    }

                    $stmt->bind_param("si", $upload_file, $id_usuario);

                    if ($stmt->execute()) {
                        return true;
                    } else {
                        return $stmt->error; // Regresa el error específico
                    }
                } else {
                    return "Error al subir la foto de perfil";
                }
            } else {
                return true;
            }
        } else {
            return $stmt->error; // Regresa el error específico
        }
    }
}
