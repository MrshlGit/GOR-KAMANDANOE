<?php
session_start();
if(!isset($_SESSION['role'])||$_SESSION['role']!="admin"){header("location:../login.php");exit;}
include "../koneksi.php";
/** @var mysqli $conn */

$msg='';
function formatRp($n){return 'Rp '.number_format($n,0,',','.');}
function validateJamRange($jam){
    if(!preg_match('/^\s*(\d{1,2})(?::(\d{2}))?\s*[-–]\s*(\d{1,2})(?::(\d{2}))?\s*$/', trim($jam), $m)) return false;
    $start=(int)$m[1]; $end=(int)$m[3];
    if($start<0||$start>23||$end<1||$end>24||$start>=$end) return false;
    return [$start,$end];
}
function hasJamConflict($conn,$lap,$tgl,$jam,$excludeId=null){
    $range=validateJamRange($jam);
    if($range===false) return false;
    list($start,$end)=$range;
    $ex=$excludeId?" AND id_booking!=".(int)$excludeId:"";
    $q=mysqli_query($conn,"SELECT jam FROM booking WHERE lapangan='$lap' AND tanggal='$tgl' AND status!='Dibatalkan'".$ex);
    while($row=mysqli_fetch_assoc($q)){
        $e2=validateJamRange($row['jam']); if($e2===false) continue;
        if($start<$e2[1]&&$end>$e2[0]) return true;
    }
    return false;
}

if(isset($_POST['aksi_tambah'])){
    $nama=mysqli_real_escape_string($conn,$_POST['nama_user']);
    $lap=mysqli_real_escape_string($conn,$_POST['lapangan']);
    $tgl=mysqli_real_escape_string($conn,$_POST['tanggal']);
    $jam=mysqli_real_escape_string($conn,$_POST['jam']);
    $harga=(int)$_POST['harga']; $total=(int)$_POST['total_bayar'];
    $status=mysqli_real_escape_string($conn,$_POST['status']);
    $metode=mysqli_real_escape_string($conn,$_POST['metode_pembayaran']);
    if(validateJamRange($jam)===false) $msg='<div class="alert alert-danger">Format jam tidak valid. Contoh: 19:00 - 20:00.</div>';
    elseif(hasJamConflict($conn,$lap,$tgl,$jam)) $msg='<div class="alert alert-danger">Booking bentrok: lapangan sudah terisi pada waktu tersebut.</div>';
    else { mysqli_query($conn,"INSERT INTO booking (nama_user,lapangan,tanggal,jam,harga,total_bayar,status,metode_pembayaran) VALUES('$nama','$lap','$tgl','$jam',$harga,$total,'$status','$metode')"); $msg='<div class="alert alert-success">Booking berhasil ditambahkan.</div>'; }
}
if(isset($_POST['aksi_edit'])){
    $id=(int)$_POST['id']; $nama=mysqli_real_escape_string($conn,$_POST['nama_user']);
    $lap=mysqli_real_escape_string($conn,$_POST['lapangan']); $tgl=mysqli_real_escape_string($conn,$_POST['tanggal']);
    $jam=mysqli_real_escape_string($conn,$_POST['jam']); $harga=(int)$_POST['harga'];
    $total=(int)$_POST['total_bayar']; $status=mysqli_real_escape_string($conn,$_POST['status']);
    $metode=mysqli_real_escape_string($conn,$_POST['metode_pembayaran']);
    if(validateJamRange($jam)===false) $msg='<div class="alert alert-danger">Format jam tidak valid.</div>';
    elseif(hasJamConflict($conn,$lap,$tgl,$jam,$id)) $msg='<div class="alert alert-danger">Booking bentrok.</div>';
    else { mysqli_query($conn,"UPDATE booking SET nama_user='$nama',lapangan='$lap',tanggal='$tgl',jam='$jam',harga=$harga,total_bayar=$total,status='$status',metode_pembayaran='$metode' WHERE id_booking=$id"); $msg='<div class="alert alert-success">Booking berhasil diperbarui.</div>'; }
}
if(isset($_POST['aksi_konfirmasi'])){ $id=(int)$_POST['id']; mysqli_query($conn,"UPDATE booking SET status='Acc' WHERE id_booking=$id"); $msg='<div class="alert alert-success">Booking dikonfirmasi.</div>'; }
if(isset($_GET['hapus'])){ mysqli_query($conn,"DELETE FROM booking WHERE id_booking=".(int)$_GET['hapus']); $msg='<div class="alert alert-success">Booking dihapus.</div>'; }

