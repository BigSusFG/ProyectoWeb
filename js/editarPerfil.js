document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formPerfil");
  const btnEditar = document.getElementById("btnEditar");
  const btnGuardar = document.getElementById("btnGuardar");
  const btnEliminar = document.getElementById("btnEliminar");
  const seccionEstadoAdmin = document.getElementById("seccionEstadoAdmin");
  const campoGanador = document.getElementById("ganador");

  const camposRestringidos = ["boleta", "salon", "hora", "fecha", "acuse", "ganador"];
  const campos = form.querySelectorAll("input, select");
  const valoresOriginales = {};

  campos.forEach(campo => {
    valoresOriginales[campo.id] = campo.value;
  });

  const evaluarSeccionEstadoAdmin = () => {
    if (campoGanador && campoGanador.value === "1") {
      seccionEstadoAdmin.classList.remove("d-none");
    } else {
      seccionEstadoAdmin.classList.add("d-none");
    }
  };

  const toggleEditMode = (modoEdicion) => {
    campos.forEach(campo => {
      const esBloqueado = camposRestringidos.includes(campo.id);
      campo.disabled = !modoEdicion || esBloqueado;

      if (modoEdicion && esBloqueado) {
        campo.classList.add("campo-bloqueado");
      } else {
        campo.classList.remove("campo-bloqueado");
      }
    });

    if (modoEdicion) {
      btnGuardar.classList.remove("d-none");
      btnEditar.textContent = "Cancelar";
      btnEditar.classList.remove("btn-outline-light");
      btnEditar.classList.add("btn-outline-secondary");
    } else {
      btnGuardar.classList.add("d-none");
      btnEditar.textContent = "Ajustar cuenta";
      btnEditar.classList.add("btn-outline-light");
      btnEditar.classList.remove("btn-outline-secondary");

      campos.forEach(campo => {
        if (valoresOriginales[campo.id] !== undefined) {
          campo.value = valoresOriginales[campo.id];
        }
      });

      evaluarSeccionEstadoAdmin();
    }
  };

  let modoEdicion = false;

  btnEditar.addEventListener("click", () => {
    modoEdicion = !modoEdicion;
    toggleEditMode(modoEdicion);
  });

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    console.log("Datos modificados enviados");

    campos.forEach(campo => {
      valoresOriginales[campo.id] = campo.value;
    });

    modoEdicion = false;
    toggleEditMode(modoEdicion);
  });

  btnEliminar.addEventListener("click", () => {
    const modal = new bootstrap.Modal(document.getElementById("modalConfirmarEliminar"));
    modal.show();
  });

  const btnConfirmarEliminar = document.getElementById("btnConfirmarEliminar");
  btnConfirmarEliminar.addEventListener("click", () => {
    confirmarEliminar();
  });

  function confirmarEliminar() {
    console.log("Cuenta eliminada");
    // Aquí va el fetch o redirección personalizada
  }

  evaluarSeccionEstadoAdmin();
  toggleEditMode(false);
});
