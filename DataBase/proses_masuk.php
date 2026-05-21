<?php
// 1. Aktifkan session untuk menyimpan status login user
session_start();

// 2. Hubungkan ke database
include 'koneksi.php';

// 3. Cek apakah form dikirim dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data dari input form Masuk.html
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // 4. Query untuk mencari user berdasarkan email
    $query  = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah email ditemukan
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // 5. Validasi password (mencocokkan input dengan yang ada di database)
        if ($password === $user['password']) {
            
            // Jika cocok, simpan data user ke dalam session
            $_SESSION['login']    = true;
            $_SESSION['id_user']  = $user['id'];
            $_SESSION['nama']     = $user['nama_depan'];
            $_SESSION['role']     = $user['role'];

            // 6. REDIRECTION: Cek role user untuk diarahkan ke halaman yang sesuai
            if ($user['role'] === 'admin') {
                // Jika dia admin, lempar ke admin panel
                echo "<script>
                        alert('Selamat datang Admin PasarNusa!');
                        window.location.href = 'admin.php';
                      </script>";
            } else {
                // Jika dia pembeli/penjual biasa, lempar ke halaman Home
                echo "<script>
                        alert('Login berhasil! Selamat datang, " . $user['nama_depan'] . "');
                        window.location.href = 'Home.php';
                      </script>";
            }
            exit();

        } else {
            // Jika password salah
            echo "<script>
                    alert('Password yang kamu masukkan salah!');
                    window.history.back();
                  </script>";
        }
    } else {
        // Jika email tidak terdaftar
        echo "<script>
                alert('Email belum terdaftar! Silakan daftar terlebih dahulu.');
                window.location.href = 'Daftarrrr.html';
              </script>";
    }
} else {
    header("Location: Masuk.html");
    exit();
}
?>