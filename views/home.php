<?php require "views/layouts/header.php"; ?>

<div class="container">
    <div class="d-flex align-items-center justify-content-between my-4">
        <h1 class="h3 mb-0">Daftar Event Wisata</h1>

    </div>

    <style>
        .event-card .badge-price {
            position: absolute;
            left: 12px;
            top: 12px;
            z-index: 3
        }

        .event-card .badge-date {
            position: absolute;
            right: 12px;
            top: 12px;
            z-index: 3
        }

        .event-card .card-img-top {
            height: 200px;
            object-fit: cover
        }

        .event-card .card-body {
            min-height: 150px
        }
    </style>

    <div class="row g-3">
        <?php if (empty($events)): ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada event tersedia.</div>
            </div>
        <?php else: ?>
            <?php foreach ($events as $e): ?>
                <?php $modalId = 'eventModal' . $e['event_id']; ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card event-card shadow-sm h-100">
                        <div class="position-relative">
                            <?php if (!empty($e['image'])): ?>
                                <img src="uploads/events/<?= e($e['image']) ?>" class="card-img-top" alt="<?= e($e['title']) ?>">
                            <?php else: ?>
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary text-white">
                                    <i class="bi bi-camera" style="font-size:2rem"></i>
                                </div>
                            <?php endif; ?>

                            <span class="badge bg-success badge-price">Rp <?= number_format($e['price'], 0, ',', '.') ?></span>
                            <span class="badge bg-dark badge-date"><i class="bi bi-calendar-event me-1"></i>
                                <?= date("d M Y", strtotime($e['date_start'])) ?></span>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1"><?= e($e['title']) ?></h5>
                            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1"></i><?= e($e['venue_name']) ?>
                            </p>
                            <p class="card-text text-truncate mb-3">
                                <?= strip_tags(substr(e($e['description']), 0, 140)) ?>
                                <?= strlen($e['description']) > 140 ? '...' : '' ?>
                            </p>

                            <div class="mt-auto d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-2">
                                    <a href="index.php?page=event&id=<?= $e['event_id'] ?>"
                                        class="btn btn-outline-primary btn-sm"><i class="bi bi-eye"></i> Detail</a>
                                    <?php if (isLoggedIn()): ?>
                                        <a href="index.php?page=order&event_id=<?= $e['event_id'] ?>"
                                            class="btn btn-primary btn-sm"><i class="bi bi-cart-fill"></i> Pesan</a>
                                    <?php else: ?>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#loginModal"><i class="bi bi-cart-fill"></i> Pesan</button>
                                    <?php endif; ?>
                                </div>

                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary quick-view-btn"
                                        data-title="<?= htmlspecialchars(e($e['title']), ENT_QUOTES) ?>"
                                        data-image="<?= !empty($e['image']) ? 'uploads/events/' . htmlspecialchars(e($e['image']), ENT_QUOTES) : '' ?>"
                                        data-price="<?= number_format($e['price'], 0, ',', '.') ?>"
                                        data-venue="<?= htmlspecialchars(e($e['venue_name']), ENT_QUOTES) ?>"
                                        data-date="<?= date("d M Y H:i", strtotime($e['date_start'])) ?>"
                                        data-capacity="<?= htmlspecialchars($e['capacity'], ENT_QUOTES) ?>"
                                        data-description="<?= htmlspecialchars(e($e['description']), ENT_QUOTES) ?>"
                                        data-order-url="index.php?page=order&event_id=<?= $e['event_id'] ?>">Quick view</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Single dynamic Quick View modal -->
<div class="modal fade" id="singleEventModal" tabindex="-1" aria-labelledby="singleEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="singleEventModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6" id="singleEventImageWrap">
                        <!-- image inserted here -->
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Harga:</strong> <span id="singleEventPrice"></span></p>
                        <p class="mb-1"><strong>Lokasi:</strong> <span id="singleEventVenue"></span></p>
                        <p class="mb-1"><strong>Tanggal:</strong> <span id="singleEventDate"></span></p>
                        <p class="mb-1"><strong>Kuota:</strong> <span id="singleEventCapacity"></span></p>
                        <hr>
                        <div id="singleEventDescription"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php if (isLoggedIn()): ?>
                    <a id="modalOrderBtn" href="#" class="btn btn-primary">Pesan Tiket</a>
                <?php else: ?>
                    <button id="modalLoginBtn" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#loginModal">Pesan Tiket</button>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var modalEl = document.getElementById('singleEventModal');
        if (!modalEl) return;
        var bsModal = new bootstrap.Modal(modalEl);

        function nl2br(s) { return s.replace(/\n/g, '<br>'); }

        document.querySelectorAll('.quick-view-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var title = this.dataset.title || '';
                var image = this.dataset.image || '';
                var price = this.dataset.price || '';
                var venue = this.dataset.venue || '';
                var date = this.dataset.date || '';
                var capacity = this.dataset.capacity || '';
                var desc = this.dataset.description || '';
                var orderUrl = this.dataset.orderUrl || '#';

                document.getElementById('singleEventModalLabel').textContent = title;

                var imgWrap = document.getElementById('singleEventImageWrap');
                if (image) {
                    imgWrap.innerHTML = '<img src="' + image + '" class="img-fluid rounded" alt="' + (title) + '">';
                } else {
                    imgWrap.innerHTML = '<div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height:260px"><i class="bi bi-camera" style="font-size:2rem"></i></div>';
                }

                document.getElementById('singleEventPrice').textContent = 'Rp ' + price;
                document.getElementById('singleEventVenue').textContent = venue;
                document.getElementById('singleEventDate').textContent = date;
                document.getElementById('singleEventCapacity').textContent = capacity;
                document.getElementById('singleEventDescription').innerHTML = nl2br(desc);

                var orderBtn = document.getElementById('modalOrderBtn');
                if (orderBtn) orderBtn.setAttribute('href', orderUrl);

                bsModal.show();
            });
        });
    })();
</script>

<?php require "views/layouts/footer.php"; ?>