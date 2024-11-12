/*let contadorPreguntas = 0;

function agregarPregunta() {
  contadorPreguntas++;
  const preguntasContainer = document.getElementById("preguntasContainer");

  const preguntaDiv = document.createElement("div");
  preguntaDiv.classList.add("pregunta");

  preguntaDiv.innerHTML = `
        <label>Pregunta:</label>
        <input type="text" name="preguntas[${contadorPreguntas}][texto_pregunta]" required><br>

        <label>Tipo de Pregunta:</label>
        <select name="preguntas[${contadorPreguntas}][tipo_pregunta]" onchange="cambiarTipoPregunta(this, ${contadorPreguntas})">
            <option value="multiple_choice">Multiple Choice</option>
            <option value="abierto">Abierto</option>
        </select><br>

        <div id="opcionesContainer${contadorPreguntas}">
            <button type="button" onclick="agregarOpcion(${contadorPreguntas})">Añadir Opción</button>
        </div>
    `;

  preguntasContainer.appendChild(preguntaDiv);
}

function cambiarTipoPregunta(select, preguntaId) {
  const opcionesContainer = document.getElementById(
    `opcionesContainer${preguntaId}`
  );
  if (select.value === "abierto") {
    opcionesContainer.style.display = "none";
  } else {
    opcionesContainer.style.display = "block";
  }
}

function agregarOpcion(preguntaId) {
  const opcionesContainer = document.getElementById(
    `opcionesContainer${preguntaId}`
  );
  const opcionInput = document.createElement("div");

  opcionInput.innerHTML = `
        <input type="text" name="preguntas[${preguntaId}][opciones][]" placeholder="Opción" required><br>
    `;

  opcionesContainer.appendChild(opcionInput);
}*/

/*let contadorPreguntas = 0;

function agregarPregunta() {
  contadorPreguntas++;
  const preguntasContainer = document.getElementById("preguntasContainer");

  const preguntaDiv = document.createElement("div");
  preguntaDiv.classList.add("pregunta");

  preguntaDiv.innerHTML = `
        <label>Pregunta:</label>
        <input type="text" name="preguntas[${contadorPreguntas}][texto_pregunta]" required><br>

        <label>Tipo de Pregunta:</label>
        <select name="preguntas[${contadorPreguntas}][tipo_pregunta]" onchange="cambiarTipoPregunta(this, ${contadorPreguntas})">
            <option value="multiple_choice">Multiple Choice</option>
            <option value="abierto">Abierto</option>
        </select><br>

        <div id="opcionesContainer${contadorPreguntas}">
            <button type="button" onclick="agregarOpcion(${contadorPreguntas})">Añadir Opción</button>
        </div>
    `;

  preguntasContainer.appendChild(preguntaDiv);
}

function cambiarTipoPregunta(select, preguntaId) {
  const opcionesContainer = document.getElementById(
    `opcionesContainer${preguntaId}`
  );
  if (select.value === "abierto") {
    opcionesContainer.style.display = "none";
  } else {
    opcionesContainer.style.display = "block";
  }
}

function agregarOpcion(preguntaId) {
  const opcionesContainer = document.getElementById(
    `opcionesContainer${preguntaId}`
  );
  const opcionInput = document.createElement("div");

  opcionInput.innerHTML = `
        <input type="text" name="preguntas[${preguntaId}][opciones][]" placeholder="Opción" required><br>
    `;

  opcionesContainer.appendChild(opcionInput);
}*/

let contadorPreguntas = 0;

function agregarPregunta() {
  contadorPreguntas++;
  const preguntasContainer = document.getElementById("preguntasContainer");

  const preguntaDiv = document.createElement("div");
  preguntaDiv.classList.add("pregunta");

  preguntaDiv.innerHTML = `
        <label>Pregunta:</label>
        <input type="text" name="preguntas[${contadorPreguntas}][texto_pregunta]" required><br>

        <label>Tipo de Pregunta:</label>
        <select name="preguntas[${contadorPreguntas}][tipo_pregunta]" onchange="cambiarTipoPregunta(this, ${contadorPreguntas})">
            <option value="multiple_choice">Multiple Choice</option>
            <option value="abierto">Abierto</option>
        </select><br>

        <div id="opcionesContainer${contadorPreguntas}">
            <button type="button" onclick="agregarOpcion(${contadorPreguntas})">Añadir Opción</button>
        </div>
    `;

  preguntasContainer.appendChild(preguntaDiv);
}

function cambiarTipoPregunta(select, preguntaId) {
  const opcionesContainer = document.getElementById(
    `opcionesContainer${preguntaId}`
  );
  if (select.value === "abierto") {
    opcionesContainer.style.display = "none";
  } else {
    opcionesContainer.style.display = "block";
  }
}

function agregarOpcion(preguntaId) {
  const opcionesContainer = document.getElementById(
    `opcionesContainer${preguntaId}`
  );
  const opcionInput = document.createElement("div");

  opcionInput.innerHTML = `
        <input type="text" name="preguntas[${preguntaId}][opciones][]" placeholder="Opción" required><br>
    `;

  opcionesContainer.appendChild(opcionInput);
}
