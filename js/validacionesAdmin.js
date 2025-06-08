document.getElementById("formularioLogin").addEventListener("submit", function (evento) {
  evento.preventDefault();

  const contrasenaLog = document.getElementById("loginPass").value;

  const contrasenaLogRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  if (!contrasenaLogRegex.test(contrasenaLog)) {
    alert("Contraseña inválida. Debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.");
    return;
  }

  alert("Inicio de Sesión Éxitoso");
});