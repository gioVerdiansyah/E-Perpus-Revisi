<?php
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) || !isset($_COOKIE["UIuDSteKukki"]) || !isset($_COOKIE["UNmeKySteKukki"])) {
    header("Location: login-admin.php");
    exit;
}

$_SESSION = []; //menimpa array agar session benar-benar kosong
session_unset();
session_destroy();
//untuk memastikan bahwa session benar-benar hilang


// Hapus COOKIE
setcookie("UIuDSteKukki", "", time() - 1);
setcookie("UNmeKySteKukki", "", time() - 1);
// "" ini set COOKIE menjadi kosong saat di tekan logout, dan time minus itu artinya waktunya mundur


// paksa ke halaman login
header("Location: login-admin.php");
exit;
?>