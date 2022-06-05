<?php
require_once './config/db_connect.php';
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./dist/css/styles.css">
    <title>Microyuc | Generador de cartas</title>
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
                    <li><a href="./generador-carta.php" class="sidebar__link">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar__icon" fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Cartas</span></a></li>
                    <li><a href="./generador-bitacora.php" class="sidebar__link sidebar__link--active">
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
                <h1 class="main__title">Generador de bitácoras</h1>
                <a href="./bitacoras.php" class="main__btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Gestionar bitácoras
                </a>
            </div>
            <div>
                <form class="form" action="exportar-bitacora.php" method="post" enctype="multipart/form-data">
                    <fieldset class="form__fieldset form__fieldset--accreditedLogbook">
                        <legend class="form__legend">Datos del acreditado</legend>
                        <div class="form__division">
                            <label class="form__label" for="acreditado_nombre">Nombre<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="text" id="acreditado_nombre"
                                   name="acreditado_nombre" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="folio">Folio<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="text" id="folio"
                                   name="folio"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="municipio">Municipio<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="text" id="municipio" name="municipio">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="garantia">Garantía<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="text" id="garantia" name="garantia">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="acreditado_telefono">Teléfono<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="text" id="acreditado_telefono"
                                   name="acreditado_telefono">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="acreditado_email">Email<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="email" id="acreditado_email"
                                   name="acreditado_email">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="direccion_negocio">Dirección del negocio<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="direccion_negocio" name="direccion_negocio"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="direccion_particular">Dirección particular<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="direccion_particular" name="direccion_particular"
                                   required>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--aval">
                        <legend class="form__legend">Datos del aval</legend>
                        <div class="form__division">
                            <label class="form__label" for="aval_nombre">Nombre:
                            </label>
                            <input class="form__input" type="text" id="aval_nombre"
                                   name="aval_nombre">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="aval_telefono">Teléfono:
                            </label>
                            <input class="form__input" type="text" id="aval_telefono"
                                   name="aval_telefono">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="aval_email">Email:
                            </label>
                            <input class="form__input" type="email" id="aval_email"
                                   name="aval_email">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="aval_direccion">Dirección:
                            </label>
                            <input class="form__input" type="text" id="aval_direccion"
                                   name="aval_direccion">
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--process">
                        <legend class="form__legend">Gestión</legend>
                        <div class="form__division">
                            <label class="form__label" for="gestion_fecha">Fecha<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="gestion_fecha" name="gestion_fecha"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="gestion_via">Vía<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="gestion_via" name="gestion_via"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="gestion_comentarios">Comentarios/Resultados:
                            </label>
                            <input class="form__input" type="text" id="gestion_comentarios" name="gestion_comentarios">
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--evidence">
                        <legend class="form__legend">Evidencias</legend>
                        <div class="form__division">
                            <label class="form__label" for="evidencia_fecha">Fecha<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="evidencia_fecha" name="evidencia_fecha"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="evidencia_fotografia">Fotografía<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input form__input--file" type="file" id="evidencia_fotografia"
                                   name="evidencia_fotografia"
                                   required>
                        </div>
                    </fieldset>
                    <div class="form__container--btn">
                        <button class="container__btn--reset" type="reset">Limpiar</button>
                        <input class="container__btn--submit" type="submit" value="Generar archivo">
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
</body>
</html>