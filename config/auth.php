<?php

class Auth
{
    private $conn;
    private $usuario_id;

    public function __construct($db)
    {
        $this->conn = $db;

        if (isset($_SESSION['id_usuario'])) {
            $this->usuario_id = $_SESSION['id_usuario'];
        }
    }

    public function checkAuth()
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ../views/users/login.php');
            exit();
        }

        $this->usuario_id = $_SESSION['usuario_id'];
    }

    public function checkRole($allowed_roles)
    {
        if (!in_array($_SESSION['rol'], $allowed_roles)) {
            header('Location: ../views/alerts/accessDenied.php');
            exit();
        }
    }

    public function getUserId()
    {
        return $this->usuario_id;
    }
}
