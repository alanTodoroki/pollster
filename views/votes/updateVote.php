<?php
//updateVote.php
session_start();
require_once '../../config/db.php';
require_once '../../models/voteModel.php';

$database = new Database();
$db = $database->getConnection();
$votacionModel = new VotacionModel($db);

// Obtener el ID de la votación a editar
$id_votacion = $_GET['id'] ?? null;

if (!$id_votacion) {
    die("ID de votación no proporcionado");
}

// Obtener los datos de la votación
$votacion = $votacionModel->obtenerVotacionPorId($id_votacion);
$opciones = $votacionModel->obtenerOpcionesVotacion($id_votacion);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        /* Estilos idénticos a crear-votacion.php */
        <?php include '../components/crear-votacion.php'; ?>
    </style>
    <title>Editar Votación</title>
</head>

<body>
    <?php include '../components/navbar.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center mb-4 mt-6">Editar Votación</h1>

        <div class="step-indicator text-center">
            <span id="step1" class="step-active">1. Información Básica</span> &raquo;
            <span id="step2">2. Configuración</span>
        </div>

        <form id="votacionCompleta" action="../../controllers/vote/voteController.php" method="post">
            <input type="hidden" name="action" value="actualizarVotacion">
            <input type="hidden" name="id_votacion" value="<?php echo $id_votacion; ?>">

            <!-- Step 1: Información básica -->
            <div id="paso1">
                <h3>Información de la Votación</h3>
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" class="form-control" name="titulo" value="<?php echo htmlspecialchars($votacion['titulo']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción:</label>
                    <textarea class="form-control" name="descripcion" required><?php echo htmlspecialchars($votacion['descripcion']); ?></textarea>
                </div>

                <div id="opcionesContainer" class="mb-3">
                    <div class="label-container">
                        <label class="form-label">Opciones de votación:</label>
                        <div id="opcionesError" class="error-message" style="display: none;">Debe agregar al menos dos opciones.</div>
                    </div>

                    <?php foreach ($opciones as $index => $opcion): ?>
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="opciones[]"
                                placeholder="Opción <?php echo $index + 1; ?>"
                                value="<?php echo htmlspecialchars($opcion['texto_opcion']); ?>" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="agregarOpcion()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-custom" onclick="siguientePaso()">Siguiente</button>
                </div>
            </div>

            <!-- Step 2: Configuración -->
            <div id="paso2" style="display: none;">
                <h3>Configuración de la Votación</h3>
                <div class="mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio:</label>
                    <input type="date" class="form-control" name="fecha_inicio"
                        value="<?php echo $votacion['fecha_inicio']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_fin" class="form-label">Fecha de Fin:</label>
                    <input type="date" class="form-control" name="fecha_fin"
                        value="<?php echo $votacion['fecha_fin']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado:</label>
                    <select class="form-select" name="estado" required>
                        <option value="activa" <?php echo $votacion['estado'] == 'activa' ? 'selected' : ''; ?>>Activa</option>
                        <option value="inactiva" <?php echo $votacion['estado'] == 'inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                    </select>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-secondary me-2" onclick="pasoAnterior()">Anterior</button>
                    <button type="submit" class="btn btn-custom">Actualizar Votación</button>
                </div>
            </div>
            <div id="error-container" class="alert alert-danger" style="display: none;"></div>
        </form>
    </div>

    <script>
        // Utilizamos el mismo script de crear-votacion.php con una pequeña modificación en el evento submit
        document.getElementById('votacionCompleta').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            fetch("../../controllers/vote/voteController.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    const errorContainer = document.getElementById('error-container');
                    if (data.includes("exitosamente")) {
                        window.location.href = 'lista-votaciones.php'; // Redirigir a la lista de votaciones
                    } else {
                        errorContainer.textContent = data;
                        errorContainer.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Ocurrió un error al actualizar la votación");
                });
        });

        // Resto del script igual al de crear-votacion.php
        let contadorOpciones = <?php echo count($opciones); ?>;

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

        // (Aquí irían las funciones agregarOpcion(), eliminarOpcion(), etc. 
        // idénticas a las de crear-votacion.php)
    </script>
</body>

</html>