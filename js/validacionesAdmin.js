document.getElementById("formularioLogin").addEventListener("submit", function (evento) {
  const contrasenaLog = document.getElementById("loginPass").value;

  const contrasenaLogRegex = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  if (!contrasenaLogRegex.test(contrasenaLog)) {
    evento.preventDefault(); 
    alert("Contraseña inválida. Debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.");
  }
});
