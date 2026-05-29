<?php
session_start();

if($_SESSION['role'] != "user"){
    header("location:../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard User</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="navbar">

    <span class="logo">
        Dashboard User
    </span>

    <div class="menu">
        <a href="../logout.php">Logout</a>
    </div>

</div>

<div class="container">

    <div class="row">

        <div class="card">
            <h2>
                <a href="booking.php">
                    Booking Lapangan
                </a>
            </h2>
        </div>

        <div class="card">
            <h2>
                <a href="jadwal.php">
                    Jadwal Bermain
                </a>
            </h2>
        </div>

        <div class="card">
            <h2>
                <a href="transaksi.php">
                    Transaksi
                </a>
            </h2>
        </div>

    </div>

</div>

</body>
</html>