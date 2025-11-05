<?php
class AdminEventController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Daftar semua event
    public function index()
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        $stmt = $this->pdo->query("
            SELECT e.*, v.nama AS venue_name 
            FROM tiket_wisata_events e
            LEFT JOIN tiket_wisata_venues v ON e.venue_id = v.venue_id
            ORDER BY e.date_start ASC
        ");
        $events = $stmt->fetchAll();

        require "views/admin/events/index.php";
    }

    // 🔹 Form tambah event
    public function create()
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        $venues = $this->pdo->query("SELECT * FROM tiket_wisata_venues ORDER BY nama")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $desc = $_POST['description'];
            $venue = $_POST['venue_id'];
            $price = $_POST['price'];
            $cap = $_POST['capacity'];
            $start = $_POST['date_start'];
            $end = $_POST['date_end'];

            // upload image
            $image = null;
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/events/";
                if (!is_dir($targetDir))
                    mkdir($targetDir, 0755, true);
                $image = time() . "_" . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
            }

            $stmt = $this->pdo->prepare("
                INSERT INTO tiket_wisata_events (title, description, venue_id, price, capacity, date_start, date_end, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $desc, $venue, $price, $cap, $start, $end, $image]);

            redirect("index.php?page=admin-events&success=1");
        }

        require "views/admin/events/create.php";
    }

    // 🔹 Form edit event
    public function edit($id)
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        $stmt = $this->pdo->prepare("SELECT * FROM tiket_wisata_events WHERE event_id=?");
        $stmt->execute([$id]);
        $event = $stmt->fetch();

        if (!$event) {
            echo "<div class='alert alert-danger'>Event tidak ditemukan.</div>";
            return;
        }

        $venues = $this->pdo->query("SELECT * FROM tiket_wisata_venues ORDER BY nama")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'];
            $desc = $_POST['description'];
            $venue = $_POST['venue_id'];
            $price = $_POST['price'];
            $cap = $_POST['capacity'];
            $start = $_POST['date_start'];
            $end = $_POST['date_end'];

            $image = $event['image'];
            if (!empty($_FILES['image']['name'])) {
                $targetDir = "uploads/events/";
                if (!is_dir($targetDir))
                    mkdir($targetDir, 0755, true);
                $image = time() . "_" . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $image);
            }

            $stmt = $this->pdo->prepare("
                UPDATE tiket_wisata_events 
                SET title=?, description=?, venue_id=?, price=?, capacity=?, date_start=?, date_end=?, image=? 
                WHERE event_id=?
            ");
            $stmt->execute([$title, $desc, $venue, $price, $cap, $start, $end, $image, $id]);

            redirect("index.php?page=admin-events&success=2");
        }

        require "views/admin/events/edit.php";
    }

    // 🔹 Hapus event
 public function delete($eventId)
{
    if (!isAdmin()) {
        redirect("index.php?page=home");
    }

    if (!$eventId) {
        $_SESSION['error'] = "ID event tidak valid.";
        redirect("index.php?page=admin-events");
        return;
    }

    // 🔹 Cek apakah event masih dipakai di tabel orders
    $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM tiket_wisata_orders WHERE event_id = ?");
    $stmt->execute([$eventId]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error'] = "Event ini tidak bisa dihapus karena sudah memiliki pesanan tiket.";
        redirect("index.php?page=admin-orders");
        return;
    }

    // 🔹 Jika aman, baru hapus
    $stmt = $this->pdo->prepare("DELETE FROM tiket_wisata_events WHERE event_id = ?");
    $stmt->execute([$eventId]);

    $_SESSION['success'] = "Event berhasil dihapus.";
    redirect("index.php?page=admin-events");
}

}
