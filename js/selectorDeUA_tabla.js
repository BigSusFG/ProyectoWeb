/* selectorDeUA_tabla.js
 * Rellena el <select name="unidad_aprendizaje"> de cada fila
 * según la academia elegida.
 */

/* ---------- catálogo ---------- */
const UA_POR_ACADEMIA = {
  "Ciencia de Datos": [
    "Minería de Datos", "Visualización de Datos", "Big Data", "Analítica Predictiva"
  ],
  "Ciencias Básicas": [
    "Álgebra Lineal", "Cálculo Vectorial", "Ecuaciones Diferenciales", "Probabilidad"
  ],
  "Ciencias de la Computación": [
    "Estructuras de Datos", "Compiladores", "Teoría de Autómatas", "Programación Avanzada"
  ],
  "Ciencias Sociales": [
    "Ética Profesional", "Sociedad y Tecnología", "Metodología de la Investigación"
  ],
  "Fundamentos de Sistemas Electrónicos": [
    "Circuitos Digitales", "Electrónica Analógica", "Señales y Sistemas"
  ],
  "Ingeniería de Software": [
    "Análisis y Diseño de Sistemas", "Pruebas de Software", "Arquitectura de Software"
  ],
  "Inteligencia Artificial": [
    "Aprendizaje Automático", "Sistemas Expertos", "Visión por Computadora", "NLP"
  ],
  "Proyectos Estratégicos para la Toma de Decisiones": [
    "Planeación Estratégica", "Administración de Proyectos", "Gestión del Cambio"
  ],
  "Sistemas Digitales": [
    "Diseño de FPGA", "Microcontroladores", "Interfases Digitales"
  ],
  "Sistemas Distribuidos": [
    "Computación en la Nube", "Comunicación de Procesos", "Seguridad en Redes"
  ]
};

/* ---------- helpers ---------- */
function poblarSelect(selectEl, opciones, valorActual = "") {
  selectEl.innerHTML = "";                          // limpia anteriores
  // añade placeholder
  const ph = document.createElement("option");
  ph.disabled = true;
  ph.selected = !valorActual;                       // si no hay valor, placeholder selected
  ph.textContent = "Selecciona";
  selectEl.appendChild(ph);

  opciones.forEach(op => {
    const option = document.createElement("option");
    option.value = option.textContent = op;
    if (op === valorActual) option.selected = true; // marca la que ya estaba guardada
    selectEl.appendChild(option);
  });

  // si valor guardado no está en catálogo → déjalo como primera opción
  if (valorActual && !opciones.includes(valorActual)) {
    const extra = document.createElement("option");
    extra.value = extra.textContent = valorActual;
    extra.selected = true;
    selectEl.insertBefore(extra, selectEl.firstChild);
  }
}

/* ---------- lógica por fila ---------- */
function sincronizarFila(selectAcademia) {
  const fila       = selectAcademia.closest("tr");
  const selectUA   = fila.querySelector('select[name="unidad_aprendizaje"]');
  if (!selectUA) return;

  const academia = selectAcademia.value;
  poblarSelect(selectUA, UA_POR_ACADEMIA[academia] || [], selectUA.value);
}

/* ---------- al cargar ---------- */
document.addEventListener("DOMContentLoaded", () => {
  const selectsAcademia = document.querySelectorAll(
    'table.tabla-hi5 select[name="academia"]'
  );

  selectsAcademia.forEach(sel => {
    sincronizarFila(sel);                 // inicial
    sel.addEventListener("change", () => sincronizarFila(sel));
  });
});
