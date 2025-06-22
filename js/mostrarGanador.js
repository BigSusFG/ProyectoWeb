document.addEventListener("DOMContentLoaded", () => {
  const seccionEstadoAdmin = document.getElementById("seccionEstadoAdmin");
  const valorGanador = document.getElementById("valorGanador");

  if (!seccionEstadoAdmin || !valorGanador) return;

  const texto = valorGanador.textContent.trim().toLowerCase();

  if (texto.includes("sí")) {
    seccionEstadoAdmin.classList.remove("d-none");
  } else {
    seccionEstadoAdmin.classList.add("d-none");
  }
});