<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Ambil data pengiriman dari form Checkout.html
    $nama_penerima      = mysqli_real_escape_string($koneksi, $_POST['nama_penerima']);
    $no_hp              = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat             = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $provinsi           = mysqli_real_escape_string($koneksi, $_POST['provinsi']);
    $kota               = mysqli_real_escape_string($koneksi, $_POST['kota']);
    $kode_pos           = mysqli_real_escape_string($koneksi, $_POST['kode_pos']);
    $catatan            = mysqli_real_escape_string($koneksi, $_POST['catatan']);
    
    // Ambil data kurir dan metode pembayaran (jika tidak diisi, set default)
    $kurir              = isset($_POST['kurir']) ? mysqli_real_escape_string($koneksi, $_POST['kurir']) : 'jne';
    $metode_pembayaran  = isset($_POST['metode_pembayaran']) ? mysqli_real_escape_string($koneksi, $_POST['metode_pembayaran']) : 'transfer_bank';

    // 2. Karena keranjang belanja di Checkout.html menggunakan data statis/hardcoded,
    // kita set nominal bayarnya sesuai dengan angka total yang tertera di keranjang belanjamu.
    $subtotal    = 551000;
    $ongkir      = 18000;
    $diskon      = 55100;
    $total_bayar = 513900;
    $status      = 'pending';

    // 3. Query SQL untuk memasukkan data ke tabel orders
    $query = "INSERT INTO orders (nama_penerima, no_hp, alamat, provinsi, kota, kode_pos, catatan, kurir, metode_pembayaran, subtotal, ongkir, diskon, total_bayar, status) 
              VALUES ('$nama_penerima', '$no_hp', '$alamat', '$provinsi', '$kota', '$kode_pos', '$catatan', '$kurir', '$metode_pembayaran', '$subtotal', '$ongkir', '$diskon', '$total_bayar', '$status')";

    if (mysqli_query($koneksi, $query)) {
        // Ambil ID order barusan untuk memasukkan detail produknya (opsional)
        $order_id = mysqli_insert_id($koneksi);

        // Memasukkan salah satu produk contoh dari item keranjang belanja kamu
        mysqli_query($koneksi, "INSERT INTO order_items (order_id, nama_produk, qty, harga) VALUES ('$order_id', 'Kain Batik Tulis Bakaran Motif Klasik', 1, 350000)");

        echo "<script>
                alert('Pesananmu berhasil dibuat! Admin akan segera memproses transaksi.');
                window.location.href = 'Home.php';
              </script>";
    } else {
        echo "Gagal memproses checkout: " . mysqli_error($koneksi);
    }
} else {
    header("Location: Checkout.html");
    exit();
}
?>