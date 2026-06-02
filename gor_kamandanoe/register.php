<?php

include "koneksi.php";

if (isset($_POST['register'])) {


    $username = $_POST['username'];
    $noHp = $_POST['noHp'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hitung = strlen($_POST['password']);

    $data = mysqli_query(
        $conn,
        "SELECT username from user"
    );


    $cek = mysqli_num_rows($data);

    if ($cek > 0) {
        while ($row = mysqli_fetch_array($data)) {
            if ($username == $row['username']) {
                echo "
                    <script>
                        alert('Username sudah digunakan');
                        window.location='register.php';
                    </script>
                    ";
            }
        }
    }

    if ($hitung < 8) {
        echo "
            <script>
                alert('password minimal 8 karakter');
                window.location='register.php';
            </script>
            ";
    }

    mysqli_query(
        $conn,
        "INSERT INTO user VALUES(
        NULL,
        '$username',
        '$noHp',
        '$email',
        '$password',
        'user'
    )"
    );

    echo "
    <script>
        alert('Registrasi berhasil');
        window.location='login.php';
    </script>
    ";
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Register</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <div class="form-container">

        <h2>DAFTAR</h2>

        <form method="POST">



            <input type="text" name="username" class="input" placeholder="Username" required>

            <input type="text" name="noHp" class="input" placeholder="Ex: 082299998888" required>

            <input type="text" name="email" class="input" placeholder="user@gmail.com" required>

            <input type="password" name="password" class="input" placeholder="Password" required>

            <button type="submit" name="register" class="button">
                DAFTAR
            </button>

        </form>

        <div class="link">
            Sudah punya akun?
            <a href="login.php">Login</a>
        </div>

    </div>

</body>

</html>