<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<style>

* { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
}

body { 
    font-family: 'Plus Jakarta Sans', sans-serif; 
    background: #f0f2f5; 
    display: flex; 
    min-height: 100vh; 
}

.sidebar {
    width: 240px; 
    min-height: 100vh; 
    background: #1a1f2e; 
    color: #fff;
    display: flex; 
    flex-direction: column; 
    position: fixed; 
    top: 0; 
    left: 0; 
    z-index: 100;
}

.sidebar-logo {
    padding: 24px 20px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}

.sidebar-logo .logo-badge {
    background: #e85d04; 
    color: #fff; 
    font-weight: 700; 
    font-size: 15px;
    width: 36px; 
    height: 36px; 
    border-radius: 8px; 
    display: inline-flex;
    align-items: center; 
    justify-content: center; 
    margin-bottom: 8px;
}

.sidebar-logo h2 { 
    font-size: 13px; 
    font-weight: 600; 
    color: #fff; 
    line-height: 1.3; 
}

.sidebar-logo p { 
    font-size: 11px; 
    color: rgba(255, 255, 255, 0.45); 
    margin-top: 2px; 
}

.sidebar-nav { 
    flex: 1; 
    padding: 12px 0; 
}

.nav-label { 
    font-size: 10px; 
    font-weight: 600; 
    color: rgba(255, 255, 255, 0.3); 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    padding: 12px 20px 6px; 
}

.nav-item {
    display: flex; 
    align-items: center; 
    gap: 10px; 
    padding: 10px 20px;
    color: rgba(255, 255, 255, 0.6); 
    text-decoration: none; 
    font-size: 13.5px; 
    font-weight: 500;
    transition: all 0.15s; 
    border-left: 3px solid transparent;
}

.nav-item:hover { 
    background: rgba(255, 255, 255, 0.06); 
    color: #fff; 
}

.nav-item.active { 
    background: rgba(232, 93, 4, 0.15); 
    color: #ff7f3f; 
    border-left-color: #e85d04; 
}

.nav-item .icon { 
    width: 18px; 
    text-align: center; 
    font-size: 16px; 
}

.nav-item .sub-label { 
    font-size: 10px; 
    color: rgba(255, 255, 255, 0.3); 
    margin-left: auto; 
}

.sidebar-bottom {
    padding: 16px 20px; 
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex; 
    align-items: center; 
    gap: 10px;
}

.admin-avatar {
    width: 32px; 
    height: 32px; 
    background: #e85d04; 
    border-radius: 50%;
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 13px; 
    font-weight: 700; 
    color: #fff; 
    flex-shrink: 0;
}

.admin-info p { 
    font-size: 13px; 
    font-weight: 600; 
    color: #fff; 
}

.admin-info span { 
    font-size: 11px; 
    color: rgba(255, 255, 255, 0.4); 
}

.logout-btn {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 8px;
    background: rgba(255,255,255,0.06);
    color: #fff;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.15s;
}

.logout-btn:hover {
    background: rgba(255,255,255,0.12);
    color: #fff;
}

.main-content { 
    margin-left: 240px; 
    flex: 1; 
    min-height: 100vh; 
}

.topbar {
    background: #fff; 
    padding: 14px 28px; 
    display: flex; 
    align-items: center;
    justify-content: space-between; 
    border-bottom: 1px solid #e8eaed; 
    position: sticky; 
    top: 0; 
    z-index: 50;
}

.topbar-title { 
    font-size: 16px; 
    font-weight: 600; 
    color: #1a1f2e; 
}

.topbar-right { 
    display: flex; 
    align-items: center; 
    gap: 12px; 
}

.topbar-search {
    display: flex; 
    align-items: center; 
    gap: 8px; 
    background: #f5f6fa;
    border: 1px solid #e8eaed; 
    border-radius: 8px; 
    padding: 8px 14px;
}

.topbar-search input { 
    border: none; 
    background: transparent; 
    font-size: 13px; 
    outline: none; 
    width: 180px; 
}

.page-body { 
    padding: 28px; 
}

.stat-grid { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 16px; 
    margin-bottom: 24px; 
}

.stat-card {
    background: #fff; 
    border-radius: 12px; 
    padding: 20px;
    border: 1px solid #e8eaed; 
    position: relative; 
    overflow: hidden;
}

.stat-card .stat-icon {
    width: 42px; 
    height: 42px; 
    border-radius: 10px;
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 20px; 
    margin-bottom: 12px;
}

.stat-card .stat-value { 
    font-size: 24px; 
    font-weight: 700; 
    color: #1a1f2e; 
    line-height: 1; 
}

.stat-card .stat-label { 
    font-size: 12px; 
    color: #7b8fa6; 
    margin-top: 4px; 
}

.stat-card .stat-change { 
    font-size: 11px; 
    margin-top: 6px; 
    font-weight: 600; 
}

.stat-card .stat-change.up { 
    color: #22c55e; 
}

.stat-card .stat-change.neutral { 
    color: #7b8fa6; 
}

.card-box { 
    background: #fff; 
    border-radius: 12px; 
    border: 1px solid #e8eaed; 
    overflow: hidden; 
}

.card-box-header {
    padding: 18px 22px; 
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    border-bottom: 1px solid #f0f2f5;
}

.card-box-title { 
    font-size: 15px; 
    font-weight: 600; 
    color: #1a1f2e; 
}

.card-box-body { 
    padding: 0; 
}

table.data-table { 
    width: 100%; 
    border-collapse: collapse; 
}

