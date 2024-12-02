<?php
// Iniciar la sesión
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al login
header("Location: login.php");
exit(); // Asegura que el script se detiene después de la redirección