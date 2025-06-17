fetch("../participantes/obtenerPerfil.php")
  .then(res => res.json())
  .then(data => {
    if (!data.boleta) {
      alert("Error: sesión no válida");
      return;
    }

    // Ejemplo para rellenar campos
    document.getElementById("boleta").value = data.boleta;
    document.getElementById("nombre").value = data.nombre;
    document.getElementById("apPat").value = data.ap_paterno;
    document.getElementById("apMat").value = data.ap_materno;
    document.getElementById("curp").value = data.curp;
    document.getElementById("telefono").value = data.telefono;
    document.getElementById("correo").value = data.correo;
    document.getElementById("semestre").value = data.semestre;
    document.getElementById("carrera").value = data.carrera;
    document.getElementById("academia").value = data.academia;
    document.getElementById("unidadAprendizaje").innerHTML = `<option selected>${data.unidad_aprendizaje}</option>`;
    document.getElementById("horario").value = data.horario;
    document.getElementById("nombreProyecto").value = data.nombre_proyecto;
    document.getElementById("nombreEquipo").value = data.nombre_equipo;
    document.getElementById("salon").value = data.salon || "Pendiente";
    document.getElementById("fecha").value = data.fecha_expo || "";
    document.getElementById("hora").value = data.hora_expo || "";
    document.getElementById("acuse").value = data.acuse ? "Sí" : "No";
    document.getElementById("ganador").value = data.ganador ? "Sí" : "No";

    // Género radio button
    if (data.genero) {
      const radio = document.querySelector(`input[name="genero"][value="${data.genero}"]`);
      if (radio) radio.checked = true;
    }
  })
  .catch(err => {
    alert("Error al obtener los datos del participante.");
    console.error(err);
  });
