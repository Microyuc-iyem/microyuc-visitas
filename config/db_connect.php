
<?php
// Configuración de la conexión a la base de datos
$host = "d6rii63wp64rsfb5.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$port = "3306";
$dbname = "xa5i7us8zbh7oqz7";
$user = "qybk61orec077c8s";
$password = "jwd9jkw5u0fgmd7i";

// Establecer la conexión

$conn = pg_connect("host=ec2-52-1-92-133.compute-1.amazonaws.com dbname=d8klmol62f7oi1 user=djgymmheoobtax password=7332bb6610847d90872b35e6ff411d5379628c314f848699b4aff90ac9029e76");

// Verifica la conexión
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}
?>
