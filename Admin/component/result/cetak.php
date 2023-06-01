<?php
require_once __DIR__ . '/vendor/autoload.php';
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../../login-admin.php");
    exit;
}

$usr = $_GET['usr'];

$data = mysqli_query($db, "SELECT * FROM peminjam WHERE username = '$usr'");

$peminjaman = '';
$pengembalian = '';


foreach ($data as $datas) {
    $peminjaman .= $datas['tanggal_pinjam'];
    $pengembalian .= $datas['tanggal_pengembalian'];
}


$html = '
<div class="struk-perpus">
<div class="title">
  <h1>STRUK PEMINJAMAN BUKU <br> PERPUSTAKAAN GREEN</h1>
  <p>No struk: 000012231</p>
</div>
<div class="isi-data-peminjam">
  <ul>
    <li>
      <p>
        Nama Peminjam &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 
      </p>
      <p>' . $usr . '</p>
    </li>
    <div>
    <li>
      <p>
        Tanggal Peminjaman &nbsp;&nbsp;&nbsp; : 
      </p>
      <p>' . $peminjaman . '</p>
  </li>
    <li>
      <p>
        Tanggal Pengembalian&nbsp; : 
      </p>
      <p>' . $pengembalian . '</p>
    </li>
  </ul>

  <table>
    <thead>
    <th>NO</th>
    <th>Kode Buku</th>
    <th>Judul Buku</th>
    <th>Jumlah Pinjam</th>
  </thead>
  <tbody>
  ';
$i = 1;
foreach ($data as $row) {
    $html .= '
    <tr>
    <td>
        <p>' . $i++ . '</p>
    </td>
      <td>
        <p>
        ' . $row["kode_buku"] . '
      </p>
    </td>
      <td>
        <p>
        ' . $row["judul_buku"] . '
      </p>
    </td>
      <td>
        <p>' . $row["jumlah_pinjam"] . '</p>
      </td>
    </tr>
    ';
}

$html .= '</tbody>
</table>
</div>
</div>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Struk_Peminjam_Buku.pdf", "I");