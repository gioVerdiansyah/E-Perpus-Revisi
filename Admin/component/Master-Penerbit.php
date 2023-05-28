<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../login-admin.php");
    exit;
}

$pagenation = new Pagenation(10, "buku", 1);

$penerbit = mysqli_query($db, "SELECT * FROM buku ORDER BY id DESC LIMIT {$pagenation->dataPerhalaman()}");

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
    <h1>Penerbit</h1>
    <hr>
    <h2>Data Penulis <img src="../Assets/angle-small-right.svg" alt=""></h2>
    <h3>Master-Penulis</h3>
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
                    $('#isi-data').load('component/result/penerbit.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
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
                    'component/result/penerbit.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
                )">
            </div>
        </div>
        <!-- isi data -->
        <div class="isi-data" id="isi-data">
            <div class="data">
                <table width="100%">
                    <thead width="100%">
                        <th>NO</th>
                        <th>PENERBIT</th>
                        <th>ACTION</th>
                    </thead>
                    <tbody width="100%" cellspacing="10">
                        <?php
                        $id = 1;
                        foreach ($penerbit as $penerbitnya):
                            ?>
                        <tr cellspacing="10">
                            <td>
                                <p>
                                    <?= $id ?>
                                </p>
                            </td>
                            <td class="limit">
                                <p>
                                    <?= $penerbitnya['penerbit'] ?>
                                </p>
                            </td>
                            <td>
                                <button onclick="
                                $('.popup').load('../Welcome/component/result/fraction_group.php?bukid=<?= $penerbitnya['id'] ?> #pop-up');
                                $('.popup').removeAttr('hidden');
                                "><i class="fa-solid fa-chart-simple"></i>Detail
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
                    <button onclick="
                    $('.isi-data').load('component/result/penerbit.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())'
                    )">
                        Next
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>