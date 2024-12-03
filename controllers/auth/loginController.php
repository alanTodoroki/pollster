<?php
require_once '../../models/userModel.php';

class LoginController
{
    private $usuarioModel;

    public function __construct($db)
    {
        $this->usuarioModel = new UsuarioModel($db);
    }

    public function login($correo_o_usuario, $contrasenia)
    {
        $usuario = $this->usuarioModel->verificarUsuario($correo_o_usuario, $contrasenia);

        if ($usuario) {
            // Iniciar sesión y redirigir según el rol
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];

            if ($usuario['rol'] === 'administrador') {
                header("Location: ../../views/admin/dashboard.php");
            } else {
                header("Location: ../../views/feed/feedUser.php");
            }
            exit();
        }

        return 'Lo siento, las credenciales no coinciden.';
    }
}
