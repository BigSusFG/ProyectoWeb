document.getElementById("formRegistro").addEventListener("submit", function (evento) {
  evento.preventDefault();

  const boleta = document.getElementById("boleta").value;
  const nombre = document.getElementById("nombre").value;
  const genero = document.getElementById("genero").value;
  const curp = document.getElementById("curp").value;
  const telefono = document.getElementById("telefono").value;
  const semestre = document.getElementById("semestre").value;
  const carrera = document.getElementById("carrera").value;
  const concurso = document.getElementById("concurso").value;
  const correo = document.getElementById("correo").value;
  const contrasena = document.getElementById("contrasena").value;

  const boletaRegex = /(^(PE|PP)\d{8}$)|(^\d{10}$)/;
  const nombreRegex = /^[a-zA-ZÁÉÍÓÚÑáéíóúñ\s]+$/;
  const curpRegex = /^[A-Z]{4}\d{6}[A-Z]{6}[A-Z0-9]{2}$/;
  const telefonoRegex = /^\d{10}$/;
  const correoRegex = /^[a-z0-9]+@alumno\.ipn\.mx$/;
  const contrasenaRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  if (!boletaRegex.test(boleta)) {
    alert("Número de boleta inválido");
    return;
  }

  if (!nombreRegex.test(nombre)) {
    alert("Nombre inválido, sólo letras");
    return;
  }

  if (!curpRegex.test(curp)) {
    alert("CURP inválido");
    return;
  }

  if (!telefonoRegex.test(telefono)) {
    alert("Teléfono inválido, deben ser 10 dígitos");
    return;
  }

  if (genero == "Selecciona") {
    alert("Selecciona un género");
    return;
  }

  if (semestre == "Selecciona") {
    alert("Selecciona un semestre");
    return;
  }

  if (carrera == "Selecciona") {
    alert("Selecciona una carrera");
    return;
  }

  if (concurso == "Selecciona") {
    alert("Selecciona una opción de concurso");
    return;
  }

  if (!correoRegex.test(correo)) {
    alert("Correo institucional inválido");
    return;
  }

  if (!contrasenaRegex.test(contrasena)) {
    alert("Contraseña inválida. Debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.");
    return;
  }

  alert("Hola " + nombre + ", verifica que los datos que ingresaste sean correctos:\n" +
  "\nNo de boleta: " + boleta +
  "\nCURP: " + curp +
  "\nGénero: " + genero +
  "\nCURP: " + curp +
  "\nTeléfono: " + telefono +
  "\nSemestre: " + semestre +
  "\nCarrera: " + carrera +
  "\nConcurso: " + concurso +
  "\nCorreo: " + correo +
  "\nContraseña: " + contrasena 
);

});


document.getElementById("formularioLogin").addEventListener("submit", function (evento) {
  evento.preventDefault();

  const correoLog = document.getElementById("loginCorreo").value;
  const contrasenaLog = document.getElementById("loginPass").value;

  const correoLogRegex = /^[a-z0-9]+@alumno\.ipn\.mx$/;
  const contrasenaLogRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  if (!correoLogRegex.test(correo)) {
    alert("Correo institucional inválido");
    return;
  }

  if (!contrasenaLogRegex.test(contrasena)) {
    alert("Contraseña inválida. Debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.");
    return;
  }
});