<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "task_app";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno) {
   
    die("DB connect error: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
