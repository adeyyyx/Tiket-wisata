<?php
class EventController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Menampilkan semua event (halaman home)
    public function index()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT e.*, v.nama AS venue_name
                FROM tiket_wisata_events e
                LEFT JOIN tiket_wisata_venues v ON e.venue_id = v.venue_id
                ORDER BY e.date_start ASC
            ");
            $events = $stmt->fetchAll();
        } catch (PDOException $e) {
            $_SESSION['error'] = "Terjadi kesalahan mengambil daftar event: " . $e->getMessage();
            $events = [];
        }

        require "views/home.php";
    }

    // 🔹 Menampilkan detail satu event
    public function show($eventId)
    {
        if (empty($eventId)) {
            $_SESSION['error'] = "Event tidak ditemukan.";
            redirect("index.php?page=home");
            return;
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    e.*, 
                    v.nama AS venue_name, 
                    v.alamat AS venue_address, 
                    v.kapasitas AS venue_capacity
                FROM tiket_wisata_events e
                LEFT JOIN tiket_wisata_venues v ON e.venue_id = v.venue_id
                WHERE e.event_id = ?
                LIMIT 1
            ");
            $stmt->execute([$eventId]);
            $event = $stmt->fetch();

            if (!$event) {
                $_SESSION['error'] = "Event tidak ditemukan.";
                redirect("index.php?page=home");
                return;
            }

            // Hitung kuota tiket tersisa
            $stmt2 = $this->pdo->prepare("
                SELECT COALESCE(SUM(qty), 0) as sold
                FROM tiket_wisata_orders
                WHERE event_id = ? AND status IN ('pending', 'paid', 'used')
            ");
            $stmt2->execute([$eventId]);
            $sold = (int) ($stmt2->fetchColumn() ?? 0);

            $remaining = null;
            if (isset($event['capacity']) && is_numeric($event['capacity'])) {
                $remaining = max(0, (int)$event['capacity'] - $sold);
            }

        } catch (PDOException $e) {
            $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
            redirect("index.php?page=home");
            return;
        }

        require "views/event_detail.php";
    }
}
