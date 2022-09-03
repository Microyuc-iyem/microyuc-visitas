<?php
// Require database connection and PHPWord library
require './config/db_connect.php';
require './lib/phpword/vendor/autoload.php';
require './includes/functions.php';

check_login();

$fmt = set_date_format_logbook();

// Check if there is an ID query
if ($_GET['id']) {
    $id = $_GET['id'];
// Write query to get a bitacora according to the ID
    $sql = "SELECT * FROM bitacora WHERE id = " . $_GET['id'] . ";";
    $sql_count = "SELECT COUNT(*) AS num FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'bitacora' AND TABLE_SCHEMA = 'microyuc_project' AND COLUMN_NAME LIKE 'gestion_via%';";

// make query and & get result
    $result = mysqli_query($conn, $sql);
    $result_count = mysqli_query($conn, $sql_count);
    if ($result && $result_count) {

// Fetch the resulting rows as an associative array
        $bitacoras = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $column_number = mysqli_fetch_all($result_count, MYSQLI_ASSOC);
        if ($bitacoras && $column_number) {
            if (isset($_GET['num'])) {
                $num = $_GET['num'];
                $sql_delete_image_query = "SELECT evidencia_fotografia$num FROM bitacora WHERE id = '$id';";
                $sql_delete_columns_query = "UPDATE bitacora SET gestion_fecha$num = '', gestion_via$num = '', gestion_comentarios$num = '', evidencia_fecha$num = '', evidencia_fotografia$num = '' WHERE id = '$id';";
                $resultado_imagen = mysqli_query($conn, $sql_delete_image_query);
                $imagename = $resultado_imagen->fetch_array()['evidencia_fotografia1'] ?? '';
                $delete = mysqli_query($conn, $sql_delete_columns_query);
                unlink('./uploads/' . $imagename);

                // Create variable with filename
                $nombre_archivo = $bitacoras[0]['acreditado_folio'] . ' ' . $bitacoras[0]['acreditado_nombre'] . ' - Bitácora.docx';
// Encode filename so that UTF-8 characters work
                $nombre_archivo_decodificado = rawurlencode($nombre_archivo);

// Create new instance of PHPWord template processor using the required template file
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-bitacora.docx');

                $sql = "SELECT * FROM bitacora WHERE id = " . $_GET['id'] . ";";
                $result = mysqli_query($conn, $sql);
                // Fetch the resulting rows as an associative array
                $bitacoras = mysqli_fetch_all($result, MYSQLI_ASSOC);
// Set values in template with post received input variables
                $values = [];
                for ($i = 1; $i <= $bitacoras[0]['gestion_contador']; $i++) {
                    if ($bitacoras[0]['gestion_fecha' . $i]) {
                        $values[] = ['gestion_fecha' => date("d-m-Y", strtotime($bitacoras[0]['gestion_fecha' . $i])), 'gestion_via' => $bitacoras[0]['gestion_via' . $i], 'gestion_comentarios' => $bitacoras[0]['gestion_comentarios' . $i]];
                    }
                }

                $templateProcessor->setValue('acreditado_nombre', $bitacoras[0]['acreditado_nombre']);
                $templateProcessor->setValue('acreditado_folio', $bitacoras[0]['acreditado_folio']);
                $templateProcessor->setValue('acreditado_municipio', $bitacoras[0]['acreditado_municipio']);
                $templateProcessor->setValue('acreditado_garantia', $bitacoras[0]['acreditado_garantia']);
                $templateProcessor->setValue('acreditado_telefono', $bitacoras[0]['acreditado_telefono']);
                $templateProcessor->setValue('acreditado_email', $bitacoras[0]['acreditado_email']);
                $templateProcessor->setValue('acreditado_direccion_negocio', $bitacoras[0]['acreditado_direccion_negocio']);
                $templateProcessor->setValue('acreditado_direccion_particular', $bitacoras[0]['acreditado_direccion_particular']);
                $templateProcessor->setValue('aval_nombre', $bitacoras[0]['aval_nombre']);
                $templateProcessor->setValue('aval_telefono', $bitacoras[0]['aval_telefono']);
                $templateProcessor->setValue('aval_email', $bitacoras[0]['aval_email']);
                $templateProcessor->setValue('aval_direccion', $bitacoras[0]['aval_direccion']);
                $templateProcessor->cloneRowAndSetValues('gestion_fecha', $values);
                $templateProcessor->cloneBlock('evidencia', $bitacoras[0]['evidencia_contador'], true, true);
                for ($i = 1; $i <= $bitacoras[0]['evidencia_contador']; $i++) {
                    $templateProcessor->setValue('evidencia_fecha#' . $i, $bitacoras[0]['evidencia_fecha' . $i] ? "Se visitó el negocio el " . datefmt_format($fmt, new DateTime($bitacoras[0]['evidencia_fecha' . $i])) . ".</w:t><w:br/><w:t>Fachada del negocio." : '');
                    if ($bitacoras[0]['evidencia_fotografia' . $i]) {
                        $templateProcessor->setImageValue('evidencia_fotografia#' . $i, array('path' => './uploads/' . $bitacoras[0]['evidencia_fotografia' . $i], 'width' => 720, 'height' => 480));
                    } else {
                        $templateProcessor->setValue('evidencia_fotografia#' . $i, '');
                    }
                }

                if (!is_dir('./files/')) {
                    mkdir('./files/');
                }

                if (!is_dir('./files/bitacoras/')) {
                    mkdir('./files/bitacoras/');
                }

                if (file_exists('./files/bitacoras/')) {
                    // Path where generated file is saved
                    $ruta_guardado = './files/bitacoras/' . $nombre_archivo;
                    $templateProcessor->saveAs($ruta_guardado);
                }

                header('Location: administrar-gestion.php?id=' . "$id");

            }
        } else {
            header('Location: ./bitacoras.php');
        }
    } else {
        header('Location: ./bitacoras.php');
    }
} else {
    header('Location: ./bitacoras.php');
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
    <title>Microyuc | Administrar gestiones</title>
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
                <h1 class="main__title">Administrar gestiones de <?= $bitacoras[0]['acreditado_nombre']; ?></h1>
                <a href="bitacoras.php" class="main__btn main__btn--main">
                    <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Gestionar bitácoras
                </a>
            </div>
            <div>
                <table class="table">
                    <thead class="table__head">
                    <tr class="table__row--head">
                        <th scope="col" class="table__head">
                            Fecha
                        </th>
                        <th scope="col" class="table__head table__data--left">
                            Vía
                        </th>
                        <th scope="col" class="table__head table__data--left">
                            Comentarios
                        </th>
                        <th scope="col" class="table__head table__data--left">
                            Acción
                        </th>
                    </tr>
                    </thead>
                    <tbody class="table__body">
                    <?php foreach ($bitacoras as $bitacora): ?>
                        <?php for ($i = 1; $i <= $column_number[0]['num']; $i++): ?>
                            <?php if ($bitacora['gestion_fecha' . $i] !== ''): ?>
                                <tr class="table__row--body">
                                    <td class="table__data table__data--left"><?= $bitacora['gestion_fecha' . $i] ?></td>
                                    <td class="table__data table__data--left"><?= $bitacora['gestion_via' . $i] ?></td>
                                    <td class="table__data table__data--left"><?= $bitacora['gestion_comentarios' . $i] ?></td>
                                    <td class="table__data"><a class="table__data--red"
                                                               href="administrar-gestion.php?id=<?= $bitacora['id'] ?>&num=<?= $i ?>">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
</body>
</html>