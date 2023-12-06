<?php
session_start();
require_once 'conexion.php';

if (isset($_SESSION['login'])) {
    header("Location: inicio.php");
}

$sql = 'SELECT nombre, password FROM usuario';
$result = pg_query($conn, $sql);
$usuarios = pg_fetch_all($result);

if ($_POST) {
    echo "<h1 style='text-align: center'>ESE ECHO 2</h1>";
    $user = pg_escape_string($conn, $_POST['user']);
    $password = pg_escape_string($conn, $_POST['password']);

    // Modifica la consulta para comparar con el usuario y la contraseña adecuados
    $query = "SELECT * FROM usuario WHERE nombre = '$user'echo "<h1 style='text-align: center'>ESE ECHO 1121</h1>";  AND password = '$password'"echo "<h1 style='text-align: center'>ESE ECHO 11231</h1>";;
    
    $result = pg_query($conn, $query);
echo "<h1 style='text-align: center'>ESE ECHO 11</h1>";
    if (pg_num_rows($result) == 1) {
        echo "<h1 style='text-align: center'>ESE ECHO 3</h1>";
        $_SESSION['login'] = true;

        // Redirigir a inicio.php si el usuario es "microyuc" y la contraseña es "admin"
        if ($user === 'microyuc.iyem@yucatan.gob.mx' && $password === 'MicroYuc.19') {
            echo "<h1 style='text-align: center'>ESE ECHO 1</h1>";
            header("Location: inicio.php");
        } else {
            // Otras redirecciones si es necesario
            // header("Location: otra_pagina.php");
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



