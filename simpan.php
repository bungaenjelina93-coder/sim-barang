
<?php

require_once __DIR__ . "/Barang.php";

// Memastikan data dikirim menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

// Mengambil data dari formulir menggunakan $_POST
$kode = trim($_POST["kode"] ?? "");
$nama = trim($_POST["nama"] ?? "");
$kategori = trim($_POST["kategori"] ?? "");
$jumlahInput = trim($_POST["jumlah"] ?? "");
$hargaInput = trim($_POST["harga"] ?? "");
$kondisi = trim($_POST["kondisi"] ?? "");

// Menyiapkan validasi
$errors = [];

// Validasi kode barang
if (!preg_match("/^BRG-\d+$/", $kode)) {
    $errors[] = "Kode barang harus menggunakan format BRG-001.";
}

// Validasi nama barang
if ($nama === "") {
    $errors[] = "Nama barang wajib diisi.";
}

// Validasi kategori
if (!in_array($kategori, ["Elektronik", "Furniture", "Peralatan"], true)) {
    $errors[] = "Kategori barang tidak valid.";
}

// Validasi jumlah barang
$jumlah = filter_var($jumlahInput, FILTER_VALIDATE_INT);

if ($jumlah === false || $jumlah <= 0) {
    $errors[] = "Jumlah barang harus berupa angka positif.";
}

// Validasi harga satuan
if (!is_numeric($hargaInput) || (float)$hargaInput < 0) {
    $errors[] = "Harga satuan tidak valid.";
} else {
    $harga = (float)$hargaInput;
}

// Validasi kondisi barang
if (!in_array($kondisi, ["Baik", "Rusak"], true)) {
    $errors[] = "Kondisi barang tidak valid.";
}

// Menghitung total estimasi nilai aset
$berhasil = empty($errors);

if ($berhasil) {
    $totalHarga = $jumlah * $harga;

    // Membuat objek dari class Barang
    $barang = new Barang($kode, $nama, $jumlah);
}

// Fungsi mengamankan teks sebelum ditampilkan
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

                <h2>Data Barang Berhasil Diproses</h2>

                <p>Berikut ringkasan data barang yang telah dikirim:</p>

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

                <p>Data barang berhasil diterima dan dihitung menggunakan PHP.</p>

                <p>
                    <strong>Informasi dari Class Barang:</strong>
                    <?= aman($barang->tampilkanInfo()) ?>
                </p>

            <?php else: ?>

                <h2>Data Tidak Valid</h2>

                <?php foreach ($errors as $error): ?>
                    <p><?= aman($error) ?></p>
                <?php endforeach; ?>

            <?php endif; ?>

            <p>
                <a href="index.php">Kembali ke Formulir SIM-Barang</a>
            </p>

        </section>
    </main>

    <footer>
        <p>&copy; 2026 SIM-Barang. Sistem Inventaris Barang Jurusan.</p>
    </footer>

</body>
</html>
