<?php
session_start();

// Verificar si hay sesión de administrador
if (!isset($_SESSION["admin"])) {
  header("Location: ../html/principal.html");
  exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "expoescom2025");
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos del administrador desde la tabla `admin`
$usuarioAdmin = $_SESSION["admin"];
$sqlAdmin = "SELECT * FROM admin WHERE usuario = ?";
$stmtAdmin = $conexion->prepare($sqlAdmin);
$stmtAdmin->bind_param("s", $usuarioAdmin);
$stmtAdmin->execute();
$resultadoAdmin = $stmtAdmin->get_result();
$admin = $resultadoAdmin->fetch_assoc();
$stmtAdmin->close();

// Definir los campos de la tabla (también usados para actualizar)
$camposTabla = [
  'boleta',
  'nombre',
  'ap_paterno',
  'ap_materno',
  'genero',
  'curp',
  'telefono',
  'semestre',
  'carrera',
  'correo',
  'contrasena',
  'academia',
  'unidad_aprendizaje',
  'horario',
  'nombre_proyecto',
  'nombre_equipo',
  'fecha_registro',
  'salon',
  'fecha_expo',
  'hora_expo',
  'puede_descargar_acuse',
  'ganador'
];

// Operaciones POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Insertar nuevo participante
  if (isset($_POST['insertar'])) {
    $valores = [];
    foreach ($camposTabla as $campo) {
      if ($campo !== 'ganador') {
        $valores[$campo] = mysqli_real_escape_string($conexion, $_POST[$campo] ?? '');
      }
    }

    $sqlInsert = "INSERT INTO participantes (
      boleta, nombre, ap_paterno, ap_materno, genero, curp, telefono,
      semestre, carrera, correo, contrasena, academia, unidad_aprendizaje,
      horario, nombre_proyecto, nombre_equipo, fecha_registro, salon,
      fecha_expo, hora_expo, puede_descargar_acuse, ganador
    ) VALUES (
      '{$valores['boleta']}', '{$valores['nombre']}', '{$valores['ap_paterno']}',
      '{$valores['ap_materno']}', '{$valores['genero']}', '{$valores['curp']}',
      '{$valores['telefono']}', '{$valores['semestre']}', '{$valores['carrera']}',
      '{$valores['correo']}', '{$valores['contrasena']}', '{$valores['academia']}',
      '{$valores['unidad_aprendizaje']}', '{$valores['horario']}',
      '{$valores['nombre_proyecto']}', '{$valores['nombre_equipo']}',
      '{$valores['fecha_registro']}', '{$valores['salon']}',
      '{$valores['fecha_expo']}', '{$valores['hora_expo']}',
      '{$valores['puede_descargar_acuse']}', 0
    )";

    mysqli_query($conexion, $sqlInsert);
  }

  // Actualizar participante individual
  if (isset($_POST['actualizar']) && isset($_POST['boleta'])) {
    $boleta = mysqli_real_escape_string($conexion, $_POST['boleta']);
    $camposActualizar = array_diff($camposTabla, ['boleta', 'ganador']);
    $updates = [];

    foreach ($camposActualizar as $campo) {
      $valor = mysqli_real_escape_string($conexion, $_POST[$campo] ?? '');
      $updates[] = "$campo = '$valor'";
    }

    $ganador = isset($_POST['ganador']) ? 1 : 0;
    $updates[] = "ganador = $ganador";

    $sqlUpdate = "UPDATE participantes SET " . implode(", ", $updates) . " WHERE boleta = '$boleta'";
    mysqli_query($conexion, $sqlUpdate);
  }

  // Marcar ganadores múltiples (modo tabla general)
  if (isset($_POST['guardar_ganadores'])) {
    mysqli_query($conexion, "UPDATE participantes SET ganador = 0");
    if (!empty($_POST['ganadores'])) {
      foreach ($_POST['ganadores'] as $boleta) {
        $boleta = mysqli_real_escape_string($conexion, $boleta);
        mysqli_query($conexion, "UPDATE participantes SET ganador = 1 WHERE boleta = '$boleta'");
      }
    }
  }

  // Eliminar participante
  if (isset($_POST['eliminar'])) {
    $boleta = mysqli_real_escape_string($conexion, $_POST['eliminar']);
    mysqli_query($conexion, "DELETE FROM participantes WHERE boleta = '$boleta'");
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Leer participantes
$sql = "SELECT * FROM participantes ORDER BY fecha_registro DESC";
$resultado = mysqli_query($conexion, $sql);
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Admin - Participantes</title>
  <link rel="icon" href="../imgs/logohifivemini.png" type="image/png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/estilos.css" />
  <link rel="stylesheet" href="../css/registro.css" />
  <link rel="stylesheet" href="../css/admin.css" />
</head>

<body class="bg-hi5-dark text-white pt-5">
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-md fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="principal.html">✋Hi-5</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav"
        aria-controls="menuNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-between" id="menuNav">
        <ul class="navbar-nav me-auto mb-2 mb-md-0">
          <li class="nav-item">
            <a class="nav-link" href="../html/principal.html">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#">Admin</a>
          </li>
        </ul>
        <div class="d-flex gap-2">
          <a href="../admin/cerrarSesion.php" class="btn btn-danger rounded-pill px-4">
            Cerrar sesión
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- LOGOS IPN Y ESCOM-->
  <div
    class="burbuja-logos position-fixed top-0 end-0 me-3 bg-white shadow rounded-pill d-flex align-items-center gap-3 px-3 py-2">
    <a href="https://www.ipn.mx/" target="_blank">
      <img src="../imgs/ipnlogo.png" alt="Logo IPN" title="IPN" class="img-fluid logo-burbuja" />
    </a>
    <a href="https://www.escom.ipn.mx/" target="_blank">
      <img src="../imgs/escudoESCOM.png" alt="Logo ESCOM" title="ESCOM" class="img-fluid logo-burbuja" />
    </a>
  </div>

  <main>
    <div class="container my-5">
      <!-- PARTICIPANTES -->
      <div class="card bg-hi5-medium p-4 rounded-4 shadow mb-5">
        <div class="card-body">
          <h2 class="text-hi5-gold mb-4">Participantes Registrados</h2>

          <?php if (mysqli_num_rows($resultado) > 0): ?>

            <div class="table-responsive rounded-4 border border-hi5-gold">
              <table class="table table-hover tabla-hi5 text-white m-0">

                <!-- TABLA DE PARTICIPANTES -->


                <form method="POST">


                  <thead class="table table-hover tabla-hi5 text-white m-0">
                    <tr>
                      <?php
                      $camposTabla = [
                        "boleta",
                        "nombre",
                        "ap_paterno",
                        "ap_materno",
                        "genero",
                        "curp",
                        "telefono",
                        "semestre",
                        "carrera",
                        "correo",
                        "academia",
                        "unidad_aprendizaje",
                        "horario",
                        "nombre_proyecto",
                        "nombre_equipo",
                        "fecha_registro",
                        "salon",
                        "fecha_expo",
                        "hora_expo",
                        "puede_descargar_acuse",
                        "ganador"
                      ];
                      foreach ($camposTabla as $campo) {
                        echo "<th>$campo</th>";
                      }
                      ?>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                      <tr>
                        <form method="POST" class="align-middle">
                          <input type="hidden" name="boleta" value="<?= htmlspecialchars($fila['boleta']) ?>">
                          <?php foreach ($camposTabla as $campo): ?>
                            <?php if ($campo === 'boleta'): ?>
                              <td><input type="text" class="form-control-plaintext text-white" readonly
                                  value="<?= htmlspecialchars($fila[$campo]) ?>"></td>
                            <?php elseif ($campo === 'ganador'): ?>
                              <td class="text-center">
                                <input type="checkbox" name="ganador" value="1" <?= $fila['ganador'] ? 'checked' : '' ?>>
                              </td>
                            <?php else: ?>
                              <td><input type="text" name="<?= $campo ?>"
                                  class="form-control form-control-sm bg-dark text-white"
                                  value="<?= htmlspecialchars($fila[$campo]) ?>"></td>
                            <?php endif; ?>
                          <?php endforeach; ?>
                          <td>
                            <div class="d-flex gap-2">
                              <button type="submit" name="actualizar" class="btn btn-sm btn-success">Guardar</button>
                              <button type="submit" name="eliminar" value="<?= $fila['boleta'] ?>"
                                class="btn btn-sm btn-danger"
                                onclick="return confirm('¿Eliminar participante?');">Eliminar</button>
                            </div>
                          </td>
                        </form>
                      </tr>
                    <?php endwhile; ?>
                  </tbody>

              </table>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
              <button type="submit" name="guardar_ganadores" class="btn btn-hi5-gold">Guardar
                Ganadores</button>
              <button type="button" class="btn btn-outline-light rounded-pill" data-bs-toggle="modal"
                data-bs-target="#modalAgregarParticipante">
                Agregar Participante
              </button>
            </div>

            </form>
          </div>
        </div>
      <?php else: ?>
        <p class="text-white-50 text-center my-4">No hay participantes registrados aún.</p>
        <div class="text-center">
          <button type="button" class="btn btn-outline-light rounded-pill" data-bs-toggle="modal"
            data-bs-target="#modalAgregarParticipante">
            Agregar Primer Participante
          </button>
        </div>
      <?php endif; ?>
  </main>

  <!-- FOOTER -->
  <footer class="py-4 text-center text-md-start bg-hi5-light text-white">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-10">
          <p class="mb-1 small">
            © 2025 Hi-5 ESCOM. Todos los derechos reservados.
          </p>
        </div>
        <div class="col-md-2 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
          <a href="#">
            <img src="../imgs/logohifive.png" alt="Logo Hi-5" class="logo-footer">
          </a>
        </div>
      </div>
    </div>
  </footer>

  <!-- MODAL: Agregar nuevo participante -->
  <div class="modal fade" id="modalAgregarParticipante" tabindex="-1" aria-labelledby="modalAgregarLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content bg-hi5-medium text-white rounded-4">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalAgregarLabel">Agregar Nuevo Participante</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">

          <!-- FORMULARIO NUEVO PARTICIPANTE -->
          <section class="py-5">
            <div class="container">
              <div class="row justify-content-center">
                <div class="col-lg-10">
                  <div class="bg-hi5-medium p-4 rounded-4 shadow-hi5 transition-hover">
                    <div class="text-center mb-4">
                      <h2 class="fw-bold text-white">Crear cuenta</h2>
                      <p>Completa tu información para registrarte al evento</p>
                    </div>
                    <form id="formRegistro">
                      <h5 class="text-start text-white mb-3">Datos personales</h5>
                      <div class="row g-3 mb-4">
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="boleta" name="boleta"
                              placeholder="Número de boleta" required />
                            <label for="boleta">Número de boleta</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="nombre" name="nombre"
                              placeholder="Nombre" required />
                            <label for="nombre">Nombre (s)</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="apPat" name="apPat"
                              placeholder="Apellido Paterno" required />
                            <label for="apPat">Apellido Paterno</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="apMat" name="apMat"
                              placeholder="Apellido Materno" required />
                            <label for="apMat">Apellido Materno</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="curp" name="curp"
                              placeholder="CURP" required />
                            <label for="curp">CURP</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <select class="form-select form-control-dark" id="semestre" name="semestre" required>
                              <option selected disabled>Selecciona</option>
                              <option value="1">1</option>
                              <option value="2">2</option>
                              <option value="3">3</option>
                              <option value="4">4</option>
                              <option value="5">5</option>
                              <option value="6">6</option>
                              <option value="7">7</option>
                              <option value="8">8</option>
                            </select>
                            <label for="semestre">Semestre</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <select class="form-select form-control-dark" id="carrera" name="carrera" required>
                              <option selected disabled>Selecciona</option>
                              <option value="ISC">ISC</option>
                              <option value="LCD">LCD</option>
                              <option value="IA">IA</option>
                            </select>
                            <label for="carrera">Carrera</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="telefono" name="telefono"
                              placeholder="Teléfono" required />
                            <label for="telefono">Teléfono</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-control form-control-dark bg-hi5-medium rounded-4 shadow-hi5 py-3">
                            <label class="form-label text-white d-block mb-2">Género</label>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="genero" id="masculino"
                                value="Masculino" required />
                              Masculino
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="genero" id="femenino"
                                value="Femenino" />
                              Femenino
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="genero" id="otro" value="Otro" />
                              Otro
                            </div>
                          </div>
                        </div>
                      </div>
                      <h5 class="text-start text-white mb-3 mt-4">
                        Datos de cuenta
                      </h5>
                      <div class="row g-3">
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="email" class="form-control form-control-dark" id="correo" name="correo"
                              placeholder="Correo institucional" required />
                            <label for="correo">Correo institucional</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="password" class="form-control form-control-dark" id="contrasena"
                              name="contrasena" placeholder="Contraseña" required />
                            <label for="contrasena">Contraseña</label>
                          </div>
                        </div>
                      </div>
                      <h5 class="text-start text-white mb-3 mt-4">
                        Datos del concurso
                      </h5>
                      <div class="row g-3 mb-4">
                        <div class="col-md-6">
                          <div class="form-floating">
                            <select class="form-select form-control-dark" id="academia" name="academia" required>
                              <option disabled selected>Selecciona</option>
                              <option value="Ciencia de Datos">
                                Ciencia de Datos
                              </option>
                              <option value="Ciencias Básicas">
                                Ciencias Básicas
                              </option>
                              <option value="Ciencias de la Computación">
                                Ciencias de la Computación
                              </option>
                              <option value="Ciencias Sociales">
                                Ciencias Sociales
                              </option>
                              <option value="Fundamentos de Sistemas Electrónicos">
                                Fundamentos de Sistemas Electrónicos
                              </option>
                              <option value="Ingeniería de Software">
                                Ingeniería de Software
                              </option>
                              <option value="Inteligencia Artificial">
                                Inteligencia Artificial
                              </option>
                              <option value="Proyectos Estratégicos para la Toma de Decisiones">
                                Proyectos Estratégicos para la Toma de Decisiones
                              </option>
                              <option value="Sistemas Digitales">
                                Sistemas Digitales
                              </option>
                              <option value="Sistemas Distribuidos">
                                Sistemas Distribuidos
                              </option>
                            </select>
                            <label for="academia">Academia</label>
                          </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                          <div class="form-floating flex-grow-1 position-relative me-2" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Primero selecciona una academia">
                            <select class="form-select form-control-dark" id="unidadAprendizaje"
                              name="unidadAprendizaje" required>
                              <option disabled selected>
                                Selecciona una unidad
                              </option>
                            </select>
                            <label for="unidadAprendizaje">Unidad de aprendizaje</label>
                          </div>
                          <button type="button" class="btn btn-info-circle btn-white" data-bs-toggle="modal"
                            data-bs-target="#modalUnidades">
                            ⓘ
                          </button>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <select class="form-select form-control-dark" id="horario" name="horario" required>
                              <option disabled selected>Selecciona</option>
                              <option value="matutino">Matutino</option>
                              <option value="vespertino">Vespertino</option>
                            </select>
                            <label for="horario">Horario preferente</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="nombreProyecto"
                              name="nombreProyecto" placeholder="Nombre del proyecto" required />
                            <label for="nombreProyecto">Nombre del proyecto</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-floating">
                            <input type="text" class="form-control form-control-dark" id="nombreEquipo"
                              name="nombreEquipo" placeholder="Nombre del equipo" required />
                            <label for="nombreEquipo">Nombre del equipo</label>
                          </div>
                        </div>
                      </div>

                      <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-hi5-gold px-4 rounded-pill">
                          Registrarse
                        </button>
                        <button type="reset" class="btn btn-outline-danger px-4 rounded-pill">
                          Borrar
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </section>

        </div>
      </div>
    </div>
  </div>


  <!-- Modal de Academias y UA -->
  <div class="modal fade" id="modalUnidades" tabindex="-1" aria-labelledby="modalUnidadesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content bg-hi5-medium text-white modal-content-ajustada">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="modalUnidadesLabel">
            Unidades de Aprendizaje por Academia
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body p-3 d-flex flex-column align-items-center">
          <p class="text-center mb-3">
            Para una mejor orientación, consulta las academias y las unidades
            de aprendizaje disponibles en las siguientes diapositivas.
          </p>
          <div id="carruselUA" class="carousel slide carrusel-ajustado" data-bs-ride="carousel">
            <div class="carousel-inner rounded-3 shadow">
              <div class="carousel-item active text-center">
                <img src="../imgs/academias1.jpg" class="img-fluid d-block mx-auto img-carrusel" alt="Academias UA 1">
              </div>
              <div class="carousel-item text-center">
                <img src="../imgs/academias2.jpg" class="img-fluid d-block mx-auto img-carrusel" alt="Academias UA 2">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carruselUA" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carruselUA" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Siguiente</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="../js/selectorDeUA.js"></script>
</body>

</html>