table.data-table th {
    background: #f8f9fc; 
    padding: 11px 18px; 
    text-align: left;
    font-size: 11.5px; 
    font-weight: 600; 
    color: #7b8fa6; 
    text-transform: uppercase; 
    letter-spacing: 0.5px;
}

table.data-table td { 
    padding: 13px 18px; 
    font-size: 13.5px; 
    color: #344054; 
    border-top: 1px solid #f0f2f5; 
}

table.data-table tr:hover td { 
    background: #fafbfd; 
}

.badge {
    display: inline-flex; 
    align-items: center; 
    padding: 3px 10px;
    border-radius: 20px; 
    font-size: 11.5px; 
    font-weight: 600; 
    white-space: nowrap;
}

.badge-green { 
    background: #dcfce7; 
    color: #16a34a; 
}

.badge-yellow { 
    background: #fef9c3; 
    color: #ca8a04; 
}

.badge-red { 
    background: #fee2e2; 
    color: #dc2626; 
}

.badge-blue { 
    background: #dbeafe; 
    color: #2563eb; 
}

.badge-gray { 
    background: #f1f5f9; 
    color: #64748b; 
}

.badge-orange { 
    background: #ffedd5; 
    color: #ea580c; 
}

.btn-action {
    padding: 6px 14px; 
    border-radius: 7px; 
    border: none; 
    cursor: pointer;
    font-size: 12px; 
    font-weight: 600; 
    transition: all 0.15s; 
    display: inline-flex; 
    align-items: center; 
    gap: 5px;
}

.btn-primary { 
    background: #e85d04; 
    color: #fff; 
}

.btn-primary:hover { 
    background: #c94d02; 
}

.btn-secondary { 
    background: #f0f2f5; 
    color: #344054; 
}

.btn-secondary:hover { 
    background: #e2e5ea; 
}

.btn-danger { 
    background: #fee2e2; 
    color: #dc2626; 
}

.btn-danger:hover { 
    background: #fecaca; 
}

.btn-success { 
    background: #dcfce7; 
    color: #16a34a; 
}

.btn-sm { 
    padding: 4px 10px; 
    font-size: 11.5px; 
}

.form-group { 
    margin-bottom: 16px; 
}

.form-group label { 
    display: block; 
    font-size: 12.5px; 
    font-weight: 600; 
    color: #344054; 
    margin-bottom: 6px; 
}

.form-control {
    width: 100%; 
    padding: 10px 14px; 
    border: 1px solid #d0d5dd;
    border-radius: 8px; 
    font-size: 13.5px; 
    outline: none; 
    transition: border 0.15s;
    font-family: inherit;
}

.form-control:focus { 
    border-color: #e85d04; 
    box-shadow: 0 0 0 3px rgba(232, 93, 4, 0.1); 
}

.modal-overlay {
    display: none; 
    position: fixed; 
    inset: 0; 
    background: rgba(0, 0, 0, 0.45);
    z-index: 1000; 
    align-items: center; 
    justify-content: center;
}

.modal-overlay.show { 
    display: flex; 
}

.modal-box {
    background: #fff; 
    border-radius: 16px; 
    padding: 28px; 
    width: 460px; 
    max-width: 95vw;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
}

.modal-box h3 { 
    font-size: 16px; 
    font-weight: 700; 
    margin-bottom: 18px; 
    color: #1a1f2e; 
}

.modal-actions { 
    display: flex; 
    gap: 10px; 
    justify-content: flex-end; 
    margin-top: 20px; 
}

.two-col { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 20px; 
}

.status-dot { 
    width: 8px; 
    height: 8px; 
    border-radius: 50%; 
    display: inline-block; 
    margin-right: 6px; 
}

.dot-green { 
    background: #22c55e; 
}

.dot-red { 
    background: #ef4444; 
}

.dot-yellow { 
    background: #f59e0b; 
}

.alert { 
    padding: 12px 16px; 
    border-radius: 8px; 
    font-size: 13px; 
    margin-bottom: 16px; 
}

.alert-warning { 
    background: #fef3c7; 
    color: #92400e; 
    border: 1px solid #fde68a; 
}

.alert-success { 
    background: #dcfce7; 
    color: #166534; 
    border: 1px solid #bbf7d0; 
}

.alert-info { 
    background: #dbeafe; 
    color: #1e40af; 
    border: 1px solid #bfdbfe; 
}
</style>

<div class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-badge">GK</div>
    <h2>GOR Kamandanoe</h2>
    <p>Admin Panel</p>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-label">Menu Utama</div>
    <a href="dashboard.php" class="nav-item <?= $current=='dashboard.php'?'active':'' ?>">
      <span class="icon">&#9632;</span> Dashboard
    </a>
    <a href="data_user.php" class="nav-item <?= $current=='data_user.php'?'active':'' ?>">
      <span class="icon">&#9679;</span> Manajemen Akun
    </a>
    <a href="info_layanan.php" class="nav-item <?= $current=='info_layanan.php'?'active':'' ?>">
      <span class="icon">&#9650;</span> Info Layanan
    </a>
    <a href="data_turnamen.php" class="nav-item <?= $current=='data_turnamen.php'?'active':'' ?>">
      <span class="icon">&#9670;</span> Manaj. Turnamen
    </a>
    <a href="Pemesanan.php" class="nav-item <?= $current=='Pemesanan.php'?'active':'' ?>">
      <span class="icon">&#9733;</span> Pemesanan
    </a>
    <a href="pembayaran.php" class="nav-item <?= $current=='pembayaran.php'?'active':'' ?>">
      <span class="icon">&#10022;</span> Pembayaran
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
