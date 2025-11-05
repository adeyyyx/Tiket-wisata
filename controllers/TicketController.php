<?php
class TicketController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Daftar tiket milik user
    public function listUserTickets()
    {
        if (!isLoggedIn()) {
            redirect("index.php?page=home");
        }

        $stmt = $this->pdo->prepare("
            SELECT 
                t.*, 
                o.order_code, 
                o.status AS order_status,
                e.title, 
                e.date_start, 
                e.date_end 
            FROM tiket_wisata_tickets t
            JOIN tiket_wisata_orders o ON t.order_id = o.order_id
            JOIN tiket_wisata_events e ON o.event_id = e.event_id
            WHERE o.user_id = ?
            ORDER BY t.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $tickets = $stmt->fetchAll();

        require "views/user/tickets.php";
    }

    // 🔹 Check-in tiket manual oleh admin
    public function checkin()
    {
        if (!isAdmin()) {
            redirect("index.php?page=home");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ticketCode = trim($_POST['ticket_code'] ?? '');

            if (empty($ticketCode)) {
                $_SESSION['error'] = "Kode tiket wajib diisi.";
                redirect("index.php?page=checkin");
                return;
            }

            // Ambil data tiket
            $stmt = $this->pdo->prepare("
                SELECT 
                    t.*, 
                    o.order_id, 
                    o.order_code, 
                    o.status AS order_status,
                    e.title 
                FROM tiket_wisata_tickets t
                JOIN tiket_wisata_orders o ON t.order_id = o.order_id
                JOIN tiket_wisata_events e ON o.event_id = e.event_id
                WHERE t.ticket_code = ?
            ");
            $stmt->execute([$ticketCode]);
            $ticket = $stmt->fetch();

            if (!$ticket) {
                $_SESSION['error'] = "❌ Tiket tidak ditemukan!";
                redirect("index.php?page=checkin");
                return;
            }

            if ($ticket['checked_in']) {
                $_SESSION['error'] = "⚠️ Tiket <strong>{$ticket['ticket_code']}</strong> sudah digunakan sebelumnya!";
                redirect("index.php?page=checkin");
                return;
            }

            // Tandai tiket sudah digunakan
            $this->pdo->prepare("
                UPDATE tiket_wisata_tickets 
                SET checked_in = 1 
                WHERE ticket_id = ?
            ")->execute([$ticket['ticket_id']]);

            // Cek apakah semua tiket di order sudah check-in
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) 
                FROM tiket_wisata_tickets 
                WHERE order_id = ? AND checked_in = 0
            ");
            $stmt->execute([$ticket['order_id']]);
            $remaining = $stmt->fetchColumn();

            // Update status order
            if ($remaining == 0) {
                // Semua tiket sudah digunakan
                $this->pdo->prepare("
                    UPDATE tiket_wisata_orders 
                    SET status = 'used' 
                    WHERE order_id = ?
                ")->execute([$ticket['order_id']]);
            } else {
                // Masih ada tiket yang belum digunakan
                $this->pdo->prepare("
                    UPDATE tiket_wisata_orders 
                    SET status = 'partial' 
                    WHERE order_id = ? AND status != 'used'
                ")->execute([$ticket['order_id']]);
            }

            $_SESSION['success'] = "✅ Tiket <strong>{$ticket['ticket_code']}</strong> berhasil check-in untuk event <strong>{$ticket['title']}</strong>.";
            redirect("index.php?page=admin-orders");
            return;
        }

        require "views/admin/checkin.php";
    }

    // 🔹 Check-in tiket via QR Scan (kamera)
    public function processScan()
    {
        if (!isAdmin()) return;

        $ticketCode = $_POST['ticket_code'] ?? '';
        if (!$ticketCode) {
            echo "❌ Kode tiket tidak ditemukan.";
            return;
        }

        $stmt = $this->pdo->prepare("
            SELECT t.*, e.title, o.order_id
            FROM tiket_wisata_tickets t
            JOIN tiket_wisata_orders o ON t.order_id = o.order_id
            JOIN tiket_wisata_events e ON o.event_id = e.event_id
            WHERE t.ticket_code = ?
        ");
        $stmt->execute([$ticketCode]);
        $ticket = $stmt->fetch();

        if (!$ticket) {
            echo "❌ Tiket tidak valid.";
            return;
        }

        if ($ticket['checked_in']) {
            echo "⚠️ Tiket <b>{$ticket['ticket_code']}</b> sudah digunakan sebelumnya.";
            return;
        }

        // Ubah status tiket
        $this->pdo->prepare("
            UPDATE tiket_wisata_tickets SET checked_in = 1 WHERE ticket_id = ?
        ")->execute([$ticket['ticket_id']]);

        // Cek apakah semua tiket di order sudah check-in
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM tiket_wisata_tickets WHERE order_id = ? AND checked_in = 0
        ");
        $stmt->execute([$ticket['order_id']]);
        $remaining = $stmt->fetchColumn();

        // Update status order
        if ($remaining == 0) {
            $this->pdo->prepare("
                UPDATE tiket_wisata_orders SET status = 'used' WHERE order_id = ?
            ")->execute([$ticket['order_id']]);
        } else {
            $this->pdo->prepare("
                UPDATE tiket_wisata_orders SET status = 'partial' WHERE order_id = ? AND status != 'used'
            ")->execute([$ticket['order_id']]);
        }

        echo "✅ Tiket <b>{$ticket['ticket_code']}</b> untuk event <b>{$ticket['title']}</b> berhasil check-in!";
    }
}
