<?php
require './librerias/phpword/vendor/autoload.php';

$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-carta.docx');

$templateProcessor->setValue('numero_expediente', 'prueba nueva');
$templateProcessor->setValue('nombre_cliente', 'prueba nueva');
$templateProcessor->setValue('calle', 'prueba nueva');
$templateProcessor->setValue('cruzamientos', 'prueba nueva');
$templateProcessor->setValue('numero_direccion', 'prueba nueva');
$templateProcessor->setValue('colonia_fraccionamiento', 'prueba nueva');
$templateProcessor->setValue('localidad', 'prueba nueva');
$templateProcessor->setValue('municipio', 'prueba nueva');
$templateProcessor->setValue('fecha_firma', 'prueba nueva');
$templateProcessor->setValue('tipo_credito', 'prueba nueva');
$templateProcessor->setValue('fecha_otorgamiento', 'prueba nueva');
$templateProcessor->setValue('monto_inicial', 'prueba nueva');
$templateProcessor->setValue('mensualidades_vencidas', 'prueba nueva');
$templateProcessor->setValue('adeudo_total', 'prueba nueva');

header('Content-Disposition: attachment; filename="${numero_expediente}.docx"');
$templateProcessor->saveAs("php://output");
?>
