<?php
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../../login-admin.php");
    exit;
}

$page = new Pagenation($_GET['lim'], "peminjam", $_GET['page']);

$keyword = $_GET["key"];

$read = mysqli_query($db, "SELECT * FROM peminjam WHERE
bukunya LIKE '%$keyword%' OR username LIKE '$keyword%' OR tanggal_pinjam LIKE '$keyword%' ORDER BY id DESC LIMIT {$page->awalData()},{$page->dataPerhalaman()}");
?>
<!-- isi data -->
<script src="JS/jquery-3.6.3.min.js"></script>
<div class="isi-data">
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
                foreach ($read as $pinjam):
                    ?>
                    <tr cellspacing="10">
                        <td>
                            <p>
                                <?= $id ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= $pinjam['username'] ?>
                            </p>
                        </td>
                        <td>
                            <img src="../.temp/<?= $pinjam['pp_user'] ?>" alt="photo profile peminjam" height="70">
                        </td>
                        <td class="limit">
                            <p>
                                <?= $pinjam['bukunya'] ?>
                            </p>
                        </td>
                        <td class="limit center">
                            <p>
                                <?= $pinjam['kategori'] ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= $pinjam['tanggal_pinjam'] ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= $pinjam['tanggal_pengembalian'] ?>
                            </p>
                        </td>
                        <td>
                            <button class="member" onclick="
                                    $.post('component/Data-Peminjam.php', { 
                                        rid: <?= $pinjam['id'] ?>
                                     });
                                     alert('Data berhasil dihapus!');
                                     $('#isi-data').load('component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $page->halamanAktif() ?>&&key=' + $('#search').val())
                                "><i class="fa-solid fa-delete-left"></i>
                            </button>
                        </td>
                    </tr>
                    <?php $id++; endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="description">
        <p>Showing
            <?= $id -= 1; ?> of
            <?= $page->dataPerhalaman() ?> entries
        </p>

        <!-- Pagenation -->

        <div class="pagination">
            <?php if ($page->halamanAktif() > 1): ?>
                <button class="left" onclick="
                $('.isi-data').load(
                    'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $keyword ?>'
                )">
                    <i class=" fa-solid fa-angle-left"></i>
                    Prev
                </button>
            <?php endif ?>
            <?php for ($i = 1; $i <= $page->halamanAktif(); $i++): ?>
                <?php if ($i == $page->halamanAktif()): ?>
                    <p class="amount-of-data">
                        <?= $i ?>
                    </p>
                <?php endif ?>
            <?php endfor ?>
            <?php if ($page->halamanAktif() < $page->jumlahHalaman()): ?>
                <button onclick="
                $('.isi-data').load(
                        'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $keyword ?>'
                    )">
                    Next
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            <?php endif ?>
        </div>
    </div>
</div>