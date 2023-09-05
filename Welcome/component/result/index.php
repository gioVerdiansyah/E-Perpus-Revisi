<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../../../index.php");
    exit;
}

$page = new Pagenation($_GET['lim'], "buku", $_GET['page']);


$keyword = $_GET["key"];

$book = mysqli_query($db, "SELECT * FROM buku WHERE
judul_buku LIKE '%$keyword%' OR kode_buku LIKE '$keyword' OR penulis LIKE '$keyword%' OR penerbit LIKE '$keyword%' ORDER BY id ASC LIMIT {$page->awalData()},{$page->dataPerhalaman()}");
?>
<!-- isi data -->
<div class="isi-data">
	<div class="data">
		<table width="100%">
			<thead width="100%">
				<th>NO</th>
				<th>THUMBNAIL</th>
				<th>JUDUL BUKU</th>
				<th>KODE BUKU</th>
				<th>KATEGORI</th>
				<th>PENULIS</th>
				<th>PENERBIT</th>
				<th>ACTION</th>
			</thead>
			<tbody width="100%" cellspacing="10">
				<?php
                $num = 1;
                foreach ($book as $book):
                    ?>
				<tr cellspacing="10">
					<td>
						<?= $num ?>
					</td>
					<td>
						<img src="../Admin/Temp/<?= $book["image"] ?>" alt="image_of_book" height="70" />
					</td>
					<td>
						<p>
							<?= $book['judul_buku'] ?>
						</p>
					</td>
					<td>
						<p>
							<?= $book["kode_buku"] ?>
						</p>
					</td>
					<td>
						<p>
							<?= $book['kategori'] ?>
						</p>
					</td>
					<td>
						<p>
							<?= $book['penulis'] ?>
						</p>
					</td>
					<td>
						<p>
							<?= $book['penerbit'] ?>
						</p>
					</td>
					<td id="detail">
						<!-- pinjam -->
						<button onclick="
                                $('.popup').load('component/result/fraction_group.php?bukid=<?= $book['id'] ?>&&bukunya=<?= urlencode($book['judul_buku']) ?>&&kode_buku=<?= $book['kode_buku'] ?>&&kategori=<?= urlencode($book['kategori']) ?>&&jml=<?= $book['jumlah_buku'] ?> #peminjaman');
                                $('.popup').removeAttr('hidden');
                                " id="baca-buku">
							Pinjam Buku
						</button>

						<!-- detail -->
						<button onclick="
                                $('.popup').load('component/result/fraction_group.php?bukid=<?= $book['id'] ?>&&bukunya=<?= urlencode($book['judul_buku']) ?>&&kode_buku=<?= $book['kode_buku'] ?>&&kategori=<?= urlencode($book['kategori']) ?>&&jml=<?= $book['jumlah_buku'] ?> #pop-up', ()=>{
                                    $('#pop-up').fadeIn(500);
                                $('.popup').removeAttr('hidden');
                                });
                                ">
							<i class="fa-solid fa-chart-simple"></i>Detail
						</button>
					</td>
				</tr>
				<?php
                    $num++;
                endforeach;
                ?>
			</tbody>
		</table>
	</div>
	<div class="description">
		<p>Showing
			<?= $num -= 1; ?> of
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