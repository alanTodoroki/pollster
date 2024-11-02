<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Recuperar Contraseña</h1>
        <form action="recuperar_backend.php" method="POST">
            <div class="form-group">
                <label for="correo">Correo Electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="Ingresa tu correo" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Enlace de Recuperación</button>
        </form>
    </div>
</body>
</html>
