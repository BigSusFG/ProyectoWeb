document.getElementById("formRegistro").addEventListener("submit", function (evento) {
  evento.preventDefault();

  const boleta = document.getElementById("boleta").value.trim();
  const nombre = document.getElementById("nombre").value.trim();
  const apPat = document.getElementById("apPat").value.trim();
  const apMat = document.getElementById("apMat").value.trim();
  const genero = document.querySelector('input[name="genero"]:checked');
  const curp = document.getElementById("curp").value.trim();
  const telefono = document.getElementById("telefono").value.trim();
  const semestre = document.getElementById("semestre").value;
  const carrera = document.getElementById("carrera").value;
  const academia = document.getElementById("academia").value;
  const unidadAprendizaje = document.getElementById("unidadAprendizaje").value;
  const horario = document.getElementById("horario").value;
  const nombreProyecto = document.getElementById("nombreProyecto").value.trim();
  const nombreEquipo = document.getElementById("nombreEquipo").value.trim();
  const correo = document.getElementById("correo").value.trim();
  const contrasena = document.getElementById("contrasena").value;

  const boletaRegex = /(^(PE|PP)\d{8}$)|(^\d{10}$)/;
  const nombreRegex = /^[a-zA-ZÁÉÍÓÚÑáéíóúñ\s]+$/;
  const curpRegex = /^[A-Z]{4}\d{6}[A-Z]{6}[A-Z0-9]{2}$/;
  const telefonoRegex = /^\d{10}$/;
  const correoRegex = /^[a-z0-9]+@alumno\.ipn\.mx$/;
  const contrasenaRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

   // Validaciones
  if (!boletaRegex.test(boleta)) return alert("Número de boleta inválido");
  if (!nombreRegex.test(nombre)) return alert("Nombre inválido, sólo letras");
  if (!nombreRegex.test(apPat)) return alert("Apellido paterno inválido, sólo letras");
  if (!nombreRegex.test(apMat)) return alert("Apellido materno inválido, sólo letras");
  if (!genero) return alert("Selecciona un género");
  if (!curpRegex.test(curp)) return alert("CURP inválido");
  if (!telefonoRegex.test(telefono)) return alert("Teléfono inválido, deben ser 10 dígitos");
  if (semestre === "Selecciona") return alert("Selecciona un semestre");
  if (carrera === "Selecciona") return alert("Selecciona una carrera");
  if (academia === "Selecciona") return alert("Selecciona una academia");
  if (unidadAprendizaje === "Selecciona una unidad") return alert("Selecciona una unidad de aprendizaje");
  if (horario === "Selecciona") return alert("Selecciona un horario");
  if (nombreProyecto === "") return alert("Ingresa el nombre del proyecto");
  if (nombreEquipo === "") return alert("Ingresa el nombre del equipo");
  if (!correoRegex.test(correo)) return alert("Correo institucional inválido");
  if (!contrasenaRegex.test(contrasena)) {
    return alert("Contraseña inválida. Debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.");
  }

// Saludo
document.getElementById("saludoConfirmacion").textContent =
  `Hola ${nombre}, verifica que los datos que ingresaste sean correctos:`;

// Crear la lista de datos
const lista = document.getElementById("listaDatosConfirmacion");
lista.innerHTML = `
  <li class="bg-hi5-light box-seccion">
    <strong class="d-block mb-2">Datos personales</strong>
    <ul class="mb-0 list-unstyled">
      <li><strong>No. de Boleta:</strong> ${boleta}</li>
      <li><strong>CURP:</strong> ${curp}</li>
      <li><strong>Género:</strong> ${genero.value}</li>
      <li><strong>Teléfono:</strong> ${telefono}</li>
      <li><strong>Semestre:</strong> ${semestre}</li>
      <li><strong>Carrera:</strong> ${carrera}</li>
    </ul>
  </li>

  <li class="bg-hi5-light box-seccion">
    <strong class="d-block mb-2">Datos de cuenta</strong>
    <ul class="mb-0 list-unstyled">
      <li><strong>Correo:</strong> ${correo}</li>
    </ul>
  </li>

  <li class="bg-hi5-light box-seccion">
    <strong class="d-block mb-2">Datos del concurso</strong>
    <ul class="mb-0 list-unstyled">
      <li><strong>Academia:</strong> ${academia}</li>
      <li><strong>Unidad de Aprendizaje:</strong> ${unidadAprendizaje}</li>
      <li><strong>Horario:</strong> ${horario}</li>
      <li><strong>Nombre del Proyecto:</strong> ${nombreProyecto}</li>
      <li><strong>Nombre del Equipo:</strong> ${nombreEquipo}</li>
    </ul>
  </li>
`;

// Mostrar el modal
const modal = new bootstrap.Modal(document.getElementById("modalConfirmacion"));
modal.show();

// Acción al hacer clic en Aceptar
document.getElementById("btnAceptar").onclick = function () {
  modal.hide();

  // Crear un objeto con los datos
  const datos = {
    boleta,
    nombre,
    apPat,
    apMat,
    genero: genero.value,
    curp,
    telefono,
    semestre,
    carrera,
    academia,
    unidadAprendizaje,
    horario,
    nombreProyecto,
    nombreEquipo,
    correo,
    contrasena,
  };

  // Enviar al servidor
  fetch("participantes/registrarParticipante.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams(datos),
  })
    .then((response) => {
      if (response.redirected) {
        window.location.href = response.url;
      } else {
        return response.text();
      }
    })
    .then((data) => {
      if (data && data.includes("error:boleta_duplicada")) {
        alert("⚠️ Ya existe un registro con esta boleta. Verifica tus datos.");
      } else if (data && !data.startsWith("http")) {
        alert("Respuesta del servidor:\n" + data);
      }
    })
    .catch((error) => {
      alert("Ocurrió un error al registrar. Revisa tu conexión o el servidor.");
      console.error(error);
    });
};
});