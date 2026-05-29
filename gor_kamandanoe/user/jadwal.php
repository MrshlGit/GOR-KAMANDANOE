<?php
include "../koneksi.php";

$data = mysqli_query($conn,"SELECT * FROM booking");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Jadwal Bermain</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="container">

    <h1>Jadwal Bermain</h1>

    <table class="table">

        <tr>
            <th>No</th>
            <th>Nama User</th>
            <th>Lapangan</th>
            <th>Tanggal</th>
            <th>Jam</th>
        </tr>

        <?php
        $no=1;

        while($d=mysqli_fetch_array($data)){
        ?>

        <tr>

            <td><?php echo $no++; ?></td>
            <td><?php echo $d['nama_user']; ?></td>
            <td><?php echo $d['lapangan']; ?></td>
            <td><?php echo $d['tanggal']; ?></td>
            <td><?php echo $d['jam']; ?></td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>