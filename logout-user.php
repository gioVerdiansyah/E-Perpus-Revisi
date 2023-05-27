<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();

$_SESSION = []; //menimpa array agar session benar-benar kosong
session_unset();
session_destroy();
//untuk memastikan bahwa session benar-benar hilang


// Hapus COOKIE
setcookie("UsrLgnMJYNiSeQlThRuE", "", time() - 1);
setcookie("UIDprpsMJYNisThroe", "", time() - 1);
// "" ini set COOKIE menjadi kosong saat di tekan logout, dan time minus itu artinya waktunya mundur


// paksa ke halaman login
header("Location: index.php");
exit;
?>