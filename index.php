<?php
require_once '../pollster/config/database.php'; // Conexión a la base de datos
require_once 'controllers/EncuestaController.php'; // Controlador de Encuestas

// Verificar qué controlador y acción ejecutar
if (isset($_GET['controller']) && isset($_GET['action'])) {
  $controllerName = $_GET['controller'];
  $action = $_GET['action'];

  // Crear el controlador
  $controller = new $controllerName($mysqli);

  // Ejecutar la acción
  $controller->{$action}();
} else {
  // Cargar vista por defecto (formulario para crear encuestas)
  $controller = new EncuestaController($conn);
  $controller->listarEncuestas();
}
