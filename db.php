<?php
$host = "localhost";
$user = "root"; // default XAMPP user
$pass = "Thisissql!19";     // leave empty if no password
$dbname = "movies_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
