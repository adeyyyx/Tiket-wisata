<?php require "views/layouts/admin_header.php"; ?>

<div class="container-fluid">
    <h2 class="mb-4">Dashboard Admin</h2>

    <div class="row g-3">
        <!-- Statistik Card -->
        <div class="col-md-3">
            <div class="card text-bg-primary shadow-sm">
                <div class="card-body">
                    <h5>Total Pengguna</h5>
                    <h3><?= $totalUsers ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-success shadow-sm">
                <div class="card-body">
                    <h5>Total Event</h5>
                    <h3><?= $totalEvents ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-warning shadow-sm">
                <div class="card-body">
                    <h5>Total Pesanan</h5>
                    <h3><?= $totalOrders ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-bg-danger shadow-sm">
                <div class="card-body">
                    <h5>Total Tiket</h5>
                    <h3><?= $totalTickets ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Event Terpopuler -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Event Terpopuler</h5>
        </div>
        <div class="card-body">
            <?php if (empty($popularEvents)): ?>
                <div class="alert alert-info">Belum ada data pemesanan.</div>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Event</th>
                            <th>Jumlah Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($popularEvents as $i => $row): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= e($row['title']) ?></td>
                                <td><?= $row['total_order'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require "views/layouts/admin_footer.php"; ?>