<?php
$host = "localhost";
$user = "afievre2";
$pass = "afievre2";
$db   = "afievre2";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
