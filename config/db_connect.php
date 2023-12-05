
//Get Heroku ClearDB connection information
//$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
//$cleardb_server = $cleardb_url["ec2-52-1-92-133.compute-1.amazonaws.com"];
//$cleardb_username = $cleardb_url["user"];
//$cleardb_password = $cleardb_url["pass"];
//$cleardb_db = substr($cleardb_url["path"],1);
//$active_group = 'default';
//query_builder = TRUE;
// Connect to DB
//$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);

<?php
$host = "ec2-52-1-92-133.compute-1.amazonaws.com";
$port = "5432";
$dbname = "d8klmol62f7oi1";
$user = "djgymmheoobtax";
$password = "7332bb6610847d90872b35e6ff411d5379628c314f848699b4aff90ac9029e76";

// Establecer la conexión
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    echo "Error de conexión: " . pg_last_error();
} else {
    echo "Conexión exitosa";
}

// Cierra la conexión cuando hayas terminado de usarla
// pg_close($conn);
?>
