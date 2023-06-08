<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../index.php");
    exit;
}

// Menangani POST delete
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    mysqli_query($db, "DELETE FROM peminjam WHERE id = $id");
}

$pagenation = new Pagenation(10, "peminjam", 1);

$borrower = mysqli_query($db, "SELECT * FROM peminjam WHERE status IN ('1', '2') ORDER BY id DESC LIMIT 10");

// Enkripsi
$iv = openssl_random_pseudo_bytes(16);
$encryptedData = openssl_encrypt('!@#)Verdi(*$&%^', 'AES-256-CBC', '#XXXMr.Verdi_Admin_407xxx#', OPENSSL_RAW_DATA, $iv);
$encodedDataUsername = urlencode(base64_encode($encryptedData . $iv));

?>
<style>
    .side-bar {
        height: 100% !important;
        box-shadow: none !important;
    }

    main {
        height: max-content !important;
    }

    .isi-data .data table tbody tr td.center {
        text-align: center !important;
    }
</style>
<link rel="stylesheet" href="CSS/style-content.css">
<div class="title">
    <h1>Laporan</h1>
    <hr>
    <h2>Data Laporan <img src="../Assets/angle-small-right.svg" alt=""></h2>
    <h3>Master-Laporan</h3>
</div>
<div class="download">
    <button onclick="window.location.href = 'component/result/cetak.php?key=<?= $encodedDataUsername ?>'"><i
            class="fi fi-rr-download"></i>
        Download Data
        Laporan (PDF)</button>
</div>
<!-- ini.isi -->
<div class="card-wrapper penulis">
    <div class="data-wrapper">
        <div class="data-indicator">
            <div class="data-entries">
                <p>show</p>
                <select id="selection" name="selection"
                    onchange="
                    let value = $(this).val();
                    $('#isi-data').load('component/result/laporan.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
                    <option value="10">10</option>
                    <option value="5">5</option>
                    <option value="2">2</option>
                </select>
                <p>entries</p>
            </div>
            <div class="data-search">
                <label for="search">Search:</label>
                <input type="search" name="search" id="search" onkeyup="
                $('#isi-data').load(
                    'component/result/laporan.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
                )">
            </div>
        </div>
        <!-- isi data -->
        <div class="isi-data" id="isi-data">
            <div class="data">
                <table width="100%">
                    <thead width="100%">
                        <th>NO</th>
                        <th>PEMINJAM</th>
                        <th>PP PEMINJAM</th>
                        <th>JUDUL <br> BUKU</th>
                        <th>JUMLAH <br> PINJAM</th>
                        <th>TGL <br> PINJAM</th>
                        <th>TGL <br> PENGEMBALIAN</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </thead>
                    <tbody width="100%" cellspacing="10">
                        <?php
                        $id = 1;
                        foreach ($borrower as $borrowers):
                            ?>
                            <tr cellspacing="10">
                                <td>
                                    <p>
                                        <?= $id ?>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?= $borrowers['username'] ?>
                                    </p>
                                </td>
                                <td>
                                    <img src="../.temp/<?= $borrowers['pp_user'] ?>" alt="photo profile peminjam"
                                        height="70">
                                </td>
                                <td class="limit">
                                    <p>
                                        <?= $borrowers['bukunya'] ?>
                                        <strong title="Ini adalah jumlah stock buku">
                                            (
                                            <?= getStock($borrowers['bukunya']) ?>)
                                        </strong>
                                    </p>
                                </td>
                                <td class="limit center">
                                    <p>
                                        <?= $borrowers['jumlah_pinjam'] ?>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?= getDay($borrowers["tanggal_pinjam"], true) ?> <br>
                                        <?= $borrowers['tanggal_pinjam'] ?>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?= getDay($borrowers["tanggal_pengembalian"], false) ?> <br>
                                        <?= $borrowers['tanggal_pengembalian'] ?>
                                    </p>
                                </td>
                                <td>
                                    <?php if ($borrowers["status"] === "1") { ?>
                                        <p class="persetujuan g">
                                            <i class="fa-solid fa-check"></i> Disetujui
                                        </p>
                                    <?php } elseif ($borrowers["status"] === "2") { ?>
                                        <p class="persetujuan r h" onclick="
                                        $('.popup').load('../Welcome/component/result/fraction_group.php?bukid=<?= $borrowers['id'] ?> #ditolak-admin', ()=>{$('.popup').removeAttr('hidden')});
                                        ">
                                            <i class="fa-regular fa-circle-xmark"></i> Ditolak!
                                        </p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <button class="delete" onclick="
                                    Peringatan.konfirmasi('Apakah anda yakin ingin menghapusnya?', (isTrue)=>{
                                        if(isTrue){
                                            $.post('component/Data-Laporan.php',{
                                                id: <?= $borrowers['id'] ?>
                                            });
                                            $('#isi-data').load('component/result/laporan.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val());
                                        }
                                    })
                                ">
                                        <i class="fa-solid fa-delete-left"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php $id++; endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class=" description">
                <p>Showing
                    <?= $id -= 1; ?> of 10 entries
                </p>
                <div class="pagination">
                    <p class="amount-of-data">1</p>
                    <?php if ($pagenation->halamanAktif() < $pagenation->jumlahHalaman()): ?>
                        <button
                            onclick="
                    $('#isi-data').load('component/result/laporan.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())">
                            Next
                            <i class="fa-solid fa-angle-right"></i>
                        </button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>