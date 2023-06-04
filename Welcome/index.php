<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../index.php");
    exit;
}


$id = $_COOKIE["UsrLgnMJYNiSeQlThRuE"];
$key = $_COOKIE["UIDprpsMJYNisThroe"];

// cek username berdasarkan id
$result = mysqli_query(mysqli_connect("localhost", "root", "", "perpustakaan_sekolah"), "SELECT * FROM loginuser WHERE id='$id'");
$row = mysqli_fetch_assoc($result); //ambil
$username = '';

// cek COOKIE dan username
if ($key === hash("sha512", $row["username"])) {
    $username = $row["username"];
} else {
    header("Location: ../logout-user.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-control" content="no-cache">
    <title>Welcome User~</title>
    <script src="https://kit.fontawesome.com/80e53aea6c.js" crossorigin="anonymous"></script>
    <script src="../Admin/JS/jquery-3.6.3.min.js"></script>
    <script src="JS/script.js"></script>
    <link rel="stylesheet" id="css" href="CSS/User/index.css" />
    <link rel="stylesheet" href="../Admin/CSS/alert.css">
    <link rel="stylesheet" id="dm" />
    <link rel="stylesheet" href="CSS/User/Buku.css">
    <link rel="stylesheet" href="CSS/User/Persetujuan.css">
    <link rel="stylesheet" href="CSS/User/Fraction_group.css" />
    <link rel="stylesheet" href="../Admin/CSS/alert.css">
    <script src="../Admin/JS/alert.js"></script>
</head>

<body>
    <div class="popup" hidden></div>

    <nav>
        <ul>
            <li>
                <h1 id="darkmode" class="light"><i class="fa-solid fa-moon"></i></h1>
                <button id="hamburger"><span></span><span></span><span></span></button>
            </li>
            <li>
                <img src="../Assets/logo-smk.png" alt="Icon Skansa">
                <h1 class="title">E-Perpus Skansa</h1>
            </li>
            <li>
                <div>
                    <h2>
                        <?= ucfirst($username) ?>
                    </h2>
                    <p>Anggota</p>
                </div>
                <img src="../.temp/<?= $row['gambar'] ?>" alt="" />
                <div class="logout">
                    <button onclick="
                        Peringatan.konfirmasi('Apakah anda yakin ingin logout?', (isTrue)=>{
                            if(isTrue){
                                window.location.href = '../logout-user.php';
                            }
                        })
                    "><i class="fi fi-rr-sign-out-alt"></i>
                        Logout</button>
            </li>
            </div>
            </li>
        </ul>
    </nav>

    <header>
        <ul>
            <li id="home" class="on"><i class="fa-solid fa-house"></i>Home</li>
            <li id="cari-buku"><i class="fa-solid fa-book"></i>Cari Buku</li>
            <li id="riwayat">
                <i class="fa-solid fa-clock-rotate-left"></i>Peminjaman
            </li>
            <li id="feedback" onclick="
              $('main').load('component/feedback.php?usr_nm=<?= urlencode($username) ?>');
              $('*').removeClass('on');
              $(this).addClass('on');"><i class="fa-solid fa-comments"></i>Feedback</li>
        </ul>
    </header>

    <main></main>

    <footer>
        <h2>
            COPYRIGHT &#x24B8;
            <a href="https://smkn1mejayan.sch.id/" target="_blank">
                2023 SMKN 1 MEJAYAN Kab. MADIUN</a>,All rights Reserved
        </h2>
    </footer>
</body>

</html>