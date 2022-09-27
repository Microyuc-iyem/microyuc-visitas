<?php
require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

$tz_CMX = new DateTimeZone('America/Mexico_City');
$CMX = new DateTime('now', $tz_CMX);
$current_timestamp = $CMX->format('d-m-Y');

$cartas = [];
$cartas = [
    ['<b>N.°</b>', '<b>Fecha de creación</b>', '<b>Folio</b>', '<b>Nombre</b>', '<b>Colonia/Fraccionamiento', '<b>Localidad</b>', '<b>Municipio</b>', '<b>Fecha de firma de anexos</b>', '<b>Documentación</b>', '<b>Monto de comprobación</b>', '<b>Tipo de comprobación</b>', '<b>Fecha de pago inicial</b>', '<b>Fecha de pago final</b>', '<b>Modalidad</b>', '<b>Tipo de crédito</b>', '<b>Fecha de otorgamiento</b>', '<b>Monto inicial</b>', '<b>Mensualidades vencidas</b>', '<b>Adeudo total</b>',],
];

$sql = 'SELECT * FROM carta;';
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res) > 0) {
    foreach ($res as $row) {
        $cartas[] = array_values($row);
    }
}

for ($i = 1; $i < count($cartas); $i++) {
    $cartas[$i][0] = $i;
    $cartas[$i][1] = date('d-m-Y H:i:s', strtotime($cartas[$i][1]));
    $cartas[$i][10] = date('d-m-Y', strtotime($cartas[$i][10]));
    $cartas[$i][12] = number_format($cartas[$i][12], 2);
    $cartas[$i][13] = ucfirst($cartas[$i][13]);
    $cartas[$i][14] = date('m-Y', strtotime($cartas[$i][14]));
    $cartas[$i][15] = date('m-Y', strtotime($cartas[$i][15]));
    $cartas[$i][18] = date('d-m-Y', strtotime($cartas[$i][18]));
    $cartas[$i][19] = number_format($cartas[$i][19], 2);
    $cartas[$i][21] = number_format($cartas[$i][21], 2);
    unset($cartas[$i][4]);
    unset($cartas[$i][5]);
    unset($cartas[$i][6]);
    unset($cartas[$i][22]);
}

$filename = 'Reporte de cartas ' . $current_timestamp . '.xlsx';

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($cartas);
$xlsx->downloadAs($filename);
