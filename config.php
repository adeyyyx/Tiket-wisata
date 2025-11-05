<?php
$dbHost = "127.0.0.1";   
$dbName = "tiket_wisata";
$dbUser = "root";
$dbPass = "";

// Buat koneksi PDO
try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // tampilkan error PDO
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // hasil query berupa array asosiatif
    ]);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
