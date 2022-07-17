<?php
// Require database connection and PHPWord library
require './config/db_connect.php';
require './lib/phpword/vendor/autoload.php';
require './includes/functions.php';

check_login();

$fmt = set_date_format_logbook();

if ($_GET['id']) {
// Write query for all acreditados
    $sql = 'SELECT * FROM bitacora WHERE id = ' . $_GET['id'] . ';';

// make query and & get result
    $result = mysqli_query($conn, $sql);
    if ($result) {

// Fetch the resulting rows as an array
        $bitacora = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($bitacora) {

            $new_management_counter = intval($bitacora[0]['gestion_contador']) + 1;
            $new_evidence_counter = intval($bitacora[0]['evidencia_contador']) + 1;

            $gestion = [
                'gestion_fecha' . $new_management_counter => '',
                'gestion_via' . $new_management_counter => '',
                'gestion_comentarios' . $new_management_counter => '',
                'evidencia_fecha' . $new_evidence_counter => '',
                'evidencia_fotografia' . $new_evidence_counter => '',
            ];

            $errores = [
                'gestion_fecha' . $new_management_counter => '',
                'gestion_via' . $new_management_counter => '',
                'evidencia_fotografia' . $new_evidence_counter => '',
            ];

            $tipos_gestion = ['Correo electrónico', 'Llamada telefónica', 'Visita'];

            $filtros = [];

            $movido = false;
            $ruta_subido = './uploads/';
            $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/tiff', 'image/webp'];
            $exts_permitidas = ['jpeg', 'jpg', 'jpe', 'jif', 'jfif', 'png', 'gif', 'bmp', 'tif', 'tiff', 'webp'];

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_GET['id'])) {

                $filtros['gestion_fecha' . $new_management_counter]['filter'] = FILTER_VALIDATE_REGEXP;
                $filtros['gestion_fecha' . $new_management_counter]['options']['regexp'] = '/^[\d\-]+$/';
                $filtros['gestion_via' . $new_management_counter]['filter'] = FILTER_VALIDATE_REGEXP;
                $filtros['gestion_via' . $new_management_counter]['options']['regexp'] = '/^(Correo electrónico|Llamada telefónica|Visita)+$/';
                $filtros['gestion_comentarios' . $new_management_counter]['filter'] = FILTER_VALIDATE_REGEXP;
                $filtros['gestion_comentarios' . $new_management_counter]['options']['regexp'] = '/[\s\S]+/';
                $filtros['gestion_comentarios' . $new_management_counter]['options']['default'] = '';
                $filtros['evidencia_fecha' . $new_evidence_counter]['filter'] = FILTER_VALIDATE_REGEXP;
                $filtros['evidencia_fecha' . $new_evidence_counter]['options']['regexp'] = '/^[\d\-]+$/';
                $filtros['evidencia_fecha' . $new_evidence_counter]['options']['default'] = '';

                $gestion = filter_input_array(INPUT_POST, $filtros);

                // Error messages
                $errores['gestion_fecha' . $new_management_counter] = $gestion['gestion_fecha' . $new_management_counter] ? '' : 'Introduzca un formato de fecha válido.';
                $errores['gestion_via' . $new_management_counter] = $gestion['gestion_via' . $new_management_counter] ? '' : 'Seleccione una opción válida.';

                $gestion['evidencia_fotografia' . $new_evidence_counter] = $_FILES['evidencia_fotografia' . $new_evidence_counter]['name'] ?? '';
                $gestion['evidencia_fecha_texto' . $new_evidence_counter] = '';
                $gestion['evidencia_fecha' . $new_evidence_counter] = $gestion['evidencia_fecha' . $new_evidence_counter] ? new DateTime($gestion['evidencia_fecha' . $new_evidence_counter]) : '';
                if (($gestion['evidencia_fecha' . $new_evidence_counter] && $gestion['evidencia_fotografia' . $new_evidence_counter]) || (!$gestion['evidencia_fecha' . $new_evidence_counter] && !$gestion['evidencia_fotografia' . $new_evidence_counter])) {
                    if ($_FILES['evidencia_fotografia' . $new_evidence_counter]['error'] === 0) {
                        $tipo = mime_content_type($_FILES['evidencia_fotografia' . $new_evidence_counter]['tmp_name']);
                        $errores['evidencia_fotografia' . $new_evidence_counter] = in_array($tipo, $tipos_permitidos) ? '' : 'Formato de archivo incorrecto. ';
                        $ext = strtolower(pathinfo($_FILES['evidencia_fotografia' . $new_evidence_counter]['name'], PATHINFO_EXTENSION));
                        $errores['evidencia_fotografia' . $new_evidence_counter] .= in_array($ext, $exts_permitidas) ? '' : 'Extensión de archivo incorrecta.';

                        if (!$errores['evidencia_fotografia' . $new_evidence_counter]) {
                            $fotografia_nombre_archivo = create_filename($_FILES['evidencia_fotografia' . $new_evidence_counter]['name'], $ruta_subido);
                            if (!file_exists('./uploads/')) {
                                mkdir('./uploads/');
                            }
                            if (file_exists('./uploads/')) {
                                $destino = $ruta_subido . $fotografia_nombre_archivo;
                                $movido = move_uploaded_file($_FILES['evidencia_fotografia' . $new_evidence_counter]['tmp_name'], $destino);
                            }
                            if ($movido) {
                                $gestion['evidencia_fotografia' . $new_evidence_counter] = $fotografia_nombre_archivo;
                            } else {
                                $new_evidence_counter = $new_evidence_counter - 1;
                            }
                        }
                    } else {
                        $new_evidence_counter = $new_evidence_counter - 1;
                    }
                } else {
                    $errores['evidencia_fotografia' . $new_evidence_counter] = 'Se deben llenar ambos campos para registrar la evidencia.';
                }

                $generacion_invalida = implode($errores);

                if (!$generacion_invalida) {

// Create variable with filename
                    $nombre_archivo = $bitacora[0]['acreditado_folio'] . ' ' . $bitacora[0]['acreditado_nombre'] . ' - Bitácora.docx';
// Encode filename so that UTF-8 characters work
                    $nombre_archivo_decodificado = rawurlencode($nombre_archivo);

// Create new instance of PHPWord template processor using the required template file
                    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-bitacora.docx');

// Set values in template with post received input variables
                    $values = [];
                    for ($i = 1; $i < $new_management_counter; $i++) {
                        $values[] = ['gestion_fecha' => date("d-m-Y", strtotime($bitacora[0]['gestion_fecha' . $i])), 'gestion_via' => $bitacora[0]['gestion_via' . $i], 'gestion_comentarios' => $bitacora[0]['gestion_comentarios' . $i]];

                    }

                    $values[] = ['gestion_fecha' => date("d-m-Y", strtotime($gestion['gestion_fecha' . $new_management_counter])), 'gestion_via' => $gestion['gestion_via' . $new_management_counter], 'gestion_comentarios' => $gestion['gestion_comentarios' . $new_management_counter]];

                    if ($bitacora[0]['evidencia_contador'] == $new_evidence_counter) {
                        $templateProcessor->setValue('acreditado_nombre', $bitacora[0]['acreditado_nombre']);
                        $templateProcessor->setValue('acreditado_folio', $bitacora[0]['acreditado_folio']);
                        $templateProcessor->setValue('acreditado_municipio', $bitacora[0]['acreditado_municipio']);
                        $templateProcessor->setValue('acreditado_garantia', $bitacora[0]['acreditado_garantia']);
                        $templateProcessor->setValue('acreditado_telefono', $bitacora[0]['acreditado_telefono']);
                        $templateProcessor->setValue('acreditado_email', $bitacora[0]['acreditado_email']);
                        $templateProcessor->setValue('acreditado_direccion_negocio', $bitacora[0]['acreditado_direccion_negocio']);
                        $templateProcessor->setValue('acreditado_direccion_particular', $bitacora[0]['acreditado_direccion_particular']);
                        $templateProcessor->setValue('aval_nombre', $bitacora[0]['aval_nombre']);
                        $templateProcessor->setValue('aval_telefono', $bitacora[0]['aval_telefono']);
                        $templateProcessor->setValue('aval_email', $bitacora[0]['aval_email']);
                        $templateProcessor->setValue('aval_direccion', $bitacora[0]['aval_direccion']);
                        $templateProcessor->cloneRowAndSetValues('gestion_fecha', $values);
                        if ($new_evidence_counter === 0) {
                            $templateProcessor->cloneBlock('evidencia', 1, true, false);
                            $templateProcessor->setValue('evidencia_fecha', '');
                            $templateProcessor->setValue('evidencia_fotografia', '');
                        } else {
                            $templateProcessor->cloneBlock('evidencia', $new_evidence_counter, true, true);
                            for ($i = 1; $i <= $new_evidence_counter; $i++) {
                                $templateProcessor->setValue('evidencia_fecha#' . $i, "Se visitó el negocio el " . datefmt_format($fmt, new DateTime($bitacora[0]['evidencia_fecha' . $i])) . ".</w:t><w:br/><w:t>Fachada del negocio.");
                                $templateProcessor->setImageValue('evidencia_fotografia#' . $i, array('path' => $ruta_subido . $bitacora[0]['evidencia_fotografia' . $i], 'width' => 720, 'height' => 480));
                            }
                        }
                    } else {
                        $templateProcessor->setValue('acreditado_nombre', $bitacora[0]['acreditado_nombre']);
                        $templateProcessor->setValue('acreditado_folio', $bitacora[0]['acreditado_folio']);
                        $templateProcessor->setValue('acreditado_municipio', $bitacora[0]['acreditado_municipio']);
                        $templateProcessor->setValue('acreditado_garantia', $bitacora[0]['acreditado_garantia']);
                        $templateProcessor->setValue('acreditado_telefono', $bitacora[0]['acreditado_telefono']);
                        $templateProcessor->setValue('acreditado_email', $bitacora[0]['acreditado_email']);
                        $templateProcessor->setValue('acreditado_direccion_negocio', $bitacora[0]['acreditado_direccion_negocio']);
                        $templateProcessor->setValue('acreditado_direccion_particular', $bitacora[0]['acreditado_direccion_particular']);
                        $templateProcessor->setValue('aval_nombre', $bitacora[0]['aval_nombre']);
                        $templateProcessor->setValue('aval_telefono', $bitacora[0]['aval_telefono']);
                        $templateProcessor->setValue('aval_email', $bitacora[0]['aval_email']);
                        $templateProcessor->setValue('aval_direccion', $bitacora[0]['aval_direccion']);
                        $templateProcessor->cloneRowAndSetValues('gestion_fecha', $values);
                        $templateProcessor->cloneBlock('evidencia', $new_evidence_counter, true, true);
                        for ($i = 1; $i < $new_evidence_counter; $i++) {
                            $templateProcessor->setValue('evidencia_fecha#' . $i, "Se visitó el negocio el " . datefmt_format($fmt, new DateTime($bitacora[0]['evidencia_fecha' . $i])) . ".</w:t><w:br/><w:t>Fachada del negocio.");
                            $templateProcessor->setImageValue('evidencia_fotografia#' . $i, array('path' => $ruta_subido . $bitacora[0]['evidencia_fotografia' . $i], 'width' => 720, 'height' => 480));
                        }
                        $templateProcessor->setValue('evidencia_fecha#' . $new_evidence_counter, "Se visitó el negocio el " . datefmt_format($fmt, $gestion['evidencia_fecha' . $new_evidence_counter]) . ".</w:t><w:br/><w:t>Fachada del negocio.");
                        $templateProcessor->setImageValue('evidencia_fotografia#' . $new_evidence_counter, array('path' => $ruta_subido . $gestion['evidencia_fotografia' . $new_evidence_counter], 'width' => 720, 'height' => 480));
                    }

// Escape strings to insert into the database table
                    $acreditado_nombre = mysqli_real_escape_string($conn, $bitacora['acreditado_nombre']);
                    $folio = mysqli_real_escape_string($conn, $bitacora['acreditado_folio']);
                    $municipio = mysqli_real_escape_string($conn, $bitacora['acreditado_municipio']);
                    $garantia = mysqli_real_escape_string($conn, $bitacora['acreditado_garantia']);
                    $acreditado_telefono = mysqli_real_escape_string($conn, $bitacora['acreditado_telefono']);
                    $acreditado_email = mysqli_real_escape_string($conn, $bitacora['acreditado_email']);
                    $direccion_negocio = mysqli_real_escape_string($conn, $bitacora['acreditado_direccion_negocio']);
                    $direccion_particular = mysqli_real_escape_string($conn, $bitacora['acreditado_direccion_particular']);
                    $aval_nombre = mysqli_real_escape_string($conn, $bitacora['aval_nombre']);
                    $aval_telefono = mysqli_real_escape_string($conn, $bitacora['aval_telefono']);
                    $aval_email = mysqli_real_escape_string($conn, $bitacora['aval_email']);
                    $aval_direccion = mysqli_real_escape_string($conn, $bitacora['aval_direccion']);
                    $gestion_fecha = mysqli_real_escape_string($conn, $bitacora['gestion_fecha1']);
                    $gestion_via = mysqli_real_escape_string($conn, $bitacora['gestion_via1']);
                    $gestion_comentarios = mysqli_real_escape_string($conn, $bitacora['gestion_comentarios1']);
                    $evidencia_fecha = mysqli_real_escape_string($conn, $bitacora['evidencia_fecha1'] ? $bitacora['evidencia_fecha1']->format('Y-m-d') : '');
                    $evidencia_fotografia = mysqli_real_escape_string($conn, $bitacora['evidencia_fotografia1'] ?? '');

// Query
                    $sql = "INSERT INTO bitacora(acreditado_nombre, acreditado_folio, acreditado_municipio, acreditado_garantia, acreditado_telefono, acreditado_email,
                     acreditado_direccion_negocio, acreditado_direccion_particular, aval_nombre, aval_telefono, aval_email, aval_direccion,
                     gestion_fecha1, gestion_via1, gestion_comentarios1, evidencia_fecha1, evidencia_fotografia1,
                     nombre_archivo, gestion_contador, evidencia_contador) VALUES('$acreditado_nombre', '$folio', '$municipio', '$garantia', '$acreditado_telefono', '$acreditado_email',
                                            '$direccion_negocio', '$direccion_particular', '$aval_nombre', '$aval_telefono', '$aval_email', '$aval_direccion', '$gestion_fecha',
                                            '$gestion_via', '$gestion_comentarios', '$evidencia_fecha', '$evidencia_fotografia', '$nombre_archivo', '$new_management_counter', '$new_evidence_counter');";

// Validation of query
                    if (mysqli_query($conn, $sql)) {

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

                            if (file_exists($ruta_guardado)) {
                                header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
                                header('Content-Disposition: attachment; filename="' . "$nombre_archivo_decodificado" . '"');
                                header('Content-Length: ' . filesize($ruta_guardado));
                                ob_clean();
                                flush();
                                // Send generated file stored in the server to the browser
                                readfile($ruta_guardado);
                                exit;
                            }
                        }
                    } else {
                        echo 'Error de consulta: ' . mysqli_error($conn);
                    }
                }

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
    <title>Microyuc | Agregar gestión</title>
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
                <h1 class="main__title">Agregar gestión a <?= $bitacora[0]['acreditado_nombre']; ?></h1>
                <a href="bitacoras.php" class="main__btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Gestionar bitácoras
                </a>
            </div>
            <div>
                <form class="form" action="agregar-gestion.php?id=<?= $bitacora[0]['id'] ?>" method="post"
                      enctype="multipart/form-data">
                    <fieldset class="form__fieldset form__fieldset--process">
                        <legend class="form__legend">Gestión</legend>
                        <div class="form__division">
                            <label class="form__label" for="gestion_fecha<?= $new_management_counter ?>">Fecha<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="gestion_fecha<?= $new_management_counter ?>"
                                   name="gestion_fecha<?= $new_management_counter ?>"
                                   value="<?= htmlspecialchars($gestion['gestion_fecha' . $new_management_counter]) ?>"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="gestion_via<?= $new_management_counter ?>">Vía<span
                                        class="asterisk">*</span>:
                            </label>
                            <select class="form__input" id="gestion_via<?= $new_management_counter ?>"
                                    name="gestion_via<?= $new_management_counter ?>" required>
                                <?php foreach ($tipos_gestion as $vias) : ?>
                                    <option value="<?= htmlspecialchars($vias) ?>" <?= $gestion['gestion_via' . $new_management_counter] === $vias ? 'selected' : '' ?>><?= htmlspecialchars($vias) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="gestion_comentarios<?= $new_management_counter ?>">Comentarios/Resultados:
                            </label>
                            <input class="form__input" type="text"
                                   id="gestion_comentarios<?= $new_management_counter ?>"
                                   name="gestion_comentarios<?= $new_management_counter ?>"
                                   value="<?= htmlspecialchars($gestion['gestion_comentarios' . $new_management_counter]) ?>">
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--evidence">
                        <legend class="form__legend">Evidencias</legend>
                        <div class="form__division">
                            <label class="form__label" for="evidencia_fecha<?= $new_evidence_counter ?>">Fecha:
                            </label>
                            <input class="form__input" type="date" id="evidencia_fecha<?= $new_evidence_counter ?>"
                                   name="evidencia_fecha<?= $new_evidence_counter ?>"
                                <?= $gestion['evidencia_fecha' . $new_evidence_counter] ? 'value=' . htmlspecialchars($gestion["evidencia_fecha" . $new_evidence_counter]->format('Y-m-d')) : '' ?>>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="evidencia_fotografia<?= $new_evidence_counter ?>">Fotografía:
                            </label>
                            <input class="form__input form__input--file" type="file"
                                   id="evidencia_fotografia<?= $new_evidence_counter ?>"
                                   name="evidencia_fotografia<?= $new_evidence_counter ?>">
                        </div>
                    </fieldset>
                    <div class="form__container--btn">
                        <button class="container__btn--reset" type="reset">Limpiar</button>
                        <input class="container__btn--submit" type="submit" value="Generar archivo">
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>
