<?php
// Require database connection and PHPWord library
require './config/db_connect.php';
require './lib/phpword/vendor/autoload.php';
require './includes/functions.php';

$sidebar_active = 'aval';
$header_title = 'Agregar fecha de visita';

require './includes/header.php';

check_login();

$fmt = set_date_format_logbook();

// Check if there is an ID query
if ($_GET['id']) {
// Write query to get a bitacora according to the ID
    $sql = 'SELECT * FROM aval WHERE id = ' . $_GET['id'] . ';';

    // make query and & get result
    $result = mysqli_query($conn, $sql);
    if ($result) {

// Fetch the resulting rows as an associative array
        $aval = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if ($aval) {

            $fecha = [
                'fecha_visita' => '',
            ];

            $errores = [
                'fecha_visita' => '',
            ];

            $filtros = [];

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_GET['id'])) {

                $filtros['fecha_visita']['filter'] = FILTER_VALIDATE_REGEXP;
                $filtros['fecha_visita']['options']['regexp'] = '/^[\d\-]+$/';

                $fecha = filter_input_array(INPUT_POST, $filtros);

                $errores['fecha_visita'] = $fecha['fecha_visita'] ? '' : 'Introduzca un formato de fecha válido.';

                $generacion_invalida = implode($errores);

                if (!$generacion_invalida) {
                    $fecha_visita = mysqli_real_escape_string($conn, $fecha['fecha_visita']);
                    // Query
                    $sql = "UPDATE aval SET fecha_visita = '" . $fecha_visita . "' WHERE id = " . $_GET['id'] . ';';

// Validation of query
                    if (mysqli_query($conn, $sql)) {
                        header('Location: ./cartas.php');
                        exit;
                    } else {
                        echo 'Error de consulta: ' . mysqli_error($conn);
                    }

                }
            }
        } else {
            header('Location: ./aval.php');
        }
    } else {
        header('Location: ./aval.php');
    }
} else {
    header('Location: ./aval.php');
}
?>
<div class="main__app">
    <div class="main__header">
        <h1 class="main__title">Agregar fecha de visita a <?= $aval[0]['nombre_cliente']; ?></h1>
        <a href="aval.php" class="main__btn main__btn--main">
            <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Gestionar cartas de Aval
        </a>
    </div>
    <div>
        <form class="form" action="agregar-fecha-aval.php?id=<?= $aval[0]['id'] ?>" method="post">
            <fieldset class="form__fieldset form__fieldset--verification">
                <legend class="form__legend">Fecha de visita<span class="asterisk">*</span></legend>
                <div class="form__division">
                    <p class="form__error"><?= $errores['fecha_visita'] ?></p>
                    <label class="form__label" for="fecha_visita"></label>
                    <input class="form__input" type="date" id="fecha_visita"
                           name="fecha_visita"
                           value="<?= htmlspecialchars($fecha['fecha_visita']) ?>" required>
                </div>
            </fieldset>
            <div class="form__container--btn">
                <input class="container__btn--submit" type="submit" value="Agregar fecha">
            </div>
        </form>
    </div>
</div>
</main>
</div>
</body>
</html>
