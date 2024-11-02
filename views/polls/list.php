<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Encuestas</title>
</head>

<body>
    <h2>Lista de Encuestas</h2>
    <ul>
        <?php if (empty($encuestas)): ?>
            <li>No hay encuestas disponibles.</li>
        <?php else: ?>
            <?php foreach ($encuestas as $encuesta): ?>
                <li>
                    <strong><?php echo htmlspecialchars($encuesta['titulo']); ?></strong>: <?php echo htmlspecialchars($encuesta['descripcion']); ?>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</body>

</html>