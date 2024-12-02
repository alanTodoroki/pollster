<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .btn-custom {
            background-color: #99C24D;
            color: white;
        }

        .btn-custom:hover {
            background-color: #638729;
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

        /* Estilos personalizados para inputs */
        .form-control:focus {
            border-color: #99C24D;
            box-shadow: 0 0 0 0.25rem rgba(153, 194, 77, 0.25);
        }

        /* Para select elementos */
        .form-select:focus {
            border-color: #99C24D;
            box-shadow: 0 0 0 0.25rem rgba(153, 194, 77, 0.25);
        }

        /* Para textarea elementos */
        textarea.form-control:focus {
            border-color: #99C24D;
            box-shadow: 0 0 0 0.25rem rgba(153, 194, 77, 0.25);
        }

        /* Color de fondo al autocompletar (Chrome/Safari) */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: inherit !important;
            border-color: #99C24D !important;
        }

        /* Color de selección de texto */
        ::selection {
            background-color: rgba(153, 194, 77, 0.3);
        }

        /* Estilo para cuando el input está activo/válido */
        .form-control:focus:valid {
            border-color: #99C24D;
        }

        .error-message {
            color: red;
            font-size: 0.875em;
            margin-top: 5px;
            margin-left: 0;
            /* Asegura que quede alineado al texto */
            display: block;
            /* Mantén el bloque visible cuando se active */
        }

        .label-container {
            display: flex;
            flex-direction: column;
            /* Alinea elementos verticalmente */
            align-items: flex-start;
            /* Alinea a la izquierda */
        }

        .label-container .form-label {
            margin-bottom: 5px;
            /* Añade espacio debajo del label */
        }
    </style>
    <title>Crear votación</title>
</head>

<body>
    <?php include_once '../components/navbar.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center mb-4 mt-6">Nueva Votación</h1>

        <div class="step-indicator text-center">
            <span id="step1" class="step-active">1. Información Básica</span> &raquo;
            <span id="step2">2. Configuración</span>
        </div>

        <form id="votacionCompleta" action="../../controllers/vote/voteController.php" method="post">
            <input type="hidden" name="action" value="crearVotacion">

            <!-- Step 1: Información básica -->
            <div id="paso1">
                <h3>Información de la Votación</h3>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" required></textarea>
                </div>

                <div id="opcionesContainer" class="mb-3">
                    <div class="label-container">
                        <label class="form-label">Opciones de votación:</label>
                        <div id="opcionesError" class="error-message" style="display: none;">Debe agregar al menos dos opciones.</div>
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" name="opciones[]" placeholder="Opción 1" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="agregarOpcion()">
                            <i class="bi bi-plus"></i>
                        </button>
                    </div>
                </div>

                <div class=" text-center">
                    <button type="button" class="btn btn-custom" onclick="siguientePaso()">Siguiente</button>
                </div>
            </div>

            <!-- Step 2: Configuración -->
            <div id="paso2" style="display: none;">
                <h3>Configuración de la Votación</h3>
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
                    <button type="submit" class="btn btn-custom">Crear Votación</button>
                </div>
            </div>
            <div id="error-container" class="alert alert-danger" style="display: none;"></div>

        </form>

    </div>

    <script>
        let contadorOpciones = 1;

        function agregarOpcion() {
            contadorOpciones++;
            const opcionesContainer = document.getElementById("opcionesContainer");

            const opcionDiv = document.createElement("div");
            opcionDiv.classList.add("input-group", "mb-2");

            opcionDiv.innerHTML = `
                <input type="text" name="opciones[]" class="form-control" placeholder="Opción ${contadorOpciones}" required>
                <button class="btn btn-outline-secondary" type="button" onclick="agregarOpcion()">
                    <i class="bi bi-plus"></i>
                </button>
                </button>
                <button class="btn btn-outline-danger" type="button" onclick="eliminarOpcion(this)">
                <i class="bi bi-trash"></i>
                </button>
            `;

            opcionesContainer.appendChild(opcionDiv);
            actualizarBotones();
        }

        function eliminarOpcion(button) {
            const opcionDiv = button.closest('.input-group');
            opcionDiv.remove();
            actualizarBotones();
            renumerarOpciones();
        }

        function actualizarBotones() {
            const inputs = document.querySelectorAll("#opcionesContainer .input-group");
            inputs.forEach((inputGroup, index) => {
                const buttons = inputGroup.querySelectorAll("button");
                buttons.forEach(button => button.remove());

                if (index === inputs.length - 1) {
                    const addButton = document.createElement("button");
                    addButton.className = "btn btn-outline-secondary";
                    addButton.type = "button";
                    addButton.innerHTML = '<i class="bi bi-plus"></i>';
                    addButton.onclick = agregarOpcion;
                    inputGroup.appendChild(addButton);
                }

                const deleteButton = document.createElement("button");
                deleteButton.className = "btn btn-outline-danger";
                deleteButton.type = "button";
                deleteButton.innerHTML = '<i class="bi bi-trash"></i>';
                deleteButton.onclick = () => eliminarOpcion(deleteButton);
                inputGroup.appendChild(deleteButton);
            });
        }

        function renumerarOpciones() {
            const opciones = document.querySelectorAll("#opcionesContainer .input-group input[name='opciones[]']");
            contadorOpciones = opciones.length; // Actualiza el contador al total actual de opciones
            opciones.forEach((opcion, index) => {
                opcion.placeholder = `Opción ${index + 1}`; // Renumera los placeholders
            });
        }

        function siguientePaso() {
            // Obtener los campos del primer paso
            const titulo = document.querySelector('input[name="titulo"]');
            const descripcion = document.querySelector('textarea[name="descripcion"]');
            const opciones = document.querySelectorAll('input[name="opciones[]"]');
            // Validar campos obligatorios primero
            if (!titulo.checkValidity()) {
                titulo.reportValidity();
                return;
            }

            if (!descripcion.checkValidity()) {
                descripcion.reportValidity();
                return;
            }

            // Validar que las opciones no estén vacías
            for (let opcion of opciones) {
                if (!opcion.checkValidity()) {
                    opcion.reportValidity();
                    return;
                }
            }
            // Validar que haya al menos dos opciones
            if (opciones.length < 2) {
                document.getElementById('opcionesError').style.display = 'block';
                return;
            } else {
                document.getElementById('opcionesError').style.display = 'none';
            }

            document.getElementById('paso1').style.display = 'none';
            document.getElementById('paso2').style.display = 'block';
            document.getElementById('step1').classList.remove('step-active');
            document.getElementById('step2').classList.add('step-active');
        }

        function pasoAnterior() {
            document.getElementById('paso2').style.display = 'none';
            document.getElementById('paso1').style.display = 'block';
            document.getElementById('step2').classList.remove('step-active');
            document.getElementById('step1').classList.add('step-active');
        }

        document.getElementById('votacionCompleta').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch("../../controllers/vote/voteController.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {

                    /*alert(data);
                    if (data.includes("exitosamente")) {
                        window.location.href = 'lista-votaciones.php'; // Redirigir a la lista de votaciones
                    }*/
                    const errorContainer = document.getElementById('error-container');
                    if (data.includes("exitosamente")) {
                        window.location.href = '../feed/feedUser.php'; // Redirigir a la lista de votaciones
                    } else {
                        errorContainer.textContent = data;
                        errorContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Ocurrió un error al crear la votación");
                });
        });

        document.addEventListener("DOMContentLoaded", function() {
            actualizarBotones();
        });
    </script>
</body>

</html>