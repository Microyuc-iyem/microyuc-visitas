<?php
session_start();
require_once 'conexion.php';

if (isset($_SESSION['login'])) {
     echo "<h1 style='text-align: center'>ESE ECHO 1</h1>";
    header("Location: inicio.php");
}

$sql = 'SELECT nombre, password FROM usuario';
$result = pg_query($conn, $sql);
$usuario = pg_fetch_all($result);

if ($_POST) {
    echo "<h1 style='text-align: center'>ESE ECHO 2</h1>";
    $nombre = pg_escape_string($conn, $_POST['nombre']);
    $password = pg_escape_string($conn, $_POST['password']);
     
     $query = "SELECT * FROM users WHERE username = " . $user . " AND password = " . $password;

    $result = pg_query($conn, $query);

    if (pg_num_rows($result) == 1) {
        
        $_SESSION['login'] = true;

        // Redirigir a inicio.php si el usuario es microyuc.iyem@yucatan.gob.mx y la contraseña es MicroYuc.19
        if ($nombre === 'microyuc.iyem@yucatan.gob.mx' && $password === 'MicroYuc.19') {
            
            header("Location: inicio.php");
        } else {
            // Otras redirecciones si es necesario
           //  header("Location: inicio.php");
        }
    } else {
        echo "<h1 style='text-align: center'>Usuario o contraseña incorrectos</h1>";

    }
}
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
    <title>Microyuc Emprendedores | Inicio de Sesión</title>
</head>
<body>
<div class="login">
    <img src="img/microyucfondo.png" alt="Logo de Microyuc" class="login__img">
    <div class="login__container">
        <h1 class="login__title">Iniciar sesión</h1>
        <p class="login__subtitle">Introduce tus credenciales para iniciar sesión.</p>
        <form action="index.php" method="post" class="login__form">
            <label for="nombre">
                <input type="text" id="nombre" name="nombre" placeholder="Usuario" class="login__input" required>
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



