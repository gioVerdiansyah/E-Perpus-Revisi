<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../../index.php");
    exit;
}
?>

<link rel="stylesheet" href="CSS/User/Home.css">
<style>
    main {
        padding: 0 !important
    }
</style>

<div class="list-creator">
    <img src="../Assets/bg5-2.svg" alt="list-creator" />
    <ul>
        <li class="title">
            <h1>Creator:</h1>
        </li>
        <li>
            <h1>1. S.G. Verdiansyah</h1>
            <p>Creator of this website</p>
        </li>
        <li>
            <h1>2. Adi .K</h1>
            <p>Support</p>
        </li>
        <li>
            <h1>3. Beta .D.P</h1>
            <p>Administrator</p>
        </li>
    </ul>
</div>

<div class="about-E-perpus">
    <img src="../Assets/bg15-1.svg" alt="about-E-perpus" />
    <ul>
        <li class="title">
            <h1>Tentang E-Perpus ini:</h1>
        </li>
        <li>
            <p>
                Ini adalah E-Perpus yang dibuat oleh siswa SMKN 1 Mejayan
                berjurusan RPL yang bertujuan untuk melatih pengalaman baik di
                bidang Programing Front End, Back End maupun Designer. <br />
                <br />
                Aplikasi ini bertujuan untuk menyediakan akses mudah bagi para
                pengguna untuk membaca buku dengan media E-book. Selain itu,
                E-perpus dilengkapi dengan fitur-fitur seperti sistem pencarian
                buku, serta penyimpanan data pengguna dan buku-buku yang tersedia.
                Dengan menggunakan E-perpus, pengguna dapat dengan mudah membaca
                buku secara online tanpa harus datang ke perpustakaan fisik.
            </p>
        </li>
    </ul>
</div>