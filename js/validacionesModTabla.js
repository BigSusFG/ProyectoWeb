
document.addEventListener("DOMContentLoaded", () => {
  /* ---------- Expresiones RegEx reutilizadas ---------- */
  /*  \p{L} => cualquier letra;  \p{M} => marca diacrítica   */
  const nombreRE = /^[\p{L}\p{M}\s.'-]+$/u;
  const curpRE   = /^[A-Z]{4}\d{6}[A-Z]{6}[A-Z0-9]{2}$/;
  const telRE    = /^\d{10}$/;
  const correoRE = /^[a-z0-9]+@alumno\.ipn\.mx$/;
  const passRE   = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  /* ---------- Normalizador ---------- */
  const norm = str =>
    str                                       // entrada original
      .normalize("NFC")                       // compón Unicode
      .replace(/\s+/g, " ")                   // un solo espacio
      .trim();

  /* ---------- Validación por cada fila ---------- */
  document
    .querySelectorAll("table.tabla-hi5 form")
    .forEach(form => {
      form.addEventListener("submit", ev => {
        /* Sólo si el botón fue “Guardar” */
        const btn = document.activeElement;
        if (!btn || btn.name !== "actualizar") return;

        /* Helpers */
        const valor = name =>
          norm(form.querySelector(`[name='${name}']`)?.value || "");

        const datos = {
          nombre            : valor("nombre"),
          ap_paterno        : valor("ap_paterno"),
          ap_materno        : valor("ap_materno"),
          genero            : form.querySelector("input[name='genero']:checked")?.value || "",
          curp              : valor("curp").toUpperCase(),
          telefono          : valor("telefono"),
          semestre          : valor("semestre"),
          carrera           : valor("carrera"),
          academia          : valor("academia"),
          unidadAprendizaje : valor("unidad_aprendizaje"),
          horario           : valor("horario"),
          nombreProyecto    : valor("nombre_proyecto"),
          nombreEquipo      : valor("nombre_equipo"),
          correo            : valor("correo").toLowerCase(),
          contrasena        : valor("contrasena")  // puede ir vacío
        };

        /* Detectar placeholder en UA (hay dos posibles textos) */
        const uaPlaceholders = ["Selecciona", "Selecciona una unidad"];
        const uaSinSeleccionar = uaPlaceholders.includes(datos.unidadAprendizaje);

        /* ---------- Cadena de validaciones ---------- */
        const error =
          !nombreRE.test(datos.nombre)              ? "Nombre inválido."                                 :
          !nombreRE.test(datos.ap_paterno)          ? "Apellido paterno inválido."                       :
          !nombreRE.test(datos.ap_materno)          ? "Apellido materno inválido."                       :
          !datos.genero                             ? "Selecciona género."                               :
          !curpRE.test(datos.curp)                  ? "CURP inválido."                                   :
          !telRE.test(datos.telefono)               ? "Teléfono inválido (10 dígitos)."                  :
          datos.semestre === "Selecciona"           ? "Selecciona semestre."                             :
          datos.carrera  === "Selecciona"           ? "Selecciona carrera."                              :
          datos.academia === "Selecciona"           ? "Selecciona academia."                             :
          uaSinSeleccionar                          ? "Selecciona Unidad de Aprendizaje."                :
          datos.horario  === "Selecciona"           ? "Selecciona horario."                              :
          datos.nombreProyecto === ""               ? "Escribe el nombre del proyecto."                  :
          datos.nombreEquipo   === ""               ? "Escribe el nombre del equipo."                    :
          !correoRE.test(datos.correo)              ? "Correo institucional inválido."                   :
          (datos.contrasena && !passRE.test(datos.contrasena))
                                                    ? "Contraseña inválida (mín. 6 car., 1 mayús., 1 número y 1 especial)."
                                                    : "";

        if (error) {
          ev.preventDefault();
          alert(error);
          return;
        }

        /* Confirmación final */
        if (!confirm("¿Estás seguro de guardar los cambios?")) {
          ev.preventDefault();
        }
      });
    });
});
