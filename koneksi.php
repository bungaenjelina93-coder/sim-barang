
<?php

// Konfigurasi database
$host = "localhost";
$dbname = "sim_barang";
$username = "root";
$password = "";

try {

    // Membuat koneksi menggunakan PDO
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Mengatur mode error PDO
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Mengatur hasil query menjadi array asosiatif
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch (PDOException $e) {

    die("Koneksi database gagal: " . 
        htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8"));

}

?>
