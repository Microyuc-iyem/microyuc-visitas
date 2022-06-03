<?php
$conn = mysqli_connect('localhost', 'sig', '1234', 'microyuc_project');

if (!$conn) {
    echo 'Connection error: ' . mysqli_connect_error();
}