$search=isset($_GET['q'])?mysqli_real_escape_string($conn,$_GET['q']):'';
$where='WHERE 1=1'; if($search) $where.=" AND (nama_user LIKE '%$search%' OR lapangan LIKE '%$search%')";

$total_all=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking"))[0];
$total_lunas=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE status='Lunas'"))[0];
$total_menunggu=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE status='Menunggu'"))[0];
$total_pendapatan=mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(total_bayar) FROM booking WHERE status='Lunas'"))[0]??0;
$data=mysqli_query($conn,"SELECT * FROM booking $where ORDER BY id_booking DESC");
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><title>Pemesanan - GOR Kamandanoe</title>
</head><body>
<?php include "sidebar.php"; ?>
<div class="topbar">
  <div class="topbar-title">Layanan Pemesanan</div>
  <div class="topbar-right">
    <form method="GET">
      <div class="topbar-search">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#8a8680" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="q" placeholder="Cari pemesanan..." value="<?=htmlspecialchars($search)?>">
      </div>
    </form>
    <button class="btn-action btn-primary" onclick="document.getElementById('modal-tambah').classList.add('show')">+ Tambah Booking</button>
  </div>
</div>
<div class="page-body">
  <?=$msg?>
  <div class="stat-grid-4">
    <div class="stat-card">
      <div class="stat-value"><?=$total_all?></div>
      <div class="stat-label">Total Pemesanan</div>
    </div>
    <div class="stat-card">
      <div class="stat-value"><?=$total_lunas?></div>
      <div class="stat-label">Lunas</div>
    </div>
    <div class="stat-card">
      <div class="stat-value text-primary"><?=$total_menunggu?></div>
      <div class="stat-label">Menunggu</div>
      <div class="stat-change warn">Perlu tindakan</div>
    </div>
    <div class="stat-card">
      <div class="stat-value-sm"><?=formatRp($total_pendapatan)?></div>
      <div class="stat-label">Total Pendapatan</div>
    </div>
  </div>

  <div class="card-box">
    <div class="card-box-header">
      <span class="card-box-title">Semua Pemesanan</span>
    </div>
    <table class="data-table">
      <thead><tr><th>ID</th><th>Nama Pemesan</th><th>Lapangan</th><th>Tanggal & Jam</th><th>Total</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
      <?php while($d=mysqli_fetch_array($data)): ?>
      <tr>
        <td class="text-secondary small-text">#<?=str_pad($d['id_booking'],3,'0',STR_PAD_LEFT)?></td>
        <td><strong><?=htmlspecialchars($d['nama_user'])?></strong></td>
        <td class="text-secondary"><?=htmlspecialchars($d['lapangan'])?></td>
        <td>
          <?=date('d M Y',strtotime($d['tanggal']))?>
          <br><small class="text-secondary small-text"><?=$d['jam']?></small>
        </td>
        <td style="font-weight:600;"><?=$d['total_bayar']?formatRp($d['total_bayar']):'-'?></td>
        <td>
          <?php if($d['metode_pembayaran']=='QRIS'): ?><span class="badge badge-blue">QRIS</span>
          <?php elseif($d['metode_pembayaran']=='Cash'): ?><span class="badge badge-green">Cash</span>
          <?php else: ?><span class="badge badge-gray"><?=htmlspecialchars($d['metode_pembayaran']??'-')?></span>
          <?php endif; ?>
        </td>
        <td>
          <?php if($d['status']=='Lunas'): ?><span class="badge badge-green">Lunas</span>
          <?php elseif($d['status']=='Acc'): ?><span class="badge badge-blue">Dikonfirmasi</span>
          <?php elseif($d['status']=='Menunggu'): ?><span class="badge badge-yellow">Menunggu</span>
          <?php else: ?><span class="badge badge-gray"><?=htmlspecialchars($d['status'])?></span>
          <?php endif; ?>
        </td>
        <td style="white-space:nowrap;">
          <button class="btn-action btn-secondary btn-sm" onclick="openEdit(<?=$d['id_booking']?>,'<?=htmlspecialchars($d['nama_user'],ENT_QUOTES)?>','<?=htmlspecialchars($d['lapangan'],ENT_QUOTES)?>','<?=$d['tanggal']?>','<?=$d['jam']?>',<?=$d['harga']??0?>,<?=$d['total_bayar']??0?>,'<?=$d['status']?>','<?=$d['metode_pembayaran']??''?>')">✏</button>
          <?php if($d['status']=='Menunggu'): ?>
          <form method="POST" class="inline-form">
            <input type="hidden" name="id" value="<?=$d['id_booking']?>">
            <button type="submit" name="aksi_konfirmasi" class="btn-action btn-success btn-sm">✓</button>
          </form>
          <?php endif; ?>
          <a href="?hapus=<?=$d['id_booking']?>" class="btn-action btn-danger btn-sm" onclick="return confirm('Hapus booking ini?')">🗑</a>
        </td>
      </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Tambah -->
