<?php
// 1. Mengaktifkan session PHP di baris paling pertama agar bisa membaca data user yang sedang login
session_start();

// Tambahan: Hubungkan ke database untuk mengambil data foto profil terbaru
include 'koneksi.php';

$id_user = $_SESSION['id'] ?? null;
$user_home = null;

if ($id_user) {
    // Ambil data user lengkap (termasuk kolom foto) berdasarkan id session
    $query_home = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$id_user'");
    $user_home = mysqli_fetch_assoc($query_home);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PasarNusa — Marketplace Terpercaya Indonesia</title>
  <link rel="stylesheet" href="style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  
  <style>
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }
    .profile-btn {
      background: var(--brown);
      color: var(--white);
      padding: 6px 14px; /* Sedikit disesuaikan biar pas dengan tinggi lingkaran foto */
      border-radius: var(--radius-sm);
      text-decoration: none;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px; /* Jarak antara foto profil dan teks nama */
      cursor: pointer;
    }
    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: var(--white);
      min-width: 160px;
      box-shadow: var(--shadow-sm);
      border-radius: var(--radius-sm);
      z-index: 1000;
      margin-top: 8px;
      overflow: hidden;
    }
    .dropdown-content a {
      color: var(--brown-dark);
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      font-size: 0.9rem;
      text-align: left;
    }
    .dropdown-content a:hover {
      background-color: var(--cream-dark);
    }
    .profile-dropdown:hover .dropdown-content {
      display: block;
    }
  </style>
</head>
<body>

  <nav class="navbar">
    <div class="nav-container">
      <a href="Home.php" class="logo">Pasar<span>Nusa</span></a>
      <ul class="nav-links">
        <li><a href="#jelajahi">Jelajahi</a></li>
        <li><a href="#kategori">Kategori</a></li>
        <li><a href="#blog">Blog</a></li>
      </ul>
      <div class="nav-actions">
  <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true) : ?>
    <div class="profile-dropdown">
      <label for="toggle-profile" class="profile-btn">
        
        <?php if (!empty($user_home['foto']) && file_exists('uploads/' . $user_home['foto'])) : ?>
            <img src="uploads/<?= $user_home['foto']; ?>" style="width: 26px; height: 26px; object-fit: cover; border-radius: 50%; border: 1.5px solid var(--white);">
        <?php else : ?>
            <div style="width: 26px; height: 26px; background: var(--orange, #c94b1a); color: var(--white, #ffffff); border-radius: 50%; text-align: center; line-height: 26px; font-size: 0.8rem; font-weight: 700; font-family: 'Playfair Display', serif;">
                <?= strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)); ?>
            </div>
        <?php endif; ?>

        <?php echo htmlspecialchars($_SESSION['nama']); ?> ▼
      </label>
      <input type="checkbox" id="toggle-profile" style="display:none;">
      
      <div class="dropdown-content">
        <?php if ($_SESSION['role'] === 'admin') : ?>
          <a href="admin.php">💻 Admin Panel</a>
        <?php endif; ?>
        
        <a href="profil.php">👤 Profil Saya</a>
        <a href="#">📦 Pesanan Saya</a>
        <a href="logout.php" style="color: var(--orange); font-weight: bold;">🚪 Keluar</a>
      </div>
    </div>
  <?php else : ?>
    <a href="Masuk.php" class="btn-masuk">Masuk</a>
    <a href="Daftarrrr.php" class="btn-daftar">Daftar</a>
  <?php endif; ?>
  <a href="Checkout.html" class="btn-cart">🛒 <span class="cart-badge">3</span></a>
