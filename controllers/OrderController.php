<?php
require_once "phpqrcode/qrlib.php"; // pastikan sudah ada folder phpqrcode/

class OrderController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Form pemesanan tiket
    public function create()
    {
        if (!isLoggedIn()) {
            redirect("index.php?page=home");
        }

        $eventId = $_GET['event_id'] ?? null;
        if (!$eventId) {
            $_SESSION['error'] = "Event tidak ditemukan.";
            redirect("index.php?page=home");
            return;
        }

        // Ambil data event
        $stmt = $this->pdo->prepare("SELECT * FROM tiket_wisata_events WHERE event_id = ?");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch();

        if (!$event) {
            $_SESSION['error'] = "Event tidak ditemukan.";
            redirect("index.php?page=home");
            return;
        }

        // Hitung kuota tersisa
        $stmt2 = $this->pdo->prepare("
            SELECT SUM(qty) as sold 
            FROM tiket_wisata_orders 
            WHERE event_id = ? AND status IN ('pending','paid','used')
        ");
        $stmt2->execute([$eventId]);
        $sold = (int) ($stmt2->fetchColumn() ?? 0);
        $remaining = max(0, $event['capacity'] - $sold);

        // Jika form dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $qty = max(1, (int) $_POST['qty']);

            // ✅ Cek jika melebihi kuota
            if ($qty > $remaining) {
                echo "
                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Kuota Tidak Cukup!',
                        text: 'Jumlah tiket yang Anda pesan melebihi kuota tersedia ($remaining tersisa).',
                        confirmButtonText: 'OK'
                    }).then(() => window.location.href = 'index.php?page=event&id=$eventId');
                    </script>
                ";
                return;
            }

            $total = $event['price'] * $qty;

            // Buat order
            $orderCode = generateOrderCode();
            $stmt = $this->pdo->prepare("
                INSERT INTO tiket_wisata_orders (order_code, user_id, event_id, qty, total, status) 
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");
            $stmt->execute([$orderCode, $_SESSION['user_id'], $eventId, $qty, $total]);
            $orderId = $this->pdo->lastInsertId();

            // Generate tiket dan QR Code
            $qrDir = "uploads/qr/";
            if (!is_dir($qrDir)) mkdir($qrDir, 0755, true);

            $owners = $_POST['ticket_owner'] ?? [];
            $stmtT = $this->pdo->prepare("
                INSERT INTO tiket_wisata_tickets (order_id, ticket_code, ticket_owner, qr_code, checked_in) 
                VALUES (?, ?, ?, ?, 0)
            ");

            for ($i = 0; $i < $qty; $i++) {
                $ticketCode = generateTicketCode();
                $owner = trim($owners[$i] ?? 'Tidak diketahui');
                $qrPath = $qrDir . $ticketCode . ".png";
                QRcode::png($ticketCode, $qrPath, QR_ECLEVEL_L, 4);
                $stmtT->execute([$orderId, $ticketCode, $owner, $qrPath]);
            }

            $_SESSION['success'] = "Pemesanan berhasil! Kode Order: <strong>$orderCode</strong>";
            redirect("index.php?page=orders");
            return;
        }

        // Tampilkan form
        require "views/user/order_form.php";
    }

    // 🔹 Daftar pesanan user
    public function listUserOrders()
    {
        if (!isLoggedIn()) redirect("index.php?page=home");

        $stmt = $this->pdo->prepare("
            SELECT o.*, e.title, e.date_start, e.price 
            FROM tiket_wisata_orders o
            JOIN tiket_wisata_events e ON o.event_id = e.event_id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll();

        require "views/user/orders.php";
    }

    // 🔹 Batalkan pesanan
    public function cancel()
    {
        if (!isLoggedIn()) redirect("index.php?page=home");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            if (!$orderId) {
                $_SESSION['error'] = "ID pesanan tidak valid.";
                redirect("index.php?page=orders");
                return;
            }

            // Pastikan order milik user
            $stmt = $this->pdo->prepare("
                SELECT * FROM tiket_wisata_orders WHERE order_id = ? AND user_id = ?
            ");
            $stmt->execute([$orderId, $_SESSION['user_id']]);
            $order = $stmt->fetch();

            if (!$order) {
                $_SESSION['error'] = "Pesanan tidak ditemukan.";
                redirect("index.php?page=orders");
                return;
            }

            if ($order['status'] !== 'pending') {
                $_SESSION['error'] = "Pesanan sudah diproses, tidak bisa dibatalkan.";
                redirect("index.php?page=orders");
                return;
            }

            // Update jadi cancelled
            $this->pdo->prepare("
                UPDATE tiket_wisata_orders SET status = 'cancelled' WHERE order_id = ?
            ")->execute([$orderId]);

            // Hapus tiket agar kuota kembali
            $this->pdo->prepare("DELETE FROM tiket_wisata_tickets WHERE order_id = ?")->execute([$orderId]);

            $_SESSION['success'] = "Pesanan berhasil dibatalkan.";
            redirect("index.php?page=orders");
        }
    }

    // 🔹 Hapus pesanan yang sudah dibatalkan
    public function delete()
    {
        if (!isLoggedIn()) redirect("index.php?page=home");

        $orderId = $_GET['id'] ?? null;
        if (!$orderId) redirect("index.php?page=orders");

        $stmt = $this->pdo->prepare("
            SELECT * FROM tiket_wisata_orders 
            WHERE order_id = ? AND user_id = ? AND status = 'cancelled'
        ");
        $stmt->execute([$orderId, $_SESSION['user_id']]);
        $order = $stmt->fetch();

        if (!$order) {
            $_SESSION['error'] = "Pesanan tidak dapat dihapus.";
            redirect("index.php?page=orders");
            return;
        }

        // Hapus order & tiketnya
        $this->pdo->prepare("DELETE FROM tiket_wisata_tickets WHERE order_id = ?")->execute([$orderId]);
        $this->pdo->prepare("DELETE FROM tiket_wisata_orders WHERE order_id = ?")->execute([$orderId]);

        $_SESSION['success'] = "Pesanan berhasil dihapus.";
        redirect("index.php?page=orders");
    }
}
