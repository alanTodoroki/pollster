<?php
class ConfiguracionModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener configuración de usuario
    public function obtenerConfiguracion($id_usuario)
    {
        $query = "SELECT nombre, nombre_usuario, correo_electronico, foto_perfil 
                  FROM usuarios 
                  WHERE id_usuario = ?";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // Actualizar configuración de usuario
    public function actualizarConfiguracion($id_usuario, $datos)
    {
        // Validate input data
        if (empty($datos['nombre']) || empty($datos['nombre_usuario']) || empty($datos['correo'])) {
            return json_encode([
                'success' => false,
                'message' => 'Por favor complete todos los campos requeridos'
            ]);
        }

        // Validate password change if attempting to change password
        if (!empty($datos['nueva_contrasenia'])) {
            if (empty($datos['contrasenia_actual'])) {
                return json_encode([
                    'success' => false,
                    'message' => 'Debe ingresar la contraseña actual para cambiar la contraseña'
                ]);
            }

            if ($datos['nueva_contrasenia'] !== $datos['confirmar_contrasenia']) {
                return json_encode([
                    'success' => false,
                    'message' => 'Las contraseñas nuevas no coinciden'
                ]);
            }
        }

        try {
            // Validate username
            if (
                isset($datos['nombre_usuario']) &&
                $this->configuracionModel->existeUsuario($datos['nombre_usuario'], null, $id_usuario)
            ) {
                return json_encode([
                    'success' => false,
                    'message' => 'Nombre de usuario ya existe'
                ]);
            }

            // Validate email
            if (
                isset($datos['correo_electronico']) &&
                $this->configuracionModel->existeUsuario(null, $datos['correo_electronico'], $id_usuario)
            ) {
                return json_encode([
                    'success' => false,
                    'message' => 'Correo electrónico ya existe'
                ]);
            }

            // Check current password if changing password
            if (isset($datos['contrasenia_actual'], $datos['nueva_contrasenia'])) {
                if (!$this->configuracionModel->verificarContrasenia($id_usuario, $datos['contrasenia_actual'])) {
                    return json_encode([
                        'success' => false,
                        'message' => 'Contraseña actual incorrecta'
                    ]);
                }

                // Replace current password with new password
                $datos['contrasenia'] = $datos['nueva_contrasenia'];
                unset($datos['contrasenia_actual'], $datos['nueva_contrasenia'], $datos['confirmar_contrasenia']);
            }

            // Hash new password if present
            if (isset($datos['contrasenia'])) {
                $datos['contrasenia'] = password_hash($datos['contrasenia'], PASSWORD_BCRYPT);
            }

            // Remove unnecessary keys
            unset($datos['nueva_contrasenia'], $datos['confirmar_contrasenia'], $datos['contrasenia_actual']);

            // Update configuration
            $this->configuracionModel->actualizarConfiguracion($id_usuario, $datos);

            return json_encode([
                'success' => true,
                'message' => 'Configuración actualizada correctamente'
            ]);
        } catch (Exception $e) {
            return json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Verificar contraseña actual
    public function verificarContrasenia($id_usuario, $contrasenia_actual)
    {
        $query = "SELECT contrasenia FROM usuarios WHERE id_usuario = ?";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $usuario = $result->fetch_assoc();

        // Verificar contraseña
        return password_verify($contrasenia_actual, $usuario['contrasenia']);
    }

    // Verificar si el nombre de usuario o correo ya existen
    public function existeUsuario($nombre_usuario = null, $correo = null, $id_usuario_actual = null)
    {
        $condiciones = [];
        $tipos = '';
        $valores = [];

        if ($nombre_usuario !== null) {
            $condiciones[] = "nombre_usuario = ?";
            $tipos .= 's';
            $valores[] = $nombre_usuario;
        }

        if ($correo !== null) {
            $condiciones[] = "correo_electronico = ?";
            $tipos .= 's';
            $valores[] = $correo;
        }

        // Excluir el usuario actual si se proporciona
        if ($id_usuario_actual !== null) {
            $condiciones[] = "id_usuario != ?";
            $tipos .= 'i';
            $valores[] = $id_usuario_actual;
        }

        $query = "SELECT COUNT(*) as count FROM usuarios WHERE " . implode(' OR ', $condiciones);

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            throw new Exception('Error en la preparación de la consulta: ' . $this->conn->error);
        }

        call_user_func_array([$stmt, 'bind_param'], array_merge([$tipos], array_map(function (&$valor) {
            return $valor;
        }, $valores)));

        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['count'];

        return $count > 0;
    }
}
