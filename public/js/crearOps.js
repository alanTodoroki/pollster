let preguntaCount = 1;

// Añadir nuevas preguntas dinámicamente
$(".add-pregunta").click(function () {
  let preguntaHtml = `
                <div class="form-group pregunta">
                    <label>Pregunta</label>
                    <input type="text" name="preguntas[${preguntaCount}][texto]" class="form-control" placeholder="Tu pregunta" required>
                    <label>Tipo de respuesta:</label>
                    <select name="preguntas[${preguntaCount}][tipo]" class="form-control">
                        <option value="unica">Única</option>
                        <option value="multiple">Múltiple</option>
                    </select>
                    <div class="opciones mt-3">
                        <label>Opciones:</label>
                        <input type="text" name="preguntas[${preguntaCount}][opciones][]" class="form-control mt-2" placeholder="Opción 1">
                        <button type="button" class="btn btn-sm btn-secondary add-opcion mt-2">Añadir opción</button>
                    </div>
                </div>
            `;
  $("#preguntas-container").append(preguntaHtml);
  preguntaCount++;
});

// Añadir más opciones a las preguntas dinámicamente
$(document).on("click", ".add-opcion", function () {
  let opcionHtml = `<input type="text" name="preguntas[${preguntaCount}][opciones][]" class="form-control mt-2" placeholder="Nueva opción">`;
  $(this).before(opcionHtml);
});
