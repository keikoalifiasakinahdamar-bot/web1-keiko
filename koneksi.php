<?php
// =====================================================
// koneksi.php - koneksi database + setup otomatis
// =====================================================

session_start();

$host = "localhost";
$user = "root";
$pass = "";
$db   = "databaseweb1"; // nama database
$tabel = "webasas1";    // nama tabel (dipakai di file lain: webasas1)

mysqli_report(MYSQLI_REPORT_OFF);

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Buat database dan tabel otomatis jika belum ada
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$db`");
mysqli_select_db($conn, $db);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS webasas1 (
    No INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    Nama_Produk TEXT NOT NULL,
    Harga_Produk INT(11) NOT NULL,
    Upload_Gambar_Produk VARCHAR(55) NOT NULL
)");

// Perbaiki kolom No agar AUTO_INCREMENT + PRIMARY KEY (jika belum)
$cekNo = mysqli_query($conn, "SHOW COLUMNS FROM webasas1 LIKE 'No'");

if ($cekNo && $kolomNo = mysqli_fetch_assoc($cekNo)) {
    if (stripos($kolomNo['Extra'], 'auto_increment') === false) {
        $tambahKey = ($kolomNo['Key'] === 'PRI') ? "" : ", ADD PRIMARY KEY (No)";
        mysqli_query($conn, "ALTER TABLE webasas1 MODIFY No INT(11) NOT NULL AUTO_INCREMENT" . $tambahKey);
    }
}

// Buat folder uploads otomatis jika belum ada
$folderUpload = __DIR__ . "/uploads/";

if (!is_dir($folderUpload)) {
    mkdir($folderUpload, 0777, true);
}

// Fungsi pesan notifikasi (flash message)
function setPesan($tipe, $teks)
{
    $_SESSION['pesan'] = ['tipe' => $tipe, 'teks' => $teks];
}