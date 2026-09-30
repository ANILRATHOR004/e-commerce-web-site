<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "127.0.0.1"; // 'localhost' ki jagah 127.0.0.1 use karein
$user = "root";
$pass = "";          // Agar phpMyAdmin mein koi password rakha ho toh yahan likhein, varna khali "" rehne dein
$db   = "school_store";

// Error reporting band karein taaki uncaught exception fatal na bane
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>