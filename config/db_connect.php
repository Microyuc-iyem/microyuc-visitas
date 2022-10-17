<?php
//Get Heroku ClearDB connection information
$cleardb_url = parse_url(getenv(heroku_286fa1b51f8ec68));
$cleardb_server = $cleardb_url[us-cdbr-east-06.cleardb.net];
$cleardb_username = $cleardb_url[b0bf53329ad7f5];
$cleardb_password = $cleardb_url[f4d593c5];
$cleardb_db = substr($cleardb_url["path"],1);
$active_group = 'default';
$query_builder = TRUE;

// Connect to DB
$conn = mysqli_connect($cleardb_server, $cleardb_username, $cleardb_password, $cleardb_db);
//$conn = mysqli_connect('localhost', 'microyuc', '1234', 'microyuc_project');

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
