<?php
session_start();
if(!isset($_SESSION['role'])||$_SESSION['role']!="admin"){header("location:../login.php");exit;}
include "../koneksi.php";
$msg='';
function formatRp($n){return 'Rp '.number_format($n,0,',','.');}

if(isset($_POST['aksi_tambah'])){
    $nama=mysqli_real_escape_string($conn,$_POST['nama_user']);
    $lap=mysqli_real_escape_string($conn,$_POST['lapangan']);
    $tgl=mysqli_real_escape_string($conn,$_POST['tanggal']);
    $jam=mysqli_real_escape_string($conn,$_POST['jam']);
    $harga=(int)$_POST['harga'];
    $total=(int)$_POST['total_bayar'];
    $status=mysqli_real_escape_string($conn,$_POST['status']);
    $metode=mysqli_real_escape_string($conn,$_POST['metode_pembayaran']);
    mysqli_query($conn,"INSERT INTO booking (nama_user,lapangan,tanggal,jam,harga,total_bayar,status,metode_pembayaran) VALUES('$nama','$lap','$tgl','$jam',$harga,$total,'$status','$metode')");
    $msg='<div class="alert alert-success">Booking berhasil ditambahkan.</div>';
}
if(isset($_POST['aksi_edit'])){
    $id=(int)$_POST['id'];
    $nama=mysqli_real_escape_string($conn,$_POST['nama_user']);
    $lap=mysqli_real_escape_string($conn,$_POST['lapangan']);
    $tgl=mysqli_real_escape_string($conn,$_POST['tanggal']);
    $jam=mysqli_real_escape_string($conn,$_POST['jam']);
    $harga=(int)$_POST['harga'];
    $total=(int)$_POST['total_bayar'];
    $status=mysqli_real_escape_string($conn,$_POST['status']);
    $metode=mysqli_real_escape_string($conn,$_POST['metode_pembayaran']);
    mysqli_query($conn,"UPDATE booking SET nama_user='$nama',lapangan='$lap',tanggal='$tgl',jam='$jam',harga=$harga,total_bayar=$total,status='$status',metode_pembayaran='$metode' WHERE id_booking=$id");
    $msg='<div class="alert alert-success">Booking berhasil diperbarui.</div>';
}
if(isset($_POST['aksi_konfirmasi'])){
    $id=(int)$_POST['id'];
    mysqli_query($conn,"UPDATE booking SET status='Acc' WHERE id_booking=$id");
    $msg='<div class="alert alert-success">Booking berhasil dikonfirmasi.</div>';
}
if(isset($_GET['hapus'])){
    mysqli_query($conn,"DELETE FROM booking WHERE id_booking=".(int)$_GET['hapus']);
    $msg='<div class="alert alert-success">Booking berhasil dihapus.</div>';
}

$filter=isset($_GET['filter'])?$_GET['filter']:'';
$search=isset($_GET['q'])?mysqli_real_escape_string($conn,$_GET['q']):'';
$where='WHERE 1=1';
if($filter=='booking') $where.="";
if($search) $where.=" AND (nama_user LIKE '%$search%' OR lapangan LIKE '%$search%')";

$total_all=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking"))[0];
$total_lunas=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE status='Lunas'"))[0];
$total_menunggu=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM booking WHERE status='Menunggu'"))[0];
$total_pendapatan=mysqli_fetch_row(mysqli_query($conn,"SELECT SUM(total_bayar) FROM booking WHERE status='Lunas'"))[0]??0;

$data=mysqli_query($conn,"SELECT * FROM booking $where ORDER BY id_booking DESC");
$users=mysqli_query($conn,"SELECT nama FROM user WHERE role='user' ORDER BY nama");
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><title>Pemesanan - GOR Kamandanoe</title>
</head><body>
<?php include "sidebar.php"; ?>
<div class="topbar">
  <div class="topbar-title">&#9733; Layanan Pemesanan</div>
  <div class="topbar-right">
    <form method="GET" style="display:flex;align-items:center;gap:8px;background:#f5f6fa;border:1px solid #e8eaed;border-radius:8px;padding:8px 14px;">
      <span>&#128269;</span>
      <input type="text" name="q" placeholder="Cari pemesanan..." value="<?=htmlspecialchars($search)?>" style="border:none;background:transparent;font-size:13px;outline:none;width:180px;">
    </form>
  </div>
