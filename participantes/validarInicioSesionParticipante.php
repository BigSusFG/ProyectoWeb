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

if (!empty($_POST["loginCorreo"]) && !empty($_POST["loginPass"])) {
    $correo = $_POST["loginCorreo"];
    $contrasena = $_POST["loginPass"];

    $sql = "SELECT * FROM participantes WHERE correo = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($usuario = mysqli_fetch_assoc($resultado)) {
        if (password_verify($contrasena, $usuario["contrasena"])) {
            $_SESSION["boleta"] = $usuario["boleta"];
            $_SESSION["nombre"] = $usuario["nombre"];
            header("Location: perfilParticipante.php");
            exit();
        } else {
            echo '<script>
                alert("Contraseña inválida");
                window.location.href = "../html/inicioSesionParticipantes.html";
            </script>';
        }
    } else {
        echo '<script>
            alert("Correo no encontrado");
            window.location.href = "../html/inicioSesionParticipantes.html";
        </script>';
    }
} else {
    echo '<script>
        alert("Campos vacíos");
        window.location.href = "../html/inicioSesionParticipantes.html";
    </script>';
}
?>
