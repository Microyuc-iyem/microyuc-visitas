
<?php
// Configuración de la conexión a la base de datos
$host = "d6rii63wp64rsfb5.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$port = "3306";
$dbname = "xa5i7us8zbh7oqz7";
$user = "qybk61orec077c8s";
$password = "jwd9jkw5u0fgmd7i";

// Establecer la conexión

// Connect to DB
$conn = mysqli_connect($d6rii63wp64rsfb5.cbetxkdyhwsb.us-east-1.rds.amazonaws.com, $qybk61orec077c8s, $jwd9jkw5u0fgmd7i, $xa5i7us8zbh7oqz7);
//$conn = mysqli_connect('localhost', 'sig', '1234', 'microyuc_project');

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
