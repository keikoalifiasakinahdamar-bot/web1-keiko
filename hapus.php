<?php
// =====================================================
// hapus.php - proses hapus produk beserta fotonya
// =====================================================

require "koneksi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$no = isset($_POST['no']) ? (int) $_POST['no'] : 0;

if ($no <= 0) {
    setPesan("error", "Nomor produk tidak valid!");
    header("Location: index.php");
    exit;
}

// Cari foto produk
$cari = mysqli_prepare($conn, "SELECT Upload_Gambar_Produk AS foto FROM webasas1 WHERE No = ?");
mysqli_stmt_bind_param($cari, "i", $no);
mysqli_stmt_execute($cari);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($cari));

if (!$data) {
    setPesan("error", "Produk tidak ditemukan!");
    header("Location: index.php");
    exit;
}

// Hapus data
$hapus = mysqli_prepare($conn, "DELETE FROM webasas1 WHERE No = ?");
mysqli_stmt_bind_param($hapus, "i", $no);

if (mysqli_stmt_execute($hapus)) {

    if (!empty($data['foto'])) {
        @unlink($folderUpload . basename($data['foto']));
    }

    setPesan("sukses", "Produk berhasil dihapus!");

} else {

    setPesan("error", "Produk gagal dihapus!");
}

header("Location: index.php");
exit;