<?php
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../../login-admin.php");
    exit;
}

$page = new Pagenation($_GET['lim'], "buku", $_GET['page']);


$key = $_GET["key"];

$write = mysqli_query($db, "SELECT * FROM buku WHERE penulis LIKE '%$key%' ORDER BY id DESC LIMIT {$page->awalData()},{$page->dataPerhalaman()}");

?>
<script src="JS/jquery-3.6.3.min.js"></script>
<div class="isi-data" id="isi-data">
    <div class="data">
        <table width="100%">
            <thead width="100%">
                <th>NO</th>
                <th>NAMA PENULIS</th>
                <th>ACTION</th>
            </thead>
            <tbody width="100%" cellspacing="10">
                <?php
                $id = 1;
                foreach ($write as $writer):
                    ?>
                    <tr cellspacing="10">
                        <td>
                            <p>
                                <?= $id ?>
                            </p>
                        </td>
                        <td class="limit">
                            <p>
                                <?= $writer['penulis'] ?>
                            </p>
                        </td>
                        <td>
                            <button onclick="
                                $('.popup').load('../Welcome/component/result/fraction_group.php?bukid=<?= $writer['id'] ?> #pop-up');
                                $('.popup').removeAttr('hidden');
                                "><i class="fa-solid fa-chart-simple"></i>Detail
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
                    'component/result/penulis.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $key ?>'
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
                        'component/result/penulis.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $key ?>'
                    )">
                    Next
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            <?php endif ?>
        </div>
    </div>
</div>