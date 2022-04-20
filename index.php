<!doctype html>
<html lang="es-MX">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/style.css">
    <title>Microyuc | Generador de Carta</title>
</head>
<body>
<h1 class="encabezado-primario">Generador de carta microyuc</h1>
<form class="formulario" action="./exportar.php" method="post">
    <div>
        <label for="numero_expediente">Número de expediente: </label>
        <input type="number" id="numero_expediente" name="numero_expediente" required>
    </div>
    <div>
        <label for="nombre_cliente">Nombre del cliente: </label>
        <input type="text" id="nombre_cliente" name="nombre_cliente" required>
    </div>
    <div>
        <label for="calle">Calle: </label>
        <input type="text" id="calle" name="calle">
    </div>
    <div>
        <label for="cruzamientos">Cruzamientos: </label>
        <input type="text" id="cruzamientos" name="cruzamientos">
    </div>
    <div>
        <label for="numero_direccion">Número: </label>
        <input type="number" id="numero_direccion" name="numero_direccion">
    </div>
    <div>
        <label for="colonia_fraccionamiento">Colonia/fraccionamiento: </label>
        <input type="text" id="colonia_fraccionamiento" name="colonia_fraccionamiento">
    </div>
    <div>
        <label for="localidad">Localidad: </label>
        <input type="text" id="localidad" name="localidad">
    </div>
    <div>
        <label for="municipio">Municipio: </label>
        <input type="text" id="municipio" name="municipio">
    </div>
    <div>
        <label for="fecha_firma">Fecha de firma de anexos: </label>
        <input type="date" id="fecha_firma" name="fecha_firma" required>
    </div>
    <div>
        <label for="tipo_credito">Tipo de crédito: </label>
        <input type="type" id="tipo_credito" name="tipo_credito" required>
    </div>
    <div>
        <label for="fecha_otorgamiento">Fecha de otorgamiento del crédito: </label>
        <input type="date" id="fecha_otorgamiento" name="fecha_otorgamiento" required>
    </div>
    <div>
        <label for="monto_inicial">Monto inicial: </label>
        <input type="number" id="monto_inicial" name="monto_inicial" required>
    </div>
    <div>
        <label for="mensualidades_vencidas">Mensualidades vencidas: </label>
        <input type="number" id="mensualidades_vencidas" name="mensualidades_vencidas" required>
    </div>
    <div>
        <label for="adeudo_total">Adeuto total: </label>
        <input type="number" id="adeudo_total" name="adeudo_total" required>
    </div>
    <div>
        <input type="submit" value="Generar archivo">
    </div>
</form>

</body>
</html>

<?php


