document.addEventListener("DOMContentLoaded", () => {
  /* --- Expresiones reutilizadas --- */
  const nombreRE  = /^[a-zA-ZÁÉÍÓÚÑáéíóúñ\s]+$/;
  const curpRE    = /^[A-Z]{4}\d{6}[A-Z]{6}[A-Z0-9]{2}$/;
  const telRE     = /^\d{10}$/;
  const correoRE  = /^[a-z0-9]+@alumno\.ipn\.mx$/;
  const passRE    = /^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{6,}$/;

  /* --- Cada formulario incrustado en la tabla --- */
  document
    .querySelectorAll("table.tabla-hi5 form")
    .forEach(form => {
      form.addEventListener("submit", ev => {
        /* Solo validar si el botón que dispara es “Guardar” */
        const btn = document.activeElement;
        if (!btn || btn.name !== "actualizar") return;

        /* --- Helper para leer valores --- */
        const valor = name =>
          form.querySelector(`[name='${name}']`)?.value.trim() ?? "";

        /* --- Recopilar datos editables --- */
        const datos = {
          nombre            : valor("nombre"),
          ap_paterno        : valor("ap_paterno"),
          ap_materno        : valor("ap_materno"),
          genero            : form.querySelector("input[name='genero']:checked")?.value ?? "",
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
          contrasena        : valor("contrasena")        // puede ir vacío
        };

        /* --- Validaciones --- */
        const error =
          !nombreRE.test(datos.nombre)          ? "Nombre inválido."                                :
          !nombreRE.test(datos.ap_paterno)      ? "Apellido paterno inválido."                      :
          !nombreRE.test(datos.ap_materno)      ? "Apellido materno inválido."                      :
          !datos.genero                         ? "Selecciona género."                              :
          !curpRE.test(datos.curp)              ? "CURP inválido."                                  :
          !telRE.test(datos.telefono)           ? "Teléfono inválido (10 dígitos)."                 :
          datos.semestre === "Selecciona"       ? "Selecciona semestre."                            :
          datos.carrera  === "Selecciona"       ? "Selecciona carrera."                             :
          datos.academia === "Selecciona"       ? "Selecciona academia."                            :
          datos.unidadAprendizaje === "Selecciona"
                                                ? "Selecciona Unidad de Aprendizaje."               :
          datos.horario  === "Selecciona"       ? "Selecciona horario."                             :
          datos.nombreProyecto === ""           ? "Escribe el nombre del proyecto."                 :
          datos.nombreEquipo   === ""           ? "Escribe el nombre del equipo."                   :
          !correoRE.test(datos.correo)          ? "Correo institucional inválido."                  :
          (datos.contrasena && !passRE.test(datos.contrasena))
                                                ? "Contraseña inválida (mín. 6 car., 1 mayús., 1 número, 1 especial)."
                                                : "";

        if (error) {
          ev.preventDefault();
          return alert(error);
        }

        /* Confirmación final */
        if (!confirm("¿Estás seguro de guardar los cambios?")) {
          ev.preventDefault();
        }
      });
    });
});
