<?php
require_once '../../models/userModel.php';

class SignUpController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new UsuarioModel($db);
    }

    public function handleSignUp($postData)
    {
        if (
            empty($postData['correo_electronico']) || empty($postData['contrasenia']) ||
            empty($postData['confirmar_contrasenia']) || empty($postData['nombre']) ||
            empty($postData['apellido']) || empty($postData['nombre_usuario'])
        ) {
            return 'Por favor completa todos los campos.';
        }

        if ($postData['contrasenia'] !== $postData['confirmar_contrasenia']) {
            return 'Las contraseñas no coinciden.';
        }

        // Verificar si el correo ya existe
        if ($this->userModel->verificarCorreo($postData['correo_electronico'])) {
            return 'El correo electrónico ya está registrado.';
        }

        // Verificar si el nombre de usuario ya existe
        if ($this->userModel->verificarNombreUsuario($postData['nombre_usuario'])) {
            return 'El nombre de usuario ya está registrado.';
        }

        // Intentar registrar al usuario
        $hashPassword = password_hash($postData['contrasenia'], PASSWORD_BCRYPT);

        $result = $this->userModel->registrarUsuario(
            $postData['nombre'],
            $postData['apellido'],
            $postData['correo_electronico'],
            $hashPassword,
            $postData['nombre_usuario']
        );

        if ($result) {
            return 'Usuario creado con éxito. <a href="login.php">Iniciar sesión</a>';
        }

        return 'Error al crear la cuenta.';
    }
}
