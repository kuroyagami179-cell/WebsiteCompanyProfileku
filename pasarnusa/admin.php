<?php
// 1. KODE PROTEKSI (Wajib ditaruh paling atas agar orang lain tidak bisa mengintip tanpa login)
session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = 'Masuk.html';
          </script>";
    exit();
}

// 2. HUBUNGKAN KE DATABASE
include 'koneksi.php';

// Mengambil total rangkuman data untuk Dashboard
$total_users = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM users"));
$total_orders = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM orders"));
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_bayar) AS total FROM orders"))['total'] ?? 0;

// Mengambil data pengguna
$query_users = mysqli_query($koneksi, "SELECT * FROM users ORDER BY created_at DESC");

// Mengambil data pesanan beserta itemnya
$query_orders = mysqli_query($koneksi, "SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard — PasarNusa</title>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet" />
  <style>
    :root {
      --primary: #5c6bc0;
      --bg: #f4f6f9;
      --dark: #2c3e50;
      --white: #ffffff;
    }
    body { font-family: 'DM Sans', sans-serif; background: var(--bg); margin: 0; color: var(--dark); }
    
    /* STYLING SIDEBAR */
    .sidebar { width: 240px; height: 100vh; background: var(--dark); position: fixed; color: var(--white); padding: 20px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between; }
    .sidebar h2 { margin-bottom: 30px; font-size: 1.5rem; text-align: center; margin-top: 0; }
    .sidebar h2 span { color: #ffb74d; }
    .sidebar a { color: #b0bec5; text-decoration: none; display: block; padding: 12px; border-radius: 6px; margin-bottom: 8px; }
    .sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.1); color: var(--white); }
    
    /* Menu logout ditaruh di paling bawah sidebar agar rapi */
    .btn-logout { color: #ff8a80 !important; font-weight: bold; margin-top: auto; border: 1px solid rgba(255,138,128,0.3); text-align: center; }
    .btn-logout:hover { background: rgba(255,138,128,0.1) !important; }

    .main-content { margin-left: 240px; padding: 40px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 40px; }
    .stat-card { background: var(--white); padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .stat-card p { margin: 0; color: #7f8c8d; font-size: 0.9rem; }
    .stat-card h3 { margin: 10px 0 0 0; font-size: 1.8rem; color: var(--dark); }
    .data-section { background: var(--white); padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
    .data-section h2 { margin-top: 0; margin-bottom: 20px; font-size: 1.2rem; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th, td { padding: 12px 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
    th { background: #f8f9fa; color: #7f8c8d; }
    .badge { padding: 4px 8px; border-radius: 50px; font-size: 0.75rem; font-weight: bold; }
    .badge-user { background: #e3f2fd; color: #1e88e5; }
    .badge-seller { background: #e8f5e9; color: #43a047; }
    .badge-success { background: #e8f5e9; color: #43a047; }
  </style>
</head>
<body>

  <div class="sidebar">
    <div>
      <h2>Pasar<span>Nusa</span></h2>
      <a href="#" class="active">💻 Dashboard</a>
      <a href="#users">👥 Data Pengguna</a>
      <a href="#orders">📦 Transaksi Masuk</a>
    </div>
    
    <a href="logout.php" class="btn-logout">Keluar (Logout)</a>
  </div>

  <div class="main-content">
    <div class="header">
      <h1>Selamat Datang, Admin</h1>
      <p><?php echo date('d M Y'); ?></p>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <p>Total Pengguna</p>
        <h3><?php echo $total_users; ?> Akun</h3>
      </div>
      <div class="stat-card">
        <p>Pesanan Masuk</p>
        <h3><?php echo $total_orders; ?> Transaksi</h3>
      </div>
      <div class="stat-card">
        <p>Total Pendapatan</p>
        <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
      </div>
    </div>

    <div class="data-section" id="users">
      <h2>👥 Data Pengguna Terdaftar</h2>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Role</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($query_users)) { ?>
          <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['nama_depan'] . ' ' . $row['nama_belakang']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['no_hp']); ?></td>
            <td>
              <span class="badge <?php echo $row['role'] == 'penjual' ? 'badge-seller' : 'badge-user'; ?>">
                <?php echo ucfirst($row['role']); ?>
              </span>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

    <div class="data-section" id="orders">
      <h2>📦 Riwayat Transaksi & Pengiriman</h2>
      <table>
        <thead>
          <tr>
            <th>ID Order</th>
            <th>Penerima</th>
            <th>Alamat Tujuan</th>
            <th>Kurir</th>
            <th>Pembayaran</th>
            <th>Total Bayar</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php while($order = mysqli_fetch_assoc($query_orders)) { ?>
          <tr>
            <td>#<?php echo $order['id']; ?></td>
            <td><strong><?php echo htmlspecialchars($order['nama_penerima']); ?></strong><br><small><?php echo $order['no_hp']; ?></small></td>
            <td><?php echo htmlspecialchars($order['alamat'] . ', ' . $order['kota'] . ', ' . $order['provinsi']); ?></td>
            <td><?php echo strtoupper($order['kurir']); ?></td>
            <td><?php echo strtoupper($order['metode_pembayaran']); ?></td>
            <td>Rp <?php echo number_format($order['total_bayar'], 0, ',', '.'); ?></td>
            <td><span class="badge badge-success"><?php echo ucfirst($order['status']); ?></span></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>