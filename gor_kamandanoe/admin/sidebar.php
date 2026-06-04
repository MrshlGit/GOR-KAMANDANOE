<?php
$current = basename($_SERVER['PHP_SELF']);
$unread_count = 0;
$kpath = __DIR__ . '/../koneksi.php';
if(file_exists($kpath)) include $kpath;
if(isset($conn)){
    $res = @mysqli_query($conn, "SELECT COUNT(*) FROM feedback WHERE status='unread'");
    if ($res) $unread_count = (int) mysqli_fetch_row($res)[0];
}
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="admin.css">

<div class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-badge">GK</div>
    <h2>GOR Kamandanoe</h2>
    <p>Admin Panel</p>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Menu Utama</div>
    <a href="dashboard.php"   class="nav-item <?= $current=='dashboard.php'  ?'active':'' ?>">Dashboard</a>
    <a href="data_user.php"   class="nav-item <?= $current=='data_user.php'  ?'active':'' ?>">Manajemen Akun</a>
    <a href="info_layanan.php" class="nav-item <?= $current=='info_layanan.php'?'active':'' ?>">Info Layanan</a>
    <a href="Pemesanan.php"   class="nav-item <?= $current=='Pemesanan.php'  ?'active':'' ?>">Pemesanan</a>
   
      <?php if ($unread_count > 0): ?>
        <span class="unread-count"><?= $unread_count ?></span>
      <?php endif; ?>
    </a>
  </nav>
  <div class="sidebar-bottom">
    <div class="admin-avatar"><?= strtoupper(substr($_SESSION['nama']??'A',0,1)) ?></div>
    <div class="admin-info">
      <p><?= htmlspecialchars($_SESSION['nama']??'Admin') ?></p>
      <span>Administrator</span>
    </div>
    <a href="../logout.php" class="logout-btn">Logout</a>
  </div>
</div>
<div class="main-content">
