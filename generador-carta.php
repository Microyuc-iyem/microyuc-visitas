<?php
require './config/db_connect.php';
require './librerias/phpword/vendor/autoload.php';
require './includes/functions.php';

check_login();

if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_POST)) && (!empty($_POST))) {

    $fmt = set_date_format();

// Assign post received inputs to variables
    $numero_expediente = $_POST['numero_expediente'] ?? false;
    $nombre_cliente = $_POST['nombre_cliente'] ?? false;
    $calle = $_POST['calle'] ?? '';
    $cruzamientos = $_POST['cruzamientos'] ?? '';
    $numero_direccion = $_POST['numero_direccion'] ?? '';
    $colonia_fraccionamiento = $_POST['colonia_fraccionamiento'] ?? '';
    $localidad = $_POST['localidad'] ?? false;
    $municipio = $_POST['municipio'] ?? false;
    $fecha_firma = $_POST['fecha_firma'] ?? false;
    $documentacion = $_POST['documentacion'] ?? '';
    $comprobacion_monto = is_numeric($_POST['comprobacion_monto']) ? $_POST['comprobacion_monto'] : false;
    $comprobacion_tipo = $_POST['comprobacion_tipo'] ?? false;
    $pagos_fecha_inicial = $_POST['pagos_fecha_inicial'] ?? false;
    $pagos_fecha_final = $_POST['pagos_fecha_final'] ?? false;
    $tipo_credito = $_POST['tipo_credito'] ?? false;
    $fecha_otorgamiento = $_POST['fecha_otorgamiento'] ?? false;
    $monto_inicial = is_numeric($_POST['monto_inicial']) ? $_POST['monto_inicial'] : false;
    $adeudo_total = is_numeric($_POST['adeudo_total']) ? $_POST['adeudo_total'] : false;

// Create variable with filename
    if (preg_match('/(^IYE{1,1})([\d\-]+)/', $numero_expediente)) {
        $nombre_archivo = $numero_expediente . ' ' . $nombre_cliente . '.docx';
    }
// Encode filename so that UTF-8 characters work
    if (isset($nombre_archivo)) {
        $nombre_archivo_decodificado = rawurlencode($nombre_archivo);
    }

// Create a date using the dates recieved by post
    $pagos_fecha_inicial_conv = date_create($pagos_fecha_inicial);
    $pagos_fecha_final_conv = date_create($pagos_fecha_final);

// Add 1 day to the created days, so it's easier to calculate the difference between dates
    date_add($pagos_fecha_inicial_conv, date_interval_create_from_date_string('1 day'));
    date_add($pagos_fecha_final_conv, date_interval_create_from_date_string('1 day'));

// Calculate the month interval diff
    $intervalo_meses = $pagos_fecha_final_conv->diff($pagos_fecha_inicial_conv);
// Calculation so that it only gives the total in months
    $total_meses = 12 * $intervalo_meses->y + $intervalo_meses->m;
// Assign the total months to variable to set the value in the template
    $mensualidades_vencidas = $total_meses + 1;

// Create new instance of PHPWord template processor using the required template file
    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-carta.docx');

// Set values in template with post received inputs and calculated variables
    $templateProcessor->setValue('numero_expediente', $numero_expediente);
    $templateProcessor->setValue('nombre_cliente', $nombre_cliente);
    $templateProcessor->setValue('calle', $calle);
    $templateProcessor->setValue('cruzamientos', $cruzamientos);
    $templateProcessor->setValue('numero_direccion', $numero_direccion);
    $templateProcessor->setValue('colonia_fraccionamiento', $colonia_fraccionamiento);
    $templateProcessor->setValue('localidad', $localidad);
    $templateProcessor->setValue('municipio', $municipio);
    $templateProcessor->setValue('fecha_firma', date("d-m-Y", strtotime($_POST['fecha_firma'])));
    $templateProcessor->setValue('documentacion', $documentacion);
    $templateProcessor->setValue('comprobacion_monto', number_format($comprobacion_monto, 2));
    $templateProcessor->setValue('comprobacion_tipo', $comprobacion_tipo);
    $templateProcessor->setValue('pagos_fecha_inicial', datefmt_format($fmt, $pagos_fecha_inicial_conv));
    $templateProcessor->setValue('pagos_fecha_final', datefmt_format($fmt, $pagos_fecha_final_conv));
    $templateProcessor->setValue('tipo_credito', $tipo_credito);
    $templateProcessor->setValue('fecha_otorgamiento', date("d-m-Y", strtotime($_POST['fecha_otorgamiento'])));
    $templateProcessor->setValue('monto_inicial', number_format($monto_inicial, 2));
    $templateProcessor->setValue('mensualidades_vencidas', $mensualidades_vencidas);
    $templateProcessor->setValue('adeudo_total', number_format($adeudo_total, 2));

// Escape strings to insert into the database table
    $numero_expediente = mysqli_real_escape_string($conn, $_POST['numero_expediente']);
    $nombre_cliente = mysqli_real_escape_string($conn, $_POST['nombre_cliente']);
    $calle = mysqli_real_escape_string($conn, $_POST['calle']);
    $cruzamientos = mysqli_real_escape_string($conn, $_POST['cruzamientos']);
    $numero_direccion = mysqli_real_escape_string($conn, $_POST['numero_direccion']);
    $colonia_fraccionamiento = mysqli_real_escape_string($conn, $_POST['colonia_fraccionamiento']);
    $localidad = mysqli_real_escape_string($conn, $_POST['localidad']);
    $municipio = mysqli_real_escape_string($conn, $_POST['municipio']);
    $fecha_firma = mysqli_real_escape_string($conn, $_POST['fecha_firma']);
    $documentacion = mysqli_real_escape_string($conn, $_POST['documentacion']);
    $comprobacion_monto = mysqli_real_escape_string($conn, $_POST['comprobacion_monto']);
    $comprobacion_tipo = mysqli_real_escape_string($conn, $_POST['comprobacion_tipo']);
    $pagos_fecha_inicial = mysqli_real_escape_string($conn, $_POST['pagos_fecha_inicial']);
    $pagos_fecha_final = mysqli_real_escape_string($conn, $_POST['pagos_fecha_final']);
    $tipo_credito = mysqli_real_escape_string($conn, $_POST['tipo_credito']);
    $fecha_otorgamiento = mysqli_real_escape_string($conn, $_POST['fecha_otorgamiento']);
    $monto_inicial = mysqli_real_escape_string($conn, $_POST['monto_inicial']);
    $mensualidades_vencidas = mysqli_real_escape_string($conn, $mensualidades_vencidas);
    $adeudo_total = mysqli_real_escape_string($conn, $_POST['adeudo_total']);

// Query
    $sql = "INSERT INTO carta(numero_expediente, nombre_cliente, calle, cruzamientos, numero_direccion, colonia_fraccionamiento, localidad, municipio, fecha_firma,
                  documentacion, comprobacion_monto, comprobacion_tipo, pagos_fecha_inicial, pagos_fecha_final, tipo_credito, fecha_otorgamiento, monto_inicial,
                  mensualidades_vencidas, adeudo_total, nombre_archivo) VALUES('$numero_expediente', '$nombre_cliente', '$calle', '$cruzamientos', '$numero_direccion', '$colonia_fraccionamiento', '$localidad', '$municipio', '$fecha_firma',
                                                               '$documentacion', '$comprobacion_monto', '$comprobacion_tipo', '$pagos_fecha_inicial', '$pagos_fecha_final', '$tipo_credito', '$fecha_otorgamiento', '$monto_inicial',
                                                               '$mensualidades_vencidas', '$adeudo_total', '$nombre_archivo')";

