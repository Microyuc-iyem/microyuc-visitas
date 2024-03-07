<?php
require './config/db_connect.php';
require './includes/SimpleXLSXGen.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);


$tz_CMX = new DateTimeZone('America/Mexico_City');
$CMX = new DateTime('now', $tz_CMX);
$current_timestamp = $CMX->format('d-m-Y');

//$aval = [];
//$aval = [
 //   ['<b>N.°</b>', '<b>Fecha de creación</b>', '<b>Fecha de visita</b>', '<b>Folio</b>', '<b>Nombre del Cliente</b>', '<b>Nombre del Aval</b>', '<b>Colonia/Fraccionamiento', '<b>Localidad</b>', '<b>Municipio</b>', '<b>Fecha de firma de anexos</b>',  '<b>Fecha de pago inicial</b>', '<b>Fecha de pago final</b>', '<b>Modalidad</b>', '<b>Fecha de otorgamiento</b>', '<b>Monto inicial</b>', '<b>Mensualidades vencidas</b>', '<b>Adeudo total</b>',],
//];

//$sql = 'SELECT * FROM aval;';
//$res = mysqli_query($conn, $sql);
//if (mysqli_num_rows($res) > 0) {
  //  foreach ($res as $row) {
    //    $aval[] = array_values($row);
    //}
//}




// Consulta SQL para obtener todos los datos de la tabla `aval`
$sql = "SELECT * FROM aval";
$resultado = mysqli_query($conn, $sql);

// Verificar si la consulta fue exitosa
if (!$resultado) {
    die('Error en la consulta SQL: ' . mysqli_error($conn));
}

// Arreglo para almacenar los datos
$datos = [];

// Agregar la primera fila con los nombres de las columnas
$datos[] = [
    'ID',
    'Fecha de creación',
    'Fecha de visita',
    'Número de expediente',
    'Nombre del cliente',
    'Nombre del aval',
    'Calle',
    'Cruzamientos',
    'Número de dirección',
    'Colonia/Fraccionamiento',
    'Localidad',
    'Municipio',
    'Fecha de firma',
    'Pagos Fecha Inicial',
    'Pagos Fecha Final',
    'Modalidad',
    'Tipo de crédito',
    'Fecha de otorgamiento',
    'Monto inicial',
    'Mensualidades vencidas',
    'Adeudo total',
    
];

// Obtener los datos de la consulta y agregarlos al arreglo
while ($fila = mysqli_fetch_assoc($resultado)) {
    $datos[] = [
        $fila['id'],
        $fila['fecha_creacion'],
        $fila['fecha_visita'],
        $fila['numero_expediente'],
        $fila['nombre_cliente'],
        $fila['nombre_aval'],
        $fila['calle'],
        $fila['cruzamientos'],
        $fila['numero_direccion'],
        $fila['colonia_fraccionamiento'],
        $fila['localidad'],
        $fila['municipio'],
        $fila['fecha_firma'],
        $fila['pagos_fecha_inicial'],
        $fila['pagos_fecha_final'],
        $fila['modalidad'],
        $fila['tipo_credito'],
        $fila['fecha_otorgamiento'],
        $fila['monto_inicial'],
        $fila['mensualidades_vencidas'],
        $fila['adeudo_total'],
  
    ];
}

// Declarar nombre con el que se va a guardar el archivo
$filename = 'Reporte_de_aval.xlsx';

// Crear el archivo Excel con los datos y mandar el archivo a descargar desde el navegador
$xlsx = Shuchkin\SimpleXLSXGen::fromArray($datos);
$xlsx->downloadAs($filename);

?>

