<?php
require "koneksi.php";

// Ambil notifikasi (sekali tampil)
$pesan = $_SESSION['pesan'] ?? null;
unset($_SESSION['pesan']);

// Ambil semua produk
$hasil  = mysqli_query($conn, "SELECT No AS no, Nama_Produk AS nama_produk, Harga_Produk AS harga, Upload_Gambar_Produk AS foto FROM webasas1 ORDER BY No DESC");
$errorDb = $hasil ? null : mysqli_error($conn);
$produk = $hasil ? mysqli_fetch_all($hasil, MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEANLIVSTORE</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="header">
    <div class="header-teks">
        <h1>SEANLIV<span>STORE</span></h1>
        <p>SELAMAT DATANG DI SEANLIVSTORE - Happy Shopping!</p>
    </div>

    <nav class="nav">
        <a href="#">Home</a>
        <a href="#products">Products</a>
        <a href="#kontak">Contact</a>
    </nav>
</header>

<!-- ================= PRODUCTS ================= -->
<main class="konten" id="products">

    <div class="judul-bar">
        <div>
            <h2>Our Products</h2>
            <p>Temukan produk terbaik dari toko kami.</p>
        </div>

        <button class="btn-tambah" onclick="bukaPopup()">+ Tambah Produk Baru</button>
    </div>

    <?php if ($pesan): ?>
        <div class="notif <?= $pesan['tipe'] === 'sukses' ? 'notif-sukses' : 'notif-error' ?>" id="notif">
            <?= htmlspecialchars($pesan['teks']) ?>
        </div>
    <?php endif; ?>

    <?php if ($errorDb): ?>
        <div class="notif notif-error">
            Gagal membaca tabel webasas1: <?= htmlspecialchars($errorDb) ?>
        </div>
    <?php endif; ?>

    <?php if (count($produk) === 0): ?>

        <p class="kosong">Belum ada produk. Silakan tambahkan produk baru.</p>

    <?php else: ?>

        <div class="produk-grid">
            <?php foreach ($produk as $p): ?>
                <div class="produk-card">
                    <img src="uploads/<?= rawurlencode($p['foto']) ?>"
                         alt="<?= htmlspecialchars($p['nama_produk']) ?>">

                    <div class="produk-info">
                        <h3><?= htmlspecialchars($p['nama_produk']) ?></h3>
                        <h4 class="harga">Rp <?= number_format($p['harga'], 0, ',', '.') ?></h4>

                        <div class="aksi">
                            <button type="button" class="btn-edit"
                                    data-no="<?= (int) $p['no'] ?>"
                                    data-nama="<?= htmlspecialchars($p['nama_produk'], ENT_QUOTES) ?>"
                                    data-harga="<?= (int) $p['harga'] ?>"
                                    onclick="editProduk(this)">Edit</button>

                            <form action="hapus.php" method="POST"
                                  onsubmit="return confirm('Apakah kamu yakin ingin menghapus produk ini?')">
                                <input type="hidden" name="no" value="<?= (int) $p['no'] ?>">
                                <button type="submit" class="btn-hapus">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</main>

<footer class="footer" id="kontak">
    <div class="footer-isi">

        <div class="footer-kolom footer-brand">
            <h2>SEANLIVSTORE</h2>
            <p>
                SEANLIVSTORE merupakan website digital dimana kamu dapat
                berbelanja setiap kebutuhan pokok kamu sehari-hari dengan
                cukup menggunakan website ini.
            </p>

            <div class="footer-sosmed">
                <a href="https://instagram.com/livwilchu" target="_blank" rel="noopener">Instagram</a>
                <a href="#" target="_blank" rel="noopener">tiktok</a>
                <a href="#" target="_blank" rel="noopener">Facebook</a>
            </div>
        </div>

        <div class="footer-kolom">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#kontak">Contact</a></li>
            </ul>
        </div>

        <div class="footer-kolom">
            <h3>Contact Us</h3>
            <ul>
                <li>Email: SEANLIVSTORE@gmail.com</li>
                <li>Instagram: @livwilchu</li>
                <li>Open: Every Day</li>
            </ul>
        </div>

    </div>

    <div class="footer-bawah">
        <p>&copy; <?= date("Y") ?> SEANLIVSTORE | All Rights Reserved</p>
    </div>
</footer>

<!-- ================= POPUP FORM ================= -->
<div class="popup" id="popupTambah">
    <div class="popup-box">

        <h2 id="judulPopup">Tambah Produk Baru</h2>

        <form action="tambahan.php" method="POST" enctype="multipart/form-data" id="formProduk">

            <input type="hidden" name="no" id="no">

            <label for="nama_produk">Nama Produk</label>
            <input type="text" name="nama_produk" id="nama_produk" required>

            <label for="harga">Harga Produk</label>
            <input type="number" name="harga" id="harga" min="1" required>

            <label for="foto">Upload Gambar Produk</label>
            <input type="file" name="foto" id="foto" accept=".jpg,.jpeg,.png,.webp" required>

            <small id="keteranganFoto">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>

            <button type="submit" class="btn-simpan" id="tombolSimpan">Simpan Produk</button>
        </form>

    </div>
</div>

<script>
    const popup = document.getElementById("popupTambah");

    function bukaPopup() {
        document.getElementById("formProduk").reset();
        document.getElementById("no").value = "";
        document.getElementById("judulPopup").innerText = "Tambah Produk Baru";
        document.getElementById("tombolSimpan").innerText = "Simpan Produk";
        document.getElementById("foto").required = true;
        document.getElementById("keteranganFoto").innerText =
            "Format JPG, PNG, atau WEBP. Maksimal 2 MB.";
        popup.style.display = "flex";
    }

    function editProduk(tombol) {
        document.getElementById("formProduk").reset();
        document.getElementById("no").value = tombol.dataset.no;
        document.getElementById("nama_produk").value = tombol.dataset.nama;
        document.getElementById("harga").value = tombol.dataset.harga;
        document.getElementById("judulPopup").innerText = "Edit Produk";
        document.getElementById("tombolSimpan").innerText = "Simpan Perubahan";
        document.getElementById("foto").required = false;
        document.getElementById("keteranganFoto").innerText =
            "Kosongkan jika tidak ingin mengganti foto.";
        popup.style.display = "flex";
    }

    function tutupPopup() {
        popup.style.display = "none";
    }

    // Klik area gelap di luar kotak = tutup popup
    window.addEventListener("click", function (e) {
        if (e.target === popup) tutupPopup();
    });

    // Tekan ESC = tutup popup
    window.addEventListener("keydown", function (e) {
        if (e.key === "Escape") tutupPopup();
    });

    // Notifikasi hilang otomatis
    const notif = document.getElementById("notif");
    // Hanya notifikasi sukses yang hilang otomatis, error tetap tampil
    if (notif && notif.classList.contains("notif-sukses")) {
        setTimeout(function () { notif.style.display = "none"; }, 4000);
    }
</script>

</body>
</html>