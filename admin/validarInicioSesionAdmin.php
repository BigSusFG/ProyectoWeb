<?php
session_start();

$servidor = "localhost";
$usuarioBD = "root";
$passBD = "";
$bd = "expoescom2025";

// Conexión a la base de datos
$conexion = mysqli_connect($servidor, $usuarioBD, $passBD, $bd);
if (!$conexion) {
    die("Error al conectar a la base de datos: " . mysqli_connect_error());
}

// Validar campos del formulario
if (!empty($_POST["logCorreo"]) && !empty($_POST["loginPass"])) {
    $correo = $_POST["logCorreo"];
    $contrasena = $_POST["loginPass"];

    // Buscar al admin por usuario (correo)
    $sql = "SELECT * FROM admin WHERE usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $correo);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($admin = mysqli_fetch_assoc($resultado)) {
        // Verificar contraseña con hash
        if (password_verify($contrasena, $admin["contrasena"])) {
            $_SESSION["admin"] = $admin["usuario"];
            $_SESSION["admin_id"] = $admin["id"];

            header("Location: ../admin/paginaAdmin.php");
            exit();
        } else {
            echo '<script>
                alert("Contraseña incorrecta.");
                window.location.href = "../html/inicioSesionAdmin.html";
            </script>';
        }
    } else {
        echo '<script>
            alert("Usuario no encontrado.");
            window.location.href = "../html/inicioSesionAdmin.html";
        </script>';
    }
} else {
    echo '<script>
        alert("Por favor, llena todos los campos.");
        window.location.href = "../html/inicioSesionAdmin.html";
    </script>';
}
?>