// Validation of query
    if (mysqli_query($conn, $sql)) {

        // Path where generated file is saved
        $ruta_guardado = './files/cartas/' . $nombre_archivo;
        $templateProcessor->saveAs($ruta_guardado);

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . "$nombre_archivo_decodificado" . '"');
        header('Content-Length: ' . filesize($ruta_guardado));
        ob_clean();
        flush();
        // Send generated file stored in the server to the browser
        readfile($ruta_guardado);
        exit;
    } else {
        echo 'Error de consulta: ' . mysqli_error($conn);
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
    <link rel="stylesheet" href="./dist/css/styles.css">
    <title>Microyuc | Generador de cartas</title>
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
                    <li><a href="./generador-carta.php" class="sidebar__link sidebar__link--active">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Cartas</span></a></li>
                    <li><a href="./generador-bitacora.php" class="sidebar__link">
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
                <h1 class="main__title">Generador de cartas</h1>
                <a href="./cartas.php" class="main__btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Gestionar cartas
                </a>
            </div>
            <div>
                <form class="form" action="generador-carta.php" method="post">
                    <fieldset class="form__fieldset form__fieldset--accredited">
                        <legend class="form__legend">Información del acreditado</legend>
                        <div class="form__division">
                            <label class="form__label" for="numero_expediente">Número de expediente<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="text" id="numero_expediente"
                                   name="numero_expediente" pattern="(^IYE{1,1})([\d\-]+)" value="IYE" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="nombre_cliente">Nombre del cliente<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="text" id="nombre_cliente"
                                   name="nombre_cliente"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="calle">Calle: </label>
                            <input class="form__input" type="text" id="calle" name="calle">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="cruzamientos">Cruzamientos: </label>
                            <input class="form__input" type="text" id="cruzamientos" name="cruzamientos">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="numero_direccion">Número: </label>
                            <input class="form__input" type="text" id="numero_direccion"
                                   name="numero_direccion">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="colonia_fraccionamiento">Colonia/fraccionamiento: </label>
                            <input class="form__input" type="text" id="colonia_fraccionamiento"
                                   name="colonia_fraccionamiento">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="localidad">Localidad<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="localidad" name="localidad"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="municipio">Municipio<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="municipio" name="municipio"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="fecha_firma">Fecha de firma de anexos<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="fecha_firma" name="fecha_firma"
                                   required>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset">
                        <legend class="form__legend">Documentación</legend>
                        <div class="form__division">
                            <label class="form__label" for="documentacion"></label>
                            <textarea class="form__input" id="documentacion"
                                      name="documentacion"></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--verification">
                        <legend class="form__legend">Comprobación</legend>
                        <div class="form__division">
                            <label class="form__label" for="comprobacion_monto">Monto de comprobación<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="number" id="comprobacion_monto"
                                   name="comprobacion_monto" step="0.01" min="0" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="comprobacion_tipo">Tipo de comprobación<span
                                        class="asterisk">*</span>: </label>
                            <select class="form__input" id="comprobacion_tipo" name="comprobacion_tipo" required>
                                <option value="capital de trabajo">Capital de trabajo</option>
                                <option value="activo fijo">Activo fijo</option>
                                <option value="adecuaciones">Adecuaciones</option>
                                <option value="insumos">Insumos</option>
                                <option value="certificaciones">Certificaciones</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--payment">
                        <legend class="form__legend">Pagos</legend>
                        <div class="form__division">
                            <label class="form__label" for="pagos_fecha_inicial">Fecha inicial<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="month" id="pagos_fecha_inicial"
                                   name="pagos_fecha_inicial" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="pagos_fecha_final">Fecha final<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="month" id="pagos_fecha_final"
                                   name="pagos_fecha_final"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="tipo_credito">Tipo de crédito<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="tipo_credito" name="tipo_credito"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="fecha_otorgamiento">Fecha de otorgamiento del crédito<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="fecha_otorgamiento"
                                   name="fecha_otorgamiento" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="monto_inicial">Monto inicial<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="number" id="monto_inicial" name="monto_inicial" step="0.01"
                                   min="0"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="adeudo_total">Adeudo total<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="number" id="adeudo_total" name="adeudo_total" step="0.01"
                                   min="0"
                                   required>
                        </div class="form__division">
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