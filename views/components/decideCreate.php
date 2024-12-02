<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .btn-votacion {
            background-color: #99C24D;
            color: white;
        }

        .btn-votacion:hover {
            background-color: #638729;
            color: white;
        }

        .btn-encuesta {
            background-color: #F18F01;
            color: white;
        }

        .btn-encuesta:hover {
            background-color: #d77a01;
            color: white;
        }

        .step-indicator {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .step-active {
            color: #99C24D;
            font-weight: bold;
        }

        .form-control:focus {
            border-color: #99C24D;
            box-shadow: 0 0 0 0.25rem rgba(153, 194, 77, 0.25);
        }

        .form-select:focus {
            border-color: #99C24D;
            box-shadow: 0 0 0 0.25rem rgba(153, 194, 77, 0.25);
        }

        textarea.form-control:focus {
            border-color: #99C24D;
            box-shadow: 0 0 0 0.25rem rgba(153, 194, 77, 0.25);
        }

        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: inherit !important;
            border-color: #99C24D !important;
        }

        ::selection {
            background-color: rgba(153, 194, 77, 0.3);
        }

        .form-control:focus:valid {
            border-color: #99C24D;
        }

        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
            margin-left: 0;
            display: block;
        }

        .label-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .label-container .form-label {
            margin-bottom: 5px;
        }

        /* Estilo para el SVG */
        .illustration {
            max-width: 150px;
            /* Ajusta el tamaño según lo necesites */
            height: auto;
            margin: 20px 0;
        }
    </style>
    <title>Crear</title>
</head>

<body>

    <?php include '../components/navbar.php'; ?>

    <div class="container text-center mt-5">
        <h1>Bienvenido a Pollster</h1>

        <!-- Imagen SVG central -->
        <img src="../../public/img/decide.svg" alt="Ilustración" class="illustration">

        <p>Elige una opción:</p>
        <div class="d-flex justify-content-center">
            <a href="../../views/votes/createVote.php" class="btn btn-votacion me-3">Crear Votación</a>
            <a href="../../views/polls/createPoll.php" class="btn btn-encuesta">Crear Encuesta</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>