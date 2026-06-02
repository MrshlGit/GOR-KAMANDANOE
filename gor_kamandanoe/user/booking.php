<?php

session_start();
include "../koneksi.php";

if (isset($_POST['booking'])) {

    $nama = $_SESSION['nama'];

    $lapangan = $_POST['lapangan'];
    $tanggal = $_POST['tanggal'];
    $jam = (int)$_POST['jam'];
    $durasi = (int)$_POST['durasi'];

    // Validasi durasi
    if ($durasi < 1) {
        echo "
        <script>
            alert('Durasi tidak boleh kurang dari 1 jam');
            history.back();
        </script>
        ";
        exit;
    }

    // Hitung jam selesai
    $jam2 = $jam + $durasi;

    // Validasi batas operasional
    if ($jam2 > 22) {
        echo "
        <script>
            alert('Jam selesai melebihi batas operasional (22:00)');
            history.back();
        </script>
        ";
        exit;
    }

    // Cek Tabrakan Jadwal
    $cekBooking = mysqli_query(
        $conn,
        "SELECT jam
         FROM booking
         WHERE tanggal='$tanggal'
         AND lapangan='$lapangan'"
    );

    while ($row = mysqli_fetch_assoc($cekBooking)) {

        // Contoh data:
        // 08:00 - 10:00

        preg_match('/(\d+):00\s-\s(\d+):00/', $row['jam'], $match);

        $jam_mulai_lama   = (int)$match[1];
        $jam_selesai_lama = (int)$match[2];

        // Cek apakah ada tabrakan waktu
        if (
            $jam < $jam_selesai_lama &&
            $jam2 > $jam_mulai_lama
        ) {
            echo "
            <script>
                alert('Lapangan sudah dibooking pada jam tersebut');
                history.back();
            </script>
            ";
            exit;
        }
    }

    // Format jam untuk disimpan
    $jamtotal = sprintf("%02d:00 - %02d:00", $jam, $jam2);

    // Harga lapangan
    if ($lapangan == "Lapangan A") {
        $harga = 50000;
    } elseif ($lapangan == "Lapangan B") {
        $harga = 70000;
    } else {
        $harga = 90000;
    }

    // Total pembayaran
    $total = $harga * $durasi;

    // Simpan booking
    mysqli_query(
        $conn,
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
        )"
    );

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

    <style>
        body {
            background-image: url('../img/bg.jpg');
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

        .form-container {
            background: hsl(220, 33%, 93%);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }
    </style>

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
                <option value="11">11:00</option>
                <option value="12">12:00</option>
                <option value="13">13:00</option>
                <option value="14">14:00</option>
                <option value="15">15:00</option>
                <option value="16">16:00</option>
                <option value="17">17:00</option>
                <option value="18">18:00</option>
                <option value="19">19:00</option>
                <option value="20">20:00</option>
                <option value="21">21:00</option>
            </select>

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