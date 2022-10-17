<?php
//Get Heroku ClearDB connection information
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["us-cdbr-east-06.cleardb.net"];
$username = $url["b8b05bd297e7a4"];
$password = $url["50b32aa6"];
$db = substr($url["heroku_575502a958f4fa8"], 1);


// Connect to DB
$conn = new mysqli($server, $username, $password, $db);
?>
