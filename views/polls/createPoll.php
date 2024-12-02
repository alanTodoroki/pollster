<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .btn-custom {
            background-color: #F18F01;
            color: white;
        }

        .btn-custom:hover {
            background-color: #C77C02;
            color: white;
        }

        .step-indicator {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .step-active {
            color: #F18F01;
            font-weight: bold;
        }

        /* Estilos personalizados para inputs */
        .form-control:focus {
            border-color: #F18F01;
            box-shadow: 0 0 0 0.25rem rgba(241, 143, 1, 0.25);
        }

        .form-select:focus {
            border-color: #F18F01;
            box-shadow: 0 0 0 0.25rem rgba(241, 143, 1, 0.25);
        }

        textarea.form-control:focus {
            border-color: #F18F01;
            box-shadow: 0 0 0 0.25rem rgba(241, 143, 1, 0.25);
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

        .form-group {
            margin-bottom: 15px;
        }

        .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .input-group input {
            width: 80%;
        }

        .input-group button {
            margin-left: 10px;
        }

        .opcion-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .opcion-container input {
            flex-grow: 1;
        }

        .opcion-container .btn {
            flex-shrink: 0;
        }
    </style>
    <title>Crear Encuesta</title>
</head>

<body>
    <?php include_once '../components/navbar.php'; ?>
    <div class="container mt-3">
        <h1 class="text-center mb-4 mt-6">Nueva Encuesta</h1>

        <div class="step-indicator text-center">
            <span id="step1" class="step-active">1. Información Básica</span> &raquo;
            <span id="step2">2. Configuración</span>
        </div>

        <form id="crearEncuestaForm" action="../../controllers/poll/pollController.php" method="post">
            <input type="hidden" name="action" value="crearEncuesta">

            <!-- Step 1: Información básica -->
            <div id="paso1">
                <h3>Información de la Encuesta</h3>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" required></textarea>
                </div>

                <div class="label-container">
                    <label class="form-label">Preguntas de la Encuesta:</label>
                </div>

                <div id="preguntasContainer"></div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-custom btn-add" onclick="agregarPregunta()">Añadir Pregunta</button>
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-custom" onclick="siguientePaso()">Siguiente</button>
                </div>
            </div>

            <!-- Step 2: Configuración -->
            <div id="paso2" style="display: none;">
                <h3>Configuración de la Encuesta</h3>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                    <input type="date" class="form-control" name="fecha_inicio" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                    <input type="date" class="form-control" name="fecha_fin" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select class="form-select" name="estado" required>
                        <option value="activa">Activa</option>
                        <option value="inactiva">Inactiva</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-secondary me-2" onclick="pasoAnterior()">Anterior</button>
                    <button type="submit" class="btn btn-custom">Crear Encuesta</button>
                </div>
            </div>

            <div id="error-container" class="alert alert-danger" style="display: none;"></div>
        </form>

    </div>

    <script>
        let contadorPreguntas = 1;

        function agregarPregunta() {
            const preguntasContainer = document.getElementById("preguntasContainer");

            const preguntaDiv = document.createElement("div");
            preguntaDiv.classList.add("form-group");

            preguntaDiv.innerHTML = `
        <div class="mb-3">
            <label class="form-label">Pregunta ${contadorPreguntas}:</label>
            <input type="text" class="form-control" name="preguntas[${contadorPreguntas}][texto]" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de pregunta:</label>
            <select class="form-select" name="preguntas[${contadorPreguntas}][tipo]" onchange="toggleOpciones(${contadorPreguntas})">
                <option value="abierta">Abierta</option>
                <option value="opciones">Con Opciones</option>
            </select>
        </div>

        <div id="opciones_${contadorPreguntas}" class="mt-3" style="display: none;">
            <label class="form-label">Opciones de respuesta:</label>
            <div id="opcionesContainer_${contadorPreguntas}">
                <!-- Contenedor para las opciones -->
            </div>
            <button type="button" class="btn btn-outline-secondary mt-2" onclick="agregarOpcion(${contadorPreguntas})">
                <i class="bi bi-plus"></i> Añadir Opción
            </button>
        </div>

        <div class="text-end mt-2">
            <button type="button" class="btn btn-outline-danger mt-2" onclick="eliminarPregunta(this)">
                <i class="bi bi-trash"></i> Eliminar Pregunta
            </button>
        </div>
    `;

            preguntasContainer.appendChild(preguntaDiv);
            contadorPreguntas++;
        }

        function agregarOpcion(preguntaId) {
            const opcionesContainer = document.getElementById(`opcionesContainer_${preguntaId}`);
            const opcionDiv = document.createElement("div");
            opcionDiv.classList.add("opcion-container");

            const numOpciones = opcionesContainer.querySelectorAll("input").length + 1;

            opcionDiv.innerHTML = `
        <input type="text" class="form-control" name="preguntas[${preguntaId}][opciones][]" placeholder="Opción ${numOpciones}" required>
        <button type="button" class="btn btn-outline-danger" onclick="eliminarOpcion(this)">
            <i class="bi bi-trash"></i>
        </button>
    `;

            opcionesContainer.appendChild(opcionDiv);
        }

        function eliminarOpcion(boton) {
            const opcionDiv = boton.closest(".opcion-container");
            opcionDiv.remove();
        }


        function toggleOpciones(preguntaId) {
            const opcionesDiv = document.getElementById(`opciones_${preguntaId}`);
            const tipoSelect = document.querySelector(`select[name="preguntas[${preguntaId}][tipo]"]`);
            if (tipoSelect.value === 'opciones') {
                opcionesDiv.style.display = 'block';
            } else {
                opcionesDiv.style.display = 'none';
            }
        }


        function eliminarPregunta(boton) {
            const preguntaDiv = boton.closest('.form-group');
            preguntaDiv.remove();
            actualizarNumeracionPreguntas();
        }

        function actualizarNumeracionPreguntas() {
            const preguntas = document.querySelectorAll('.form-group');
            preguntas.forEach((pregunta, index) => {
                const label = pregunta.querySelector('label');
                label.textContent = `Pregunta ${index + 1}:`;
            });

            contadorPreguntas = preguntas.length + 1;
        }

        function siguientePaso() {
            document.getElementById('paso1').style.display = 'none';
            document.getElementById('paso2').style.display = 'block';
            document.getElementById('step1').classList.remove('step-active');
            document.getElementById('step2').classList.add('step-active');
        }

        function pasoAnterior() {
            document.getElementById('paso1').style.display = 'block';
            document.getElementById('paso2').style.display = 'none';
            document.getElementById('step1').classList.add('step-active');
            document.getElementById('step2').classList.remove('step-active');
        }

        document.getElementById('crearEncuestaForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío tradicional del formulario
            const formData = new FormData(this); // Obtener los datos del formulario

            fetch("../../controllers/poll/pollController.php", { // Asegúrate de que la ruta sea correcta
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    const errorContainer = document.getElementById('error-container');

                    if (data.includes("exitosamente")) {
                        // Redirigir al feed de usuario tras éxito
                        window.location.href = '../feed/feedUser.php';
                    } else {
                        // Mostrar mensaje de error en caso de fallo
                        errorContainer.textContent = data;
                        errorContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Ocurrió un error al crear la encuesta");
                });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>