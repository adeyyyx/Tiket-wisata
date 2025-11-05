<?php
class AdminDashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        // Statistik umum
        $totalUsers = $this->pdo->query("SELECT COUNT(*) FROM tiket_wisata_users WHERE role='user'")->fetchColumn();
        $totalEvents = $this->pdo->query("SELECT COUNT(*) FROM tiket_wisata_events")->fetchColumn();
        $totalOrders = $this->pdo->query("SELECT COUNT(*) FROM tiket_wisata_orders")->fetchColumn();
        $totalTickets = $this->pdo->query("SELECT COUNT(*) FROM tiket_wisata_tickets")->fetchColumn();

        // Event paling populer
        $stmt = $this->pdo->query("
            SELECT e.title, COUNT(o.order_id) AS total_order
            FROM tiket_wisata_orders o
            JOIN tiket_wisata_events e ON e.event_id = o.event_id
            GROUP BY o.event_id
            ORDER BY total_order DESC
            LIMIT 5
        ");
        $popularEvents = $stmt->fetchAll();

        // Tampilkan halaman dashboard
        require "views/admin/dashboard.php";
    }
}
