
<?php
// Archivo de conexión a la base de datos
require_once 'conexion.php';

// Inicia la sesión (si no está iniciada)
session_start();

// Verifica si el formulario de inicio de sesión fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera los datos del formulario
    $nombreUsuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    // Consulta SQL para verificar las credenciales
    $query = "SELECT * FROM usuarios WHERE nombre_usuario = $1 AND contrasena = $2";
    $result = pg_query_params($conn, $query, array($nombreUsuario, $contrasena));

    // Verifica si se encontraron coincidencias
    if (pg_num_rows($result) == 1) {
        // Inicia la sesión y redirige al usuario a la página de inicio
        $_SESSION['nombre_usuario'] = $nombreUsuario;
        header("location: inicio.php");
        exit(); // Asegura que el script se detenga después de redirigir
    } else {
        $error = "Credenciales incorrectas. Por favor, inténtalo de nuevo.";
    }
}

// Cierra la conexión
pg_close($conn);
?>



<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="dist/css/styles.css">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <title>Microyuc Emprendedores | Inicio de sesión</title>
</head>
<body>
<div class="login">
    <img src="img/microyucfondo.png" alt="Logo de Microyuc" class="login__img">
    <div class="login__container">
        <h1 class="login__title">Iniciar sesión</h1>
        <p class="login__subtitle">Introduce tus credenciales para iniciar sesión.</p>
        <form action="index.php" method="post" class="login__form">
            <label for="user">
                <input type="text" id="user" name="user" placeholder="Usuario" class="login__input" required>
            </label>
            <label for="password">
                <input type="password" id="password" name="password" placeholder="Contraseña" class="login__input"
                       required>
            </label>
            <input type="submit" value="Iniciar sesión" class="login__btn">
        </form>
    </div>
</div>
</body>
</html>
