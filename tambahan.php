<?php
// =====================================================
// tambahan.php (v5) - proses TAMBAH dan EDIT produk
// no kosong = INSERT, no terisi = UPDATE
// =====================================================

require "koneksi.php";

function kembali($tipe, $teks)
{
    setPesan($tipe, $teks);
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// File melebihi post_max_size -> seluruh data kosong
if (empty($_POST) && empty($_FILES) && ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
    kembali("error", "Ukuran file terlalu besar! Batas server: " . ini_get('post_max_size'));
}

$no    = isset($_POST['no']) ? (int) $_POST['no'] : 0;
$nama  = trim($_POST['nama_produk'] ?? "");
$harga = (int) preg_replace('/\D/', '', $_POST['harga'] ?? "");

if ($nama === "") {
    kembali("error", "Nama produk belum diisi!");
}

if ($harga <= 0) {
    kembali("error", "Harga produk belum diisi atau tidak valid!");
}

$adaFoto = isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE;

if ($no === 0 && !$adaFoto) {
    kembali("error", "Foto produk harus dipilih!");
}

$fotoBaru = null;

// ---------------- Upload foto ----------------
if ($adaFoto) {

    if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        kembali("error", "Upload foto gagal! Kode error: " . $_FILES['foto']['error']
            . " (batas server: " . ini_get('upload_max_filesize') . ")");
    }

    $ekstensi = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!in_array($ekstensi, ['jpg', 'jpeg', 'png', 'webp'])) {
        kembali("error", "Format foto harus JPG, PNG, atau WEBP!");
    }

    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        kembali("error", "Ukuran foto maksimal 2 MB!");
    }

    $info = getimagesize($_FILES['foto']['tmp_name']);

    if (!$info || !in_array($info['mime'], ['image/jpeg', 'image/png', 'image/webp'])) {
        kembali("error", "File bukan gambar yang valid!");
    }

    $fotoBaru = bin2hex(random_bytes(12)) . "." . $ekstensi;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $folderUpload . $fotoBaru)) {
        kembali("error", "Foto gagal disimpan ke folder uploads!");
    }
}

$namaSql = mysqli_real_escape_string($conn, $nama);

// ---------------- INSERT ----------------
if ($no === 0) {

    $fotoSql = mysqli_real_escape_string($conn, $fotoBaru);

    $ok = mysqli_query(
        $conn,
        "INSERT INTO webasas1 (Nama_Produk, Harga_Produk, Upload_Gambar_Produk)
         VALUES ('$namaSql', $harga, '$fotoSql')"
    );

    if ($ok) {
        kembali("sukses", "Produk berhasil ditambahkan!");
    }

    $alasan = mysqli_error($conn);
    @unlink($folderUpload . $fotoBaru);
    kembali("error", "Gagal INSERT (v5): " . $alasan);
}

// ---------------- UPDATE ----------------
$cari = mysqli_query($conn, "SELECT Upload_Gambar_Produk AS foto FROM webasas1 WHERE No = $no");

if (!$cari) {
    $alasan = mysqli_error($conn);
    if ($fotoBaru) {
        @unlink($folderUpload . $fotoBaru);
    }
    kembali("error", "Gagal membaca produk (v5): " . $alasan);
}

$data = mysqli_fetch_assoc($cari);

if (!$data) {
    if ($fotoBaru) {
        @unlink($folderUpload . $fotoBaru);
    }
    kembali("error", "Produk tidak ditemukan!");
}

$fotoLama  = $data['foto'];
$fotoAkhir = $fotoBaru ?? $fotoLama;
$fotoSql   = mysqli_real_escape_string($conn, $fotoAkhir);

$ok = mysqli_query(
    $conn,
    "UPDATE webasas1
     SET Nama_Produk = '$namaSql', Harga_Produk = $harga, Upload_Gambar_Produk = '$fotoSql'
     WHERE No = $no"
);

if ($ok) {

    if ($fotoBaru && !empty($fotoLama)) {
        @unlink($folderUpload . basename($fotoLama));
    }

    kembali("sukses", "Produk berhasil diperbarui!");
}

$alasan = mysqli_error($conn);

if ($fotoBaru) {
    @unlink($folderUpload . $fotoBaru);
}

kembali("error", "Gagal UPDATE (v5): " . $alasan);