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
                <h1 class="main__title">Generador de cartas</h1>
                <a href="./cartas.php" class="main__btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="main__icon" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Gestionar cartas
                </a>
            </div>
            <div>
                <form class="form" action="exportar-carta.php" method="post">
                    <fieldset class="form__fieldset form__fieldset--accredited">
                        <legend class="form__legend">Información del acreditado</legend>
                        <div class="form__division">
                            <label class="form__label" for="numero_expediente">Número de expediente<span
                                        class="asterisk">*</span>:</label>
                            <input class="form__input" type="text" id="numero_expediente"
                                   name="numero_expediente" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="nombre_cliente">Nombre del cliente<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="text" id="nombre_cliente"
                                   name="nombre_cliente"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="calle">Calle: </label>
                            <input class="form__input" type="text" id="calle" name="calle">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="cruzamientos">Cruzamientos: </label>
                            <input class="form__input" type="text" id="cruzamientos" name="cruzamientos">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="numero_direccion">Número: </label>
                            <input class="form__input" type="text" id="numero_direccion"
                                   name="numero_direccion">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="colonia_fraccionamiento">Colonia/fraccionamiento: </label>
                            <input class="form__input" type="text" id="colonia_fraccionamiento"
                                   name="colonia_fraccionamiento">
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="localidad">Localidad<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="localidad" name="localidad"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="municipio">Municipio<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="municipio" name="municipio"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="fecha_firma">Fecha de firma de anexos<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="fecha_firma" name="fecha_firma"
                                   required>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset">
                        <legend class="form__legend">Documentación</legend>
                        <div class="form__division">
                            <label class="form__label" for="documentacion"></label>
                            <textarea class="form__input" id="documentacion"
                                      name="documentacion"></textarea>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--verification">
                        <legend class="form__legend">Comprobación</legend>
                        <div class="form__division">
                            <label class="form__label" for="comprobacion_monto">Monto de comprobación<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="number" id="comprobacion_monto"
                                   name="comprobacion_monto" step="0.01" min="0" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="comprobacion_tipo">Tipo de comprobación<span
                                        class="asterisk">*</span>: </label>
                            <select class="form__input" id="comprobacion_tipo" name="comprobacion_tipo" required>
                                <option value="capital de trabajo">Capital de trabajo</option>
                                <option value="activo fijo">Activo fijo</option>
                                <option value="adecuaciones">Adecuaciones</option>
                                <option value="insumos">Insumos</option>
                                <option value="certificaciones">Certificaciones</option>
                            </select>
                        </div>
                    </fieldset>
                    <fieldset class="form__fieldset form__fieldset--payment">
                        <legend class="form__legend">Pagos</legend>
                        <div class="form__division">
                            <label class="form__label" for="pagos_fecha_inicial">Fecha inicial<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="month" id="pagos_fecha_inicial"
                                   name="pagos_fecha_inicial" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="pagos_fecha_final">Fecha final<span
                                        class="asterisk">*</span>: </label>
                            <input class="form__input" type="month" id="pagos_fecha_final"
                                   name="pagos_fecha_final"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="tipo_credito">Tipo de crédito<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="text" id="tipo_credito" name="tipo_credito"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="fecha_otorgamiento">Fecha de otorgamiento del crédito<span
                                        class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="date" id="fecha_otorgamiento"
                                   name="fecha_otorgamiento" required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="monto_inicial">Monto inicial<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="number" id="monto_inicial" name="monto_inicial" step="0.01"
                                   min="0"
                                   required>
                        </div>
                        <div class="form__division">
                            <label class="form__label" for="adeudo_total">Adeudo total<span class="asterisk">*</span>:
                            </label>
                            <input class="form__input" type="number" id="adeudo_total" name="adeudo_total" step="0.01"
                                   min="0"
                                   required>
                        </div class="form__division">
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