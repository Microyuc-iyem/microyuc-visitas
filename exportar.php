<?php
require './librerias/phpword/vendor/autoload.php';

$numero_expediente = $_POST['numero_expediente'];
$nombre_cliente = $_POST['nombre_cliente'];
$calle = $_POST['calle'];
$cruzamientos = $_POST['cruzamientos'];
$numero_direccion = $_POST['numero_direccion'];
$colonia_fraccionamiento = $_POST['colonia_fraccionamiento'];
$localidad = $_POST['localidad'];
$municipio = $_POST['municipio'];
$fecha_firma = $_POST['fecha_firma'];
$tipo_credito = $_POST['tipo_credito'];
$fecha_otorgamiento = $_POST['fecha_otorgamiento'];
$monto_inicial = $_POST['monto_inicial'];
$mensualidades_vencidas = $_POST['mensualidades_vencidas'];
$adeudo_total = $_POST['adeudo_total'];
$nombre_archivo = 'IYE' . $_POST['numero_expediente'] . ' ' . $_POST['nombre_cliente'] . '.docx';

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-carta.docx');

$templateProcessor->setValue('numero_expediente', $numero_expediente);
$templateProcessor->setValue('nombre_cliente', $nombre_cliente);
$templateProcessor->setValue('calle', $calle);
$templateProcessor->setValue('cruzamientos', $cruzamientos);
$templateProcessor->setValue('numero_direccion', $numero_direccion);
$templateProcessor->setValue('colonia_fraccionamiento', $colonia_fraccionamiento);
$templateProcessor->setValue('localidad', $localidad);
$templateProcessor->setValue('municipio', $municipio);
$templateProcessor->setValue('fecha_firma', date("d-m-Y", strtotime($fecha_firma)));
$templateProcessor->setValue('tipo_credito', $tipo_credito);
$templateProcessor->setValue('fecha_otorgamiento', date("d-m-Y", strtotime($fecha_otorgamiento)));
$templateProcessor->setValue('monto_inicial', $monto_inicial);
$templateProcessor->setValue('mensualidades_vencidas', $mensualidades_vencidas);
$templateProcessor->setValue('adeudo_total', $adeudo_total);

header("Content-Disposition: attachment; filename=$nombre_archivo");
$templateProcessor->saveAs("php://output");
?>
