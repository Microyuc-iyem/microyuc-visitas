<?php
//Get Heroku ClearDB connection information

$cleardb_server = "us-cdbr-east-06.cleardb.net";
$cleardb_username = "b8b05bd297e7a4";
$cleardb_password = "50b32aa6";
$cleardb_name = "heroku_575502a958f4fa8" ;

// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_name);


if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
