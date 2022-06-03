<?php
// Require database connection and PHPWord library
require_once './config/db_connect.php';
require './librerias/phpword/vendor/autoload.php';

//Set date format to replace in the docx
$fmt = datefmt_create(
    'es-MX',
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'America/Mexico_City',
    IntlDateFormatter::GREGORIAN,
    "dd 'de' MMMM 'de' yyyy"
);

// Assign post received inputs to variables
$acreditado_nombre = $_POST['acreditado_nombre'];
$folio = $_POST['folio'];
$municipio = $_POST['municipio'];
$garantia = $_POST['garantia'];
$acreditado_telefono = $_POST['acreditado_telefono'];
$acreditado_email = $_POST['acreditado_email'];
$direccion_negocio = $_POST['direccion_negocio'];
$direccion_particular = $_POST['direccion_particular'];
$aval_nombre = $_POST['aval_nombre'];
$aval_telefono = $_POST['aval_telefono'];
$aval_direccion = $_POST['aval_direccion'];
$aval_email = $_POST['aval_email'];
$gestion_fecha = $_POST['gestion_fecha'];
$gestion_via = $_POST['gestion_via'];
$gestion_comentarios = $_POST['gestion_comentarios'];
$evidencia_fecha = $_POST['evidencia_fecha'];
$evidencia_fotografia = $_FILES['evidencia_fotografia']['name'];

move_uploaded_file($_FILES['evidencia_fotografia']['tmp_name'], './uploads/' . $_FILES['evidencia_fotografia']['name']);

// Create variable with filename
$nombre_archivo = 'IYE' . $folio . ' ' . $acreditado_nombre . ' - Bitácora.docx';
// Encode filename so that UTF-8 characters work
$nombre_archivo_decodificado = rawurlencode($nombre_archivo);

// Create new instance of PHPWord template processor using the required template file
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-bitacora.docx');

// Set values in template with post received input variables
$templateProcessor->setValue('acreditado_nombre', $acreditado_nombre);
$templateProcessor->setValue('folio', $folio);
$templateProcessor->setValue('municipio', $municipio);
$templateProcessor->setValue('garantia', $garantia);
$templateProcessor->setValue('acreditado_telefono', $acreditado_telefono);
$templateProcessor->setValue('acreditado_email', $acreditado_email);
$templateProcessor->setValue('direccion_negocio', $direccion_negocio);
$templateProcessor->setValue('direccion_particular', $direccion_particular);
$templateProcessor->setValue('aval_nombre', $aval_nombre);
$templateProcessor->setValue('aval_telefono', $aval_telefono);
$templateProcessor->setValue('aval_email', $aval_email);
$templateProcessor->setValue('aval_direccion', $aval_direccion);
$templateProcessor->setValue('gestion_fecha', date("d-m-Y", strtotime($gestion_fecha)));
$templateProcessor->setValue('gestion_via', $gestion_via);
$templateProcessor->setValue('gestion_comentarios', $gestion_comentarios);
$templateProcessor->setValue('evidencia_fecha', datefmt_format($fmt, strtotime($evidencia_fecha)));
$templateProcessor->setImageValue('evidencia_fotografia', './uploads/' . $evidencia_fotografia);

// Escape strings to insert into the database table
$acreditado_nombre = mysqli_real_escape_string($conn, $_POST['acreditado_nombre']);
$folio = mysqli_real_escape_string($conn, $_POST['folio']);
$municipio = mysqli_real_escape_string($conn, $_POST['municipio']);
$garantia = mysqli_real_escape_string($conn, $_POST['garantia']);
$acreditado_telefono = mysqli_real_escape_string($conn, $_POST['acreditado_telefono']);
$acreditado_email = mysqli_real_escape_string($conn, $_POST['acreditado_email']);
$direccion_negocio = mysqli_real_escape_string($conn, $_POST['direccion_negocio']);
$direccion_particular = mysqli_real_escape_string($conn, $_POST['direccion_particular']);
$aval_nombre = mysqli_real_escape_string($conn, $_POST['aval_nombre']);
$aval_telefono = mysqli_real_escape_string($conn, $_POST['aval_telefono']);
$aval_email = mysqli_real_escape_string($conn, $_POST['aval_email']);
$aval_direccion = mysqli_real_escape_string($conn, $_POST['aval_direccion']);
$gestion_fecha = mysqli_real_escape_string($conn, $_POST['gestion_fecha']);
$gestion_via = mysqli_real_escape_string($conn, $_POST['gestion_via']);
$gestion_comentarios = mysqli_real_escape_string($conn, $_POST['gestion_comentarios']);
$evidencia_fecha = mysqli_real_escape_string($conn, $_POST['evidencia_fecha']);
$evidencia_fotografia = mysqli_real_escape_string($conn, $_FILES['evidencia_fotografia']['name']);

// Query
$sql = "INSERT INTO bitacora(acreditado_nombre, folio, municipio, garantia, acreditado_telefono, acreditado_email,
                     direccion_negocio, direccion_particular, aval_nombre, aval_telefono, aval_email, aval_direccion,
                     gestion_fecha, gestion_via, gestion_comentarios, evidencia_fecha, evidencia_fotografia,
                     nombre_archivo) VALUES('$acreditado_nombre', '$folio', '$municipio', '$garantia', '$acreditado_telefono', '$acreditado_email',
                                            '$direccion_negocio', '$direccion_particular', '$aval_nombre', '$aval_telefono', '$aval_email', '$aval_direccion', '$gestion_fecha',
                                            '$gestion_via', '$gestion_comentarios', '$evidencia_fecha', '$evidencia_fotografia', '$nombre_archivo');";

// Validation of query
if (mysqli_query($conn, $sql)) {

    // Path where generated file is saved
    $ruta_guardado = './files/bitacoras/' . $nombre_archivo;
    $templateProcessor->saveAs($ruta_guardado);

    header('Content-Description: File Transfer');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Disposition: attachment; filename="' . "$nombre_archivo_decodificado" . '"');
    header('Content-Transfer-Encoding: binary');

    ob_clean();
    flush();
    // Send generated file stored in the server to the browser
    readfile($ruta_guardado);
    exit;
} else {
    echo 'Error de consulta: ' . mysqli_error($conn);
}