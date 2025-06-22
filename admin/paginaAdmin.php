<?php
session_start();

// Verificar si hay sesión de administrador
if (!isset($_SESSION["admin"])) {
  header("Location: ../html/principal.html"); // Redirige si no hay sesión
  exit();
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "expoescom2025");
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos del administrador
$usuarioAdmin = $_SESSION["admin"];
$sql = "SELECT * FROM administradores WHERE usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $usuarioAdmin);
$stmt->execute();
$resultado = $stmt->get_result();
$admin = $resultado->fetch_assoc();
$stmt->close();
$conexion->close();
?>