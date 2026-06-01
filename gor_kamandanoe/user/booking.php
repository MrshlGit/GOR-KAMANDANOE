<?php

session_start();
include "../koneksi.php";

if(isset($_POST['booking'])){

    $nama = $_SESSION['nama'];

    $lapangan = $_POST['lapangan'];
    $tanggal = $_POST['tanggal'];

    $jam2 = $_POST['jam'] + $_POST['durasi'];
    $jam = $_POST['jam'];
    $jamtotal = $jam . " - " . $jam2;
    $durasi = $_POST['durasi'];

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

        <select id="jamMulai" name="jam" class="input">
            <option value="8">08:00</option>
            <option value="9">09:00</option>
            <option value="10">10:00</option>
        </select>

        <select id="jamSelesai" class="input">
            <option value="9">09:00</option>
            <option value="10">10:00</option>
            <option value="11">11:00</option>
        </select>

        <!-- <input type="time"
        name="jam"
        class="input"
        placeholder="Contoh : 19.00"
        required> -->

        <input type="number"
            id="durasi"
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

        <script>
            const jamMulai = document.getElementById('jamMulai');
            const jamSelesai = document.getElementById('jamSelesai');
            const durasi = document.getElementById('durasi');

            function hitungDurasi() {
                const mulai = parseInt(jamMulai.value);
                const selesai = parseInt(jamSelesai.value);
                if (selesai > mulai) {
                durasi.value = selesai - mulai;
                } else {
                durasi.value = 1; // default minimal
                }
            }

            jamMulai.addEventListener('change', hitungDurasi);
            jamSelesai.addEventListener('change', hitungDurasi);
        </script>

</body>
</html>