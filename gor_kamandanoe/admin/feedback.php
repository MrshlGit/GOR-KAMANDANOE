<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != "admin") {
  header("location:../login.php");
  exit;
}
include "../koneksi.php";
$msg = '';
if (isset($_GET['mark_read'])) {
  $id = (int) $_GET['mark_read'];
  mysqli_query($conn, "UPDATE feedback SET status='read' WHERE id=$id");
  $msg = '<div class="alert alert-success">Pesan ditandai dibaca.</div>';
}
if (isset($_GET['hapus'])) {
  $id = (int) $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM feedback WHERE id=$id");
  $msg = '<div class="alert alert-success">Pesan dihapus.</div>';
}
$data = mysqli_query($conn, "SELECT * FROM feedback ORDER BY created_at DESC");
$total_msg = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM feedback"))[0];
$unread_msg = mysqli_fetch_row(mysqli_query($conn, "SELECT COUNT(*) FROM feedback WHERE status='unread'"))[0];
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Kritik & Saran - GOR Kamandanoe</title>
</head>

<body>
  <?php include "sidebar.php"; ?>
  <div class="topbar">
    <div class="topbar-title">Kritik &amp; Saran</div>
    <?php if ($unread_msg > 0): ?>
      <div class="topbar-right">
        <span class="pill pill-primary"><?= $unread_msg ?> pesan belum dibaca</span>
      </div>
    <?php endif; ?>
  </div>
  <div class="page-body">
    <?= $msg ?>
    <div class="card-box">
      <div class="card-box-header">
        <span class="card-box-title">Semua Pesan</span>
        <span class="pill"><?= $total_msg ?> pesan</span>
      </div>
      <table class="data-table">
        <thead>
          <tr>
            <th style="width:60px">ID</th>
            <th>Nama</th>
            <th>Pesan</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($d = mysqli_fetch_assoc($data)): ?>
            <tr>
              <td class="text-secondary small-text">#<?= $d['id'] ?></td>
              <td class="text-dark" style="font-weight:600;"><?= htmlspecialchars($d['name']) ?></td>
              <td class="text-dark" style="max-width:360px;"><?= nl2br(htmlspecialchars($d['message'])) ?></td>
              <td>
                <?php if ($d['status'] == 'unread'): ?>
                  <span class="badge badge-orange">Belum dibaca</span>
                <?php else: ?>
                  <span class="badge badge-green">Dibaca</span>
                <?php endif; ?>
              </td>
              <td class="text-secondary small-text"><?= $d['created_at'] ?></td>
              <td style="white-space:nowrap;">
                <?php if ($d['status'] == 'unread'): ?>
                  <a href="?mark_read=<?= $d['id'] ?>" class="btn-action btn-secondary btn-sm">✓ Tandai Dibaca</a>
                <?php endif; ?>
                <a href="?hapus=<?= $d['id'] ?>" class="btn-action btn-danger btn-sm"
                  onclick="return confirm('Hapus pesan ini?')">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>
</body>

</html>