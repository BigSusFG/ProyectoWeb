<?php
session_start();

$servidor = "localhost";
$usuarioBD = "root";
$passBD = "";
$bd = "expoescom2025";

$conexion = mysqli_connect($servidor, $usuarioBD, $passBD, $bd);
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertar
    if (isset($_POST['insertar'])) {
        $campos = [
            'boleta', 'nombre', 'ap_paterno', 'ap_materno', 'genero', 'curp', 'telefono',
            'semestre', 'carrera', 'correo', 'contrasena', 'academia', 'unidad_aprendizaje',
            'horario', 'nombre_proyecto', 'nombre_equipo', 'fecha_registro', 'salon',
            'fecha_expo', 'hora_expo', 'puede_descargar_acuse'
        ];

        $valores = [];
        foreach ($campos as $campo) {
            $valores[$campo] = mysqli_real_escape_string($conexion, $_POST[$campo]);
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

    // Marcar ganadores
    if (isset($_POST['guardar_ganadores'])) {
        mysqli_query($conexion, "UPDATE participantes SET ganador = 0");
        if (!empty($_POST['ganadores'])) {
            foreach ($_POST['ganadores'] as $boleta) {
                $boleta = mysqli_real_escape_string($conexion, $boleta);
                mysqli_query($conexion, "UPDATE participantes SET ganador = 1 WHERE boleta = '$boleta'");
            }
        }
    }

    // Eliminar
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
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      crossorigin="anonymous"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../css/estilos.css" />
    <link rel="stylesheet" href="../css/registro.css" />
  </head>
  <body class="bg-hi5-dark text-white pt-5">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-md fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="principal.html">✋Hi-5</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#menuNav"
          aria-controls="menuNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div
          class="collapse navbar-collapse justify-content-between"
          id="menuNav"
        >
          <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link" href="../html/principal.html">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="#">Admin</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- LOGOS IPN Y ESCOM-->
    <div
      class="burbuja-logos position-fixed top-0 end-0 me-3 bg-white shadow rounded-pill d-flex align-items-center gap-3 px-3 py-2"
    >
      <a href="https://www.ipn.mx/" target="_blank">
        <img
          src="../imgs/ipnlogo.png"
          alt="Logo IPN"
          title="IPN"
          class="img-fluid logo-burbuja"
        />
      </a>
      <a href="https://www.escom.ipn.mx/" target="_blank">
        <img
          src="../imgs/escudoESCOM.png"
          alt="Logo ESCOM"
          title="ESCOM"
          class="img-fluid logo-burbuja"
        />
      </a>
    </div>
    
    <main>
      <div class="container my-5">
        <!-- TABLA DE PARTICIPANTES -->
        <div class="card bg-hi5-medium p-4 rounded-4 shadow mb-5">
          <div class="card-body">
            <h2 class="text-hi5-gold mb-4">Participantes Registrados</h2>
            <form method="POST">
              <div class="table-responsive">
                <table class="table table-dark table-striped table-hover">
                  <thead>
                    <tr>
                      <?php
                      $camposTabla = [
                        "boleta", "nombre", "ap_paterno", "ap_materno", "genero", "curp", "telefono",
                        "semestre", "carrera", "correo", "academia", "unidad_aprendizaje",
                        "horario", "nombre_proyecto", "nombre_equipo", "fecha_registro", "salon",
                        "fecha_expo", "hora_expo", "puede_descargar_acuse", "ganador"
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
                      <?php foreach ($camposTabla as $campo): ?>
                        <td><?= htmlspecialchars($fila[$campo]) ?></td>
                      <?php endforeach; ?>
                      <td>
                        <div class="d-flex gap-2">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="ganadores[]" 
                                   value="<?= $fila['boleta'] ?>" <?= $fila['ganador'] == 1 ? 'checked' : '' ?>>
                          </div>
                          <form method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar participante?');">
                            <input type="hidden" name="eliminar" value="<?= $fila['boleta'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
              <div class="d-flex justify-content-end mt-3">
                <button type="submit" name="guardar_ganadores" class="btn btn-hi5-gold">Guardar Ganadores</button>
              </div>
            </form>
          </div>
        </div>

        <!-- FORMULARIO NUEVO PARTICIPANTE -->
        <div class="card bg-hi5-medium p-4 rounded-4 shadow">
          <div class="card-body">
            <h2 class="text-hi5-gold mb-4">Agregar Nuevo Participante</h2>
            <form method="POST" class="row g-3">
              <input type="hidden" name="insertar" value="1">
              
              <!-- Datos Personales -->
              <h5 class="text-hi5-gold mb-3">Datos personales</h5>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="boleta" name="boleta" required>
                  <label for="boleta">Boleta</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="nombre" name="nombre" required>
                  <label for="nombre">Nombre</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="ap_paterno" name="ap_paterno" required>
                  <label for="ap_paterno">Apellido Paterno</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="ap_materno" name="ap_materno" required>
                  <label for="ap_materno">Apellido Materno</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <select class="form-select form-control-dark" id="genero" name="genero" required>
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                    <option value="O">Otro</option>
                  </select>
                  <label for="genero">Género</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="curp" name="curp" required>
                  <label for="curp">CURP</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="telefono" name="telefono" required>
                  <label for="telefono">Teléfono</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <select class="form-select form-control-dark" id="semestre" name="semestre" required>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                      <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                  </select>
                  <label for="semestre">Semestre</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <select class="form-select form-control-dark" id="carrera" name="carrera" required>
                    <option value="ISC">Ingeniería en Sistemas Computacionales</option>
                    <option value="LCD">Licenciatura en Ciencia de Datos</option>
                    <option value="IA">Ingeniería en Inteligencia Artificial</option>
                  </select>
                  <label for="carrera">Carrera</label>
                </div>
              </div>
              
              <!-- Datos de Cuenta -->
              <h5 class="text-hi5-gold mb-3 mt-4">Datos de cuenta</h5>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="email" class="form-control form-control-dark" id="correo" name="correo" required>
                  <label for="correo">Correo</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="password" class="form-control form-control-dark" id="contrasena" name="contrasena" required>
                  <label for="contrasena">Contraseña</label>
                </div>
              </div>
              
              <!-- Datos del Concurso -->
              <h5 class="text-hi5-gold mb-3 mt-4">Datos del concurso</h5>
              <div class="col-md-6">
                <div class="form-floating">
                  <select class="form-select form-control-dark" id="academia" name="academia" required>
                    <option value="Ciencia de Datos">Ciencia de Datos</option>
                    <option value="Ciencias Básicas">Ciencias Básicas</option>
                    <option value="Ciencias de la Computación">Ciencias de la Computación</option>
                    <option value="Ciencias Sociales">Ciencias Sociales</option>
                    <option value="Fundamentos de Sistemas Electrónicos">Fundamentos de Sistemas Electrónicos</option>
                    <option value="Ingeniería de Software">Ingeniería de Software</option>
                    <option value="Inteligencia Artificial">Inteligencia Artificial</option>
                    <option value="Proyectos Estratégicos para la Toma de Decisiones">Proyectos Estratégicos para la Toma de Decisiones</option>
                    <option value="Sistemas Digitales">Sistemas Digitales</option>
                    <option value="Sistemas Distribuidos">Sistemas Distribuidos</option>
                  </select>
                  <label for="academia">Academia</label>
                </div>
              </div>
              <div class="col-md-6 d-flex align-items-center">
                <div class="form-floating flex-grow-1 position-relative me-2">
                  <input type="text" class="form-control form-control-dark" id="unidad_aprendizaje" name="unidad_aprendizaje" required>
                  <label for="unidad_aprendizaje">Unidad de Aprendizaje</label>
                </div>
                <button type="button" class="btn btn-info-circle btn-white" data-bs-toggle="modal" data-bs-target="#modalUnidades">
                  ⓘ
                </button>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <select class="form-select form-control-dark" id="horario" name="horario" required>
                    <option value="matutino">Matutino</option>
                    <option value="vespertino">Vespertino</option>
                  </select>
                  <label for="horario">Horario</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="nombre_proyecto" name="nombre_proyecto" required>
                  <label for="nombre_proyecto">Nombre del Proyecto</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="nombre_equipo" name="nombre_equipo" required>
                  <label for="nombre_equipo">Nombre del Equipo</label>
                </div>
              </div>
              
              <!-- Datos Asignados -->
              <h5 class="text-hi5-gold mb-3 mt-4">Datos asignados</h5>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="date" class="form-control form-control-dark" id="fecha_registro" name="fecha_registro" required>
                  <label for="fecha_registro">Fecha de Registro</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" class="form-control form-control-dark" id="salon" name="salon">
                  <label for="salon">Salón</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="date" class="form-control form-control-dark" id="fecha_expo" name="fecha_expo">
                  <label for="fecha_expo">Fecha de Exposición</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="time" class="form-control form-control-dark" id="hora_expo" name="hora_expo">
                  <label for="hora_expo">Hora de Exposición</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <select class="form-select form-control-dark" id="puede_descargar_acuse" name="puede_descargar_acuse">
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                  </select>
                  <label for="puede_descargar_acuse">Puede descargar acuse</label>
                </div>
              </div>
              
              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-hi5-gold">Agregar Participante</button>
              </div>
            </form>
          </div>
        </div>
      </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/selectorDeUA.js"></script>
  </body>
</html>