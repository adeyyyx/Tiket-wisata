<?php require "views/layouts/admin_header.php"; ?>

<h2>Tambah Venue</h2>
<form method="post">
    <div class="mb-3">
        <label>Nama Venue</label>
        <input type="text" name="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="index.php?page=admin-venues" class="btn btn-secondary">Kembali</a>
</form>

<?php require "views/layouts/admin_footer.php"; ?>