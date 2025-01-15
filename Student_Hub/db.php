<?php
// database.php

$host = 'localhost';
$dbname = 'student_hub';
$username = 'root';
$password = '';
$port = 3307; // Port number for MySQL

try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>




