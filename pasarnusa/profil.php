<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: Masuk.html");
    exit;
}

$id_user = $_SESSION['id'] ?? null;
if (!$id_user) {
    echo "Sesi berakhir. Silakan login kembali.";
    exit;
}

// Ambil data user saat ini
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user'");
$user = mysqli_fetch_assoc($query);

// PROSES UPDATE DATA & UPLOAD FOTO
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profil'])) {
    $nama_depan = mysqli_real_escape_string($koneksi, $_POST['nama_depan']);
    $email      = mysqli_real_escape_string($koneksi, $_POST['email']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    
    // Ambil data foto lama dari database untuk cadangan
    $foto_nama = $user['foto']; 

    // Cek apakah user mengunggah foto baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
        $nama_file = $_FILES['foto']['name'];
        $ukuran_file = $_FILES['foto']['size'];
        $tmp_name = $_FILES['foto']['tmp_name'];
        
        // Ekstensi yang diperbolehkan
        $ekstensi_valid = ['jpg', 'jpeg', 'png'];
        $ekstensi_file = explode('.', $nama_file);
        $ekstensi_file = strtolower(end($ekstensi_file));

        // Validasi format file
        if (in_array($ekstensi_file, $ekstensi_valid)) {
            // Validasi ukuran (maksimal 2MB)
            if ($ukuran_file <= 2000000) {
                // Acak nama file baru agar tidak bentrok di folder uploads
                $foto_nama = uniqid() . '.' . $ekstensi_file;
                
                // Pindahkan file ke folder uploads
                move_uploaded_file($tmp_name, 'uploads/' . $foto_nama);
                
                // Hapus foto lama dari folder jika bukan foto default
                if (!empty($user['foto']) && file_exists('uploads/' . $user['foto'])) {
                    unlink('uploads/' . $user['foto']);
                }
            } else {
                echo "<script>alert('Ukuran foto terlalu besar! Maksimal 2MB.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Format file harus JPG, JPEG, atau PNG!'); window.history.back();</script>";
            exit;
        }
    }

    // Query Update data beserta nama file fotonya
    $update_query = "UPDATE users SET nama_depan = '$nama_depan', email = '$email', alamat = '$alamat', foto = '$foto_nama' WHERE id = '$id_user'";
    
    if (mysqli_query($koneksi, $update_query)) {
        $_SESSION['nama'] = $nama_depan;
        echo "<script>
                alert('Profil dan Foto berhasil diperbarui!');
                window.location.href = 'profil.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Gagal menyimpan! Masalah database: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil — PasarNusa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: var(--cream, #f5f0e8); min-height: 100vh; padding: 60px 20px; font-family: var(--font-body, 'DM Sans', sans-serif);">

    <form action="" method="POST" enctype="multipart/form-data" style="max-width: 480px; margin: 0 auto; background: var(--white, #ffffff); border-radius: var(--radius, 16px); box-shadow: var(--shadow, 0 8px 32px rgba(60,30,10,.12)); overflow: hidden;">
        
        <div style="background: var(--brown-dark, #1a1008); padding: 35px 30px; text-align: center; position: relative;">
            
            <div style="width: 85px; height: 85px; margin: 0 auto 15px; position: relative;">
                <?php if (!empty($user['foto']) && file_exists('uploads/' . $user['foto'])) : ?>
                    <img src="uploads/<?= $user['foto']; ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid var(--cream-dark, #ede6d9); box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                <?php else : ?>
                    <div style="width: 100%; height: 100%; background: var(--orange, #c94b1a); color: var(--white, #ffffff); border-radius: 50%; line-height: 80px; font-size: 2rem; font-weight: 700; font-family: var(--font-display, 'Playfair Display', serif); border: 3px solid var(--cream-dark, #ede6d9); box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <?= strtoupper(substr($user['nama_depan'] ?? 'U', 0, 1)); ?>
                    </div>
                <?php endif; ?>
            </div>

            <h1 style="font-family: var(--font-display, 'Playfair Display', serif); font-size: 1.6rem; color: var(--white, #ffffff); margin-bottom: 4px; font-weight: 900;">
                Kelola Profil Anda
            </h1>
            <p style="font-size: 0.8rem; color: var(--gray-lt, #b0a898); letter-spacing: 1px; text-transform: uppercase; margin: 0;">
                ID Akun: #<?= htmlspecialchars($user['id']); ?>
            </p>
        </div>

        <div style="padding: 30px 30px 40px; display: block;">
            
            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; color: var(--gray, #7a7060); display: block; margin-bottom: 8px; text-transform: uppercase;">
                    Ganti Foto Profil (Avatar)
                </label>
                <input type="file" name="foto" accept="image/*"
                       style="width: 100%; padding: 10px; border: 1px dashed var(--cream-dark, #ede6d9); border-radius: var(--radius-sm, 10px); font-size: 0.85rem; background: #faf8f5;">
                <small style="color: #a09580; font-size: 0.75rem; margin-top: 4px; display: block;">Format: JPG, JPEG, PNG (Maks 2MB)</small>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; color: var(--gray, #7a7060); display: block; margin-bottom: 8px; text-transform: uppercase;">
                    Nama Depan
                </label>
                <input type="text" name="nama_depan" value="<?= htmlspecialchars($user['nama_depan']); ?>" required 
                       style="width: 100%; padding: 12px 16px; border: 1px solid var(--cream-dark, #ede6d9); border-radius: var(--radius-sm, 10px); font-size: 0.95rem; color: var(--brown-dark, #1a1008); background: #faf8f5;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; color: var(--gray, #7a7060); display: block; margin-bottom: 8px; text-transform: uppercase;">
                    Alamat Email
                </label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required 
                       style="width: 100%; padding: 12px 16px; border: 1px solid var(--cream-dark, #ede6d9); border-radius: var(--radius-sm, 10px); font-size: 0.95rem; color: var(--brown-dark, #1a1008); background: #faf8f5;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; color: var(--gray, #7a7060); display: block; margin-bottom: 8px; text-transform: uppercase;">
                    Alamat Pengiriman
                </label>
                <textarea name="alamat" rows="3" required placeholder="Masukkan alamat lengkap..."
                          style="width: 100%; padding: 12px 16px; border: 1px solid var(--cream-dark, #ede6d9); border-radius: var(--radius-sm, 10px); font-size: 0.95rem; color: var(--brown-dark, #1a1008); background: #faf8f5; resize: none;"><?= htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
            </div>

            <div style="margin-bottom: 30px; padding: 10px 14px; background: rgba(200,75,26,0.05); border-radius: var(--radius-sm, 10px); display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; font-weight: 500; color: var(--gray, #7a7060);">Tipe Akun</span>
                <span style="font-size: 0.75rem; font-weight: 700; color: var(--orange, #c94b1a); text-transform: uppercase;">
                    ✨ <?= htmlspecialchars($user['role']); ?>
                </span>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">
                <button type="submit" name="update_profil" 
                        style="width: 100%; padding: 14px; background: var(--orange, #c94b1a); color: var(--white, #ffffff); border: none; border-radius: 50px; font-size: 0.95rem; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(201,75,26,0.2);">
                    Simpan Perubahan
                </button>
                <a href="Home.php" style="display: block; text-align: center; text-decoration: none; padding: 14px; background: transparent; color: var(--brown-dark, #1a1008); border: 1px solid var(--brown-dark, #1a1008); border-radius: 50px; font-size: 0.9rem; font-weight: 700;">
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </form>

</body>
</html>