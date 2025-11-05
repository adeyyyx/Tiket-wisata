<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket Wisata</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --sidebar-bg: #0d1b2a;
            --sidebar-accent: #0f4c75;
            --sidebar-text: #cbd5e1;
            --content-bg: #f4f6f9;
        }

        .site-navbar {
            background: var(--sidebar-bg) !important;
            box-shadow: 0 2px 8px rgba(2, 6, 23, 0.25);
        }

        .site-navbar .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #fff;
            font-weight: 700
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--sidebar-accent), #062a3f);
            color: #fff;
            font-size: 1.15rem;
            box-shadow: 0 2px 6px rgba(2, 6, 23, 0.35)
        }

        .site-navbar .nav-link {
            color: var(--sidebar-text) !important;
            font-weight: 600
        }

        .site-navbar .nav-link:hover {
            color: #fff !important
        }

        .site-navbar .nav-link.text-danger {
            color: #ef4444 !important
        }

        @media (max-width:768px) {
            .logo-icon {
                width: 40px;
                height: 40px
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="site-navbar navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php?page=home">
                <span class="logo-icon" aria-hidden="true"><i class="bi bi-ticket-fill"></i></span>
                <span class="ms-1">Tiket Wisata</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=home">Home</a></li>

                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=orders">Pesanan Saya</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php?page=tickets">Tiket Saya</a></li>

                        <?php if (isAdmin()): ?>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=admin-events">Kelola Event</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=admin-venues">Kelola Venue</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=checkin">Check-in</a></li>
                        <?php endif; ?>

                        <li class="nav-item"><a class="nav-link text-danger" href="index.php?page=logout">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login /
                                Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php include "views/layouts/modals.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <div class="container mt-4">