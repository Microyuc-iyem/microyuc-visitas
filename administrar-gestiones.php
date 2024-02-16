<?php
// Require database connection and PHPWord library
require './config/db_connect.php';
require './lib/phpword/vendor/autoload.php';
require './includes/functions.php';

$sidebar_active = 'bitácora';
$header_title = 'Administrar gestión';

require './includes/header.php';

check_login();

$fmt = set_date_format_logbook();

// Check if there is an ID query
if ($_GET['id']) {
    $id = $_GET['id'];
    // Write query to get a bitacora according to the ID and order by gestion_fecha
    $sql = "SELECT * FROM bitacora WHERE id = " . $_GET['id'] . " ORDER BY gestion_fecha;";

    // make query and get result
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Fetch the resulting rows as an associative array
        $bitacoras = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $column_number = 0;

        if (!empty($bitacoras[0])) {
            foreach (array_keys($bitacoras[0]) as $key) {
                if (str_contains($key, 'gestion_via')) {
                    $column_number++;
                }
            }
        }

        if ($bitacoras) {
            if (isset($_GET['num'])) {
                $num = $_GET['num'];
                $sql_delete_image_query = "SELECT evidencia_fotografia$num FROM bitacora WHERE id = '$id';";
                $sql_delete_columns_query = "UPDATE bitacora SET gestion_fecha$num = '', gestion_via$num = '', gestion_comentarios$num = '', evidencia_fecha$num = '', evidencia_fotografia$num = '' WHERE id = '$id';";
                $resultado_imagen = mysqli_query($conn, $sql_delete_image_query);
                $imagename = $resultado_imagen->fetch_array()['evidencia_fotografia' . $num] ?? '';
                $delete = mysqli_query($conn, $sql_delete_columns_query);
                if (file_exists('./uploads/' . $imagename)) {
                    unlink('./uploads/' . $imagename);
                }

                // Create variable with filename
                $nombre_archivo = $bitacoras[0]['acreditado_folio'] . ' ' . $bitacoras[0]['acreditado_nombre'] . ' - Bitácora.docx';
                // Encode filename so that UTF-8 characters work
                $nombre_archivo_decodificado = rawurlencode($nombre_archivo);



// Obtener las fechas de gestión y sus índices
$fechas_gestion = [];
foreach ($bitacoras as $bitacora) {
    for ($i = 1; $i <= $column_number; $i++) {
        if (!empty($bitacora['gestion_fecha' . $i])) {
            $fechas_gestion[] = [
                'fecha' => strtotime($bitacora['gestion_fecha' . $i]),
                'indice' => $i,
                'bitacora' => $bitacora // También puedes almacenar la bitácora completa si es necesario
            ];
        }
    }
}

// Ordenar las fechas de gestión por fecha
usort($fechas_gestion, function($a, $b) {
    return $a['fecha'] - $b['fecha'];
});

// Iniciar el procesador de plantillas de PHPWord
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('./plantillas/plantilla-bitacora.docx');

// Iterar sobre las fechas de gestión ordenadas e insertarlas en la plantilla de Word
foreach ($fechas_gestion as $fecha_gestion) {
    $gestion = $fecha_gestion['bitacora'];
    $indice = $fecha_gestion['indice'];

    $templateProcessor->setValue('gestion_fecha#' . $indice, date("d-m-Y", $fecha_gestion['fecha']));
    $templateProcessor->setValue('gestion_via#' . $indice, $gestion['gestion_via' . $indice]);
    $templateProcessor->setValue('gestion_comentarios#' . $indice, $gestion['gestion_comentarios' . $indice]);
}
                $templateProcessor->setValue('acreditado_nombre', $bitacoras[0]['acreditado_nombre']);
                $templateProcessor->setValue('acreditado_folio', $bitacoras[0]['acreditado_folio']);
                $templateProcessor->setValue('acreditado_municipio', $bitacoras[0]['acreditado_municipio']);
                $templateProcessor->setValue('acreditado_localidad', $bitacoras[0]['acreditado_localidad']);
                $templateProcessor->setValue('tipo_garantia', $bitacoras[0]['tipo_garantia']);
                $templateProcessor->setValue('acreditado_garantia', $bitacoras[0]['acreditado_garantia']);
                $templateProcessor->setValue('acreditado_telefono', $bitacoras[0]['acreditado_telefono']);
                $templateProcessor->setValue('acreditado_email', $bitacoras[0]['acreditado_email']);
                $templateProcessor->setValue('acreditado_direccion_negocio', $bitacoras[0]['acreditado_direccion_negocio']);
                $templateProcessor->setValue('acreditado_direccion_particular', $bitacoras[0]['acreditado_direccion_particular']);
                $templateProcessor->setValue('aval_nombre', $bitacoras[0]['aval_nombre']);
                $templateProcessor->setValue('aval_telefono', $bitacoras[0]['aval_telefono']);
                $templateProcessor->setValue('aval_email', $bitacoras[0]['aval_email']);
                $templateProcessor->setValue('aval_direccion', $bitacoras[0]['aval_direccion']);
                $templateProcessor->cloneRowAndSetValues('gestion_fecha', $values);
                
                if (!is_dir('./files/')) {
                    mkdir('./files/');
                }

                if (!is_dir('./files/bitacoras/')) {
                    mkdir('./files/bitacoras/');
                }

                if (file_exists('./files/bitacoras/')) {
                    // Path where generated file is saved
                    $ruta_guardado = './files/bitacoras/' . $nombre_archivo;
                    $templateProcessor->saveAs($ruta_guardado);
                }

                header('Location: administrar-gestion.php?id=' . "$id");
            }
        } else {
            header('Location: ./bitacoras.php');
        }
    } else {
        header('Location: ./bitacoras.php');
    }
} else {
    header('Location: ./bitacoras.php');
}
?>
    

