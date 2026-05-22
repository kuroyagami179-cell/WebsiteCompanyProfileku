<?php
// 1. Hubungkan ke database menggunakan file koneksi yang sudah dibuat sebelumnya
include 'koneksi.php';

// 2. Cek apakah form dikirim menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Ambil data dari tag <input> berdasarkan atribut 'name' di file Daftarrrr.html
    // Menggunakan mysqli_real_escape_string untuk keamanan dari SQL Injection dasar
    $nama_depan    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nama_belakang = mysqli_real_escape_string($koneksi, $_POST['nama_belakang']);
    $email         = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp         = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $password      = $_POST['password'];
    $konfirmasi    = $_POST['konfirmasi_password'];
    $role          = mysqli_real_escape_string($koneksi, $_POST['role']);

    // 4. Validasi: Cek apakah password dan konfirmasi password sudah cocok
    if ($password !== $konfirmasi) {
        echo "<script>
                alert('Konfirmasi password tidak cocok! Silakan ulangi.');
                window.history.back();
              </script>";
        exit();
    }

    // 5. Validasi: Cek apakah email sudah pernah terdaftar di database
    $cek_email = mysqli_query($koneksi, "SELECT email FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>
                alert('Email ini sudah terdaftar! Gunakan email lain.');
                window.history.back();
              </script>";
        exit();
    }

    // 6. Jalankan Query untuk memasukkan data ke tabel 'users'
    // (Catatan: Password langsung disimpan. Jika untuk produksi resmi sekolah/ujian, disarankan pakai password_hash)
    $query = "INSERT INTO users (nama_depan, nama_belakang, email, no_hp, password, role) 
              VALUES ('$nama_depan', '$nama_belakang', '$email', '$no_hp', '$password', '$role')";

    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, munculkan pesan sukses dan arahkan ke halaman Masuk (Login)
        echo "<script>
                alert('Pendaftaran akun PasarNusa berhasil! Silakan masuk.');
                window.location.href = 'Masuk.html';
              </script>";
    } else {
        // Jika gagal, tampilkan pesan error dari database
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }
} else {
    // Jika file ini diakses langsung tanpa lewat form, kembalikan ke halaman daftar
    header("Location: Daftarrrr.html");
    exit();
}
?>