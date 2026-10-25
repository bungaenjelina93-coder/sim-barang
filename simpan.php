
<?php
session_start();

// Proteksi halaman
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: login.php");
    exit;
}

// Menghubungkan database dan class Barang
require_once __DIR__ . "/koneksi.php";
require_once __DIR__ . "/Barang.php";

// Memastikan formulir menggunakan POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

// Mengambil data formulir
$kode = strtoupper(trim($_POST["kode"] ?? ""));
$nama = trim($_POST["nama"] ?? "");
$kategori = trim($_POST["kategori"] ?? "");
$jumlahInput = trim($_POST["jumlah"] ?? "");
$hargaInput = trim($_POST["harga"] ?? "");
$kondisi = trim($_POST["kondisi"] ?? "");

$errors = [];

// Validasi kode barang
if (!preg_match("/^BRG-\d+$/", $kode)) {
    $errors[] = "Kode barang harus menggunakan format BRG-001.";
}

// Validasi nama
if ($nama === "") {
    $errors[] = "Nama barang wajib diisi.";
}

// Validasi kategori
if (!in_array($kategori, ["Elektronik", "Furniture", "Peralatan"], true)) {
    $errors[] = "Kategori tidak valid.";
}

// Validasi jumlah
$jumlah = filter_var($jumlahInput, FILTER_VALIDATE_INT);

if ($jumlah === false || $jumlah <= 0) {
    $errors[] = "Jumlah barang harus lebih dari 0.";
}

// Validasi harga
if (!is_numeric($hargaInput) || (float)$hargaInput < 0) {
    $errors[] = "Harga satuan tidak valid.";
} else {
    $harga = (float)$hargaInput;
}

// Validasi kondisi
if (!in_array($kondisi, ["Baik", "Rusak"], true)) {
    $errors[] = "Kondisi barang tidak valid.";
}

$berhasil = false;

if (empty($errors)) {
    try {
        // Memeriksa kode barang yang sudah terdaftar
        $cek = $pdo->prepare(
            "SELECT COUNT(*) FROM barang WHERE kode_barang = ?"
        );
        $cek->execute([$kode]);

        if ($cek->fetchColumn() > 0) {
            $errors[] = "Kode barang sudah terdaftar.";
        } else {
            // Mengambil ID kategori
            $queryKategori = $pdo->prepare(
                "SELECT id_kategori FROM kategori WHERE nama_kategori = ?"
            );
            $queryKategori->execute([$kategori]);

            $dataKategori = $queryKategori->fetch();

            if (!$dataKategori) {
                $errors[] = "Kategori tidak ditemukan.";
            } else {
                // Menyimpan barang ke database
                $insert = $pdo->prepare(
                    "INSERT INTO barang
                    (kode_barang, nama_barang, jumlah, harga_satuan, kondisi, id_kategori)
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                $insert->execute([
                    $kode,
                    $nama,
                    $jumlah,
                    $harga,
                    $kondisi,
                    $dataKategori["id_kategori"]
                ]);

                // Membuat objek Barang
                $barang = new Barang($kode, $nama, $jumlah);

                // Menghitung total nilai aset
                $totalHarga = $jumlah * $harga;

                $berhasil = true;
            }
        }

    } catch (PDOException $e) {
        $errors[] = "Gagal menyimpan data barang ke database.";
    }
}

// Mengamankan teks sebelum ditampilkan
function aman($data) {
    return htmlspecialchars((string)$data, ENT_QUOTES, "UTF-8");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Input Barang - SIM-Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>SIM-Barang</h1>
    <p>Hasil Pengolahan Data Barang</p>
</header>

<main>
    <section>

        <?php if ($berhasil): ?>

            <h2>Data Barang Berhasil Disimpan</h2>

            <p>Data barang berhasil diterima dan disimpan ke database MySQL.</p>

            <table>
                <thead>
                    <tr>
                        <th>Keterangan</th>
                        <th>Data Barang</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Kode Barang</td>
                        <td><?= aman($kode) ?></td>
                    </tr>
                    <tr>
                        <td>Nama Barang</td>
                        <td><?= aman($nama) ?></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td><?= aman($kategori) ?></td>
                    </tr>
                    <tr>
                        <td>Jumlah Barang</td>
                        <td><?= aman($jumlah) ?></td>
                    </tr>
                    <tr>
                        <td>Harga Satuan</td>
                        <td>Rp <?= number_format($harga, 0, ",", ".") ?></td>
                    </tr>
                    <tr>
                        <td>Kondisi Barang</td>
                        <td><?= aman($kondisi) ?></td>
                    </tr>
                    <tr>
                        <th>Total Estimasi Nilai Aset</th>
                        <th>Rp <?= number_format($totalHarga, 0, ",", ".") ?></th>
                    </tr>
                </tbody>
            </table>

            <p>
                <strong>Informasi dari Class Barang:</strong>
                <?= aman($barang->tampilkanInfo()) ?>
            </p>

        <?php else: ?>

            <h2>Data Barang Gagal Diproses</h2>

            <?php foreach ($errors as $error): ?>
                <p style="color: red;">
                    <?= aman($error) ?>
                </p>
            <?php endforeach; ?>

        <?php endif; ?>

        <p>
            <a href="index.php#inventaris">Kembali ke Data Inventaris</a>
        </p>

    </section>
</main>

<footer>
    <p>&copy; 2026 SIM-Barang. Sistem Inventaris Barang Jurusan.</p>
</footer>

</body>
</html>
