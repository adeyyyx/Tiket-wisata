<?php require "views/layouts/admin_header.php"; ?>

<h2>Check-in Tiket</h2>

<?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= e($message) ?></div>
<?php endif; ?>

<form method="post" class="mb-4">
    <div class="mb-3">
        <label>Masukkan Kode Tiket</label>
        <input type="text" name="ticket_code" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Check-in</button>
  </form>
  <li class="nav-item">
  <a class="nav-link" href="index.php?page=admin-checkin-scan">
    <i class="bi bi-qr-code-scan"></i> Scan QR (Kamera)
  </a>
</li>


</form>

<?php require "views/layouts/admin_footer.php"; ?>