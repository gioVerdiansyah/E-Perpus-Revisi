<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../../index.php");
    exit;
}

$pagenation = new Pagenation(10, "buku", 1);

$id = $_COOKIE["UsrLgnMJYNiSeQlThRuE"];
$key = $_COOKIE["UIDprpsMJYNisThroe"];

// cek username berdasarkan id
$result = mysqli_query($db, "SELECT * FROM loginuser WHERE id='$id'");
$row = mysqli_fetch_assoc($result); //ambil
$username = '';
$rowGambar = $row['gambar'];

// cek COOKIE dan username
if ($key === hash("sha512", $row["username"])) {
    $username = $row["username"];
}


$buku = mysqli_query($db, "SELECT * FROM buku ORDER BY id ASC LIMIT {$pagenation->dataPerhalaman()}");

// Menangani data POST dari meminjam buku
if (isset($_POST['send'])) {
    $jumlah_pinjam = $_POST['jumlah_pinjam'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $bukunya = $_POST['bukunya'];
    $kategori = $_POST['kategori'];
    date_default_timezone_set('Asia/Jakarta');
    $tanggal_pinjam = date("H:i d/m/Y");
    mysqli_query($db, "INSERT INTO peminjam VALUE('', '$rowGambar','$username', '$bukunya', '$kategori', '$jumlah_pinjam' ,'$tanggal_pinjam', '$tanggal_pengembalian', 0)");
}
?>

<link rel="stylesheet" href="CSS/User/Buku.css">
<link rel="stylesheet" href="CSS/User/Fraction_group.css" />
<link rel="stylesheet" href="../Admin/CSS/alert.css">
<div class="title">
    <h2>Cari Buku</h2>
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
                    $('#isi-data').load('component/result/index.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())
                    if(value != 10){
                        if(window.matchMedia('(max-width: 768px)').matches){
                            $('body').css({'height': '100vh'})
                        }
                    }else{
                        $('body').removeAttr('style');
                    }
                    ">
                <option value="10" selected>10</option>
                <option value="5">5</option>
                <option value="2">2</option>
            </select>
            <p>entries</p>
        </div>
        <div class="data-search">
            <label for="search">Search by</label>
            <input type="search" name="search" id="search" onkeyup="
            $('#isi-data').load(
                    'component/result/index.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
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
                    <th>THUMBNAIL</th>
                    <th>JUDUL BUKU</th>
                    <th>KATEGORI</th>
                    <th>PENULIS</th>
                    <th>PENERBIT</th>
                    <th>ACTION</th>
                </thead>
                <tbody>
                    <?php
                    $num = 1;
                    foreach ($buku as $books):
                        ?>
                        <tr>
                            <td>
                                <p>
                                    <?= $num ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <img src="../Admin/Temp/<?= $books["image"] ?>"
                                        alt="image of book: <?= $books['judul_buku'] ?>" height="70" />
                                </p>
                            </td>
                            <td>
                                <p class="limit">
                                    <?= $books["judul_buku"] ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?= $books["kategori"] ?>
                                </p>
                            </td>
                            <td>
                                <p class="limit">
                                    <?= $books["penulis"] ?>
                                </p>
                            </td>
                            <td>
                                <p class="limit">
                                    <?= $books["penerbit"] ?>
                                </p>
                            </td>
                            <td id="detail">
                                <!-- pinjam -->
                                <button onclick="
                                $('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&kategori=<?= urlencode($books['kategori']) ?>&&jml=<?= $books['jumlah_buku'] ?> #peminjaman');
                                $('.popup').removeAttr('hidden');
                                " id="baca-buku">
                                    Pinjam Buku
                                </button>

                                <!-- detail -->
                                <button onclick="
                                $('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&kategori=<?= urlencode($books['kategori']) ?>&&jml=<?= $books['jumlah_buku'] ?> #pop-up',()=>{
                                    $('#pop-up').fadeIn(500);
                                    $('.popup').removeAttr('hidden');
                                });
                                " id="detail">
                                    <i class="fa-solid fa-chart-simple"></i>Detail
                                </button>
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