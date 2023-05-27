<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"])) {
    header("Location: ../login-admin.php");
    exit;
}
$id = (isset($_POST['id'])) ? $_POST['id'] : 0;
$ops = (isset($_POST['ops'])) ? $_POST['ops'] : 'loginuser';

if (isset($id)) {
    mysqli_query($db, "DELETE FROM $ops WHERE id = $id");
}

$pagenation = new Pagenation(10, $ops, 1);

$member = mysqli_query($db, "SELECT * FROM loginuser ORDER BY id DESC LIMIT {$pagenation->dataPerhalaman()}");
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
    <h1>Anggota</h1>
    <hr>
    <h2>Data Anggota <img src="../Assets/angle-small-right.svg" alt=""></h2>
    <h3>Master-Anggota</h3>
</div>
<!-- ini.isi -->
<div class="card-wrapper penulis">
    <p>Tampilkan Anggota berdasarkan: </p>
    <select name="opsi" id="opsi"
        onchange="
                    let val = $(this).val();
                    $('#isi-data').load('component/result/anggota.php?ops=' + val + '&&lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
        <option value="loginuser">Anggota</option>
        <option value="loginadmin">Admin</option>
    </select>
    <div class="data-wrapper">
        <div class="data-indicator">
            <div class="data-entries">
                <p>show</p>
                <select id="selection" name="selection"
                    onchange="
                    let value = $(this).val();
                    $('#isi-data').load('component/result/anggota.php?ops=' + $('#opsi').val() + '&&lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
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
                    'component/result/anggota.php?ops=' + $('#opsi').val() + '&&lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
                )">
            </div>
        </div>
        <!-- isi data -->
        <div class="isi-data" id="isi-data">
            <div class="data">
                <table width="100%" cols="7">
                    <thead width="100%">
                        <th>NO</th>
                        <th>PP USER</th>
                        <th>NICKNAME USER</th>
                        <th>ACTION</th>
                    </thead>
                    <tbody width="100%">
                        <?php
                        $id = 1;
                        foreach ($member as $members):
                            ?>
                            <tr>
                                <td>
                                    <?= $id ?>
                                </td>
                                <td>
                                    <img src="../.temp/<?= $members['gambar'] ?>" alt="Thumbnail" height="70">
                                </td>
                                <td class="limit">
                                    <p>
                                        <?= $members['username'] ?>
                                    </p>
                                </td>
                                <td>
                                    <button class="member" onclick="
                                    let isDelete = confirm('Apakah anda yakin ingin mengahpus akun: <?= $members['username'] ?>?');
                                    if(!isDelete){
                                        return;
                                    }
                                    $.post('component/Master-Anggota.php', { 
                                        id: '<?= $members['id'] ?>'
                                     });
                                     alert('Data berhasil dihapus!');
                                     $('#isi-data').load('component/result/anggota.php?ops=' + $('#opsi').val() + '&&lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())
                                "><i class="fa-solid fa-delete-left"></i></button>
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
                        'component/result/anggota.php?ops=' + $('#opsi').val() + '&&lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())'
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