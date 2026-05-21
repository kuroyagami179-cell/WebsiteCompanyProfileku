<?php
include 'koneksi.php'; // Pastikan koneksi.php sudah ada (untuk session_start & database)

// Cek apakah user sudah login
if (!isset($_SESSION['login'])) {
    header("Location: Masuk.html");
    exit;
}

// Ambil data user dari database (asumsi tabel kamu bernama 'users')
$id_user = $_SESSION['id']; // Pastikan kamu menyimpan ID saat login
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Profil Saya</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container { max-width: 600px; margin: 50px auto; padding: 30px; background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow); }
        .data-row { margin-bottom: 20px; }
        .label { font-weight: bold; color: var(--gray); }
        .value { font-size: 1.2rem; color: var(--brown-dark); }
    </style>
</head>
<body>
    <div class="profile-container">
        <h1>Biodata Saya</h1>
        <hr style="margin: 20px 0;">
        <div class="data-row">
            <p class="label">Nama Lengkap</p>
            <p class="value"><?php echo $user['nama'] . " " . $user['nama_belakang']; ?></p>
        </div>
        <div class="data-row">
            <p class="label">Alamat Email</p>
            <p class="value"><?php echo $user['email']; ?></p>
        </div>
        <div class="data-row">
            <p class="label">Status Akun</p>
            <p class="value"><?php echo ucfirst($user['role']); ?></p>
        </div>
        <a href="Home.php" class="btn-primary">Kembali ke Beranda</a>
    </div>
</body>
</html>