<?php require "views/layouts/header.php"; ?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($event['title']) ?></h2>
    <p><strong>Tanggal:</strong> 
        <?= htmlspecialchars($event['date_start']) ?> 
        s/d 
        <?= htmlspecialchars($event['date_end']) ?>
    </p>
    <p><strong>Lokasi:</strong> <?= htmlspecialchars($event['venue_name'] ?? '-') ?></p>
    <p><strong>Alamat:</strong> <?= htmlspecialchars($event['venue_address'] ?? '-') ?></p>

    <?php if (!empty($event['capacity'])): ?>
        <p><strong>Kapasitas:</strong> <?= (int)$event['capacity'] ?> orang</p>
    <?php endif; ?>

    <p><strong>Harga Tiket:</strong> Rp <?= number_format($event['price'], 0, ',', '.') ?></p>

    <hr>
    <p><?= nl2br(htmlspecialchars($event['description'] ?? '')) ?></p>

    <?php if (isLoggedIn() && $_SESSION['role'] === 'user'): ?>
        <a href="index.php?page=orders&event_id=<?= $event['event_id'] ?>" class="btn btn-primary">
            Pesan Tiket
        </a>
    <?php else: ?>
        <a href="index.php?page=home" class="btn btn-secondary">Kembali</a>
    <?php endif; ?>
</div>


<?php require "views/layouts/footer.php"; ?>