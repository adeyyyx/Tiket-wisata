<?php
class AdminVenueController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // 🔹 Daftar venue
    public function index()
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        $venues = $this->pdo->query("SELECT * FROM tiket_wisata_venues ORDER BY nama")->fetchAll();
        require "views/admin/venues/index.php";
    }

    // 🔹 Tambah venue
    public function create()
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];

            $stmt = $this->pdo->prepare("INSERT INTO tiket_wisata_venues (nama, alamat) VALUES (?, ?)");
            $stmt->execute([$nama, $alamat]);

            redirect("index.php?page=admin-venues&success=1");
        }

        require "views/admin/venues/create.php";
    }

    // 🔹 Edit venue
    public function edit($id)
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        $stmt = $this->pdo->prepare("SELECT * FROM tiket_wisata_venues WHERE venue_id=?");
        $stmt->execute([$id]);
        $venue = $stmt->fetch();

        if (!$venue) {
            echo "<div class='alert alert-danger'>Venue tidak ditemukan.</div>";
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];

            $stmt = $this->pdo->prepare("UPDATE tiket_wisata_venues SET nama=?, alamat=? WHERE venue_id=?");
            $stmt->execute([$nama, $alamat, $id]);

            redirect("index.php?page=admin-venues&success=2");
        }

        require "views/admin/venues/edit.php";
    }

    // 🔹 Hapus venue
    public function delete($id)
    {
        if (!isAdmin())
            redirect("index.php?page=home");

        $stmt = $this->pdo->prepare("DELETE FROM tiket_wisata_venues WHERE venue_id=?");
        $stmt->execute([$id]);

        redirect("index.php?page=admin-venues&success=3");
    }
}
