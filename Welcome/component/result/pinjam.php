<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../../../index.php");
    exit;
}

$page = new Pagenation($_GET['lim'], "peminjam", $_GET['page']);
echo $page->halamanAktif() . $page->jumlahHalaman();

$keyword = $_GET["key"];
$username = $_GET["usr"];

$result = mysqli_query($db, "SELECT * FROM peminjam WHERE username = '$username' AND (bukunya LIKE '%$keyword%' OR kategori LIKE '$keyword%' OR tanggal_pinjam LIKE '$keyword%' OR tanggal_pengembalian LIKE '$keyword%') ORDER BY id ASC LIMIT {$page->awalData()},{$page->dataPerhalaman()}");
?>
<!-- isi data -->
<div class="isi-data">
    <div class="data">
        <table width="100%">
            <thead width="100%">
                <th>NO</th>
                <th>JUDUL BUKU</th>
                <th>JUMLAH PINJAM</th>
                <th>TGL PINJAM</th>
                <th>TGL PENGEMBALIAN</th>
                <th>STATUS</th>
                <th>UNDUH BUKTI</th>
                <th>HAPUS/CANCEL</th>
            </thead>
            <tbody>
                <?php
                $num = 1;
                foreach ($result as $peminjam):
                    ?>
                    <tr>
                        <td>
                            <p>
                                <?= $num ?>
                            </p>
                        </td>
                        <td>
                            <p class="limit">
                                <?= $peminjam["bukunya"] ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= $peminjam["jumlah_pinjam"] ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= getDay($peminjam["tanggal_pinjam"], true) ?>
                                <?= $peminjam["tanggal_pinjam"] ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?= getDay($peminjam["tanggal_pengembalian"], false) ?> <br>
                                <?= $peminjam["tanggal_pengembalian"] ?>
                            </p>
                        </td>
                        <td>
                            <?php if ($peminjam["status"]) { ?>
                                <p class="persetujuan g">
                                    <i class="fa-solid fa-check"></i> Disetujui
                                </p>
                            <?php } else { ?>
                                <p class="persetujuan o">
                                    <i class="fa-regular fa-clock"></i> Belum
                                </p>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($peminjam["status"]) { ?>
                                <p class="persetujuan g h">
                                    <i class="fa-solid fa-floppy-disk"></i> Unduh
                                </p>
                            <?php } else { ?>
                                <p class="persetujuan o">
                                    <i class="fa-regular fa-clock"></i> Belum Ada
                                </p>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($peminjam["status"]) { ?>
                                <button class="delete" onclick="
                                    Peringatan.konfirmasi('Apakah anda yakin ingin menghapus data yang sudah disetujui?', function(isTrue){
                                        if(isTrue){
                                            $.post('component/Peminjaman.php', {id: <?= $peminjam['id'] ?>})
                                            Peringatan.sukses('Data peminjaman berhasil di HAPUS')
                                            $('#isi-data').load('component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key= <?= $keyword ?>&&usr=<?= $username ?>')
                                        }
                                    });
                                "><i class="fa-solid fa-delete-left"></i>
                                </button>
                            <?php } else { ?>
                                <button class="delete" onclick="
                                    Peringatan.konfirmasi('Apakah anda yakin ingin membatalkan meminjam buku <?= $peminjam['bukunya'] ?>?', function(isTrue){
                                        if(isTrue){
                                            $.post('component/Peminjaman.php', {id: <?= $peminjam['id'] ?>})
                                            Peringatan.sukses('Data peminjaman berhasil di CANCEL')
                                            $('#isi-data').load('component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key= <?= $keyword ?>&&usr=<?= $username ?>')
                                        }
                                    });
                                "><i class="fa-solid fa-delete-left"></i>
                                </button>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $num++; endforeach;
                ?>
            </tbody>
        </table>
    </div>
    <div class="description">
        <p>Showing
            <?= $num -= 1; ?> of
            <?= $page->dataPerhalaman() ?> entries
        </p>

        <!-- Page -->

        <div class="pagination">
            <?php if ($page->halamanAktif() > 1): ?>
                <button class="left" onclick="
                $('.isi-data').load(
                    'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $keyword ?>&&usr=<?= $username ?>'
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
                        'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $keyword ?>&&usr=<?= $username ?>'
                    )">
                    Next
                    <i class="fa-solid fa-angle-right"></i>
                </button>
            <?php endif ?>
        </div>
    </div>
</div>