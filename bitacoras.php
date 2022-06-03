<?php
require_once './config/db_connect.php';
require './librerias/phpword/vendor/autoload.php';

session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
}

// Write query for all acreditados
$sql = 'SELECT acreditado_nombre, archivo, imagen FROM test;';

// make query and & get result
$result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$bitacoras = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./dist/css/styles.css">
    <title>Microyuc | Panel de bitácoras</title>
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <a href="./inicio.php"><img src="./img/microyucfondo.png" alt="Logo de microyuc" class="sidebar__image"></a>
        <nav class="sidebar__nav">
            <div class="sidebar__dashboard">
                <h2 class="sidebar__title">Tablero</h2>
                <ul class="sidebar__list">
                    <li><a href="./inicio.php" class="sidebar__link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Inicio</span></a></li>
                </ul>
            </div>
            <div class="sidebar__apps">
                <h2 class="sidebar__title">Apps</h2>
                <ul class="sidebar__list">
                    <li><a href="./generador-carta.php" class="sidebar__link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Cartas</span></a></li>
                    <li><a href="./generador-bitacora.php" class="sidebar__link sidebar__link--active">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Bitácoras</span></a></li>
                </ul>
            </div>
        </nav>
    </aside>
    <main class="main">
        <div class="main__app">
            <form action="exportar-bitacora.php" method="post" enctype="multipart/form-data">
                <div>
                    <label for="acreditado_nombre">Nombre del acreditado<span class="asterisk">*</span>:</label>
                    <input type="text" id="acreditado_nombre"
                           name="acreditado_nombre" required>
                    <label for="imagen">Fotografía:</label>
                    <input type="file" name="imagen" id="imagen">
                    <label for="evidencia"></label>
                    <div>
                        <button type="submit">Generar archivo</button>
                    </div>
                </div>
            </form>
            <?php foreach ($bitacoras as $bitacora): ?>
                <br>
                <p><?= $bitacora['acreditado_nombre'] ?></p>
                <a href="./files/bitacoras/<?= $bitacora['archivo'] ?>">Descargar</a>
                <img src="./uploads/<?= $bitacora['imagen'] ?>" alt="Fotografía de la fachada">
            <?php endforeach; ?>
        </div>
    </main>
</div>
</body>
</html>