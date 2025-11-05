<?php require "views/layouts/admin_header.php"; ?>

<div class="container-fluid">
    <h2>Kelola Pesanan</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'];
        unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <table class="table table-striped table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Kode Order</th>
                <th>User</th>
                <th>Event</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= e($order['order_code']) ?></td>
                    <td><?= e($order['user_name']) ?></td>
                    <td><?= e($order['event_title']) ?></td>
                    <td><?= e($order['qty']) ?></td>
                    <td>Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                    <td>
                        <form method="post" action="index.php?page=admin-orders-update" class="d-flex">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <select name="status" class="form-select form-select-sm me-2">
                                <?php
                                $statuses = ['pending', 'paid', 'partial', 'used', 'cancelled'];
                                foreach ($statuses as $s): ?>
                                    <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>>
                                        <?= ucfirst($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Ubah</button>
                        </form>
                    </td>
                    <td>
                        <a href="index.php?page=admin-orders-detail&id=<?= $order['order_id'] ?>"
                            class="btn btn-info btn-sm">
                            Detail
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require "views/layouts/admin_footer.php"; ?>