<div class="modal-overlay" id="modal-tambah">
  <div class="modal-box modal-large">
    <h3>+ Tambah Booking</h3>
    <form method="POST">
      <div class="two-col">
        <div class="form-group"><label>Nama Pemesan</label>
          <select name="nama_user" class="form-control">
            <?php $u2=mysqli_query($conn,"SELECT username FROM user WHERE role='user'"); while($u=mysqli_fetch_array($u2)): ?>
            <option><?=htmlspecialchars($u['username'])?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group"><label>Lapangan</label>
          <select name="lapangan" class="form-control"><option>Lapangan A</option><option>Lapangan B</option><option>Lapangan C</option><option>Lapangan D</option></select>
        </div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
        <div class="form-group"><label>Jam</label><input type="text" name="jam" class="form-control" placeholder="19:00 - 20:00"></div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Harga/Jam (Rp)</label><input type="number" name="harga" value="50000" class="form-control"></div>
        <div class="form-group"><label>Total Bayar (Rp)</label><input type="number" name="total_bayar" value="50000" class="form-control"></div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Status</label>
          <select name="status" class="form-control"><option>Menunggu</option><option>Acc</option><option>Lunas</option></select>
        </div>
        <div class="form-group"><label>Metode Pembayaran</label>
          <select name="metode_pembayaran" class="form-control"><option value="QRIS">QRIS</option><option value="Cash">Cash</option><option value="-">-</option></select>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-action btn-secondary" onclick="document.getElementById('modal-tambah').classList.remove('show')">Batal</button>
        <button type="submit" name="aksi_tambah" class="btn-action btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit -->
<div class="modal-overlay" id="modal-edit">
  <div class="modal-box" style="width:520px;">
    <h3>Edit Booking</h3>
    <form method="POST">
      <input type="hidden" name="id" id="eb-id">
      <div class="two-col">
        <div class="form-group"><label>Nama Pemesan</label><input type="text" name="nama_user" id="eb-nama" class="form-control"></div>
        <div class="form-group"><label>Lapangan</label>
          <select name="lapangan" id="eb-lap" class="form-control"><option>Lapangan A</option><option>Lapangan B</option><option>Lapangan C</option><option>Lapangan D</option></select>
        </div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" id="eb-tgl" class="form-control"></div>
        <div class="form-group"><label>Jam</label><input type="text" name="jam" id="eb-jam" class="form-control"></div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Harga (Rp)</label><input type="number" name="harga" id="eb-harga" class="form-control"></div>
        <div class="form-group"><label>Total Bayar (Rp)</label><input type="number" name="total_bayar" id="eb-total" class="form-control"></div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Status</label>
          <select name="status" id="eb-status" class="form-control"><option>Menunggu</option><option>Acc</option><option>Lunas</option></select>
        </div>
        <div class="form-group"><label>Metode Pembayaran</label>
          <select name="metode_pembayaran" id="eb-metode" class="form-control"><option value="QRIS">QRIS</option><option value="Cash">Cash</option><option value="-">-</option></select>
        </div>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-action btn-secondary" onclick="document.getElementById('modal-edit').classList.remove('show')">Batal</button>
        <button type="submit" name="aksi_edit" class="btn-action btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<script>
function openEdit(id,nama,lap,tgl,jam,harga,total,status,metode){
  document.getElementById('eb-id').value=id;
  document.getElementById('eb-nama').value=nama;
  document.getElementById('eb-lap').value=lap;
  document.getElementById('eb-tgl').value=tgl;
  document.getElementById('eb-jam').value=jam;
  document.getElementById('eb-harga').value=harga;
  document.getElementById('eb-total').value=total;
  document.getElementById('eb-status').value=status;
  document.getElementById('eb-metode').value=metode;
  document.getElementById('modal-edit').classList.add('show');
}
document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',function(e){if(e.target===this)this.classList.remove('show')}));
const priceMap={'Lapangan A':50000,'Lapangan B':70000,'Lapangan C':90000,'Lapangan D':90000};
const addLap=document.querySelector('#modal-tambah select[name="lapangan"]');
const addHarga=document.querySelector('#modal-tambah input[name="harga"]');
const addTotal=document.querySelector('#modal-tambah input[name="total_bayar"]');
if(addLap) addLap.addEventListener('change',function(){ const p=priceMap[this.value]||50000; if(addHarga)addHarga.value=p; if(addTotal)addTotal.value=p; });
</script>
</div>
</div></body></html>
