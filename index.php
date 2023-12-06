

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
    <?php
session_start();
require_once 'conexion.php';

if (isset($_SESSION['login'])) {
    header("Location: inicio.php");
        exit();
    }

    // Verificar si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = $_POST['user'];
        $password = $_POST['password'];

        // Verificar las credenciales (aquí deberías realizar la verificación segura, por ejemplo, mediante hash de contraseñas)
        if ($user === 'microyuc.iyem@yucatan.gob.mx' && $password === 'MicroYuc.19') {
            $_SESSION['login'] = true;
            header("Location: inicio.php");
            exit();
        } else {
            echo "<p>Usuario o contraseña incorrectos.</p>";
        }
    }
    ?>
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



