<?php
require_once '../../models/configurationModel.php';
require_once '../../config/db.php';

class ConfiguracionController
{
    private $configuracionModel;

    public function __construct()
    {
        $database = new Database();
        $conexion = $database->getConnection();
        $this->configuracionModel = new ConfiguracionModel($conexion);
    }
    public function actualizarConfiguracion($id_usuario, $datos)
    {
        try {
            // Validaciones previas
            if (
                isset($datos['nombre_usuario']) &&
                $this->configuracionModel->existeUsuario($datos['nombre_usuario'], null, $id_usuario)
            ) {
                throw new Exception('Nombre de usuario ya existe');
            }

            if (
                isset($datos['correo_electronico']) &&
                $this->configuracionModel->existeUsuario(null, $datos['correo_electronico'], $id_usuario)
            ) {
                throw new Exception('Correo electrónico ya existe');
            }

            // Verificar contraseña actual si se intenta cambiar contraseña
            if (isset($datos['contrasenia_actual'], $datos['nueva_contrasenia'])) {
                if (!$this->configuracionModel->verificarContrasenia($id_usuario, $datos['contrasenia_actual'])) {
                    throw new Exception('Contraseña actual incorrecta');
                }

                // Reemplazar contraseña actual con nueva contraseña
                $datos['contrasenia'] = $datos['nueva_contrasenia'];
                unset($datos['contrasenia_actual'], $datos['nueva_contrasenia']);
            }

            // **Hashear la nueva contraseña si está presente**
            if (isset($datos['contrasenia'])) {
                // Hashear la contraseña antes de pasarla al modelo
                $datos['contrasenia'] = password_hash($datos['contrasenia'], PASSWORD_BCRYPT);
            }

            // Actualizar configuración
            $this->configuracionModel->actualizarConfiguracion($id_usuario, $datos);
            return json_encode(['status' => 'success', 'message' => 'Configuración actualizada correctamente']);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
