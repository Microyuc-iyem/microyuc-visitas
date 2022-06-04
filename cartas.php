<?php
require_once './config/db_connect.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
}

// Write query for all acreditados
$sql = 'SELECT id, nombre_cliente, numero_expediente, fecha_creacion, comprobacion_tipo, comprobacion_monto, tipo_credito, fecha_otorgamiento, monto_inicial, mensualidades_vencidas, adeudo_total, nombre_archivo FROM carta ORDER BY fecha_creacion DESC;';

// make query and & get result
$result = mysqli_query($conn, $sql);

// Fetch the resulting rows as an array
$cartas = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Free result from memory
mysqli_free_result($result);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $resultado = mysqli_query($conn, "SELECT nombre_archivo FROM carta WHERE id = '$id';");
    $filename = $resultado->fetch_array()['nombre_archivo'] ?? '';
    $delete = mysqli_query($conn, "DELETE FROM carta WHERE id = '$id';");
    unlink('./files/cartas/' . $filename);
    header('Location: cartas.php');
}

// Close connection
//mysqli_close($conn);
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./dist/css/styles.css">
    <title>Microyuc | Panel de cartas</title>
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
        <a href="./inicio.php"><img src="./img/microyucfondo.png" alt="Logo de microyuc" class="sidebar__image"></a>
        <nav class="sidebar__nav">
            <div class="sidebar__dashboard">
                <h2 class="sidebar__title">Tablero</h2>
                <ul class="sidebar__list">
                    <li><a href="./inicio.php" class="sidebar__link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Inicio</span></a></li>
                </ul>
            </div>
            <div class="sidebar__apps">
                <h2 class="sidebar__title">Apps</h2>
                <ul class="sidebar__list">
                    <li><a href="./generador-carta.php" class="sidebar__link sidebar__link--active">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Cartas</span></a></li>
                    <li><a href="./generador-bitacora.php" class="sidebar__link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Bitácoras</span></a></li>
                </ul>
            </div>
        </nav>
    </aside>
    <main class="main">
        <div class="main__app">
            <div class="main__header">
                <div>
                    <h1 class="main__title">Cartas</h1>
                    <span class="main__subtitle"><?php
                        $dash_carta_query = "SELECT * FROM carta";
                        $dash_carta_query_run = mysqli_query($conn, $dash_carta_query);

                        if ($cartas_total = mysqli_num_rows($dash_carta_query_run)) {
                            echo $cartas_total . ' cartas';
                        } else {
                            echo "Sin datos";
                        }
                        ?></span>
                </div>
                <div class="main__btnContainer">
                    <a href="./cartas-excel.php">Exportar Excel</a>
                    <a href="./generador-carta.php" class="main__btn">
                        <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Nueva carta
                    </a>
                </div>
            </div>
            <input type="text" placeholder="Busca por nombre, folio..." style="margin-bottom: 24px">
            <table class="table">
                <!--                <thead class="table__superhead">-->
                <!--                <tr>-->
                <!--                    <th scope="col" colspan="2" class="table__superhead--column">-->
                <!--                        Cliente-->
                <!--                    </th>-->
                <!--                    <th scope="col" colspan="3" class="table__superhead--column">-->
                <!--                        Pagos-->
                <!--                    </th>-->
                <!--                    <th scope="col" colspan="3" class="table__superhead--column">-->
                <!--                        Acciones-->
                <!--                    </th>-->
                <!--                </tr>-->
                <!--                </thead>-->
                <thead class="table__head">
                <tr class="table__row--head">
                    <th scope="col" class="table__head">
                        Acreditado
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Folio
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Monto inicial
                    </th>
                    <th scope="col" class="table__head table__head--width">
                        Mensualidades vencidas
                    </th>
                    <th scope="col" class="table__head table__data--left">
                        Adeudo total
                    </th>
                    <th scope="col" class="table__head">
                        Fecha de creación
                    </th>
                    <th scope="col" colspan="2" class="table__head">
                        Acciones
                    </th>
                </tr>
                </thead>
                <tbody class="table__body">
                <?php foreach ($cartas as $carta): ?>
                    <tr class="table__row--body">
                        <td class="table__data table__data--bold"><?= $carta['nombre_cliente'] ?></td>
                        <td class="table__data table__data--left"><?= $carta['numero_expediente'] ?></td>
                        <td class="table__data table__data--left"><?= '$' . number_format($carta['monto_inicial'], 2); ?></td>
                        <td class="table__data"><?= $carta['mensualidades_vencidas']; ?></td>
                        <td class="table__data table__data--left"><?= '$' . number_format($carta['adeudo_total'], 2); ?></td>
                        <td class="table__data"><?= date("d-m-Y", strtotime($carta['fecha_creacion'])); ?></td>
                        <td class="table__data"><a class="table__data--link"
                                                   href="./files/cartas/<?= $carta['nombre_archivo'] ?>">Descargar</a>
                        </td>
                        <td class="table__data"><a class="table__data--red" href="./cartas.php?id=<?= $carta['id'] ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>
</body>
</html>