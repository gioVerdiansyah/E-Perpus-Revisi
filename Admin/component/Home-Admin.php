<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../login-admin.php");
    exit;
}

$books = mysqli_query($db, "SELECT * FROM buku ORDER BY id");
$user = mysqli_query($db, "SELECT * FROM loginuser ORDER BY id");
$pinjam = mysqli_query($db, "SELECT * FROM peminjam ORDER BY id");

$jumlahBuku = 0;
$jumlahUser = 0;
$jumlahPeminjam = 0;
foreach ($books as $book) {
    $jumlahBuku++;
}
foreach ($user as $users) {
    $jumlahUser++;
}
foreach ($pinjam as $peminjam) {
    $jumlahPeminjam++;
}
?>

<link rel="stylesheet" href="CSS/M-dashboard.css">
<div class="content">
    <div class="title">
        <h1>Dashboard</h1>
        <hr>
        <h2>Dashboard <img src="../Assets/angle-small-right.svg" alt=""></h2>
        <h3>Index</h3>
    </div>
    <div class="card-wrapper">
        <div class="card1">
            <div class="col1">
                <h2>Welcome Admin Ku!</h2>
                <p>Anda bisa mengatur data buku</p>
            </div>
            <div class="col2">
                <h1>
                    <?= $jumlahBuku ?> Buku
                </h1>
                <button onclick="
                $('.content').load('component/Master-Buku.php');
                $('*').removeClass('active');
                $('#buku h3').addClass('active');
                $('#list-master-data').addClass('list-master-data-onclick');
                $('.side-bar ul li.dropdown .master-data .dropdown-icon').addClass('dropdown-icon-onclick');
                $('.side-bar ul .dropdown .master-data').addClass('addBg');
                ">Atur Data Buku</button>
            </div>
        </div>
        <div class="card2">
            <div class="title">
                <h1>Jumlah Data</h1>
                <p>Data terupdate otomatis</p>
            </div>
            <div class="content-data">
                <ul>
                    <li>
                        <div class="icon">
                            <h1><i class="fa-solid fa-book-open-reader"></i></h1>
                        </div>
                        <div>
                            <h2>
                                <?= $jumlahBuku ?>
                            </h2>
                            <p>Buku</p>
                        </div>
                    </li>
                    <li>
                        <div class="icon">
                            <h1><i class="fa-solid fa-user"></i></h1>
                        </div>
                        <div>
                            <h2>
                                <?= $jumlahUser ?>
                            </h2>
                            <p>Anggota</p>
                        </div>
                    </li>
                    <li>
                        <div class="icon">
                            <h1><i class="fa-solid fa-cubes"></i></h1>
                        </div>
                        <div>
                            <h2>
                                <?= $jumlahPeminjam ?>
                            </h2>
                            <p>Pembaca</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>