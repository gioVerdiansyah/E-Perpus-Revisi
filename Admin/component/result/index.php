<?php
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../index.php");
    exit;
}

$page = new Pagenation($_GET['lim'], "buku", $_GET['page']);


$keyword = $_GET["key"];

$books = mysqli_query($db, "SELECT * FROM buku WHERE
judul_buku LIKE '%$keyword%' OR kode_buku LIKE '$keyword' OR penulis LIKE '$keyword%' OR penerbit LIKE '$keyword%' ORDER BY id DESC LIMIT {$page->awalData()},{$page->dataPerhalaman()}");
;
?>
<!-- isi data -->
<script src="JS/jquery-3.6.3.min.js"></script>
<div class="isi-data">
    <div class="data">
        <table width="100%">
            <thead width="100%">
                <th>NO</th>
                <th>THUMBNAIL</th>
                <th>JUDUL <br> BUKU</th>
                <th>KODE <br> BUKU</th>
                <th>STOCK <br> BUKU</th>
                <th>KATEGORI</th>
                <th>PENULIS</th>
                <th>PENERBIT</th>
                <th>ACTION</th>
            </thead>
            <tbody width="100%" cellspacing="10">
                <?php
                $id = 1;
                foreach ($books as $book):
                    ?>
                    <tr cellspacing="10">
                        <td>
                            <?= $id ?>
                        </td>
                        <td>
                            <img src="Temp/<?= $book['image'] ?>" alt=" Thumbnail" height="70">
                        </td>
                        <td class="limit">
                            <?= $book['judul_buku'] ?>
                        </td>
                        <td>
                            <p>
                                <?= $book['kode_buku'] ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= getStock($book['judul_buku']) ?>
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
                            <!-- delete -->
                            <button class="edit" onclick="$('.popup').load('database/update.php?id=<?= $book['id'] ?>')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="delete" onclick="
                                    Peringatan.konfirmasi('Apakah anda yakin ingin menghapus buku <?= $book['judul_buku'] ?>?', function(isTrue){
                                        if(isTrue){
                                            $.post('component/Master-Buku.php', { 
                                                id: '<?= $book['id'] ?>'
                                            });
                                            Peringatan.sukses('Buku <?= $book['judul_buku'] ?> berhasil di HAPUS');
                                            $('#isi-data').load('component/result/index.php?lim=' + $('#selection').val() + '&&page=<?= $page->halamanAktif() ?>&&key=<?= $keyword ?>')
                                        }
                                    });
                                "><i class="fa-solid fa-delete-left"></i>
                            </button><br>
                            <!-- detail -->
                            <button onclick="
                                $('.popup').load('../Welcome/component/result/fraction_group.php?bukid=<?= $book['id'] ?>');
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
            <?= $id -= 1; ?> of
            <?= $page->dataPerhalaman() ?> entries
        </p>

        <!-- Pagenation -->

        <div class="pagination">
            <?php if ($page->halamanAktif() > 1): ?>
                <button class="left" onclick="
                $('.isi-data').load(
                    'component/result/index.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $keyword ?>'
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
                        'component/result/index.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $keyword ?>'
                    )">
                    Next
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            <?php endif ?>
        </div>
    </div>
</div>