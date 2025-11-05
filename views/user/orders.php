<?php require "views/layouts/header.php"; ?>

<?php
// pastikan $orders selalu ada (hindari warning)
$orders = $orders ?? [];
?>

<div class="container mt-4">
    <h2>Pesanan Saya</h2>

    <!-- Catatan: tiket hanya bisa dibatalkan saat status pending -->
    <div class="alert alert-info small">
        Catatan: Tiket hanya bisa dibatalkan jika status pesanan masih <strong>pending</strong>.
    </div>

    <!-- Session messages -->
    <?php if (!empty($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= e($_SESSION['success']);
        unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?= e($_SESSION['error']);
        unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (empty($orders)): ?>
    <div class="alert alert-info">Belum ada pesanan.</div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($orders as $o):
            $status = $o['status'] ?? 'pending';
            $badge = $status === 'paid' ? 'success' : ($status === 'used' ? 'secondary' : ($status === 'cancelled' ? 'danger' : 'warning'));
        ?>
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h5 class="card-title mb-1"><?= e($o['title'] ?? '-') ?></h5>
                                <small class="text-muted">Kode: <strong><?= e($o['order_code'] ?? '') ?></strong></small>
                            </div>
                            <span class="badge bg-<?= $badge ?>"><?= e(ucfirst($status)) ?></span>
                        </div>

                        <p class="card-text mb-2">
                            <strong>Tanggal:</strong>
                            <?= !empty($o['date_start']) ? date("d M Y H:i", strtotime($o['date_start'])) : '-' ?><br>
                            <strong>Jumlah:</strong> <?= (int)($o['qty'] ?? 0) ?> &nbsp; 
                            <strong>Total:</strong> Rp <?= number_format((float)($o['total'] ?? 0), 0, ',', '.') ?>
                        </p>

                        <div class="mt-auto d-flex gap-2">
                            <a href="index.php?page=tickets" class="btn btn-sm btn-outline-info">Lihat Tiket</a>

                            <?php if (($o['status'] ?? 'pending') === 'pending'): ?>
                                <form method="post" action="index.php?page=order-cancel" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= e($o['order_id']) ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Yakin batalkan pesanan ini?')">
                                        Batalkan
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($o['created_at'])): ?>
                        <div class="card-footer text-muted small">
                            Dibuat: <?= date("d M Y H:i", strtotime($o['created_at'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

<?php require "views/layouts/footer.php"; ?>