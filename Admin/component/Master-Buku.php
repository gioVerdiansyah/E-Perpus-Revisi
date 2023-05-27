<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../login-admin.php");
    exit;
}

$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
if (isset($id)) {
    mysqli_query($db, "DELETE FROM buku WHERE id = $id");
}

$pagenation = new Pagenation(10, "buku", 1);

$books = mysqli_query($db, "SELECT * FROM buku ORDER BY id ASC LIMIT {$pagenation->dataPerhalaman()}");
?>

<style>
    .side-bar {
        height: 100% !important;
        box-shadow: none !important;
    }

    main {
        height: max-content !important;
    }
</style>

<link rel="stylesheet" href="CSS/style-content.css">
<script src="JS/jquery-3.6.3.min.js"></script>
<script src="JS/script.js"></script>
<div class="title">
    <h1>Buku</h1>
    <hr>
    <h2>Data Buku <img src="../Assets/angle-small-right.svg" alt=""></h2>
    <h3>Master-Buku</h3>
</div>
<!-- ini.isi -->
<div class="card-wrapper penulis">
    <a href="database/insert.php" rel="noopener noreferrer">
        <button class="tambah"><i class="fa-solid fa-plus"></i>Tambah</button>
    </a>
    <div class="data-wrapper">
        <div class="data-indicator">
            <div class="data-entries">
                <p>show</p>
                <select id="selection" name="selection"
                    onchange="
                    let value = $(this).val();
                    $('#isi-data').load('component/result/index.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
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
                    'component/result/index.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
                )">
            </div>
        </div>
        <!-- isi data -->
        <div class="isi-data" id="isi-data">
            <div class="data">
                <table width="100%" cols="7">
                    <thead width="100%">
                        <th>NO</th>
                        <th>THUMBNAIL</th>
                        <th>JUDUL BUKU</th>
                        <th>KATEGORI</th>
                        <th>PENULIS</th>
                        <th>PENERBIT</th>
                        <th>ACTION</th>
                    </thead>
                    <tbody width="100%">
                        <?php
                        $id = 1;
                        foreach ($books as $book):
                            ?>
                            <tr>
                                <td>
                                    <?= $id ?>
                                </td>
                                <td>
                                    <img src="Temp/<?= $book['image'] ?>" alt="Thumbnail" height="70">
                                </td>
                                <td class="limit">
                                    <p>
                                        <?= $book['judul_buku'] ?>
                                    </p>
                                </td>
                                <td>
                                    <?= $book['kategori'] ?>
                                </td>
                                <td class="limit">
                                    <?= $book['penulis'] ?>
                                </td>
                                <td class="limit">
                                    <?= $book['penerbit'] ?>
                                </td>
                                <td>
                                    <a href="database/update.php?id=<?= $book['id'] ?>"><i
                                            class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <button class="member" onclick="
                                    let isDelete = confirm('Apakah anda yakin ingin menghapus buku: <?= $book['judul_buku'] ?>?');
                                    if(!isDelete){
                                        return;
                                    }
                                    $.post('component/Master-Buku.php', { 
                                        id: '<?= $book['id'] ?>'
                                     });
                                     alert('Data berhasil dihapus!');
                                     $('#isi-data').load('component/result/index.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())
                                "><i class="fa-solid fa-delete-left"></i>
                                    </button><br>
                                    <button onclick="
                                $('.popup').load('../Welcome/component/result/fraction_group.php?bukid=<?= $book['id'] ?> #pop-up');
                                $('.popup').removeAttr('hidden');
                                "><i class="fa-solid fa-chart-simple"></i>Detail
                                    </button>
                                </td>
                            </tr>
                            <?php
                            $id++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="description">
                <p>Showing
                    <?= $id -= 1; ?> of 10 entries
                </p>
                <div class="pagination">
                    <p class="amount-of-data">1</p>
                    <?php if ($pagenation->halamanAktif() < $pagenation->jumlahHalaman()): ?>
                        <button onclick="
                    $('.isi-data').load(
                        'component/result/index.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())'
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