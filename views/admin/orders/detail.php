<?php require "views/layouts/admin_header.php"; ?>

<div class="container-fluid">
    <h3>Detail Pesanan: <?= e($order['order_code']) ?></h3>
    <p><strong>User:</strong> <?= e($order['user_name']) ?></p>
    <p><strong>Event:</strong> <?= e($order['event_title']) ?></p>
    <p><strong>Status:</strong> <?= ucfirst(e($order['status'])) ?></p>
    <p><strong>Total:</strong> Rp <?= number_format($order['total'], 0, ',', '.') ?></p>

    <h5 class="mt-4">Daftar Tiket</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kode Tiket</th>
                <th>Nama Pemilik</th>
                <th>QR Code</th>
                <th>Status Check-in</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= e($t['ticket_code']) ?></td>
                    <td><?= e($t['ticket_owner']) ?></td>
                    <td><img src="<?= e($t['qr_code']) ?>" width="80"></td>
                    <td><?= $t['checked_in'] ? '<span class="badge bg-success">Sudah</span>' : '<span class="badge bg-danger">Belum</span>' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="index.php?page=admin-orders" class="btn btn-secondary mt-3">← Kembali</a>
</div>

<?php require "views/layouts/admin_footer.php"; ?>