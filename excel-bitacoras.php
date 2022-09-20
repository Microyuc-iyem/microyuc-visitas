<?php

require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

$tz_CMX = new DateTimeZone('America/Mexico_City');
$CMX = new DateTime('now', $tz_CMX);
$current_timestamp = $CMX->format('d-m-Y');

$bitacoras = [];
$bitacoras = [
    ['N.º', 'Fecha de creación', 'Nombre', 'Folio', 'Municipio', 'Localidad', 'Tipo de garantía', 'Garantía', 'Número de teléfono', 'Correo electrónico', 'Nombre del aval',],];
$sql = 'SELECT * FROM bitacora';
$res = mysqli_query($conn, $sql);
$bitacora = mysqli_fetch_all($res, MYSQLI_ASSOC);

$column_number = 0;

if (!empty($bitacora[0])) {
    foreach (array_keys($bitacora[0]) as $key) {
        if (str_contains($key, 'gestion_via')) {
            $column_number++;
        }
    }
}

for ($i = 1; $i <= $column_number; $i++) {
    $bitacoras[0][] = 'Fecha de gestión ' . $i;
    $bitacoras[0][] = 'Vía de gestión ' . $i;
    $bitacoras[0][] = 'Comentarios de gestión ' . $i;
    $bitacoras[0][] = 'Fecha de evidencia ' . $i;
    $bitacoras[0][] = 'Fotografía de evidencia ' . $i;
}


if (mysqli_num_rows($res) > 0) {
    foreach ($res as $row) {
        $bitacoras[] = array_values($row);
    }
}

for ($i = 1; $i < count($bitacoras); $i++) {
    $bitacoras[$i][0] = $i;
    $bitacoras[$i][1] = date('d-m-Y H:i:s', strtotime($bitacoras[$i][1]));
    for ($j = 12; $j < count($bitacoras[$i]); $j++) {
        if (DateTime::createFromFormat('Y-m-d', $bitacoras[$i][$j]) !== false) {
            $bitacoras[$i][$j] = date('d-m-Y', strtotime($bitacoras[$i][$j]));
        }
    }
    unset($bitacoras[$i][10]);
    unset($bitacoras[$i][11]);
    unset($bitacoras[$i][13]);
    unset($bitacoras[$i][14]);
    unset($bitacoras[$i][15]);
    unset($bitacoras[$i][19]);
    unset($bitacoras[$i][22]);
    unset($bitacoras[$i][23]);
}

$filename = 'Reporte de bitácoras ' . $current_timestamp . '.xlsx';

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($bitacoras);
$xlsx->downloadAs($filename);
