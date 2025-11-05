<?php
session_start();

// Load config & helper
require_once "config.php";
require_once "functions.php";

// Ambil parameter halaman (default = home)
$page = $_GET['page'] ?? 'home';

// Fungsi helper cepat
function requireController($controller, $class, $method, $param = null)
{
    require_once "controllers/$controller.php";
    $instance = new $class($GLOBALS['pdo']);
    $param ? $instance->$method($param) : $instance->$method();
}

// Routing sederhana
switch ($page) {

    // ===================== USER SIDE =====================
    case 'home':
        requireController("EventController", "EventController", "index");
        break;

    case 'event':
        requireController("EventController", "EventController", "show", $_GET['id'] ?? null);
        break;

    case 'login':
        requireController("AuthController", "AuthController", "login");
        break;

    case 'register':
        requireController("AuthController", "AuthController", "register");
        break;

    case 'logout':
        requireController("AuthController", "AuthController", "logout");
        break;

    case 'order':
        requireController("OrderController", "OrderController", "create");
        break;

    case 'orders':
        requireController("OrderController", "OrderController", "listUserOrders");
        break;

    case 'tickets':
        requireController("TicketController", "TicketController", "listUserTickets");
        break;
    case 'order-cancel':
        require "controllers/OrderController.php";
        (new OrderController($pdo))->cancel();
        break;

    // ===================== ADMIN SIDE =====================
    case 'admin-dashboard':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminDashboardController", "AdminDashboardController", "index");
        break;

    // ---- EVENT MANAGEMENT ----
    case 'admin-events':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminEventController", "AdminEventController", "index");
        break;

    case 'admin-events-create':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminEventController", "AdminEventController", "create");
        break;

    case 'admin-events-edit':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminEventController", "AdminEventController", "edit", $_GET['id'] ?? null);
        break;

    case 'admin-events-delete':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminEventController", "AdminEventController", "delete", $_GET['id'] ?? null);
        break;

    // ---- VENUE MANAGEMENT ----
    case 'admin-venues':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminVenueController", "AdminVenueController", "index");
        break;

    case 'admin-venues-create':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminVenueController", "AdminVenueController", "create");
        break;

    case 'admin-venues-edit':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminVenueController", "AdminVenueController", "edit", $_GET['id'] ?? null);
        break;

    case 'admin-venues-delete':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("AdminVenueController", "AdminVenueController", "delete", $_GET['id'] ?? null);
        break;

    // ---- CHECK-IN ----
    case 'checkin':
        if (!isAdmin())
            redirect("index.php?page=home");
        requireController("TicketController", "TicketController", "checkin");
        break;
    // ================= ADMIN: Kelola Pesanan ==================
    case 'admin-orders':
        if (!isAdmin())
            redirect("index.php?page=home");
        require "controllers/AdminOrderController.php";
        (new AdminOrderController($pdo))->index();
        break;

    case 'admin-orders-detail':
        if (!isAdmin())
            redirect("index.php?page=home");
        require "controllers/AdminOrderController.php";
        (new AdminOrderController($pdo))->detail($_GET['id'] ?? null);
        break;

    case 'admin-orders-update':
        if (!isAdmin())
            redirect("index.php?page=home");
        require "controllers/AdminOrderController.php";
        (new AdminOrderController($pdo))->updateStatus();
        break;
     case 'order-delete':
    require "controllers/OrderController.php";
    (new OrderController($pdo))->delete();
    break;


  case 'admin-checkin-scan':
    if (!isAdmin()) redirect("index.php?page=home");
    require "views/admin/checkin_scan.php";
    break;

case 'checkin-process':
    if (!isAdmin()) redirect("index.php?page=home");
    require "controllers/TicketController.php";
    (new TicketController($pdo))->processScan();
    break;


    // ===================== 404 HANDLER =====================
    default:
        http_response_code(404);
        require "views/layouts/header.php";
        echo "<div class='container mt-5 text-center'>
                <h1 class='display-5 fw-bold text-danger'>404</h1>
                <p class='lead'>Halaman yang kamu cari tidak ditemukan.</p>
                <a href='index.php?page=home' class='btn btn-primary'>Kembali ke Beranda</a>
              </div>";
        require "views/layouts/footer.php";
        break;
}
