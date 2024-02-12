<?php
require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

// Número total de gestiones
$column_number = 0;

// Arreglo para generar la tabla de excel
$bitacoras = [];
$bitacoras = [
    ['N.º', 'Fecha de creación', 'Nombre', 'Folio', 'Municipio', 'Localidad', 'Tipo de garantía', 'Garantía', 'Número de teléfono', 'Correo electrónico', 'Nombre del aval', 'Fecha de gestión', 'Vía de gestión', 'Comentarios de gestión', 'Fecha de evidencia', 'Fotografía de evidencia'],
];

$sql = "SELECT * FROM bitacora;";
$res = mysqli_query($conn, $sql);
$columnas = mysqli_fetch_all($res, MYSQLI_ASSOC);

// Conseguir el número total de columnas de gestiones que hay en la base de datos
if (!empty($columnas[0])) {
    foreach (array_keys($columnas[0]) as $key) {
        if (str_contains($key, 'gestion_via')) {
            $column_number++;
        }
    }
}

// Crear nueva variable con la tabla de bitácoras de la base de datos
$bitacora = mysqli_fetch_all($res, MYSQLI_ASSOC);

// Si el número de filas es mayor a 0, añadir al arreglo de bitácoras los valores de todas las filas de la base de datos
if (mysqli_num_rows($res) > 0) {
    foreach ($res as $row) {
        $bitacoras[] = array_values($row);
        // Añadir al arreglo las gestiones de cada fila como arreglos separados
        for ($i = 2; $i <= $column_number; $i++) {
            if (!empty($row['gestion_fecha' . $i])) {
                $bitacoras[] = ['', '', '', '', '', '', '', '', '', '', '', $row['gestion_fecha' . $i], $row['gestion_via' . $i], $row['gestion_comentarios' . $i], $row['evidencia_fecha' . $i], $row['evidencia_fotografia' . $i]];
            }
        }
    }
}

// Formatear las fechas
foreach ($bitacoras as &$row) {
    for ($i = 11; $i < count($row); $i++) {
        if (DateTime::createFromFormat('Y-m-d', $row[$i]) !== false) {
            $row[$i] = date('d/m/Y', strtotime($row[$i]));
        }
    }
}

// Eliminar las columnas innecesarias
foreach ($bitacoras as &$row) {
    unset($row[10], $row[11], $row[13], $row[14], $row[15], $row[19], $row[22]);
}

// Declarar nombre con el que se va a guardar el archivo
$filename = 'Reporte_de_bitácoras.xlsx';

// Instanciar la clase SimpleXLSXGen
$xlsx = new SimpleXLSXGen();

// Agregar las filas al archivo Excel
$xlsx->addRows($bitacoras);

// Descargar el archivo
$xlsx->downloadAs($filename);
?>
