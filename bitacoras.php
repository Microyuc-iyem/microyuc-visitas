<?php
require_once './config/db_connect.php';
require './includes/functions.php';

$sidebar_active = 'bitácora';
$header_title = 'Panel de bitácoras';

require './includes/header.php';

check_login();

// Write query for all acreditados
$sql = 'SELECT id, acreditado_nombre, acreditado_folio, acreditado_telefono, acreditado_email, gestion_fecha1, gestion_via1, fecha_creacion, nombre_archivo FROM bitacora ORDER BY id DESC;';

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
            <a href="excel-bitacoras.php" class="main__btn main__btn--excel">
                <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                     stroke-width="1.5"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5"/>
                </svg>
                Exportar a Excel</a>
            <a href="generador-bitacora.php" class="main__btn main__btn--main">
                <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Nueva bitácora
            </a>
        </div>
    </div>
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
            <th scope="col" class="table__head">
                Fecha de creación
            </th>
            <th scope="col" colspan="4" class="table__head">
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
                <td class="table__data"><?= date("d-m-Y", strtotime($bitacora['fecha_creacion'])); ?></td>
                <?php if (file_exists('./files/bitacoras/' . $bitacora['nombre_archivo'])): ?>
                    <td class="table__data"><a class="table__data--link"
                                               href="./files/bitacoras/<?= $bitacora['nombre_archivo'] ?>">Descargar</a>
                    </td>
                <?php else: ?>
                    <td class="table__data"><a class="table__data--nolink">Descargar</a>
                    </td>
                <?php endif; ?>
                <td class="table__data"><a class="table__data--green"
                                           href="agregar-gestion.php?id=<?= $bitacora['id'] ?>">Agregar</a>
                </td>
                <td class="table__data"><a class="table__data--red"
                                           href="bitacoras.php?id=<?= $bitacora['id'] ?>">Eliminar</a>
                </td>
                <td class="table__data"><a class="table__data--gold"
                                           href="administrar-gestiones.php?id=<?= $bitacora['id'] ?>">Gestionar</a>
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
