<?php
session_name("SSILGNPERPUSMEJAYAN");
require 'function.php';
session_start(); //memulai session

if (isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && isset($_COOKIE["UIDprpsMJYNisThroe"])) {
  // cek dulu ada atau tidak
  $id = $_COOKIE["UsrLgnMJYNiSeQlThRuE"];
  $key = $_COOKIE["UIDprpsMJYNisThroe"];

  // cek username berdasarkan id
  $result = mysqli_query($db, "SELECT username FROM loginuser WHERE id='$id'");
  $row = mysqli_fetch_assoc($result); //ambil

  // cek COOKIE dan username
  if ($key === hash("sha512", $row["username"])) {
    $_SESSION["login-user"] = true;
  }
}
// jika SESSION["login"] nya ada maka pindah ke welcome
if (isset($_SESSION["login-user"])) {
  header("Location: Welcome/");
  exit;
}



if (isset($_POST["submit"])) {
  // tampung
  $username = $_POST["username"];
  $pass = $_POST["pass"];

  $result = mysqli_query($db, "SELECT * FROM loginuser WHERE username = '$username'");

  // cek username-nya
  if (mysqli_num_rows($result) === 1) {


    // cek password
    $row = mysqli_fetch_assoc($result);
    if (password_verify($pass, $row["pass"])) {


      // cek input checkbox Remember Me!
      if (isset($_POST["remember"])) {

        // buat COOKIE
        $time = time() + 60 * 60 * 24;
        setcookie("UsrLgnMJYNiSeQlThRuE", $row["id"], $time);
        setcookie("UIDprpsMJYNisThroe", hash("sha512", $row["username"]), $time);
      }


      // cek session
      $_SESSION["login-user"] = true;
      header("Location: Welcome/");
      exit;
    }

  }
  $err = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login dulu~</title>
  <link rel="stylesheet" href="Welcome/CSS/index.css" />
</head>

<body>
  <div class="image"></div>
  <main>
    <h1>Selamat Datang <span id="hello">&#128075</span></h1>
    <form action="" method="post">
      <ul>
        <li>
          <label for="username">Username</label>
          <input type="text" name="username" id="username" placeholder="Masukkan Username" maxlength="64" required />
        </li>
        <li>
          <label for="pass">Password</label>
          <input type="password" name="pass" id="pass" maxlength="144" required />
        </li>
        <li><input type="checkbox" name="remember" checked /> Remember Me</li>
        <li>
          <?php if (isset($err)): ?>
            <p class="error">Username atau Password salah!</p>
          <?php endif ?>
        </li>
        <li>
          <button type="submit" name="submit">Send</button>
        </li>
      </ul>
    </form>
    <p>Belum terdaftar menjadi Anggota? <a href="register.php">daftar</a></p>
  </main>
</body>

</html>