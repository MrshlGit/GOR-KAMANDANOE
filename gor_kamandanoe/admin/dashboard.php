<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../login.php"); exit;
}
include "../koneksi.php";

$total_booking_hari = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE tanggal=CURDATE()"))[0];
$total_booking      = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking"))[0];
$total_pendapatan   = mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(total_bayar) FROM booking WHERE status='Lunas'"))[0] ?? 0;
$booking_terbaru    = mysqli_query($conn,"SELECT * FROM booking WHERE nama_user!='' ORDER BY id_booking DESC LIMIT 6");
$lapangans = ['Lapangan A','Lapangan B','Lapangan C','Lapangan D'];

function formatRp($n){ return 'Rp '.number_format($n,0,',','.'); }
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard Admin - GOR Kamandanoe</title>
</head><body>
<?php include "sidebar.php"; ?>
<div class="topbar">
  <div class="topbar-title">Dashboard</div>
  <div class="topbar-right">
    <div class="topbar-search">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8a8680" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Cari...">
    </div>
  </div>
</div>

<div class="page-body">
  <p class="text-muted welcome-banner" style="font-size:13px;margin-bottom:22px;">Selamat datang kembali, <strong class="text-dark"><?= htmlspecialchars($_SESSION['nama']) ?></strong>!</p>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-value"><?= count($lapangans) ?></div>
      <div class="stat-label">Total Lapangan</div>
      <div class="stat-change neutral">✓ Aktif semua</div>
    </div>
    <div class="stat-card">
      <div class="stat-value"><?= $total_booking ?></div>
      <div class="stat-label">Total Pemesanan</div>
      <div class="stat-change neutral">Hari ini: <?= $total_booking_hari ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-value-sm" style="color:#e05c00;"><?= formatRp($total_pendapatan) ?></div>
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-change up">Dari booking lunas</div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 320px;gap:20px;">
    <div class="card-box">
      <div class="card-box-header">
        <span class="card-box-title">Riwayat Pemesanan Terbaru</span>
        <a href="Pemesanan.php" class="btn-link">Lihat Semua </a>
      </div>
      <table class="data-table">
        <thead><tr><th>Nama</th><th>Lapangan</th><th>Tanggal</th><th>Status</th></tr></thead>
        <tbody>
        <?php while($b=mysqli_fetch_array($booking_terbaru)): ?>
        <tr>
          <td><strong><?= htmlspecialchars($b['nama_user']) ?></strong></td>
          <td class="text-secondary"><?= htmlspecialchars($b['lapangan']) ?></td>
          <td>
            <?= date('d M Y',strtotime($b['tanggal'])) ?>
            <br><small class="text-secondary small-text"><?= $b['jam'] ?></small>
          </td>
          <td>
            <?php if($b['status']=='Lunas'): ?><span class="badge badge-green">Lunas</span>
            <?php elseif($b['status']=='Acc'): ?><span class="badge badge-blue">Dikonfirmasi</span>
            <?php else: ?><span class="badge badge-yellow">Menunggu</span><?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>

    <div class="card-box">
      <div class="card-box-header"><span class="card-box-title">Status Lapangan</span></div>
      <div style="padding:8px 0;">
        <?php
        $today = date('Y-m-d');
        foreach($lapangans as $lap):
          $q   = mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE lapangan='$lap' AND tanggal='$today' AND status!='Dibatalkan'");
          $cnt = mysqli_fetch_row($q)[0];
          $s   = $cnt > 0 ? 'Terpakai' : 'Tersedia';
        ?>
        <div class="two-col" style="align-items:center;justify-content:space-between;padding:12px 20px;border-bottom:1px solid var(--border);">
          <div style="display:flex;align-items:center;gap:8px;">
            <span class="status-dot <?= $s=='Tersedia'?'dot-green':'dot-red' ?>"></span>
            <span class="text-dark" style="font-size:13.5px;font-weight:500;"><?= $lap ?></span>
          </div>
          <span class="badge <?= $s=='Tersedia'?'badge-green':'badge-orange' ?>"><?= $s ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
</div>
</div></body></html>
