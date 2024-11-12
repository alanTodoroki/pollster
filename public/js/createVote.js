let contadorOpciones = 0;

function agregarOpcion() {
  contadorOpciones++;
  const opcionesContainer = document.getElementById("opcionesContainer");

  const opcionDiv = document.createElement("div");
  opcionDiv.classList.add("opcion");

  opcionDiv.innerHTML = `
        <input type="text" name="opciones[]" placeholder="Opción ${contadorOpciones}" required><br>
    `;

  opcionesContainer.appendChild(opcionDiv);
}

document.addEventListener("DOMContentLoaded", function () {
  const crearVotacionForm = document.getElementById("crearVotacionForm");
  const eliminarVotacionForm = document.getElementById("eliminarVotacionForm");
  const actualizarVotacionForm = document.getElementById(
    "actualizarVotacionForm"
  );
  const agregarVotoForm = document.getElementById("agregarVotoForm");

  crearVotacionForm.addEventListener("submit", function (event) {
    event.preventDefault();
    enviarFormulario(crearVotacionForm, "crearVotacion");
  });

  eliminarVotacionForm.addEventListener("submit", function (event) {
    event.preventDefault();
    enviarFormulario(eliminarVotacionForm, "eliminarVotacion");
  });

  actualizarVotacionForm.addEventListener("submit", function (event) {
    event.preventDefault();
    enviarFormulario(actualizarVotacionForm, "actualizarVotacion");
  });

  agregarVotoForm.addEventListener("submit", function (event) {
    event.preventDefault();
    enviarFormulario(agregarVotoForm, "agregarVoto");
  });

  function enviarFormulario(form, action) {
    const formData = new FormData(form);
    formData.append("action", action);

    fetch("../../controllers/VoteController.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        alert(data);
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }
});
