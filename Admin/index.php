<?php
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) || !isset($_COOKIE["USRADMNLGNISEQLTHROE"]) || !isset($_COOKIE["UISADMNLGNISEQLTRE"])) {
    header("Location: login-admin.php");
    exit;
}


$id = $_COOKIE["USRADMNLGNISEQLTHROE"];
$key = $_COOKIE["UISADMNLGNISEQLTRE"];

// cek username berdasarkan id
$result = mysqli_query(mysqli_connect("localhost", "root", "", "perpustakaan_sekolah"), "SELECT * FROM loginadmin WHERE id='$id'");
$row = mysqli_fetch_assoc($result); //ambil
$username = '';

// cek COOKIE dan username
if ($key === hash("sha512", $row["username"])) {
    $username = $row["username"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomePage Admin</title>
    <link rel="stylesheet" href="CSS/sidebar.css">
    <link rel="stylesheet" href="CSS/alert.css">
    <link rel="stylesheet" id="dm">
    <link rel="stylesheet" href="../Welcome/CSS/User/Fraction_group.css">
    <script src="https://kit.fontawesome.com/981acb16d7.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="popup" hidden></div>

    <div class="side-bar">
        <a href="" class="icon">
            <!-- logo -->
            <div class="kotak"></div>
            <h1>E-perpus Inpres</h1>
        </a>
        <ul>
            <li class="dashboard active">
                <h2><i class="fa-solid fa-house"></i> Dashboard</h2>
            </li>
            <li class="dropdown">
                <div class="master-data">
                    <h2><i class="fa-solid fa-database"></i>Master data <span class="notif">5</span></h2>
                    <div class="dropdown-icon">
                        <h2><i class="fa-sharp fa-solid fa-angle-down"></i></h2>
                    </div>
                </div>
                <ul class="list-master-data" id="list-master-data">
                    <li id="penulis">
                        <h3>Penulis</h3>
                    </li>
                    <li id="penerbit">
                        <h3>Penerbit</h3>
                    </li>
                    <li id="kategori">
                        <h3>Kategori</h3>
                    </li>
                    <li id="buku">
                        <h3>Buku</h3>
                    </li>
                    <li id="anggota">
                        <h3>Anggota</h3>
                    </li>
                </ul>
            </li>
            <li id="persetujuan">
                <h2><i class="fa-solid fa-chart-simple"></i>Persetujuan</h2>
            </li>
        </ul>
    </div>
    <!-- content -->
    <main>
        <div class="content-wrapper">
            <header class="heading">
                <div class="action">
                    <button id="humberger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <button id="darkmode" class="light"><i class="fa-solid fa-moon"></i></button>
                </div>
                <div class="profile">
                    <div class="text">
                        <h2 class="name">
                            <?= ucfirst($username) ?>
                        </h2>
                        <h3 class="type">Admin</h3>
                    </div>
                    <!-- photo profile user -->
                    <img src="Temp/<?= $row['gambar'] ?>" alt="photo profile">
                    <div class="dropdown-profile">
                        <ul>
                            <li><a href="logout-admin.php" onclick="return confirm('Sure?');"><i
                                        class="fi fi-rr-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </header>
            <div class="content">
                <!-- isi konten -->
            </div>
        </div>
        <footer>
            <h2>COPYRIGHT &#x24B8; <a href="https://smkn1mejayan.sch.id/" target="_blank"> 2023 SMKN 1 MEJAYAN Kab.
                    MADIUN</a>,All rights Reserved</h2>
        </footer>
    </main>
</body>
<script src="JS/jquery-3.6.3.min.js"></script>
<script src="JS/script.js"></script>
<script src="JS/alert.js"></script>

</html>