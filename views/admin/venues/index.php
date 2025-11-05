<?php require "views/layouts/admin_header.php"; ?>

<h2 class="mb-4">Kelola Venue</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        <?php
        if ($_GET['success'] == 1)
            echo "Venue berhasil ditambahkan!";
        elseif ($_GET['success'] == 2)
            echo "Venue berhasil diperbarui!";
        elseif ($_GET['success'] == 3)
            echo "Venue berhasil dihapus!";
        ?>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between mb-3">
    <a href="index.php?page=admin-dashboard" class="btn btn-outline-secondary">⬅ Kembali ke Dashboard</a>
    <a href="index.php?page=admin-venues-create" class="btn btn-primary">Tambah Venue</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nama Venue</th>
                    <th>Alamat</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($venues)): ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">Belum ada data venue.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($venues as $v): ?>
                        <tr>
                            <td><?= e($v['nama']) ?></td>
                            <td><?= e($v['alamat']) ?></td>
                            <td>
                                <a href="index.php?page=admin-venues-edit&id=<?= $v['venue_id'] ?>"
                                    class="btn btn-warning btn-sm">Edit</a>
                                <a href="index.php?page=admin-venues-delete&id=<?= $v['venue_id'] ?>"
                                    class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus venue?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require "views/layouts/admin_footer.php"; ?>