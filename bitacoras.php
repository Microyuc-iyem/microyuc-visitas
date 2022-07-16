<?php
require_once './config/db_connect.php';
require './includes/functions.php';

check_login();

// Write query for all acreditados
$sql = 'SELECT id, acreditado_nombre, acreditado_folio, acreditado_telefono, acreditado_email, gestion_fecha1, gestion_via1, fecha_creacion, nombre_archivo FROM bitacora ORDER BY fecha_creacion DESC;';

// make query and & get result
$result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$bitacoras = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado_imagen = mysqli_query($conn, "SELECT evidencia_fotografia1 FROM bitacora WHERE id = '$id';");
    $imagename = $resultado_imagen->fetch_array()['evidencia_fotografia1'] ?? '';
    $resultado_archivo = mysqli_query($conn, "SELECT nombre_archivo FROM bitacora WHERE id = '$id';");
    $filename = $resultado_archivo->fetch_array()['nombre_archivo'] ?? '';
    $delete = mysqli_query($conn, "DELETE FROM bitacora WHERE id = '$id';");
    unlink('./files/bitacoras/' . $filename);
    unlink('./uploads/' . $imagename);
    header('Location: bitacoras.php');
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
    <title>Microyuc | Panel de bitácoras</title>
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <a href="inicio.php"><img src="img/microyucfondo.png" alt="Logo de microyuc" class="sidebar__image"></a>
        <nav class="sidebar__nav">
            <div class="sidebar__dashboard">
                <h2 class="sidebar__title">Tablero</h2>
                <ul class="sidebar__list">
                    <li><a href="inicio.php" class="sidebar__link">
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
                    <li><a href="generador-carta.php" class="sidebar__link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Cartas</span></a></li>
                    <li><a href="generador-bitacora.php" class="sidebar__link sidebar__link--active">
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
            <div class="main__header">
                <div>
                    <h1 class="main__title">Bitácoras</h1>
                    <span class="main__subtitle"><?php
                        $dash_logbook_query = "SELECT * FROM bitacora";
                        $dash_logbook_query_run = mysqli_query($conn, $dash_logbook_query);

                        if ($bitacoras_total = mysqli_num_rows($dash_logbook_query_run)) {
                            echo $bitacoras_total . ' bitácoras';
                        } else {
                            echo "Sin datos";
                        }
                        ?></span>
                </div>
                <div class="main__btnContainer">
                    <a href="cartas-excel.php">Exportar Excel</a>
                    <a href="generador-bitacora.php" class="main__btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Nueva bitácora
                    </a>
                </div>
            </div>
            <input type="text" placeholder="Busca por nombre, folio..." style="margin-bottom: 24px">
            <table class="table">
                <thead class="table__head">
                <tr class="table__row--head">
                    <th scope="col" class="table__head">
                        Acreditado
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Folio
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Teléfono
                    </th>
                    <th scope="col" class="table__head">
                        E-mail
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Fecha de gestión
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Vía de gestión
                    </th>
                    <th scope="col" class="table__head">
                        Fecha de creación
                    </th>
                    <th scope="col" colspan="2" class="table__head">
                        Acciones
                    </th>
                </tr>
                </thead>
                <tbody class="table__body">
                <?php foreach ($bitacoras as $bitacora): ?>
                    <tr class="table__row--body">
                        <td class="table__data table__data--bold"><?= $bitacora['acreditado_nombre'] ?></td>
                        <td class="table__data table__data--left"><?= $bitacora['acreditado_folio'] ?></td>
                        <td class="table__data table__data--left"><?= $bitacora['acreditado_telefono'] ?></td>
                        <td class="table__data"><a
                                    href="mailto:<?= $bitacora['acreditado_email']; ?>"><?= $bitacora['acreditado_email']; ?></a>
                        </td>
                        <td class="table__data table__data--left"><?= date("d-m-Y", strtotime($bitacora['gestion_fecha1'])); ?></td>
                        <td class="table__data"><?= $bitacora['gestion_via1']; ?></td>
                        <td class="table__data"><?= date("d-m-Y", strtotime($bitacora['fecha_creacion'])); ?></td>
                        <td class="table__data"><a class="table__data--link"
                                                   href="./files/bitacoras/<?= $bitacora['nombre_archivo'] ?>">Descargar</a>
                        </td>
                        <td class="table__data"><a class="table__data--red"
                                                   href="bitacoras.php?id=<?= $bitacora['id'] ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>