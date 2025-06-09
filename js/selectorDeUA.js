  const unidadesPorAcademia = {
    "Sistemas Distribuidos": [
      "Administración de Servicios en Red",
      "Aplicaciones para Comunicaciones en Red",
      "Ciberseguridad",
      "Computer Security",
      "Cómputo de Alto Desempeño",
      "Cómputo en la Nube",
      "Cómputo Paralelo",
      "Cryptography",
      "Desarrollo de Sistemas Distribuidos",
      "Protección de Datos",
      "Redes de Computadoras",
      "Sistemas Distribuidos",
      "Sistemas Operativos",
      "Teoría de Comunicaciones y Señales"
    ],
    "Ciencias de la Computación": [
      "Algoritmos Bioinspirados",
      "Algoritmos y Estructuras de Datos",
      "Análisis y Diseño de Algoritmos",
      "Bioinformática Básica",
      "Bioinformes",
      "Compiladores",
      "Sistemas Complejos",
      "Gráficos por Computadora",
      "Fundamentos de Programación",
      "Algoritmos Genéticos",
      "Introducción a la Criptografía",
      "Métodos Numéricos",
      "Paradigmas de Programación",
      "Tópicos Selectos de Criptografía",
      "Tópicos Selectos de Algoritmos Bioinspirados",
      "Realidad Virtual y Aumentada"
    ],
    "Ciencias Sociales": [
      "Comunicación Oral y Escrita",
      "Desarrollo de Habilidades Sociales",
      "Ética y Legalidad",
      "Ingeniero y Sociedad",
      "Liderazgo Personal",
      "Metodología de la Investigación y Divulgación Científica"
    ],
    "Ingeniería de Software": [
      "Análisis y Diseño de Sistemas",
      "Bases de Datos",
      "Desarrollo de Aplicaciones Móviles Nativas",
      "Desarrollo de Aplicaciones Web",
      "Ingeniería de Software",
      "Aseguramiento de Calidad y Patrones de Diseño",
      "Tecnologías para el Desarrollo de Aplicaciones Web",
      "Desarrollo de Aplicaciones Web",
      "Frameworks para el Desarrollo Web"
    ],
    "Ciencias Básicas": [
      "Álgebra Lineal",
      "Análisis Vectorial",
      "Cálculo",
      "Cálculo Aplicado",
      "Cálculo Multivariable",
      "Ingeniería Económica",
      "Ecuaciones Diferenciales",
      "Estadística",
      "Matemáticas Avanzadas para la Ingeniería",
      "Matemáticas Discretas",
      "Mecánica y Electromagnetismo",
      "Probabilidad",
      "Probabilidad y Estadística",
      "Herramientas Estadísticas para Ciencia de Datos"
    ],
    "Ciencia de Datos": [
      "Análisis de Series de Tiempo",
      "Analítica Avanzada de Datos",
      "Analítica y Visualización de Datos",
      "Bases de Datos Avanzadas",
      "Big Data",
      "Minería de Datos",
      "Desarrollo de Aplicaciones para Análisis de Datos",
      "Introducción a la Ciencia de Datos",
      "Modelado Predictivo",
      "Modelos Econométricos",
      "Procesos Estocásticos",
      "Programación para la Ciencia de Datos"
    ],
    "Fundamentos de Sistemas Electrónicos": [
      "Circuitos Eléctricos",
      "Electrónica Analógica",
      "Instrumentación",
      "Instrumentación y Control"
    ],
    "Inteligencia Artificial": [
      "Aplicaciones de Lenguaje Natural",
      "Aprendizaje de Máquina",
      "Fundamentos de Inteligencia Artificial",
      "Análisis de Imágenes",
      "Machine Learning",
      "Procesamiento de Lenguaje Natural",
      "Reconocimiento de Voz",
      "Redes Neuronales",
      "Temas Selectos de IA",
      "Visión Artificial"
    ],
    "Proyectos Estratégicos para la Toma de Decisiones": [
      "Administración de Proyectos de TI",
      "Finanzas Empresariales",
      "Formulación y Evaluación de Proyectos",
      "Fundamentos Económicos",
      "Gestión Empresarial",
      "Alta Tecnología Empresarial",
      "Innovación y Emprendimiento Tecnológico",
      "Gobierno de TI",
      "Métodos Cuantitativos para la Toma de Decisiones"
    ],
    "Sistemas Digitales": [
      "Arquitectura de Computadoras",
      "Diseño de Sistemas Digitales",
      "Sistemas Embebidos",
      "Fundamentos de Diseño Digital",
      "Internet de las Cosas",
      "Microcontroladores",
      "Procesamiento Digital de Señales",
      "Sistemas en Chip"
    ]
  };

  const selectorAcademia = document.getElementById("academia");
  const selectorUnidad = document.getElementById("unidadAprendizaje");

  selectorAcademia.addEventListener("change", function () {
    const academiaSeleccionada = this.value;
    const listaUnidades = unidadesPorAcademia[academiaSeleccionada] || [];

    selectorUnidad.innerHTML = '<option disabled selected>Selecciona una unidad</option>';

    listaUnidades.forEach(unidad => {
      const opcion = document.createElement("option");
      opcion.value = unidad;
      opcion.textContent = unidad;
      selectorUnidad.appendChild(opcion);
    });
  });



  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

