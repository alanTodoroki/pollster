<?php
//session_start();

class configuracionModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function actualizarConfiguracion($id_usuario, $nombre, $nombre_usuario, $correo, $contrasenia, $foto_perfil)
    {
        $query = "UPDATE Usuarios SET nombre = ?, nombre_usuario = ?, correo_electronico = ?, contrasenia = ?, WHERE id_usuario = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die('Error en la preparacion de la consulta: ' . $this->conn->error);
        }
        $contrasenia_hash = password_hash($contrasenia, PASSWORD_DEFAULT);
        $stmt->bind_param("sssi", $nombre, $correo, $contrasenia_hash, $id_usuario);

        if ($stmt->execute()) {
            if ($foto_perfil['error'] == UPLOAD_ERR_OK) {
                $upload_dir = '../uploads/';
                return "Error en la carga de la foto de perfil. Código de error: " . $foto_perfil['error'];
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $upload_file = $upload_dir . basename($foto_perfil['name']);

                if (move_uploaded_file($foto_perfil['tmp_name'], $upload_file)) {
                    $query = "UPDATE Usuarios SET foto_perfil = ? WHERE id_usuario = ?";
                    $stmt = $this->conn->prepare($query);

                    if ($stmt === false) {
                        die('Error en la preparación de la consulta: ' . $this->conn->error);
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
