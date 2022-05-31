<!doctype html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./dist/css/styles.css">
    <title>Microyuc | Generador de Carta</title>
</head>
<body>
<div class="container">
    <div class="letterGeneratorForm__container">
        <h1 class="primary-heading">Generador de carta Microyuc</h1>
        <form class="letterGeneratorForm" action="exportar-carta.php" method="post">
            <div>
                <label for="numero_expediente">Número de expediente<span class="asterisk">*</span>:</label>
                <input class="letterGeneratorForm__input" type="text" id="numero_expediente"
                       name="numero_expediente" required>
            </div>
            <div>
                <label for="nombre_cliente">Nombre del cliente<span class="asterisk">*</span>: </label>
                <input class="letterGeneratorForm__input" type="text" id="nombre_cliente" name="nombre_cliente"
                       required>
            </div>
            <div>
                <label for="calle">Calle: </label>
                <input class="letterGeneratorForm__input" type="text" id="calle" name="calle">
            </div>
            <div>
                <label for="cruzamientos">Cruzamientos: </label>
                <input class="letterGeneratorForm__input" type="text" id="cruzamientos" name="cruzamientos">
            </div>
            <div>
                <label for="numero_direccion">Número: </label>
                <input type="number" id="numero_direccion"
                       name="numero_direccion">
            </div>
            <div>
                <label for="colonia_fraccionamiento">Colonia/fraccionamiento: </label>
                <input class="letterGeneratorForm__input" type="text" id="colonia_fraccionamiento"
                       name="colonia_fraccionamiento">
            </div>
            <div>
                <label for="localidad">Localidad: </label>
                <input class="letterGeneratorForm__input" type="text" id="localidad" name="localidad">
            </div>
            <div>
                <label for="municipio">Municipio: </label>
                <input class="letterGeneratorForm__input" type="text" id="municipio" name="municipio">
            </div>
            <div>
                <label for="fecha_firma">Fecha de firma de anexos<span class="asterisk">*</span>: </label>
                <input class="letterGeneratorForm__input" type="date" id="fecha_firma" name="fecha_firma" required>
            </div>
            <div>
                <label for="documentacion">Documentación: </label>
                <input class="letterGeneratorForm__input" type="text" id="documentacion" name="documentacion">
            </div>
            <div>
                <label for="comprobacion_monto">Monto de comprobación: </label>
                <input type="number" id="comprobacion_monto"
                       name="comprobacion_monto" step="0.01" min="0">
            </div>
            <div>
                <label for="comprobacion_tipo">Tipo de comprobación: </label>
                <!--                <input type="text" id="comprobacion_tipo" name="comprobacion_tipo">-->
                <select id="comprobacion_tipo" name="comprobacion_tipo">
                    <option value="capital de trabajo">Capital de trabajo</option>
                    <option value="activo fijo">Activo fijo</option>
                    <option value="adecuaciones">Adecuaciones</option>
                    <option value="insumos">Insumos</option>
                    <option value="certificaciones">Certificaciones</option>
                </select>
            </div>
            <div>
                <label for="pagos_fecha_inicial">Fecha inicial<span class="asterisk">*</span>: </label>
                <input class="letterGeneratorForm__input" type="month" id="pagos_fecha_inicial"
                       name="pagos_fecha_inicial" required>
            </div>
            <div>
                <label for="pagos_fecha_final">Fecha final<span class="asterisk">*</span>: </label>
                <input class="letterGeneratorForm__input" type="month" id="pagos_fecha_final" name="pagos_fecha_final"
                       required>
            </div>
            <div>
                <label for="tipo_credito">Tipo de crédito<span class="asterisk">*</span>: </label>
                <input class="letterGeneratorForm__input" type="text" id="tipo_credito" name="tipo_credito"
                       required>
            </div>
            <div>
                <label for="fecha_otorgamiento">Fecha de otorgamiento del crédito<span class="asterisk">*</span>:
                </label>
                <input class="letterGeneratorForm__input" type="date" id="fecha_otorgamiento"
                       name="fecha_otorgamiento" required>
            </div>
            <div>
                <label for="monto_inicial">Monto inicial<span class="asterisk">*</span>: </label>
                <input type="number" id="monto_inicial" name="monto_inicial" step="0.01" min="0"
                       required>
            </div>
            <div>
                <label for="adeudo_total">Adeudo total<span class="asterisk">*</span>: </label>
                <input type="number" id="adeudo_total" name="adeudo_total" step="0.01" min="0"
                       required>
            </div>
            <div class="letterGeneratorForm__btnContainer">
                <input class="btn btn--animated" type="submit" value="Generar archivo">
            </div>
        </form>
    </div>
</div>
<!--<script>-->
<!--    const submitBtnEl = document.querySelector('.btn');-->
<!--    const formInputEl = document.querySelectorAll('.letterGeneratorForm__input');-->
<!--    submitBtnEl.addEventListener('click', () => {-->
<!--        formInputEl.value = '';-->
<!--    });-->
<!--</script>-->
</body>
</html>