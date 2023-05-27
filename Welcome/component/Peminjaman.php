<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../../index.php");
    exit;
}

$pagenation = new Pagenation(10, "peminjam", 1);

$id = $_COOKIE["UsrLgnMJYNiSeQlThRuE"];
$key = $_COOKIE["UIDprpsMJYNisThroe"];

// cek username berdasarkan id
$result = mysqli_query($db, "SELECT * FROM loginuser WHERE id='$id'");
$row = mysqli_fetch_assoc($result); //ambil
$username = '';

// cek COOKIE dan username
if ($key === hash("sha512", $row["username"])) {
    $username = $row["username"];
}

$result = mysqli_query($db, "SELECT * FROM peminjam WHERE username = '$username' ORDER BY id DESC LIMIT {$pagenation->dataPerhalaman()}");
$row = mysqli_fetch_assoc($result);

// Menangani POST method DELETE data 
$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
if (isset($_POST['id'])) {
    mysqli_query($db, "DELETE FROM peminjam WHERE id = $id");
}
?>
<link rel="stylesheet" href="CSS/User/Buku.css">
<link rel="stylesheet" href="CSS/User/Persetujuan.css">
<div class="title">
    <h2>Data buku yang dipinjam</h2>
    <p>Data di update secara otomatis</p>
</div>
</script>
<!-- ini.isi -->
<div class="data-wrapper">
    <div class="data-indicator">
        <div class="data-entries">
            <p>show</p>
            <select id="selection" name="selection" onchange="
                    let value = $(this).val();
                    $('#isi-data').load('component/result/pinjam.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val() + '&&usr=<?= $username ?>')
                    if(value != 10){
                        if(window.matchMedia('(max-width: 768px)').matches){
                            $('body').css({'height': '100vh'})
                        }
                    }else{
                        $('body').removeAttr('style');
                    }
                    ">
                <option value="10">10</option>
                <option value="5">5</option>
                <option value="2">2</option>
            </select>
            <p>entries</p>
        </div>
        <div class="data-search">
            <label for="search">Search by</label>
            <input type="search" name="search" id="search" onkeyup="
            $('#isi-data').load(
                    'component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val() + '&&usr=<?= $username ?>'
                )
            " placeholder="judul buku/penulis/penerbit" />
        </div>
    </div>
    <!-- isi data -->
    <div class="isi-data" id="isi-data">
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
                                    let isDelete = confirm('Apakah anda yakin ingin mengahpus data peminjaman yang telah disetujui?');
                                    if(!isDelete){
                                        return;
                                    }
                                    $.post('component/Peminjaman.php', { 
                                        id: '<?= $peminjam['id'] ?>'
                                     });
                                     Alert.success('Data peminjaman berhasil dihapus!','Success',{displayDuration: 4000, pos: 'top'});

                                     $('#isi-data').load('component/Peminjaman.php #isi-data')
                                "><i class="fa-solid fa-delete-left"></i>
                            </button>
                            <?php } else { ?>
                            <button class="delete" onclick="
                                    $.post('component/Peminjaman.php', { 
                                        id: '<?= $peminjam['id'] ?>'
                                     });
                                     Alert.success('Data peminjaman berhasil dihapus!','Success',{displayDuration: 4000, pos: 'top'});

                                     $('#isi-data').load('component/Peminjaman.php #isi-data')
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
                <?= $num -= 1 ?> of 10 entries
            </p>
            <div class="pagination">
                <p class="amount-of-data">1</p>
                <?php if ($pagenation->halamanAktif() < $pagenation->jumlahHalaman()): ?>
                <button onclick="
                    $('.isi-data').load(
                        'component/result/pinjam.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val() + '&&usr=<?= $username ?>')'
                    )">
                    Next
                    <i class="fa-solid fa-angle-right"></i>
                </button>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>