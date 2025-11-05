<?php
// functions.php

// 🔹 Redirect ke halaman lain
function redirect($url)
{
    header("Location: $url");
    exit;
}

// 🔹 Cek apakah user sudah login
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// 🔹 Cek apakah user admin
function isAdmin()
{
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

// 🔹 Generate kode unik untuk order
function generateOrderCode()
{
    return "ORD" . time() . rand(100, 999);
}

// 🔹 Generate kode unik untuk tiket
function generateTicketCode()
{
    return "TKT" . bin2hex(random_bytes(4)); // contoh: TKT8fa13b2c
}

// 🔹 Escape output agar aman dari XSS
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
