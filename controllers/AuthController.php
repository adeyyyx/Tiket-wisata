<?php
class AuthController
{
    private $pdo;

    public function __construct($pdo)
    {
        // Pastikan PDO dalam mode exception agar error tampil
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo = $pdo;
    }

    // 🔹 Register User Baru
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($nama) || empty($email) || empty($password)) {
                $_SESSION['error'] = "Semua field wajib diisi.";
                redirect("index.php?page=home");
                return;
            }

            try {
                // Cek email sudah ada
                $stmt = $this->pdo->prepare("SELECT user_id FROM tiket_wisata_users WHERE email = ?");
                $stmt->execute([$email]);

                if ($stmt->fetch()) {
                    $_SESSION['error'] = "Email sudah terdaftar!";
                    redirect("index.php?page=home");
                    return;
                }

                // Simpan user baru
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $this->pdo->prepare("
                    INSERT INTO tiket_wisata_users (nama, email, password, role)
                    VALUES (?, ?, ?, 'user')
                ");
                $stmt->execute([$nama, $email, $hashedPassword]);

                // Auto login
                $newUserId = $this->pdo->lastInsertId();
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['nama'] = $nama;
                $_SESSION['role'] = "user";

                $_SESSION['success'] = "Registrasi berhasil, selamat datang $nama!";
                redirect("index.php?page=home");
            } catch (PDOException $e) {
                $_SESSION['error'] = "Terjadi kesalahan saat registrasi: " . $e->getMessage();
                redirect("index.php?page=home");
            }
        } else {
            redirect("index.php?page=home");
        }
    }

    // 🔹 Login User / Admin
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $_SESSION['error'] = "Email dan password wajib diisi.";
                redirect("index.php?page=home");
                return;
            }

            try {
                $stmt = $this->pdo->prepare("SELECT * FROM tiket_wisata_users WHERE email = ?");
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['nama'] = $user['nama'];
                    $_SESSION['role'] = $user['role'];

                    $_SESSION['success'] = "Selamat datang kembali, {$user['nama']}!";

                    if ($user['role'] === 'admin') {
                        redirect("index.php?page=admin-dashboard");
                    } else {
                        redirect("index.php?page=home");
                    }
                } else {
                    $_SESSION['error'] = "Email atau password salah!";
                    redirect("index.php?page=home");
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Login gagal: " . $e->getMessage();
                redirect("index.php?page=home");
            }
        } else {
            redirect("index.php?page=home");
        }
    }

    // 🔹 Logout
    public function logout()
    {
        session_unset();
        session_destroy();
        redirect("index.php?page=home");
    }
}
