<?php require "views/layouts/header.php"; ?>

<h2>Pesan Tiket: <?= e($event['title']) ?></h2>
<p><strong>Harga per Tiket:</strong> Rp <?= number_format($event['price'], 0, ',', '.') ?></p>
<p><strong>Sisa Kuota:</strong> <?= $remaining ?> tiket</p>

<form method="post" id="orderForm">
    <div class="mb-3">
        <label>Jumlah Tiket</label>
        <input 
            type="number" 
            name="qty" 
            id="qty" 
            class="form-control" 
            min="1"
            max="<?= $remaining ?>" 
            value="1" 
            required
        >
    </div>

    <div id="owner-fields"></div>

    <button type="button" id="btnPesan" class="btn btn-success">Pesan Sekarang</button>
    <a href="index.php?page=home" class="btn btn-secondary">Batal</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const qtyInput = document.getElementById('qty');
const ownerFields = document.getElementById('owner-fields');
const maxQty = parseInt(qtyInput.max);
const btnPesan = document.getElementById('btnPesan');
const form = document.getElementById('orderForm');

// 🔹 Buat input nama pemilik tiket sesuai jumlah tiket
function renderOwnerFields(count) {
    ownerFields.innerHTML = '';
    for (let i = 0; i < count; i++) {
        ownerFields.innerHTML += `
            <div class="mb-2">
                <label>Nama Pemilik Tiket #${i + 1}</label>
                <input 
                    type="text" 
                    name="ticket_owner[]" 
                    class="form-control" 
                    placeholder="Nama Pemilik Tiket" 
                    required
                >
            </div>`;
    }
}

// 🔹 Update field nama sesuai input qty
qtyInput.addEventListener('input', function() {
    let val = parseInt(this.value);
    if (val > maxQty) {
        Swal.fire({
            icon: 'warning',
            title: 'Kuota Tidak Cukup!',
            text: `Sisa kuota hanya ${maxQty} tiket.`,
            confirmButtonText: 'OK'
        });
        this.value = maxQty;
        val = maxQty;
    } else if (val < 1 || isNaN(val)) {
        val = 1;
        this.value = 1;
    }
    renderOwnerFields(val);
});

// 🔹 SweetAlert konfirmasi sebelum submit
btnPesan.addEventListener('click', function() {
    const qty = parseInt(qtyInput.value);

    if (qty < 1 || qty > maxQty) {
        Swal.fire({
            icon: 'error',
            title: 'Jumlah tiket tidak valid',
            text: 'Silakan periksa kembali jumlah tiket yang ingin dipesan.',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Pemesanan',
        text: `Apakah kamu yakin ingin memesan ${qty} tiket untuk acara ini?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, pesan sekarang',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

// render pertama kali
renderOwnerFields(1);
</script>

<?php require "views/layouts/footer.php"; ?>
