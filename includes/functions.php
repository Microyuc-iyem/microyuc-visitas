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

function create_filename($filename, $upload_path)              // Function to make filename
{
    $basename   = pathinfo($filename, PATHINFO_FILENAME);      // Get basename
    $extension  = pathinfo($filename, PATHINFO_EXTENSION);     // Get extension
    $basename   = preg_replace('/[^A-zÀ-ÿ\d]/', '-', $basename); // Clean basename
    $i          = 0;                                           // Counter
    while (file_exists($upload_path . $filename)) {            // If file exists
        $i        = $i + 1;                                    // Update counter
        $filename = $basename . $i . '.' . $extension;         // New filepath
    }
    return $filename;                                          // Return filename
}