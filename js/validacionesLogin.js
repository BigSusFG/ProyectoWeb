document.getElementById("formularioLogin").addEventListener("submit", function (evento) {
  evento.preventDefault();

  const correoLog = document.getElementById("loginCorreo").value;
  const contrasenaLog = document.getElementById("loginPass").value;

  const correoLogRegex = /^[a-z0-9]+@alumno\.ipn\.mx$/;
  const contrasenaLogRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  if (!correoLogRegex.test(correoLog)) {
    alert("Correo institucional inválido");
    return;
  }

  if (!contrasenaLogRegex.test(contrasenaLog)) {
    alert("Contraseña inválida. Debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.");
    return;
  }

  alert("Inicio de Sesión Éxitoso");
});