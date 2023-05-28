<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../login-admin.php");
    exit;
}

// Menangani POST pinjam
if (isset($_POST['jumlah_pinjam'])) {
    $idP = $_POST['idP'];
    $book_name = $_POST['bukunya'];
    $buku_tersedia = mysqli_query($db, "SELECT jumlah_buku FROM buku WHERE judul_buku = '$book_name'");
    $result = mysqli_fetch_assoc($buku_tersedia);
    $number_buku_tersedia = $result['jumlah_buku'];
    $jumlah_pinjam = $_POST['jumlah_pinjam'];

    // calc
    $jumlah = $number_buku_tersedia - $jumlah_pinjam;
    // update value
    mysqli_query($db, "UPDATE buku SET jumlah_buku = $jumlah WHERE judul_buku = '$book_name'");
    mysqli_query($db, "UPDATE peminjam SET status = true WHERE id = $idP");
}

// Menangani POST delete
if (isset($_POST['idbor'])) {
    $idBor = $_POST['idbor'];
    mysqli_query($db, "DELETE FROM peminjam WHERE id = $idBor");
}

$pagenation = new Pagenation(10, "peminjam", 1);

$borrower = mysqli_query($db, "SELECT * FROM peminjam WHERE status = false ORDER BY id DESC LIMIT 10");

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
    <h1>Peminjam</h1>
    <hr>
    <h2>Data Peminjam <img src="../Assets/angle-small-right.svg" alt=""></h2>
    <h3>Master-Peminjam</h3>
</div>
<div class="download">
    <button onclick="window.location.href = 'component/result/cetak.php?lim=' + $('#selection').val() +
            '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val()"><i class="fi fi-rr-download"></i>
        Download Data
        Peminjaman (PDF)</button>
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
                    $('#isi-data').load('component/result/pinjam.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
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
                    'component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
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
                        <th>JUDUL BUKU</th>
                        <th>JUMLAH PINJAM</th>
                        <th>TGL PINJAM</th>
                        <th>TGL PENGEMBALIAN</th>
                        <th>SETUJUI</th>
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
                                        (<?= getStock($borrowers['bukunya']) ?>)
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
                                <?php if (!$borrowers['status']) { ?>
                                <button class="o" onclick="
                                            Peringatan.menyetujui('Apakah anda ingin menyetujui <?= $borrowers['username'] ?> meminjam buku <?= $borrowers['bukunya'] ?>?', function(isTrue){
                                                if(isTrue){
                                                    Peringatan.sukses('Anda telah menyetujui <?= $borrowers['username'] ?> meminjam buku <?= $borrowers['bukunya'] ?>');

                                                    $.post('component/Data-Peminjam.php', {
                                                        idP: <?= $borrowers['id'] ?>,
                                                        bukunya: '<?= $borrowers['bukunya'] ?>',
                                                        jumlah_pinjam: '<?= $borrowers['jumlah_pinjam'] ?>'
                                                    });
                                                    $('#isi-data').load('component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val());
                                                }else{
                                                    Peringatan.ditolak('Anda telah menolak <?= $borrowers['username'] ?> untuk meminjam buku <?= $borrowers['bukunya'] ?>');
                                                }
                                            });
                                            ">
                                    <i class="fa-regular fa-clock"></i> Menunggu
                                </button>
                                <?php } ?>
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
                    $('#isi-data').load('component/result/pinjam.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())">
                        Next
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>