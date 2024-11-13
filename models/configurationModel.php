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

    public function actualizarConfiguracion($id_usuario, $nombre, $correo, $contrasenia, $foto_perfil)
    {
        $query = "UPDATE Usuarios SET nombre = ?, correo_electronico = ?, contrasenia = ? WHERE id_usuario = ?";
        $stmt = $this->db->prepare($query);

        if ($stmt === false) {
            die('Error en la preparación de la consulta: ' . $this->db->error);
        }

        $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
        $stmt->bind_param("sssi", $nombre, $correo, $contrasenia_hash, $id_usuario);

        if ($stmt->execute()) {
            if ($foto_perfil['error'] == UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/';
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
