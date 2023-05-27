<?php
require_once __DIR__ . '/vendor/autoload.php';
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../../login-admin.php");
    exit;
}

$dataPerHalaman = $_GET['lim'];
$jumlahData = count(query("SELECT * FROM peminjam"));
$jumlahHalaman = ceil($jumlahData / $dataPerHalaman);
$halamanAktif = (isset($_GET['page'])) ? $_GET['page'] : 1;
$awalData = ($dataPerHalaman * $halamanAktif) - $dataPerHalaman;


$keyword = $_GET["key"];

$read = mysqli_query($db, "SELECT * FROM peminjam WHERE
bukunya LIKE '%$keyword%' OR username LIKE '$keyword%' OR tanggal_pinjam LIKE '$keyword%' ORDER BY id DESC LIMIT $awalData, $dataPerHalaman");

$html = '
<table width="100%">
<tr>
        <th>NO</th>
        <th>PEMINJAM</th>
        <th>PP PEMINJAM</th>
        <th>JUDUL BUKU</th>
        <th>KATEGORI</th>
        <th>TGL PINJAM</th>
        <th>TGL PENGEMBALIAN</th>
    </tr>';
$id = 1;
foreach ($read as $reader) {
    $html .= '
<tr cellspacing="10">
    <td>
        <p>
            ' . $id . '
</p>
</td>
<td>
    <p>
        ' . $reader["username"] . '
    </p>
</td>
<td>
    <img src="../../../.temp/' . $reader["pp_user"] . '" alt="photo profile peminjam" height="70">
</td>
<td class="limit">
    <p>
        ' . $reader["bukunya"] . '
    </p>
</td>
<td class="limit center">
    <p>
        ' . $reader["kategori"] . '
    </p>
</td>
<td>
    <p>
        ' . $reader["tanggal_pinjam"] . '
    </p>
</td>
<td>
    <p>
        ' . $reader["tanggal_pengembalian"] . '
    </p>
</td>
</tr>
';
    $id++;
}
;
$html .= '
</table>
<link rel="stylesheet" href="../../CSS/cetak.css">
';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Data_Laporan_Peminjam_Buku.pdf", "I");