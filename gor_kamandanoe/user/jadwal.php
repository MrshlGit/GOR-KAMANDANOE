<?php
session_start();
include "../koneksi.php";

$data = mysqli_query($conn,"
SELECT * FROM booking
ORDER BY tanggal ASC
");

$total_booking = mysqli_num_rows($data);
?>

<!DOCTYPE html>
<html>
<head>

    <title>Jadwal Bermain</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body class="background-jadwal">

<div class="overlay">

    <div class="jadwal-card">

        <div class="judul-box">

            <h1>🏸 Jadwal Bermain</h1>

            <p>GOR Kamandanoe Badminton Center</p>

        </div>

        <div class="info-box">

            <div class="box-info">

                <h2><?php echo $total_booking; ?></h2>

                <p>Total Booking</p>

            </div>

        </div>

        <table class="jadwal-table">

            <tr>

                <th>No</th>
                <th>Nama Pemesan</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Jam Bermain</th>
                <th>Status</th>

            </tr>

            <?php
            $no = 1;

            while($d = mysqli_fetch_array($data)){
            ?>

            <tr>

                <td><?php echo $no++; ?></td>

                <td><?php echo $d['nama_user']; ?></td>

                <td><?php echo $d['lapangan']; ?></td>

                <td>
                    <?php echo date('d-m-Y', strtotime($d['tanggal'])); ?>
                </td>

                <td><?php echo $d['jam']; ?></td>

                <td>

                    <?php
                    if($d['status']=="Lunas"){
                        echo "<span class='status-lunas'>✅ Lunas</span>";
                    }else{
                        echo "<span class='status-menunggu'>⏳ Menunggu</span>";
                    }
                    ?>

                </td>

            </tr>

            <?php } ?>

        </table>

        <div class="btn-area">

            <a href="dashboard.php" class="btn-kembali">
                Kembali ke Dashboard
            </a>

        </div>

    </div>

</div>

</body>
</html>