<div class="main__app">
    <div class="main__header">
        <h1 class="main__title">Administrar gestiones de <?= $bitacoras[0]['acreditado_nombre']; ?></h1>
        <a href="bitacoras.php" class="main__btn main__btn--main">
            <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Gestionar bitácoras
        </a>
    </div>
    <div>
        <table class="table">
            <thead class="table__head">
            <tr class="table__row--head">
                <th scope="col" class="table__head">
                    Fecha
                </th>
                <th scope="col" class="table__head table__data--left">
                    Vía
                </th>
                <th scope="col" class="table__head table__data--left">
                    Comentarios
                </th>
                <th scope="col" class="table__head table__data--left">
                    Acción
                </th>
            </tr>
            </thead>
            <tbody class="table__body">
                
<?php
// Crear un array para almacenar las fechas y sus índices correspondientes
$dates = [];
foreach ($bitacoras as $index => $bitacora) {
    for ($i = 1; $i <= $column_number; $i++) {
        if (!empty($bitacora['gestion_fecha' . $i])) {
            // Almacenar la fecha, el índice y el id de la bitácora
            $dates[$index.$i] = [
                'fecha' => strtotime($bitacora['gestion_fecha' . $i]),
                'index' => $index,
                'num'   => $i
            ];
        }
    }
}

// Ordenar los índices de acuerdo a las fechas almacenadas (de manera ascendente)
asort($dates);

// Iterar sobre los índices ordenados y mostrar las filas correspondientes
foreach ($dates as $date): 
    $index = $date['index'];
    $num = $date['num'];
    $bitacora = $bitacoras[$index]; ?>
    <tr class="table__row--body">
        <td class="table__data table__data--left"><?= date("d-m-Y", $date['fecha']) ?></td>
        <td class="table__data table__data--left"><?= $bitacora['gestion_via' . $num] ?></td>
        <td class="table__data table__data--left"><?= $bitacora['gestion_comentarios' . $num] ?></td>
        <td class="table__data"><a class="table__data--red"
                                   href="administrar-gestion.php?id=<?= $bitacora['id'] ?>&num=<?= $num ?>">Eliminar</a>
        </td>
    </tr>
<?php endforeach; ?>




                
            </tbody>
        </table>
    </div>
</div>

</main>
</div>
</body>
</html>

