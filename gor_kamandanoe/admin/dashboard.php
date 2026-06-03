<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("location:../login.php"); exit;
}
include "../koneksi.php";

$total_user = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM user WHERE role='user'"))[0];
$total_booking_hari = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE tanggal=CURDATE()"))[0];
$total_booking = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking"))[0];
$total_pendapatan = mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(total_bayar) FROM booking WHERE status='Lunas'"))[0] ?? 0;
$total_turnamen = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM turnamen"))[0];
$booking_terbaru = mysqli_query($conn,"SELECT * FROM booking WHERE nama_user!='' ORDER BY id_booking DESC LIMIT 6");

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
      <input type="text" placeholder="Cari...">
    </div>
  </div>
</div>
<div class="page-body">
  <p style="font-size:13px;color:#7b8fa6;margin-bottom:20px;">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['nama']) ?></strong>!</p>

  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-icon" style="background:#fff7ed;"></div>
      <div class="stat-value">3</div>
      <div class="stat-label">Total Lapangan</div>
      <div class="stat-change neutral">Aktif semua</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#f0fdf4;"></div>
      <div class="stat-value"><?= $total_booking ?></div>
      <div class="stat-label">Total Pemesanan</div>
      <div class="stat-change neutral">Hari ini: <?= $total_booking_hari ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#eff6ff;"></div>
      <div class="stat-value" style="font-size:18px;"><?= formatRp($total_pendapatan) ?></div>
      <div class="stat-label">Total Pendapatan</div>
      <div class="stat-change up">&#8593; Dari booking lunas</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#fdf4ff;"></div>
      <div class="stat-value"><?= $total_turnamen ?></div>
      <div class="stat-label">Turnamen</div>
      <div class="stat-change neutral"><?= $total_user ?> pengguna terdaftar</div>
    </div>
  </div>

  <div class="two-col">
    <div class="card-box">
      <div class="card-box-header">
        <span class="card-box-title">Riwayat Pemesanan Terbaru</span>
        <a href="Pemesanan.php" style="font-size:12px;color:#e85d04;text-decoration:none;font-weight:600;">Lihat Semua &#8594;</a>
      </div>
      <div class="card-box-body">
        <table class="data-table">
          <thead><tr><th>Nama</th><th>Lapangan</th><th>Tanggal</th><th>Status</th></tr></thead>
          <tbody>
          <?php while($b=mysqli_fetch_array($booking_terbaru)): ?>
          <tr>
            <td><strong><?= htmlspecialchars($b['nama_user']) ?></strong></td>
            <td><?= htmlspecialchars($b['lapangan']) ?></td>
            <td><?= date('d M Y',strtotime($b['tanggal'])) ?><br><small style="color:#7b8fa6;"><?= $b['jam'] ?></small></td>
            <td>
              <?php if($b['status']=='Lunas'): ?>
                <span class="badge badge-green">Lunas</span>
              <?php elseif($b['status']=='Acc'): ?>
                <span class="badge badge-blue">Dikonfirmasi</span>
              <?php else: ?>
                <span class="badge badge-yellow">Menunggu</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

    <div>
      <div class="card-box" style="margin-bottom:16px;">
        <div class="card-box-header"><span class="card-box-title">Status Lapangan</span></div>
        <div style="padding:16px;">
          <?php
          $lapangans = ['Lapangan A','Lapangan B','Lapangan C'];
          $statuses = [];
          $today = date('Y-m-d');
          foreach($lapangans as $lap){
            $q = mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE lapangan='$lap' AND tanggal='$today' AND status!='Dibatalkan'");
            $cnt = mysqli_fetch_row($q)[0];
            $statuses[$lap] = $cnt > 0 ? 'Terpakai' : 'Tersedia';
          }
          foreach($lapangans as $lap): $s=$statuses[$lap]; ?>
          <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f2f5;">
            <div style="display:flex;align-items:center;gap:8px;">
              <span class="status-dot <?= $s=='Tersedia'?'dot-green':'dot-red' ?>"></span>
              <span style="font-size:13.5px;font-weight:500;"><?= $lap ?></span>
            </div>
            <span class="badge <?= $s=='Tersedia'?'badge-green':'badge-orange' ?>"><?= $s ?></span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="card-box">
        <div class="card-box-header"><span class="card-box-title">Akses Cepat</span></div>
        <div style="padding:16px;display:flex;flex-direction:column;gap:8px;">
          <a href="Pemesanan.php?aksi=tambah" class="btn-action btn-primary" style="text-decoration:none;justify-content:center;">+ Tambah Booking</a>
          <a href="data_turnamen.php?aksi=tambah" class="btn-action btn-secondary" style="text-decoration:none;justify-content:center;">+ Tambah Turnamen</a>
          <a href="data_user.php" class="btn-action btn-secondary" style="text-decoration:none;justify-content:center;">Kelola Pengguna</a>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</body></html>
