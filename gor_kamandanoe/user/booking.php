<?php

session_start();
include "../koneksi.php";

if(isset($_POST['booking'])){

    $nama = $_SESSION['nama'];

    $lapangan = $_POST['lapangan'];
    $tanggal = $_POST['tanggal'];
    $jam = (int)$_POST['jam'];
    $durasi = (int)$_POST['durasi'];
    if ($durasi < 1) {
        echo "<script>alert('Durasi tidak boleh kurang dari 1 jam');history.back();</script>";
        exit;
    }

    // Hitung jam selesai
    $jam2 = $jam + $durasi;

    // Validasi jam selesai (misal maksimal jam 22:00)
    if ($jam2 > 22) {
        echo "
        <script>
            alert('Jam selesai melebihi batas operasional (22:00)');
            history.back();
        </script>
        ";
        exit;
    }

    // Format jam tampil
    $jamtotal = $jam . ":00 - " . $jam2 . ":00";

    // Harga lapangan per jam
    if($lapangan == "Lapangan A"){
        $harga = 50000;
    }
    elseif($lapangan == "Lapangan B"){
        $harga = 70000;
    }
    else{
        $harga = 90000;
    }

    // Hitung total bayar
    $total = $harga * $durasi;

    mysqli_query($conn,
"INSERT INTO booking
(
    nama_user,
    lapangan,
    tanggal,
    jam,
    status,
    harga,
    total_bayar,
    metode_pembayaran
)

VALUES
(
    '$nama',
    '$lapangan',
    '$tanggal',
    '$jamtotal',
    'Menunggu',
    '$harga',
    '$total',
    '-'
)");

    echo "
    <script>
        alert('Booking berhasil');
        window.location='transaksi.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html>
<head>

    <title>Booking Lapangan</title>

    <link rel='stylesheet' href='../css/style.css'>

</head>
<body>

<div class="form-container">

    <h2>Booking Lapangan</h2>

    <form method="POST">

        <select name="lapangan" class="input">

            <option>Lapangan A</option>
            <option>Lapangan B</option>
            <option>Lapangan C</option>

        </select>

        <input type="date"
        name="tanggal"
        class="input"
        required>

        <select name="jam" class="input">

            <option value="8">08:00</option>
            <option value="9">09:00</option>
            <option value="10">10:00</option>

        </select>

        <!-- <input type="time"
        name="jam"
        class="input"
        placeholder="Contoh : 19.00"
        required> -->

        <input type="number"
        name="durasi"
        min="1"
        class="input"
        placeholder="Durasi / Jam"
        required>

        <button type="submit"
        name="booking"
        class="button">
            Booking Sekarang
        </button>

    </form>
</div>

</body>
</html>