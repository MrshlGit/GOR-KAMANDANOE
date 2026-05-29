<?php
include "../koneksi.php";

$data = mysqli_query($conn,"SELECT * FROM turnamen");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Data Turnamen</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="container">

    <h1>Data Turnamen</h1>

    <table class="table">

        <tr>
            <th>No</th>
            <th>Nama Turnamen</th>
            <th>Tanggal</th>
            <th>Hadiah</th>
        </tr>

        <?php
        $no=1;

        while($d=mysqli_fetch_array($data)){
        ?>

        <tr>

            <td><?php echo $no++; ?></td>
            <td><?php echo $d['nama_turnamen']; ?></td>
            <td><?php echo $d['tanggal']; ?></td>
            <td><?php echo $d['hadiah']; ?></td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>