<?php require "views/layouts/admin_header.php"; ?>
<script src="https://unpkg.com/qr-scanner@1.4.2/qr-scanner.min.js" type="module"></script>
<script>
QrScanner.WORKER_PATH = "https://unpkg.com/qr-scanner@1.4.2/qr-scanner-worker.min.js";
</script>

<div class="container mt-4">
    <h2><i class="bi bi-qr-code-scan"></i> Scan QR Check-In (Offline Ready)</h2>
    <p class="text-muted">Arahkan kamera ke QR Code tiket untuk check-in otomatis.</p>

    <div class="row">
        <div class="col-md-6">
            <video id="preview" class="w-100 border rounded shadow-sm" style="aspect-ratio: 1/1; background: #000;"></video>
        </div>
        <div class="col-md-6">
            <div id="result-box" class="alert alert-info mt-3">
                <strong>Menunggu hasil scan...</strong>
            </div>
        </div>
    </div>
</div>


<script type="module">
import QrScanner from "./assets/js/qr-scanner.min.js";
QrScanner.WORKER_PATH = "./assets/js/qr-scanner-worker.min.js";

const video = document.getElementById("preview");
const resultBox = document.getElementById("result-box");

const scanner = new QrScanner(video, result => {
    if (result) {
        scanner.stop();
        resultBox.className = "alert alert-warning";
        resultBox.innerHTML = `<strong>Memproses tiket:</strong> ${result}`;

        fetch("index.php?page=checkin-process", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "ticket_code=" + encodeURIComponent(result)
        })
        .then(res => res.text())
        .then(msg => {
            resultBox.className = "alert alert-success";
            resultBox.innerHTML = msg + "<br><br><button class='btn btn-primary' onclick='window.location.reload()'>Scan Lagi</button>";
        })
        .catch(err => {
            resultBox.className = "alert alert-danger";
            resultBox.innerHTML = "Terjadi kesalahan saat proses check-in.";
        });
    }
});

scanner.start().catch(err => {
    console.error("Camera Error:", err);
    resultBox.className = "alert alert-danger";
    resultBox.innerHTML = "❌ Tidak dapat mengakses kamera.<br><small>" + err.message + "</small>";
});

</script>

<?php require "views/layouts/footer.php"; ?>
