<?php
session_start();
require 'function.php';


$massage = "";
$redirect;

if (isset($_POST["submit"])) {
    global $massage, $redirect;
    if (register($_POST) === 1) {
        $redirect = true;
    } else {
        global $massage;
        $error = $_SESSION["error"];
        $massage = "<p style='color:red;margin:0;'>register gagal karena $error</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login dulu~</title>
    <link rel="stylesheet" href="Welcome/CSS/register.css" />
    <script src="Welcome/JS/script.js"></script>
</head>

<body>
    </div>
    <main>
        <h1>Selamat Datang user baru!<span id="hello">&#128075</span></h1>
        <form action="" method="post" enctype="multipart/form-data">
            <ul>
                <li>
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Masukkan nama" maxlength="64"
                        required />
                </li>
                <li>
                    <label for="pass">Password</label>
                    <input type="password" name="pass" id="pass" maxlength="144" required />
                </li>
                <li>
                    <label for="confirmPass">Confirmation Password :</label>
                    <input type="password" name="confirmPass" id="confirmPass" required>
                </li>
                <li>
                    <label for="gambar">Photo Profile</label>
                    <img src="" width="45" id="img">
                    <input type="file" name="gambar" id="gambar" onchange="
                    let img = document.querySelector('#img');
                    let input = document.querySelector('#gambar');
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                    }
                    reader.readAsDataURL(input.files[0]);
                    ">

                </li>
                <?php echo $massage ?>
                <li>
                    <button type="submit" name="submit">Sign Up</button>
                </li>
            </ul>
        </form>
        <p>Sudah terdaftar menjadi Anggota? <a href="http://localhost/e-perpus/Admin">masuk</a></p>
    </main>
</body>

</html>