<?php
session_start();
if(!isset($_SESSION['role'])||$_SESSION['role']!="admin"){header("location:../login.php");exit;}
include "../koneksi.php";
$msg='';

// Edit lapangan (simulasi, data statis karena tidak ada tabel lapangan di DB)
if(isset($_POST['aksi_edit_lap'])){
    $msg='<div class="alert alert-success">Perubahan lapangan berhasil disimpan.</div>';
}

$lapangans = [
    ['nama'=>'Lapangan A','tipe'=>'Badminton Indoor','harga'=>50000,'status'=>'Aktif','kapasitas'=>4],
    ['nama'=>'Lapangan B','tipe'=>'Badminton Indoor','harga'=>50000,'status'=>'Aktif','kapasitas'=>4],
    ['nama'=>'Lapangan C','tipe'=>'Badminton Indoor','harga'=>50000,'status'=>'Aktif','kapasitas'=>4],
];
function formatRp($n){return 'Rp '.number_format($n,0,',','.');}
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><title>Info Layanan - GOR Kamandanoe</title>
</head><body>
<?php include "sidebar.php"; ?>
<div class="topbar">
  <div class="topbar-title">&#9650; Info Layanan</div>
</div>
<div class="page-body">
  <?= $msg ?>

  <!-- Jadwal Lapangan -->
  <div class="card-box" style="margin-bottom:20px;">
    <div class="card-box-header">
      <span class="card-box-title">Jadwal Lapangan - <?= date('d M Y') ?></span>
      <span class="text-muted small-text">Data booking aktif hari ini</span>
    </div>
    <div style="overflow-x:auto;padding:16px;">
      <table class="data-table" style="min-width:700px;">
        <thead>
          <tr>
            <th class="text-secondary" style="padding:10px 14px;text-align:left;">Lapangan</th>
            <?php for($h=7;$h<=20;$h++): ?>
            <th class="text-secondary" style="padding:8px 6px;text-align:center;min-width:48px;"><?= $h ?>-<?= $h+1 ?></th>
            <?php endfor; ?>
          </tr>
        </thead>
        <tbody>
        <?php foreach($lapangans as $lap):
          $booked=[];
          $q=mysqli_query($conn,"SELECT jam FROM booking WHERE lapangan='{$lap['nama']}' AND tanggal=CURDATE() AND status!='Dibatalkan'");
          while($r=mysqli_fetch_row($q)){
            preg_match('/(\d+)/',trim($r[0]),$m);
            if(isset($m[1])) $booked[]=(int)$m[1];
          }
        ?>
        <tr>
          <td class="text-dark" style="padding:10px 14px;font-weight:600;font-size:13px;border-top:1px solid var(--border);"><?= $lap['nama'] ?></td>
          <?php for($h=7;$h<=20;$h++):
            $isBusy=in_array($h,$booked);
          ?>
          <td style="padding:6px;border-top:1px solid var(--border);text-align:center;">
            <div class="status-pill <?= $isBusy?'danger':'success' ?>">
              <?= $isBusy?'Terpakai':'Bebas' ?>
            </div>
          </td>
          <?php endfor; ?>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <div style="display:flex;gap:16px;margin-top:12px;">
        <span class="status-key"><span class="success"></span>Tersedia</span>
        <span class="status-key"><span class="danger"></span>Terpakai</span>
      </div>
    </div>
  </div>

  <!-- Data Lapangan -->
  <div class="card-box" style="margin-bottom:20px;">
    <div class="card-box-header"><span class="card-box-title">Data Lapangan</span></div>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;padding:20px;">
      <?php foreach($lapangans as $i=>$lap): ?>
      <div class="court-card">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
          <div class="court-num-badge"><?= $i+1 ?></div>
          <div>
            <p class="text-dark" style="font-size:14px;font-weight:700;"><?= $lap['nama'] ?></p>
            <span class="badge badge-green" style="font-size:10.5px;padding:2px 8px;margin-top:3px;display:inline-flex;">Aktif</span>
          </div>
        </div>
        <div class="text-muted" style="font-size:12.5px;display:flex;flex-direction:column;gap:6px;">
          <div><span class="text-dark" style="font-weight:600;">Tipe:</span> <?= $lap['tipe'] ?></div>
          <div><span class="text-dark" style="font-weight:600;">Harga/Jam:</span> <?= formatRp($lap['harga']) ?></div>
          <div><span class="text-dark" style="font-weight:600;">Kapasitas:</span> <?= $lap['kapasitas'] ?> orang</div>
        </div>
        <button class="btn-action btn-secondary btn-sm" style="margin-top:14px;width:100%;justify-content:center;" onclick="openEditLap('<?= $lap['nama'] ?>',<?= $lap['harga'] ?>)">Edit Lapangan</button>
      </div>
      <?php endforeach; ?>
    </div>
  </div>



<!-- Modal Edit Lapangan -->
<div class="modal-overlay" id="modal-edit-lap">
  <div class="modal-box">
    <h3>Edit Lapangan</h3>
    <form method="POST">
      <div class="form-group"><label>Nama Lapangan</label><input type="text" name="nama_lap" id="el-nama" class="form-control" readonly></div>
      <div class="form-group"><label>Tipe</label><input type="text" name="tipe" value="Badminton Indoor" class="form-control"></div>
      <div class="form-group"><label>Harga/Jam (Rp)</label><input type="number" name="harga" id="el-harga" class="form-control"></div>
      <div class="form-group"><label>Status</label>
        <select name="status" class="form-control"><option>Aktif</option><option>Maintenance</option><option>Nonaktif</option></select>
      </div>
      <div class="form-group"><label>Kapasitas (orang)</label><input type="number" name="kapasitas" value="4" class="form-control"></div>
      <div class="modal-actions">
        <button type="button" class="btn-action btn-secondary" onclick="document.getElementById('modal-edit-lap').classList.remove('show')">Batal</button>
        <button type="submit" name="aksi_edit_lap" class="btn-action btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<script>
function openEditLap(nama,harga){
  document.getElementById('el-nama').value=nama;
  document.getElementById('el-harga').value=harga;
  document.getElementById('modal-edit-lap').classList.add('show');
}
document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',function(e){if(e.target===this)this.classList.remove('show')}));
</script>
</div>
</div></body></html>
