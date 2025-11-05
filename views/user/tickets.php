<?php require "views/layouts/header.php"; ?>

<div class="container mt-4">
    <h2>Tiket Saya</h2>

    <div class="row">
        <?php if (empty($tickets)): ?>
            <div class="alert alert-info">Belum ada tiket.</div>
        <?php else: ?>
            <?php foreach ($tickets as $t): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?= e($t['title']) ?></h5>
                            <p><strong>Nama Pemilik:</strong> <?= e($t['ticket_owner']) ?></p>

                            <p><strong>Status:</strong>
                                <?php if ($t['checked_in']): ?>
                                    <span class="badge bg-success">Sudah Check-in</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Belum Check-in</span>
                                <?php endif; ?>
                            </p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#ticketModal<?= $t['ticket_id'] ?>">
                                Detail Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Tiket -->
                <div class="modal fade" id="ticketModal<?= $t['ticket_id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Tiket</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <h5><?= e($t['title']) ?></h5>
                                <p><strong>Kode Tiket:</strong> <?= e($t['ticket_code']) ?></p>
                                <img src="<?= e($t['qr_code']) ?>" alt="QR Code" class="img-fluid mb-3"
                                    style="max-width:200px;">

                                <a href="<?= e($t['qr_code']) ?>" download class="btn btn-outline-success btn-sm">
                                    Download QR
                                </a>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require "views/layouts/footer.php"; ?>