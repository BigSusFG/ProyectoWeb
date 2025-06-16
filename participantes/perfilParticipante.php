<?php
session_start();

if (!isset($_SESSION["boleta"])) {
    echo "Acceso denegado. Inicia sesión.";
    exit();
}

$conexion = new mysqli("localhost", "root", "", "expoescom2025");
$boleta = $_SESSION["boleta"];

$sql = "SELECT * FROM participantes WHERE boleta = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $boleta);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Perfil del Participante</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-dark text-white">
  <div class="container py-5">
    <h2 class="mb-4">Bienvenido, <?= htmlspecialchars($usuario["nombre"]) ?></h2>
    
    <div class="bg-secondary p-4 rounded">
      <h5>Datos Personales</h5>
      <p><strong>Boleta:</strong> <?= $usuario["boleta"] ?></p>
      <p><strong>Nombre:</strong> <?= $usuario["nombre"] . " " . $usuario["ap_paterno"] . " " . $usuario["ap_materno"] ?></p>
      <p><strong>CURP:</strong> <?= $usuario["curp"] ?></p>
      <p><strong>Teléfono:</strong> <?= $usuario["telefono"] ?></p>
      <p><strong>Semestre:</strong> <?= $usuario["semestre"] ?></p>
      <p><strong>Carrera:</strong> <?= $usuario["carrera"] ?></p>
      <p><strong>Correo:</strong> <?= $usuario["correo"] ?></p>
    </div>

    <div class="bg-secondary p-4 rounded mt-4">
      <h5>Datos del Concurso</h5>
      <p><strong>Academia:</strong> <?= $usuario["academia"] ?></p>
      <p><strong>Unidad de Aprendizaje:</strong> <?= $usuario["unidad_aprendizaje"] ?></p>
      <p><strong>Horario:</strong> <?= $usuario["horario"] ?></p>
      <p><strong>Proyecto:</strong> <?= $usuario["nombre_proyecto"] ?></p>
      <p><strong>Equipo:</strong> <?= $usuario["nombre_equipo"] ?></p>
    </div>
  </div>
</body>
</html>
