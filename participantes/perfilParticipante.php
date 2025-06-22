<?php
session_start();

if (!isset($_SESSION["boleta"])) {
  echo "<h2>Acceso denegado. Inicia sesión.</h2>";
  exit();
}

$conexion = new mysqli("localhost", "root", "", "expoescom2025");
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

$boleta = $_SESSION["boleta"];
$sql = "SELECT * FROM participantes WHERE boleta = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $boleta);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Datos de cuenta</title>
  <link rel="icon" href="../imgs/logohifivemini.png" type="image/png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/estilos.css" />
  <link rel="stylesheet" href="../css/registro.css" />
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
            <a class="nav-link active" href="principal.html">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="registro.html">Registro</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="inicioSesionAdmin.html">Admin</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="inicioSesionParticipantes.html">Participantes</a>
          </li>
        </ul>
        <div class="d-flex gap-2">
          <a class="btn btn-hi5-gold px-4 rounded-pill" href="registro.html">Registrarse</a>
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
      <form action="../participantes/modificarParticipante.php" id="formPerfil" class="row g-4">
        <!-- DATOS PERSONALES -->
        <div class="col-12 bg-hi5-medium p-4 rounded-4 shadow">
          <h5 class="text-hi5-gold mb-4">Datos personales</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>No. de Boleta:</strong> <?= $usuario["boleta"] ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Nombre(s):</strong> <?= $usuario["nombre"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Apellido paterno:</strong>
                <?= $usuario["ap_paterno"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Apellido materno:</strong>
                <?= $usuario["ap_materno"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Género:</strong> <?= $usuario["genero"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>CURP:</strong> <?= $usuario["curp"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Teléfono:</strong> <?= $usuario["telefono"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Semestre:</strong> <?= $usuario["semestre"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Carrera:</strong> <?= $usuario["carrera"] ?></div>
            </div>
          </div>
        </div>

        <!-- DATOS DE CUENTA -->
        <div class="col-12 bg-hi5-medium p-4 rounded-4 shadow">
          <h5 class="text-hi5-gold mb-4">Datos de cuenta</h5>
          <div class="form-control form-control-dark">
            <strong>Correo institucional:</strong> <?= $usuario["correo"] ?>
          </div>
        </div>


        <!-- DATOS DEL CONCURSO -->
        <div class="col-12 bg-hi5-medium p-4 rounded-4 shadow">
          <h5 class="text-hi5-gold mb-4">Datos del concurso</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Academia:</strong> <?= $usuario["academia"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Unidad de Aprendizaje:</strong>
                <?= $usuario["unidad_aprendizaje"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Nombre del Proyecto:</strong>
                <?= $usuario["nombre_proyecto"] ?></div>
            </div>
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>Nombre del Equipo:</strong>
                <?= $usuario["nombre_equipo"] ?></div>
            </div>
          </div>
        </div>


        <!-- DATOS ASIGNADOS AUTOMÁTICAMENTE -->
        <div class="col-12 bg-hi5-medium p-4 rounded-4 shadow">
          <h5 class="text-hi5-gold mb-4">Datos asignados</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <div class="form-control form-control-dark"><strong>Salón:</strong>
                <?= $usuario["salon"] ?? "Pendiente" ?></div>
            </div>
            <div class="col-md-4">
              <div class="form-control form-control-dark"><strong>Hora de exposición:</strong>
                <?= $usuario["hora_expo"] ?? "Pendiente" ?></div>
            </div>
            <div class="col-md-4">
              <div class="form-control form-control-dark"><strong>Fecha de exposición:</strong>
                <?= $usuario["fecha_expo"] ?? "Pendiente" ?></div>
            </div>
          </div>
        </div>


        <!-- ESTADO ADMINISTRATIVO -->
        <div id="seccionEstadoAdmin" class="col-12 bg-hi5-medium p-4 rounded-4 shadow">
          <h5 class="text-hi5-gold mb-4">Estado administrativo</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="form-control form-control-dark"><strong>¿Puede descargar acuse?:</strong>
                <?= isset($usuario["puede_descargar_acuse"]) && $usuario["puede_descargar_acuse"] ? "Sí" : "No" ?></div>
            </div>
            <div class="col-md-6">
              <div id="valorGanador" class="form-control form-control-dark"><strong>¿Es ganador?:</strong>
                <?= isset($usuario["ganador"]) && $usuario["ganador"] ? "Sí" : "No" ?></div>
            </div>
            <div class="col-12 mt-3">
              <a href="../pdf/diploma.php" target="_blank" class="btn btn-hi5-gold rounded-pill px-4"
                id="btnDescargarDiploma">
                Descargar diploma de participación
              </a>
            </div>
          </div>
        </div>

        <!-- BOTÓNES -->
        <div class="col-12 d-flex justify-content-end gap-3 mt-4">
          <a href="../participantes/cerrarSesion.php" class="btn btn-outline-light rounded-pill px-4">
            Cerrar sesión
          </a>
          <a href="../pdf/registro.php" class="btn btn-hi5-gold rounded-pill px-4 d-none" id="btnDescargar"
            target="_blank">
            Descargar Registro
          </a>
        </div>

      </form>
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
          <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-3 small">
            <a href="https://www.instagram.com/isaacmontoyar/" class="text-decoration-none text-white">Contacto</a>
          </div>
        </div>
        <div class="col-md-2 d-flex justify-content-md-end justify-content-center mt-3 mt-md-0">
          <a href="#">
            <img src="../imgs/logohifive.png" alt="Logo Hi-5" title="Logo HI-5" class="logo-footer" />
          </a>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="../js/mostrarGanador.js"></script>
</body>

</html>