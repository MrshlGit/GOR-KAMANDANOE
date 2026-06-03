<?php

session_start();
include "../koneksi.php";

$nama = $_SESSION['nama'];

// proses pembayaran
if(isset($_POST['bayar'])){

    $id_booking = $_POST['id_booking'];
    $metode = $_POST['metode'];

    mysqli_query($conn,
    "UPDATE booking SET
    status='Lunas',
    metode_pembayaran='$metode'
    WHERE id_booking='$id_booking'");

    echo "
    <script>
        alert('Pembayaran berhasil');
        window.location='transaksi.php';
    </script>
    ";
}

$data = mysqli_query($conn,
"SELECT * FROM booking
WHERE nama_user='$nama'");

?>

<!DOCTYPE html>
<html>
<head>

    <title>Transaksi</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>
        body {
            background-image: url('../img/bobo.jpg');
            /* background:  hsl(30, 64%, 48%);; */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
/* 
        .payment-box{
            background:white;
            padding:20px;
            border-radius:10px;
            margin-top:20px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        } */

        .payment-btn{
            padding:10px 20px;
            border:none;
            background:#ff6b00;
            color:white;
            cursor:pointer;
            margin-right:10px;
            border-radius:5px;
        }

    </style>

</head>
<body>

<div class="container">

    <h1 style="margin-top:30px;">
        Tagihan Pembayaran
    </h1>

    <?php
    while($d=mysqli_fetch_array($data)){
    ?>

    <div class="payment-box">

        <h3>
            <?php echo $d['lapangan']; ?>
        </h3>

        <p>
            Tanggal :
            <?php echo $d['tanggal']; ?>
        </p>

        <p>
            Jam :
            <?php echo $d['jam'];  ?>
        </p>

        <p>
            Total Bayar :
            <b>
                Rp <?php echo number_format($d['total_bayar']); ?>
            </b>
        </p>

        <p>
            Status :
            <b>
                <?php echo $d['status']; ?>
            </b>
        </p>

        <p>
            Metode :
            <?php echo $d['metode_pembayaran']; ?>
        </p>

        <?php
        if($d['status'] != "Lunas"){
        ?>

        <form method="POST">

            <input type="hidden"
            name="id_booking"
            value="<?php echo $d['id_booking']; ?>">

            <button type="submit"
            name="bayar"
            value="QRIS"
            class="payment-btn"
            onclick="this.form.metode.value='QRIS'">
                Bayar QRIS
            </button>

            <button type="submit"
            name="bayar"
            value="Cash"
            class="payment-btn"
            onclick="this.form.metode.value='Cash'">
                Bayar Cash
            </button>

            <input type="hidden"
            name="metode">

        </form>

        <?php } ?>

    </div>

    <?php } ?>

</div>
</body>
</html>