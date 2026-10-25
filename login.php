
<?php
session_start();

// Jika pengguna sudah login, arahkan ke halaman utama
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: index.php");
    exit;
}

// Data pengguna
$users = [
    "admin" => [
        "password" => "bunga123",
        "nama" => "Administrator"
    ]
];

$error = "";

// Memproses login
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (
        isset($users[$username]) &&
        $password === $users[$username]["password"]
    ) {
        session_regenerate_id(true);

        $_SESSION["login"] = true;
        $_SESSION["username"] = $username;
        $_SESSION["nama"] = $users[$username]["nama"];

        header("Location: index.php");
        exit;

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIM-Barang</title>

    <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">

    <!-- HEADER -->
    <header class="login-header">
        <h1>SIM-Barang</h1>
        <p>Sistem Pengelolaan Inventaris Barang Jurusan</p>
    </header>

    <!-- FORM LOGIN -->
    <main class="login-main">

        <section class="login-card">

            <h2>Login</h2>

            <?php if ($error !== ""): ?>
                <p class="login-error">
                    <?= htmlspecialchars($error, ENT_QUOTES, "UTF-8") ?>
                </p>
            <?php endif; ?>

            <form action="login.php" method="POST" class="login-form">

                <div class="form-group">
                    <label for="username">Username</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit">Masuk</button>

            </form>

        </section>

    </main>

    <!-- FOOTER -->
    <footer class="login-footer">
        <p>&copy; 2026 SIM-Barang. Sistem Inventaris Barang Jurusan.</p>
    </footer>

</body>
</html>
