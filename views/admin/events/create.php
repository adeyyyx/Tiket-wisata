<?php require "views/layouts/admin_header.php"; ?>

<h2>Tambah Event</h2>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Deskripsi</label>
        <textarea name="description" class="form-control" rows="4"></textarea>
    </div>
    <div class="mb-3">
        <label>Venue</label>
        <select name="venue_id" class="form-select" required>
            <option value="">Pilih Venue</option>
            <?php foreach ($venues as $v): ?>
                <option value="<?= $v['venue_id'] ?>"><?= e($v['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Harga</label>
        <input type="number" name="price" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Kuota</label>
        <input type="number" name="capacity" class="form-control">
    </div>
    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="datetime-local" name="date_start" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Selesai</label>
        <input type="datetime-local" name="date_end" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Gambar</label>
        <input type="file" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php?page=admin-events" class="btn btn-secondary">Kembali</a>
</form>

<?php require "views/layouts/admin_footer.php"; ?>