</div>
<div class="page-body">
  <?=$msg?>

  <div class="stat-grid" style="margin-bottom:20px;">
    <div class="stat-card"><div class="stat-icon" style="background:#fff7ed;">&#128203;</div><div class="stat-value"><?=$total_all?></div><div class="stat-label">Total Pemesanan</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:#f0fdf4;">&#9989;</div><div class="stat-value"><?=$total_lunas?></div><div class="stat-label">Lunas</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:#fef9c3;">&#8987;</div><div class="stat-value"><?=$total_menunggu?></div><div class="stat-label">Menunggu</div><div class="stat-change neutral">Perlu tindakan</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:#eff6ff;">&#128176;</div><div class="stat-value" style="font-size:17px;"><?=formatRp($total_pendapatan)?></div><div class="stat-label">Total Pendapatan</div></div>
  </div>

  <div class="card-box">
    <div class="card-box-header">
      <span class="card-box-title">Semua Pemesanan</span>
      <button class="btn-action btn-primary" onclick="document.getElementById('modal-tambah').classList.add('show')">+ Tambah Booking</button>
    </div>
    <div class="card-box-body">
      <table class="data-table">
        <thead><tr><th>ID</th><th>Nama Pemesan</th><th>Lapangan</th><th>Tanggal & Jam</th><th>Total</th><th>Metode</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php while($d=mysqli_fetch_array($data)): ?>
        <tr>
          <td style="color:#7b8fa6;font-size:12px;">#<?=str_pad($d['id_booking'],3,'0',STR_PAD_LEFT)?></td>
          <td><strong><?=htmlspecialchars($d['nama_user'])?></strong></td>
          <td><?=htmlspecialchars($d['lapangan'])?></td>
          <td><?=date('d M Y',strtotime($d['tanggal']))?><br><small style="color:#7b8fa6;"><?=$d['jam']?></small></td>
          <td><?=$d['total_bayar']?formatRp($d['total_bayar']):'-'?></td>
          <td>
            <?php if($d['metode_pembayaran']=='QRIS'): ?>
              <span class="badge badge-blue">QRIS</span>
            <?php elseif($d['metode_pembayaran']=='Cash'): ?>
              <span class="badge badge-green">Cash</span>
            <?php else: ?>
              <span class="badge badge-gray"><?=htmlspecialchars($d['metode_pembayaran']??'-')?></span>
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
            <button class="btn-action btn-secondary btn-sm" onclick="openEdit(<?=$d['id_booking']?>,'<?=htmlspecialchars($d['nama_user'],ENT_QUOTES)?>','<?=htmlspecialchars($d['lapangan'],ENT_QUOTES)?>','<?=$d['tanggal']?>','<?=$d['jam']?>',<?=$d['harga']??0?>,<?=$d['total_bayar']??0?>,'<?=$d['status']?>','<?=$d['metode_pembayaran']??''?>')">&#9998;</button>
            <?php if($d['status']=='Menunggu'): ?>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="id" value="<?=$d['id_booking']?>">
              <button type="submit" name="aksi_konfirmasi" class="btn-action btn-success btn-sm">&#10003;</button>
            </form>
            <?php endif; ?>
            <a href="?hapus=<?=$d['id_booking']?>" class="btn-action btn-danger btn-sm" onclick="return confirm('Hapus booking ini?')">&#128465;</a>
          </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal-overlay" id="modal-tambah">
  <div class="modal-box" style="width:520px;">
    <h3>+ Tambah Booking</h3>
    <form method="POST">
      <div class="two-col">
        <div class="form-group"><label>Nama Pemesan</label>
          <select name="nama_user" class="form-control">
            <?php $users2=mysqli_query($conn,"SELECT nama FROM user WHERE role='user'"); while($u=mysqli_fetch_array($users2)): ?>
            <option><?=htmlspecialchars($u['nama'])?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="form-group"><label>Lapangan</label>
          <select name="lapangan" class="form-control"><option>Lapangan A</option><option>Lapangan B</option><option>Lapangan C</option></select>
        </div>
      </div>
      <div class="two-col">
        <div class="form-group"><label>Tanggal</label><input type="date" name="tanggal" class="form-control" required></div>
        <div class="form-group"><label>Jam</label><input type="text" name="jam" class="form-control" placeholder="19.00-20.00"></div>
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

<!-- Modal Edit -->
<div class="modal-overlay" id="modal-edit">
  <div class="modal-box" style="width:520px;">
    <h3>&#9998; Edit Booking</h3>
    <form method="POST">
      <input type="hidden" name="id" id="eb-id">
      <div class="two-col">
        <div class="form-group"><label>Nama Pemesan</label><input type="text" name="nama_user" id="eb-nama" class="form-control"></div>
        <div class="form-group"><label>Lapangan</label>
          <select name="lapangan" id="eb-lap" class="form-control"><option>Lapangan A</option><option>Lapangan B</option><option>Lapangan C</option></select>
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
</script>
</div></body></html>
