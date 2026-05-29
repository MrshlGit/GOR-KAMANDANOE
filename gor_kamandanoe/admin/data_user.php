<?php
include "../koneksi.php";

$data = mysqli_query($conn,"SELECT * FROM user");
?>

<!DOCTYPE html>
<html>
<head>

    <title>Data User</title>

    <link rel="stylesheet" href="../css/style.css">

</head>
<body>

<div class="container">

    <h1>Data User</h1>

    <table class="table">

        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
        </tr>

        <?php
        $no=1;

        while($d=mysqli_fetch_array($data)){
        ?>

        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $d['nama']; ?></td>
            <td><?php echo $d['username']; ?></td>
            <td><?php echo $d['role']; ?></td>
        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>