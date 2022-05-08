<?php

require './librerias/phpword/vendor/autoload.php';
include './config/db_connect.php';

$fmt = datefmt_create(
    'es-MX',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'America/Mexico_City',
    IntlDateFormatter::GREGORIAN,
    "MMMM 'de' yyyy"
);

$numero_expediente = $_POST['numero_expediente'];
$nombre_cliente = $_POST['nombre_cliente'];
$calle = $_POST['calle'];
$cruzamientos = $_POST['cruzamientos'];
$numero_direccion = $_POST['numero_direccion'];
$colonia_fraccionamiento = $_POST['colonia_fraccionamiento'];
$localidad = $_POST['localidad'];
$municipio = $_POST['municipio'];
$fecha_firma = $_POST['fecha_firma'];
$documentacion = $_POST['documentacion'];
$comprobacion_monto = $_POST['comprobacion_monto'];
$comprobacion_tipo = $_POST['comprobacion_tipo'];
$pagos_fecha_inicial = $_POST['pagos_fecha_inicial'];
$pagos_fecha_final = $_POST['pagos_fecha_final'];
$tipo_credito = $_POST['tipo_credito'];
$fecha_otorgamiento = $_POST['fecha_otorgamiento'];
$monto_inicial = $_POST['monto_inicial'];
$mensualidades_vencidas = $_POST['mensualidades_vencidas'];
$adeudo_total = $_POST['adeudo_total'];
$nombre_archivo = 'IYE' . $numero_expediente . ' ' . $nombre_cliente . '.docx';
$nombre_archivo_decodificado = rawurlencode($nombre_archivo);

$pagos_fecha_inicial_conv = date_create($pagos_fecha_inicial);
$pagos_fecha_final_conv = date_create($pagos_fecha_final);

date_add($pagos_fecha_inicial_conv, date_interval_create_from_date_string('1 day'));
date_add($pagos_fecha_final_conv, date_interval_create_from_date_string('1 day'));

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-carta.docx');

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
$templateProcessor->setValue('comprobacion_monto', $comprobacion_monto);
$templateProcessor->setValue('comprobacion_tipo', $comprobacion_tipo);
$templateProcessor->setValue('pagos_fecha_inicial', datefmt_format($fmt, $pagos_fecha_inicial_conv));
$templateProcessor->setValue('pagos_fecha_final', datefmt_format($fmt, $pagos_fecha_final_conv));
$templateProcessor->setValue('tipo_credito', $tipo_credito);
$templateProcessor->setValue('fecha_otorgamiento', date("d-m-Y", strtotime($_POST['fecha_otorgamiento'])));
$templateProcessor->setValue('monto_inicial', $monto_inicial);
$templateProcessor->setValue('mensualidades_vencidas', $mensualidades_vencidas);
$templateProcessor->setValue('adeudo_total', $adeudo_total);

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
$mensualidades_vencidas = mysqli_real_escape_string($conn, $_POST['mensualidades_vencidas']);
$adeudo_total = mysqli_real_escape_string($conn, $_POST['adeudo_total']);

$sql = "INSERT INTO carta(numero_expediente, nombre_cliente, calle, cruzamientos, numero_direccion, colonia_fraccionamiento, localidad, municipio, fecha_firma, 
                  documentacion, comprobacion_monto, comprobacion_tipo, pagos_fecha_inicial, pagos_fecha_final, tipo_credito, fecha_otorgamiento, monto_inicial,
                  mensualidades_vencidas, adeudo_total) VALUES('$numero_expediente', '$nombre_cliente', '$calle', '$cruzamientos', '$numero_direccion', '$colonia_fraccionamiento', '$localidad', '$municipio', '$fecha_firma',
                                                               '$documentacion', '$comprobacion_monto', '$comprobacion_tipo', '$pagos_fecha_inicial', '$pagos_fecha_final', '$tipo_credito', '$fecha_otorgamiento', '$monto_inicial',
                                                               '$mensualidades_vencidas', '$adeudo_total')";

if (mysqli_query($conn, $sql)) {
    header('Content-Disposition: attachment; filename="' . "$nombre_archivo_decodificado" . '"');
    $templateProcessor->saveAs("php://output");
} else {
    echo 'Error de consulta: ' . mysqli_error($conn);
}
?>