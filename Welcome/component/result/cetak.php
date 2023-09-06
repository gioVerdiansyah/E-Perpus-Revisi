<?php
require_once '../../../Admin/component/result/vendor/autoload.php';
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
	header("Location: ../../../index.php");
	exit;
}

// denkripsi data username
$GETDataUsername = urldecode($_GET['usr']);
$decodedData = base64_decode($GETDataUsername);
$iv = substr($decodedData, -16);
$encryptedDataUsr = substr($decodedData, 0, -16);
$decryptedData = openssl_decrypt($encryptedDataUsr, 'AES-256-CBC', '#XXXMr.Verdi_407xxx#', OPENSSL_RAW_DATA, $iv);

$query = "SELECT
peminjam.*,
buku.*,
loginuser.username,
data_user.gambar
FROM
peminjam
LEFT JOIN buku ON peminjam.buku_id = buku.id
LEFT JOIN loginuser ON peminjam.user_id = loginuser.id
LEFT JOIN data_user ON loginuser.id = data_user.user_id
WHERE
username = '$decryptedData' AND
peminjam.status = '1'
ORDER BY
peminjam.id DESC
";

$data = mysqli_query($db, $query);

$peminjaman = '';
$pengembalian = '';


foreach ($data as $datas) {
	$peminjaman .= $datas['tanggal_pinjam'];
	$pengembalian .= $datas['tanggal_pengembalian'];
}

$html = '<link rel="stylesheet" href="../../CSS/cetak.css">
<div class="struk-perpus">
    <div class="title">
    <img src="../../../Assets/logo-smk.png" alt="Logo SMKN 1 Mejayan" width="70" style="margin-left:45%">
        <h1>STRUK PEMINJAMAN BUKU <br> PERPUSTAKAAN SKANSA</h1>
    </div>
    <div class="isi-data-peminjam">
        <ul style="margin:30px 0 20px 0;padding:0">
            <li>
                <p>
                    Nama Peminjam &nbsp;:
                    ' . ucfirst($decryptedData) . '
                </p>
            </li>
        </ul>
          <h4 style="margin:0 0 5px 0;padding:0">Detail Pinjaman Buku:</h4>
        <table style="margin:0;padding:0">
            <thead>
            <tr>
                <th style="border: 1px solid grey;padding: 3px"><p>NO</p></th>
                <th style="border: 1px solid grey;padding: 3px"><p>Kode <br> Buku</p></th>
                <th style="border: 1px solid grey;padding: 3px"><p>Judul Buku</p></th>
                <th style="border: 1px solid grey;padding: 3px"><p>Tanggal <br> Pinjam</p></th>
                <th style="border: 1px solid grey;padding: 3px"><p>Harus <br> di kembalikan pada</p></th>
                <th style="border: 1px solid grey;padding: 3px"><p>Jumlah <br> Pinjam</p></th>
                </tr>
            </thead>
            <tbody>
                ';
$id = 1;
foreach ($data as $datas) {
	$html .= '
                <tr>
                    <td style="border: 1px solid grey;padding: 3px">
                        <p>
                            ' . $id . '
                        </p>
                    </td>
                    <td style="border: 1px solid grey;padding: 3px">
                        <p>
                            ' . $datas['kode_buku'] . '
                        </p>
                    </td>
                    <td style="width:30%;border: 1px solid grey;padding: 3px; overflow-wrap: anywhere;">
                        <p>
                           ' . $datas['judul_buku'] . '
                        </p>
                    </td>
                    <td style="border: 1px solid grey;padding: 3px">
                    <p>
                       ' . $datas['tanggal_pinjam'] . '
                    </p>
                    </td>
                    <td style="border: 1px solid grey;padding: 3px">
                        <p>
                            <strong>
                               ' . $datas['tanggal_pengembalian'] . '
                            </strong>
                        </p>
                    </td>
                    <td style="border: 1px solid grey;padding: 3px">
                        <p>
                           ' . $datas['jumlah_pinjam'] . '
                        </p>
                    </td>
                </tr>
                ' . $id++;
}

$html .= '</tbody>
        </table>
    </div>
    <div class="note" style="padding: 5px;border:2px solid grey;margin-top:50px;width:max-content">
      <h3 style="margin: 0 0 0 10px">Catatan:</h3>
      <ul style="list-style-type: numeric;padding-left:50px; margin:0">
      ';
if (!$decryptedData) {
	$html .= '
        <li style="margin:0;padding:0">
            <p style="margin:0 0 10px 0;padding:0">Jika data tidak muncul diharap me-refresh dan tekan tombol download struk lagi</p>
        </li>
        ';
} else {
	$html .= '
        <li style="margin:0;padding:0">
          <p style="margin:0 0 10px 0;padding:0">Cek apakah tanggal yang <b>"Harus di kembalikan pada"</b> melebihi tanggal pada saat pengembalian buku</p>
        </li>
        <li style="margin:0;padding:0">
          <p style="margin:0;padding:0">Jika melebihi maka peminjam wajib dikenakan denda berdasarkan perbuku dan perhari</p>
        </li>
        ';
}

$html .= '  </ul>
    </div>
</div>';

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Struk_Peminjam_Buku.pdf", "I");