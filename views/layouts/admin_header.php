<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Tiket Wisata</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root {
            --sidebar-bg: #0d1b2a;
            --sidebar-accent: #0f4c75;
            --sidebar-text: #cbd5e1;
            --content-bg: #f4f6f9;
        }

        body {
            display: flex;
            min-height: 100vh;
            background: var(--content-bg);
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            color: #1f2937;
        }

        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            padding: 1.25rem 0.5rem;
            box-shadow: 2px 0 8px rgba(2, 6, 23, 0.35);
            gap: 0.5rem;
            z-index: 1000;
        }

        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            margin-bottom: 0.5rem;
        }

        .sidebar .brand img {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
        }

        /* logo as icon badge */
        .sidebar .brand .logo-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--sidebar-accent), #062a3f);
            color: #fff;
            font-size: 1.15rem;
            box-shadow: 0 2px 6px rgba(2, 6, 23, 0.35);
        }

        .sidebar h4 {
            font-size: 1rem;
            margin: 0;
            font-weight: 700;
            color: #fff;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .nav-link {
            color: var(--sidebar-text);
            text-decoration: none;
            padding: 0.6rem 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-radius: 8px;
            transition: all .12s ease-in-out;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .nav-link i {
            font-size: 1.05rem;
            min-width: 20px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #fff;
            transform: translateX(3px)
        }

        .nav-link.active {
            background: linear-gradient(90deg, var(--sidebar-accent), rgba(15, 76, 117, 0.6));
            color: #fff;
            box-shadow: inset 3px 0 0 rgba(255, 255, 255, 0.06)
        }

        .content {
            margin-left: 250px;
            padding: 1.25rem;
            width: 100%;
        }

        .toggle-sidebar {
            display: none;
            position: fixed;
            left: 260px;
            top: 12px;
            z-index: 1100;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -260px;
                transition: left .22s ease-in-out
            }

            .sidebar.open {
                left: 0
            }

            .content {
                margin-left: 0
            }

            .toggle-sidebar {
                display: block
            }
        }
    </style>
</head>

<body>
    <button class="btn btn-sm btn-outline-secondary toggle-sidebar" id="toggleSidebar" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>

    <aside class="sidebar" id="adminSidebar" role="navigation" aria-label="Admin sidebar">
        <div class="brand">
            <span class="logo-icon" aria-hidden="true"><i class="bi bi-calendar2-event-fill"></i></span>
            <div>
                <h4>Tiket Wisata</h4>
                <div style="font-size:.78rem;color:var(--sidebar-text);margin-top:2px">Admin Panel</div>
            </div>
        </div>

        <nav>
            <ul class="nav-list">
                <li>
                    <a href="index.php?page=admin-dashboard"
                        class="nav-link <?= ($_GET['page'] ?? '') === 'admin-dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-house-door-fill" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=admin-events"
                        class="nav-link <?= ($_GET['page'] ?? '') === 'admin-events' ? 'active' : '' ?>">
                        <i class="bi bi-ticket-fill" aria-hidden="true"></i>
                        <span>Kelola Event</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=admin-venues"
                        class="nav-link <?= ($_GET['page'] ?? '') === 'admin-venues' ? 'active' : '' ?>">
                        <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                        <span>Kelola Venue</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=admin-orders"
                        class="nav-link <?= str_contains($_GET['page'] ?? '', 'admin-orders') ? 'active' : '' ?>">
                        <i class="bi bi-receipt-cutoff" aria-hidden="true"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=checkin"
                        class="nav-link <?= ($_GET['page'] ?? '') === 'checkin' ? 'active' : '' ?>">
                        <i class="bi bi-check2-square" aria-hidden="true"></i>
                        <span>Check-in Tiket</span>
                    </a>
                </li>
                <li>
                    <a href="index.php?page=logout" class="nav-link">
                        <i class="bi bi-box-arrow-right" aria-hidden="true"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <script>
            (function () {
                var btn = document.getElementById('toggleSidebar');
                var sidebar = document.getElementById('adminSidebar');
                if (!btn || !sidebar) return;

                function setExpanded(v) {
                    btn.setAttribute('aria-expanded', v ? 'true' : 'false');
                }

                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('open');
                    setExpanded(sidebar.classList.contains('open'));
                });

                // Close sidebar when clicking outside on small screens
                document.addEventListener('click', function (e) {
                    if (window.innerWidth > 768) return;
                    if (!sidebar.classList.contains('open')) return;
                    if (e.target.closest('#adminSidebar') || e.target.closest('#toggleSidebar')) return;
                    sidebar.classList.remove('open');
                    setExpanded(false);
                });

                // Ensure sidebar resets when resizing
                window.addEventListener('resize', function () {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('open');
                        setExpanded(false);
                    }
                });
            })();
        </script>