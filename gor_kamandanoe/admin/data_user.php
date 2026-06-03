<?php
session_start();
if(!isset($_SESSION['role'])||$_SESSION['role']!="admin"){header("location:../login.php");exit;}
include "../koneksi.php";

$msg = '';
// Tambah user
if(isset($_POST['aksi_tambah'])){
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $password=mysqli_real_escape_string($conn,$_POST['password']);
    $role=mysqli_real_escape_string($conn,$_POST['role']);
    $cek=mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM user WHERE username='$username'"))[0];
    if($cek>0){ $msg='<div class="alert alert-warning">Username sudah digunakan.</div>'; }
    else {
        mysqli_query($conn,"INSERT INTO user (username,password,role) VALUES('$username','$password','$role')");
        $msg='<div class="alert alert-success">User berhasil ditambahkan.</div>';
    }
}
// Edit user
if(isset($_POST['aksi_edit'])){
    $id=(int)$_POST['id'];
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $role=mysqli_real_escape_string($conn,$_POST['role']);
    $pass_sql = !empty($_POST['password']) ? ",password='".mysqli_real_escape_string($conn,$_POST['password'])."'" : '';
    mysqli_query($conn,"UPDATE user SET username='$username'$pass_sql,role='$role' WHERE id=$id");
    $msg='<div class="alert alert-success">User berhasil diperbarui.</div>';
}
// Hapus user
if(isset($_GET['hapus'])){
    $id=(int)$_GET['hapus'];
    mysqli_query($conn,"DELETE FROM user WHERE id=$id");
    $msg='<div class="alert alert-success">User berhasil dihapus.</div>';
}

$search = isset($_GET['q']) ? mysqli_real_escape_string($conn,$_GET['q']) : '';
$where = $search ? "WHERE username LIKE '%$search%'" : '';
$data = mysqli_query($conn,"SELECT * FROM user $where ORDER BY id");
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><title>Manajemen Akun - GOR Kamandanoe</title>
</head><body>
<?php include "sidebar.php"; ?>
<div class="topbar">
  <div class="topbar-title">&#9679; Manajemen Akun</div>
  <div class="topbar-right">
    <form method="GET" style="display:flex;align-items:center;gap:8px;background:#f5f6fa;border:1px solid #e8eaed;border-radius:8px;padding:8px 14px;">
      <span>&#128269;</span>
      <input type="text" name="q" placeholder="Cari pengguna..." value="<?= htmlspecialchars($search) ?>" style="border:none;background:transparent;font-size:13px;outline:none;width:180px;">
    </form>
  </div>
</div>
<div class="page-body">
  <?= $msg ?>
  <div class="card-box">
    <div class="card-box-header">
      <span class="card-box-title">Daftar Pengguna Terdaftar</span>
      <button class="btn-action btn-primary" onclick="document.getElementById('modal-tambah').classList.add('show')">+ Tambah</button>
    </div>
    <div class="card-box-body">
      <table class="data-table">
        <thead><tr><th>#</th><th>Username</th><th>Role</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php $no=1; while($d=mysqli_fetch_array($data)): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td style="color:#7b8fa6;"><?= htmlspecialchars($d['username']) ?></td>
          <td><span class="badge <?= $d['role']=='admin'?'badge-orange':'badge-blue' ?>"><?= ucfirst($d['role']) ?></span></td>
          <td>
            <button class="btn-action btn-secondary btn-sm" onclick="openEdit(<?= $d['id'] ?>,'<?= htmlspecialchars($d['username'],ENT_QUOTES) ?>','<?= $d['role'] ?>')">&#9998; Edit</button>
            <a href="?hapus=<?= $d['id'] ?>" class="btn-action btn-danger btn-sm" onclick="return confirm('Yakin hapus user ini?')">&#128465; Hapus</a>
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
  <div class="modal-box">
    <h3>+ Tambah User Baru</h3>
    <form method="POST">
      <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
      <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
      <div class="form-group"><label>Role</label>
        <select name="role" class="form-control">
          <option value="user">User</option><option value="admin">Admin</option>
        </select>
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
  <div class="modal-box">
    <h3>&#9998; Edit User</h3>
    <form method="POST">
      <input type="hidden" name="id" id="edit-id">
      <div class="form-group"><label>Username</label><input type="text" name="username" id="edit-username" class="form-control" required></div>
      <div class="form-group"><label>Password Baru <small style="color:#7b8fa6;">(kosongkan jika tidak diubah)</small></label><input type="password" name="password" class="form-control"></div>
      <div class="form-group"><label>Role</label>
        <select name="role" id="edit-role" class="form-control">
          <option value="user">User</option><option value="admin">Admin</option>
        </select>
      </div>
      <div class="modal-actions">
        <button type="button" class="btn-action btn-secondary" onclick="document.getElementById('modal-edit').classList.remove('show')">Batal</button>
        <button type="submit" name="aksi_edit" class="btn-action btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
function openEdit(id,username,role){
  document.getElementById('edit-id').value=id;
  document.getElementById('edit-username').value=username;
  document.getElementById('edit-role').value=role;
  document.getElementById('modal-edit').classList.add('show');
}
document.querySelectorAll('.modal-overlay').forEach(m=>m.addEventListener('click',function(e){if(e.target===this)this.classList.remove('show')}));
</script>
</div></body></html>
