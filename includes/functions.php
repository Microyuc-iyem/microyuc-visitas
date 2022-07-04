<?php

declare(strict_types=1);

function check_login(): void
{
    session_start();
    if (!isset($_SESSION['login'])) {
        header('Location: index.php');
    }
}

//Set date format to replace in the docx
function set_date_format(): IntlDateFormatter
{
    return datefmt_create(
        'es-MX',
        IntlDateFormatter::FULL,
        IntlDateFormatter::FULL,
        'America/Mexico_City',
        IntlDateFormatter::GREGORIAN,
        "MMMM 'de' yyyy"
    );
}

function is_greater_than_0($number): bool
{
    return $number >= 0;
}

function validate_number($number): string
{
    if (!is_numeric($number)) {
        return 'El campo debe tener un valor numérico.';
    } else {
        if (!is_greater_than_0($number)) {
            return 'El monto debe ser mayor o igual a 0.';
        }
        return '';
    }
}

function validate_required_variable($variable): string {
    if ($variable !== '') {
        return '';
    } else {
        return 'Este campo es requerido.';
    }
}