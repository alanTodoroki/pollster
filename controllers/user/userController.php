<?php
require_once '../../models/userModel.php';

class CreateUserController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new UsuarioModel($db);
    }

    public function handleCreateUser($postData)
    {
        if (
            empty($postData['correo_electronico']) || empty($postData['contrasenia']) ||
            empty($postData['nombre']) || empty($postData['apellido']) ||
            empty($postData['nombre_usuario']) || empty($postData['rol'])
        ) {
            return 'Por favor completa todos los campos.';
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

        $result = $this->userModel->registrarUsuarios(
            $postData['nombre'],
            $postData['apellido'],
            $postData['correo_electronico'],
            $hashPassword,
            $postData['rol'], // Se incluye el rol
            $postData['nombre_usuario']
        );

        if ($result) {
            return 'Usuario creado con éxito.';
        }

        return 'Error al crear el usuario.';
    }
}
