<?php
//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$cleardb_server = $cleardb_url["us-cdbr-east-06.cleardb.net"];
$cleardb_username = $cleardb_url["b8b05bd297e7a4"];
$cleardb_password = $cleardb_url["50b32aa6"];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;

// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
//$conn = mysqli_connect('localhost', 'sig', '1234', 'microyuc_project');

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
