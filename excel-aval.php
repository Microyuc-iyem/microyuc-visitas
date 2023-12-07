<?php
require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

$tz_CMX = new DateTimeZone('America/Mexico_City');
$CMX = new DateTime('now', $tz_CMX);
$current_timestamp = $CMX->format('d-m-Y');

$avales = [];
$avales = [
    ['<b>N.°</b>', '<b>Fecha de creación</b>', '<b>Fecha de visita</b>', '<b>Folio</b>', '<b>Nombre</b>', '<b>Nombre del Aval</b>', '<b>Colonia/Fraccionamiento', '<b>Localidad</b>', '<b>Municipio</b>', '<b>Fecha de firma de anexos</b>',  '<b>Fecha de pago inicial</b>', '<b>Fecha de pago final</b>', '<b>Modalidad</b>', '<b>Fecha de otorgamiento</b>', '<b>Monto inicial</b>', '<b>Mensualidades vencidas</b>', '<b>Adeudo total</b>',],
];

$sql = 'SELECT * FROM aval;';
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res) > 0) {
    foreach ($res as $row) {
        $avales[] = array_values($row);
    }
}

for ($i = 1; $i < count($avales); $i++) {
    $avales[$i][0] = '<b>' . $i . '</b>';
    $avales[$i][1] = date('d-m-Y H:i:s', strtotime($avales[$i][1]));
    $avales[$i][2] = $avales[$i][2] ? date('d-m-Y', strtotime($avales[$i][2])) : '';
    $avales[$i][11] = date('d-m-Y', strtotime($avales[$i][11]));
    $avales[$i][13] = number_format($avales[$i][13], 2);
    $avales[$i][14] = ucfirst($avales[$i][14]);
    $avales[$i][15] = date('m-Y', strtotime($avales[$i][15]));
    $avales[$i][16] = date('m-Y', strtotime($avales[$i][16]));
    $avales[$i][19] = date('d-m-Y', strtotime($avales[$i][19]));
    $avales[$i][20] = number_format($avales[$i][20], 2);
    $avales[$i][22] = number_format($avales[$i][22], 2);
    unset($avales[$i][5]);
    unset($avales[$i][6]);
    unset($avales[$i][7]);
    unset($avales[$i][23]);
}

$filename = 'Reporte de cartas de avales' . $current_timestamp . '.xlsx';

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($avales);
$xlsx->downloadAs($filename);
