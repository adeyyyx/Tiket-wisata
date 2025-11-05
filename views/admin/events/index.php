<?php require "views/layouts/admin_header.php"; ?>

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h2 class="mb-0">Kelola Event</h2>
            <?php if (isset($_GET['success'])): ?>
                <div class="mt-2">
                    <div class="alert alert-success mb-0">
                        <?php if ($_GET['success'] == 1)
                            echo "Event berhasil ditambahkan!";
                        elseif ($_GET['success'] == 2)
                            echo "Event berhasil diperbarui!";
                        elseif ($_GET['success'] == 3)
                            echo "Event berhasil dihapus!"; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex gap-2">
            <form class="d-flex" method="get" action="index.php">
                <input type="hidden" name="page" value="admin-events">
                <input class="form-control form-control-sm me-2" type="search" name="q"
                    placeholder="Cari judul atau venue" value="<?= isset($_GET['q']) ? e($_GET['q']) : '' ?>"
                    aria-label="Search">
                <button class="btn btn-outline-secondary btn-sm" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <a href="index.php?page=admin-events-create" class="btn btn-primary btn-sm"> <i class="bi bi-plus-lg"></i>
                Tambah Event</a>
        </div>
    </div>

    <div class="table-responsive">
        <?php if (empty($events)): ?>
            <div class="alert alert-info">Belum ada event. <a href="index.php?page=admin-events-create">Buat event
                    pertama</a>.</div>
        <?php else: ?>
            <table class="table table-hover table-striped table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Judul</th>
                        <th>Venue</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Kuota</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $e): ?>
                        <tr>
                            <td style="min-width:220px"><?= e($e['title']) ?></td>
                            <td><?= e($e['venue_name']) ?></td>
                            <td class="text-end"><span class="badge bg-success">Rp
                                    <?= number_format($e['price'], 0, ',', '.') ?></span></td>
                            <td class="text-center"><span class="badge bg-primary"><?= $e['capacity'] ?></span></td>
                            <td><?= date("d M Y H:i", strtotime($e['date_start'])) ?></td>
                            <td class="text-center">
                                <a href="index.php?page=admin-events-edit&id=<?= $e['event_id'] ?>"
                                    class="btn btn-outline-warning btn-sm me-1" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="index.php?page=admin-events-delete&id=<?= $e['event_id'] ?>"
                                    class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin hapus event?')"
                                    title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require "views/layouts/admin_footer.php"; ?>