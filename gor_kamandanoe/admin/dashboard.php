<?php
session_start();

if($_SESSION['role'] != "admin"){
    header("location:../login.php");
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="navbar">

    <span class="logo">
        Dashboard Admin
    </span>

    <div class="menu">
        <a href="../logout.php">Logout</a>
    </div>

</div>

<div class="container">

    <div class="row">

        <div class="card">
            <h2>
                <a href="data_user.php">
                    Data User
                </a>
            </h2>
        </div>

        <div class="card">
            <h2>
                <a href="data_booking.php">
                    Data Booking
                </a>
            </h2>
        </div>

        <div class="card">
            <h2>
                <a href="data_turnamen.php">
                    Data Turnamen
                </a>
            </h2>
        </div>

    </div>

</div>

</body>
</html>