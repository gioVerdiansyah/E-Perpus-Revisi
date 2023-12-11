<?php
require_once __DIR__ . '../../../../vendor/autoload.php';
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../../login-admin.php");
	exit;
}

$query = "SELECT
peminjam.*,
buku.judul_buku,
loginuser.username,
data_user.gambar
FROM
peminjam
LEFT JOIN buku ON peminjam.buku_id = buku.id
LEFT JOIN loginuser ON peminjam.user_id = loginuser.id
LEFT JOIN data_user ON loginuser.id = data_user.user_id
WHERE
peminjam.status IN ('1', '2')
ORDER BY
peminjam.id DESC
";

$data = mysqli_query($db, $query);


// denkripsi
$decodedData = base64_decode($_GET['key']);
$iv = substr($decodedData, -16);
$encryptedDataKey = substr($decodedData, 0, -16);
$decryptedData = openssl_decrypt($encryptedDataKey, 'AES-256-CBC', '#XXXMr.Verdi_Admin_407xxx#', OPENSSL_RAW_DATA, $iv);

$html = '';

if ($decryptedData === "!@#)Verdi(*$&%^") {
	$html .= '
  <img src="../../../Assets/logo-smk.png" alt="Logo SMKN 1 Mejayan" width="70" style="margin-left:45%">
  <h1>Data Laporan Peminjaman Buku E-Perpus Skansa</h1>
        <table width="100%" style="padding:0 !impoetant; border: 1px solid grey;">
            <thead>
            <tr>
                <th>NO</th>
                <th>PEMINJAM</th>
                <th>PP PEMINJAM</th>
                <th>JUDUL BUKU</th>
                <th>JUMLAH <br> PINJAM</th>
                <th>TGL PINJAM</th>
                <th>TGL <br> PENGEMBALIAN</th>
                <th>STATUS</th>
                </tr>
            </thead>
            <tbody width="100%" cellspacing="10">
            ';
	$id = 1;
	foreach ($data as $peminjam) {
		$html .= '
                    <tr cellspacing="10">
                        <td>
                            <p>
                                ' . $id . '
                            </p>
                        </td>
                        <td>
                            <p>
                                ' . $peminjam['username'] . '
                            </p>
                        </td>
                        <td>
                            <img src="../../../.temp/' . $peminjam['gambar'] . '" alt="photo profile peminjam" height="70">
                        </td>
                        <td class="limit">
                            <p>
                                ' . $peminjam['judul_buku'] . '
                            </p>
                        </td>
                        <td class="limit center" style="text-align: center;">
                            <p>
                                ' . $peminjam['jumlah_pinjam'] . '
                            </p>
                        </td>
                        <td class="limit" style="text-align:center;">
                            <p>
                                ' . getDay($peminjam["tanggal_pinjam"], true) . ' <br>
                                ' . $peminjam['tanggal_pinjam'] . '
                            </p>
                        </td>
                        <td>
                            <p>
                               ' . getDay($peminjam["tanggal_pengembalian"], false) . ' <br>
                                ' . $peminjam['tanggal_pengembalian'] . '
                            </p>
                        </td>
                        <td>
                        ';
		if ($peminjam["status"] === "1") {
			$html .= '<p class="persetujuan g">
                                    <i class="fa-solid fa-check"></i> Disetujui
                                </p>';
		} elseif ($peminjam["status"] === "2") {
			$html .= '<p class="persetujuan r">
                                    <i class="fa-regular fa-circle-xmark"></i> Ditolak!
                                </p>';
		}
		$html .= '</td>
                    </tr>
                    ' . $id++;
	}
	;
	$html .= '</tbody>
        </table>
    <script src="https://kit.fontawesome.com/981acb16d7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../CSS/cetak.css">';
} else {
	$html .= '
  <h1>Data tidak dapat ditampilkan!!!</h1>
  <p>Coba refresh dan tekan tombol "Download Data Laporan"</p>
  <p style="font-size: 8px">Apakah Anda Hengker Pro Tzy???</p>
  ';
}

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output("Data_laporan_Peminjam_Buku.pdf", "I");