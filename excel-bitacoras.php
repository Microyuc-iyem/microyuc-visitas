<?php
require 'conexion.php';
require './includes/SimpleXLSXGen.php';

$tz_CMX = new DateTimeZone('America/Mexico_City');
$CMX = new DateTime('now', $tz_CMX);
$current_timestamp = $CMX->format('d-m-Y');

$bitacoras = [];
$bitacoras = [
    ['<b>N.º</b>', '<b>Fecha de creación</b>', '<b>Nombre</b>', '<b>Folio</b>', '<b>Municipio</b>', '<b>Localidad</b>', '<b>Tipo de garantía</b>', '<b>Garantía</b>', '<b>Número de teléfono</b>', '<b>Correo electrónico</b>', '<b>Nombre del aval</b>', '<b>Fecha de gestión</b>', '<b>Vía de gestión</b>', '<b>Comentarios de gestión</b>', '<b>Fecha de evidencia</b>', '<b>Fotografía de evidencia</b>',],
];

$sql = "SELECT * FROM bitacora;";
$res = pg_query($conn, $sql);
$columnas = pg_fetch_all($res);

$column_number = 0;

if (!empty($columnas[0])) {
    foreach (array_keys($columnas[0]) as $key) {
        if (strpos($key, 'gestion_via') !== false) {
            $column_number++;
        }
    }
}

$bitacora = pg_fetch_all($res);

if (pg_num_rows($res) > 0) {
    foreach ($res as $row) {
        $bitacoras[] = array_values($row);
        for ($i = 2; $i <= $column_number; $i++) {
            if (!empty($row['gestion_fecha' . $i])) {
                $bitacoras[] = ['', '', '', '', '', '', '', '', '', '', '', $row['gestion_fecha' . $i], $row['gestion_via' . $i], $row['gestion_comentarios' . $i], $row['evidencia_fecha' . $i], $row['evidencia_fotografia' . $i]];
            }
        }
    }
}

$id_counter = 1;

for ($i = 1; $i < count($bitacoras); $i++) {
    for ($j = 11; $j < count($bitacoras[$i]); $j++) {
        if (DateTime::createFromFormat('Y-m-d', $bitacoras[$i][$j]) !== false) {
            $bitacoras[$i][$j] = date('d/m/Y', strtotime($bitacoras[$i][$j]));
        }
    }
    if (is_numeric($bitacoras[$i][0])) {
        $bitacoras[$i][0] = '<b>' . $id_counter . '</b>';
        $bitacoras[$i][1] = date('d/m/Y H:i:s', strtotime($bitacoras[$i][1]));

        $num = count($bitacoras[$i]);
        for ($j = 23; $j <= $num; $j++) {
            unset($bitacoras[$i][$j]);
        }
        unset($bitacoras[$i][10]);
        unset($bitacoras[$i][11]);
        unset($bitacoras[$i][13]);
        unset($bitacoras[$i][14]);
        unset($bitacoras[$i][15]);
        unset($bitacoras[$i][19]);
        unset($bitacoras[$i][22]);
        $id_counter++;
    }
}

$filename = 'Reporte de bitácoras ' . $current_timestamp . '.xlsx';

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($bitacoras);
$xlsx->downloadAs($filename);
?>

