<?php

require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

$bitacoras = [];
$bitacoras = [
    ['ID', 'Fecha de creación', 'Nombre', 'Folio', 'Municipio', 'Garantía', 'Número de teléfono', 'Correo electrónico', 'Dirección de negocio', 'Dirección particular', 'Nombre del aval', 'Número de teléfono del aval', 'Correo electrónico del aval', 'Dirección del aval',],
];
$sql = 'SELECT * FROM bitacora';
$res = mysqli_query($conn, $sql);
$bitacora = mysqli_fetch_all($res, MYSQLI_ASSOC);
$sql_count = "SELECT COUNT(*) AS num FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'bitacora' AND TABLE_SCHEMA = 'microyuc_project' AND COLUMN_NAME LIKE 'gestion_via%';";
$res_count = mysqli_query($conn, $sql_count);
$column_number = mysqli_fetch_all($res_count, MYSQLI_ASSOC);

for ($i = 1; $i <= $column_number[0]['num']; $i++) {
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
    $bitacoras[$i][1] = date('d-m-Y H:i:s', strtotime($bitacoras[$i][1]));
    for ($j = 14; $j < count($bitacoras[$i]); $j++) {
        if (DateTime::createFromFormat('Y-m-d', $bitacoras[$i][$j]) !== false) {
            echo $bitacoras[$i][$j];
            echo '<br>';
            $bitacoras[$i][$j] = date('d-m-Y', strtotime($bitacoras[$i][$j]));
        }
    }
    unset($bitacoras[$i][17]);
    unset($bitacoras[$i][20]);
    unset($bitacoras[$i][21]);
}

$xlsx = Shuchkin\SimpleXLSXGen::fromArray($bitacoras);
$xlsx->downloadAs('Registro de bitácoras.xlsx');
