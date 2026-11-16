<?php
// cek.php - alat diagnosa sementara (hapus setelah selesai)
require "koneksi.php";

echo "<h2>Cek Koneksi &amp; Tabel</h2>";
echo "<p>Database aktif: <b>" . htmlspecialchars($db) . "</b></p>";

// 1. Daftar tabel di database
echo "<h3>Tabel di database:</h3><ul>";
$t = mysqli_query($conn, "SHOW TABLES");
while ($r = mysqli_fetch_row($t)) {
    echo "<li>" . htmlspecialchars($r[0]) . "</li>";
}
echo "</ul>";

// 2. Kolom tabel webasas1
echo "<h3>Kolom tabel webasas1:</h3>";
$k = mysqli_query($conn, "SHOW COLUMNS FROM webasas1");

if (!$k) {
    echo "<p style='color:red'>ERROR: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
} else {
    echo "<table border='1' cellpadding='6'><tr><th>Nama kolom</th><th>Tipe</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($r = mysqli_fetch_assoc($k)) {
        echo "<tr><td>" . htmlspecialchars($r['Field']) . "</td><td>" . htmlspecialchars($r['Type']) .
             "</td><td>" . $r['Null'] . "</td><td>" . $r['Key'] . "</td><td>" .
             htmlspecialchars((string) $r['Default']) . "</td><td>" . $r['Extra'] . "</td></tr>";
    }
    echo "</table>";
}

// 3. Uji INSERT lalu langsung dihapus lagi
echo "<h3>Uji INSERT:</h3>";
$ok = mysqli_query($conn, "INSERT INTO webasas1 (nama_produk, harga, foto) VALUES ('tes', 1000, 'tes.jpg')");

if ($ok) {
    echo "<p style='color:green'>INSERT BERHASIL (data uji langsung dihapus).</p>";
    mysqli_query($conn, "DELETE FROM webasas1 WHERE nama_produk = 'tes' AND foto = 'tes.jpg'");
} else {
    echo "<p style='color:red'>INSERT GAGAL: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
}

echo "<p>PHP versi: " . PHP_VERSION . " | post_max_size: " . ini_get('post_max_size') .
     " | upload_max_filesize: " . ini_get('upload_max_filesize') . "</p>";
