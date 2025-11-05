<?php
class AdminOrderController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Menampilkan daftar semua pesanan
    public function index()
    {
        if (!isAdmin()) {
            redirect("index.php?page=home");
        }

        // ✅ Gunakan kolom "nama" (bukan "name")
        $stmt = $this->pdo->query("
            SELECT 
                o.*, 
                e.title AS event_title, 
                u.nama AS user_name
            FROM tiket_wisata_orders o
            JOIN tiket_wisata_events e ON o.event_id = e.event_id
            JOIN tiket_wisata_users u ON o.user_id = u.user_id
            ORDER BY o.created_at DESC
        ");
        $orders = $stmt->fetchAll();

        require "views/admin/orders/index.php";
    }

    // 🔹 Detail satu pesanan
    public function detail($orderId)
    {
        if (!isAdmin()) {
            redirect("index.php?page=home");
        }

        if (!$orderId) {
            $_SESSION['error'] = "ID pesanan tidak valid.";
            redirect("index.php?page=admin-order");
            return;
        }

        // ✅ Gunakan "nama" dan "email" sesuai database
        $stmt = $this->pdo->prepare("
            SELECT 
                o.*, 
                e.title AS event_title, 
                e.date_start, 
                e.date_end, 
                u.nama AS user_name, 
                u.email AS user_email
            FROM tiket_wisata_orders o
            JOIN tiket_wisata_events e ON o.event_id = e.event_id
            JOIN tiket_wisata_users u ON o.user_id = u.user_id
            WHERE o.order_id = ?
            LIMIT 1
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        if (!$order) {
            $_SESSION['error'] = "Pesanan tidak ditemukan.";
            redirect("index.php?page=admin-order");
            return;
        }

        // Ambil tiket terkait
        $stmt2 = $this->pdo->prepare("
            SELECT * FROM tiket_wisata_tickets WHERE order_id = ?
        ");
        $stmt2->execute([$orderId]);
        $tickets = $stmt2->fetchAll();

        require "views/admin/orders/detail.php";
    }

    // 🔹 Ubah status pesanan
    public function updateStatus()
    {
        if (!isAdmin()) {
            redirect("index.php?page=home");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if (!$orderId || !$status) {
                $_SESSION['error'] = "Data tidak lengkap.";
                redirect("index.php?page=admin-orders");
                return;
            }

            $allowed = ['pending', 'paid', 'used', 'cancelled'];
            if (!in_array($status, $allowed)) {
                $_SESSION['error'] = "Status tidak valid.";
                redirect("index.php?page=admin-orders");
                return;
            }

            $stmt = $this->pdo->prepare("
                UPDATE tiket_wisata_orders 
                SET status = ? 
                WHERE order_id = ?
            ");
            $stmt->execute([$status, $orderId]);

            $_SESSION['success'] = "Status pesanan berhasil diperbarui.";
            redirect("index.php?page=admin-orders");
            return;
        }

        redirect("index.php?page=admin-orders");
    }

    // 🔹 Hapus pesanan
    public function delete($orderId)
    {
        if (!isAdmin()) {
            redirect("index.php?page=home");
        }

        if (!$orderId) {
            $_SESSION['error'] = "ID pesanan tidak valid.";
            redirect("index.php?page=admin-order");
            return;
        }

        // Hapus tiket dulu
        $stmt = $this->pdo->prepare("DELETE FROM tiket_wisata_tickets WHERE order_id = ?");
        $stmt->execute([$orderId]);

        // Hapus pesanan
        $stmt = $this->pdo->prepare("DELETE FROM tiket_wisata_orders WHERE order_id = ?");
        $stmt->execute([$orderId]);

        $_SESSION['success'] = "Pesanan berhasil dihapus.";
        redirect("index.php?page=admin-order");
    }
}
