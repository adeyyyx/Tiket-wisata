<?php require "views/layouts/admin_header.php"; ?>

<h2>Edit Event</h2>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="<?= e($event['title']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="4"><?= e($event['description']) ?></textarea>
    </div>
    <div class="mb-3">
        <label>Venue</label>
        <select name="venue_id" class="form-select" required>
            <option value="">Pilih Venue</option>
            <?php foreach ($venues as $v): ?>
                <option value="<?= $v['venue_id'] ?>" <?= $event['venue_id'] == $v['venue_id'] ? 'selected' : '' ?>>
                    <?= e($v['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" value="<?= e($event['price']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Kuota</label>
        <input type="number" name="capacity" class="form-control" value="<?= e($event['capacity']) ?>">
    </div>
    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="datetime-local" name="date_start" class="form-control"
            value="<?= date('Y-m-d\TH:i', strtotime($event['date_start'])) ?>" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Selesai</label>
        <input type="datetime-local" name="date_end" class="form-control"
            value="<?= date('Y-m-d\TH:i', strtotime($event['date_end'])) ?>" required>
    </div>
    <div class="mb-3">
        <label>Gambar</label><br>
        <?php if ($event['image']): ?>
            <img src="uploads/events/<?= e($event['image']) ?>" alt="Event Image" width="150" class="mb-2">
        <?php endif; ?>
        <input type="file" name="image" class="form-control">
        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
    <a href="index.php?page=admin-events" class="btn btn-secondary">Kembali</a>
</form>

<?php require "views/layouts/admin_footer.php"; ?>