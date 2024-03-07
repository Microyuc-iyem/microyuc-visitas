<?php
require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


$tz_CMX = new DateTimeZone('America/Mexico_City');
$CMX = new DateTime('now', $tz_CMX);
$current_timestamp = $CMX->format('d-m-Y');

$aval = [];
$aval = [
    ['<b>N.°</b>', '<b>Fecha de creación</b>', '<b>Fecha de visita</b>', '<b>Folio</b>', '<b>Nombre</b>', '<b>Nombre del Aval</b>', '<b>Colonia/Fraccionamiento', '<b>Localidad</b>', '<b>Municipio</b>', '<b>Fecha de firma de anexos</b>',  '<b>Fecha de pago inicial</b>', '<b>Fecha de pago final</b>', '<b>Modalidad</b>', '<b>Fecha de otorgamiento</b>', '<b>Monto inicial</b>', '<b>Mensualidades vencidas</b>', '<b>Adeudo total</b>',],
];

$sql = 'SELECT * FROM aval;';
$res = mysqli_query($conn, $sql);
if (mysqli_num_rows($res) > 0) {
    foreach ($res as $row) {
        $aval[] = array_values($row);
    }
}

for ($i = 1; $i < count($aval); $i++) {
    $aval[$i][0] = '<b>' . $i . '</b>';
    $aval[$i][1] = date('d-m-Y H:i:s', strtotime($aval[$i][1]));
    $aval[$i][2] = $aval[$i][2] ? date('d-m-Y', strtotime($aval[$i][2])) : '';
    $aval[$i][11] = date('d-m-Y', strtotime($aval[$i][11]));
    //Convertir a número
                       $aval[$i][13] = number_format(intval($aval[$i][13]), 2); 
    $aval[$i][14] = ucfirst($aval[$i][14]);
    $aval[$i][15] = date('m-Y', strtotime($aval[$i][15]));
    $aval[$i][16] = date('m-Y', strtotime($aval[$i][16]));
    $aval[$i][19] = date('d-m-Y', strtotime($aval[$i][19]));
    $aval[$i][20] = number_format($aval[$i][20], 2);
    $aval[$i][22] = number_format($aval[$i][22], 2);
    unset($aval[$i][5]);
    unset($aval[$i][6]);
    unset($aval[$i][7]);
    unset($aval[$i][23]);
}

$filename = 'Reporte de cartas de avales.xlsx';

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($cartas);
$xlsx->downloadAs($filename);
