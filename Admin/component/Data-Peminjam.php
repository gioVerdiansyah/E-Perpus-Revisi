<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../login-admin.php");
    exit;
}

$idRead = (isset($_POST['rid'])) ? $_POST['rid'] : 0;

if (isset($idRead)) {
    mysqli_query($db, "DELETE FROM peminjam WHERE id = $idRead");
}

$pagenation = new Pagenation(10, "peminjam", 1);

$read = mysqli_query($db, "SELECT * FROM peminjam ORDER BY id DESC LIMIT 10");

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
<script src="JS/jquery-3.6.3.min.js"></script>
<script src="JS/script.js"></script>
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
                        <th>KATEGORI</th>
                        <th>TGL PINJAM</th>
                        <th>TGL PENGEMBALIAN</th>
                        <th>ACTION</th>
                    </thead>
                    <tbody width="100%" cellspacing="10">
                        <?php
                        $id = 1;
                        foreach ($read as $reader):
                            ?>
                            <tr cellspacing="10">
                                <td>
                                    <p>
                                        <?= $id ?>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?= $reader['username'] ?>
                                    </p>
                                </td>
                                <td>
                                    <img src="../.temp/<?= $reader['pp_user'] ?>" alt="photo profile peminjam" height="70">
                                </td>
                                <td class="limit">
                                    <p>
                                        <?= $reader['bukunya'] ?>
                                    </p>
                                </td>
                                <td class="limit center">
                                    <p>
                                        <?= $reader['kategori'] ?>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?= $reader['tanggal_pinjam'] ?>
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?= $reader['tanggal_pengembalian'] ?>
                                    </p>
                                </td>
                                <td>
                                    <button class="member" onclick="
                                    $.post('component/Data-peminjam.php', { 
                                        rid: <?= $reader['id'] ?>
                                     });
                                     alert('Data berhasil dihapus!');
                                     $('#isi-data').load('component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())
                                "><i class="fa-solid fa-delete-left"></i>
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