</div>
  </nav>

  <div class="ticker-wrap">
    <div class="ticker">
      <span>✦ BAYAR DI TEMPAT TERSEDIA</span>
      <span>✦ GARANSI PENGEMBALIAN 7 HARI</span>
      <span>✦ SUPPORT 24 JAM</span>
      <span>✦ PENGIRIMAN SELURUH INDONESIA</span>
      <span>✦ PRODUK UMKM TERVERIFIKASI</span>
      <span>✦ BAYAR DI TEMPAT TERSEDIA</span>
      <span>✦ GARANSI PENGEMBALIAN 7 HARI</span>
      <span>✦ SUPPORT 24 JAM</span>
      <span>✦ PENGIRIMAN SELURUH INDONESIA</span>
      <span>✦ PRODUK UMKM TERVERIFIKASI</span>
    </div>
  </div>

  <section class="hero" id="jelajahi">
    <div class="hero-content">
      <p class="hero-label">— MARKETPLACE TERPERCAYA INDONESIA</p>
      <h1 class="hero-title">
        Temukan<br>Produk<br><em>Terbaik</em><br>Nusantara
      </h1>
      <p class="hero-desc">Ribuan produk UMKM lokal pilihan — dari kerajinan tangan, kuliner, fashion, hingga produk kecantikan alami. Belanja mudah, aman, dan mendukung pengrajin lokal.</p>
      <div class="hero-btns">
        <a href="#produk" class="btn-primary">Mulai Belanja →</a>
        <a href="#penjual" class="btn-ghost">Jual Produkmu →</a>
      </div>
    </div>
    <div class="hero-cards">
      <div class="hero-card anim-float-1">
        <span class="badge badge-terlaris">TERLARIS</span>
        <div class="card-img">🏺</div>
        <div class="card-info">
          <p class="card-name">Gerabah Lombok</p>
          <p class="card-seller">Pengrajin Sasak</p>
          <p class="card-price">Rp 185.000</p>
        </div>
        <a href="Checkout.html" class="btn-add-cart">+ Keranjang</a>
      </div>
      <div class="hero-card anim-float-2">
        <div class="card-img">👗</div>
        <div class="card-info">
          <p class="card-name">Batik Tulis Solo</p>
          <p class="card-seller">Batik Laras</p>
          <p class="card-price">Rp 420.000</p>
        </div>
        <a href="Checkout.html" class="btn-add-cart">+ Keranjang</a>
      </div>
      <div class="hero-card anim-float-3">
        <span class="badge badge-baru">BARU</span>
        <div class="card-img">🍃</div>
        <div class="card-info">
          <p class="card-name">Teh Herbal Jawa</p>
          <p class="card-seller">Herbal Nusantara</p>
          <p class="card-price">Rp 95.000</p>
        </div>
        <a href="Checkout.html" class="btn-add-cart">+ Keranjang</a>
      </div>
    </div>
  </section>

  <section class="kategori-section" id="kategori">
    <div class="inner">
      <div class="section-header">
        <h2 class="section-title">Jelajahi<br><em>Kategori</em></h2>
        <a href="#produk" class="lihat-semua">LIHAT SEMUA →</a>
      </div>
      <div class="kategori-grid">
        <a href="#produk" class="kat-card">
          <div class="kat-icon">🏺</div>
          <p class="kat-name">Kerajinan Tangan</p>
          <p class="kat-count">3.200+ produk</p>
        </a>
        <a href="#produk" class="kat-card">
          <div class="kat-icon">👗</div>
          <p class="kat-name">Fashion &amp; Tekstil</p>
          <p class="kat-count">8.400+ produk</p>
        </a>
        <a href="#produk" class="kat-card">
          <div class="kat-icon">🍜</div>
          <p class="kat-name">Kuliner &amp; Camilan</p>
          <p class="kat-count">5.100+ produk</p>
        </a>
        <a href="#produk" class="kat-card">
          <div class="kat-icon">🌿</div>
          <p class="kat-name">Kecantikan Alami</p>
          <p class="kat-count">2.800+ produk</p>
        </a>
      </div>
    </div>
  </section>

  <section class="produk-section" id="produk">
    <div class="inner">
      <div class="section-header">
        <h2 class="section-title">Produk<br><em>Unggulan</em></h2>
        <a href="#" class="lihat-semua">SEMUA PRODUK →</a>
      </div>
      <div class="produk-grid">

        <div class="produk-card">
          <div class="produk-img" style="background:#f0ebe3;">🧵</div>
          <div class="produk-info">
            <span class="produk-cat">FASHION</span>
            <p class="produk-name">Tenun Ikat NTT</p>
            <p class="produk-seller">oleh Mama Teno Studio</p>
            <div class="produk-footer">
              <div>
                <p class="produk-price">Rp 380.000</p>
                <p class="produk-stars">★★★★★ (124)</p>
              </div>
              <a href="Checkout.html" class="btn-plus">+</a>
            </div>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-img" style="background:#e8f0eb;">🫙</div>
          <div class="produk-info">
            <span class="produk-cat">KULINER</span>
            <p class="produk-name">Sambal Matah Bali</p>
            <p class="produk-seller">oleh Dapur Ni Putu</p>
            <div class="produk-footer">
              <div>
                <p class="produk-price">Rp 48.000</p>
                <p class="produk-stars">★★★★★ (312)</p>
              </div>
              <a href="Checkout.html" class="btn-plus">+</a>
            </div>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-img" style="background:#ede8e3;">🪑</div>
          <div class="produk-info">
            <span class="produk-cat">KERAJINAN</span>
            <p class="produk-name">Kursi Rotan Anyam</p>
            <p class="produk-seller">oleh Rattan Nusantara</p>
            <div class="produk-footer">
              <div>
                <p class="produk-price">Rp 1.250.000</p>
                <p class="produk-stars">★★★★☆ (58)</p>
              </div>
              <a href="Checkout.html" class="btn-plus">+</a>
            </div>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-img" style="background:#f5eee8;">🌺</div>
          <div class="produk-info">
            <span class="produk-cat">KECANTIKAN</span>
            <p class="produk-name">Lulur Kunyit Jawa</p>
            <p class="produk-seller">oleh Spa Ayu Bali</p>
            <div class="produk-footer">
              <div>
                <p class="produk-price">Rp 75.000</p>
                <p class="produk-stars">★★★★★ (201)</p>
              </div>
              <a href="Checkout.html" class="btn-plus">+</a>
            </div>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-img" style="background:#e8ebe8;">🏺</div>
          <div class="produk-info">
            <span class="produk-cat">KERAJINAN</span>
            <p class="produk-name">Gerabah Lombok</p>
            <p class="produk-seller">oleh Pengrajin Sasak</p>
            <div class="produk-footer">
              <div>
                <p class="produk-price">Rp 185.000</p>
                <p class="produk-stars">★★★★★ (89)</p>
              </div>
              <a href="Checkout.html" class="btn-plus">+</a>
            </div>
          </div>
        </div>

        <div class="produk-card">
          <div class="produk-img" style="background:#eae5f0;">👗</div>
          <div class="produk-info">
            <span class="produk-cat">FASHION</span>
            <p class="produk-name">Batik Tulis Solo</p>
            <p class="produk-seller">oleh Batik Laras</p>
            <div class="produk-footer">
              <div>
                <p class="produk-price">Rp 420.000</p>
                <p class="produk-stars">★★★★★ (176)</p>
              </div>
              <a href="Checkout.html" class="btn-plus">+</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="promo-banner" id="penjual">
    <div class="inner promo-inner">
      <div class="promo-content">
        <p class="promo-label">Penawaran Spesial Hari Ini</p>
        <h2 class="promo-title">Belanja Lebih,<br>Hemat Lebih <em>Banyak</em></h2>
        <p class="promo-desc">Gratis ongkir ke seluruh Indonesia + diskon ekstra untuk pembelian pertama kamu. Berlaku hingga akhir bulan ini.</p>
        <a href="Checkout.html" class="btn-klaim">Klaim Sekarang →</a>
      </div>
      <div class="promo-badge-wrap">
        <div class="badge-circle">
          <p class="badge-persen">15%</p>
          <p class="badge-text">DISKON EKSTRA</p>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer" id="blog">
    <div class="footer-container">
      <div class="footer-brand">
        <p class="footer-logo">Pasar<span>Nusa</span></p>
        <p class="footer-tagline">Platform marketplace yang menghubungkan pembeli dengan pengrajin dan penjual lokal terbaik dari seluruh penjuru Nusantara.</p>
      </div>
      <div class="footer-links">
        <div>
          <p class="footer-col-title">BELANJA</p>
          <ul>
            <li><a href="#">Produk Terbaru</a></li>
            <li><a href="#">Flash Sale</a></li>
            <li><a href="#kategori">Semua Kategori</a></li>
          </ul>
        </div>
        <div>
          <p class="footer-col-title">BANTUAN</p>
          <ul>
            <li><a href="#">Hubungi Kami</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 PasarNusa. Mendukung UMKM Indonesia.</p>
      <p>🇮🇩 Bangga Buatan Indonesia</p>
    </div>
  </footer>

</body>
</html>