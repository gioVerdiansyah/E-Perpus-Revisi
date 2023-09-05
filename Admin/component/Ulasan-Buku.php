<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../index.php");
	exit;
}

$pagenation = new Pagenation(10, "ulasan", 1);

$query = "SELECT 
	ulasan.*,
	buku.judul_buku,
	buku.image,
	buku.penulis
FROM
    ulasan
INNER JOIN
    buku
ON
    ulasan.buku_id = buku.id
INNER JOIN
    loginuser
ON
    ulasan.user_id = loginuser.id
LIMIT
    10
";

$ulasan = mysqli_query($db, $query);
?>

<link rel="stylesheet" href="CSS/style-content.css">
<div class="title">
	<h1>Master Data</h1>
	<hr />
	<h2>Ulasan <img src="../Assets/angle-small-right.svg" alt="" /></h2>
	<h3>Buku</h3>
</div>
<!-- ini.isi -->
<div class="card-wrapper penulis">
	<div class="data-wrapper">
		<div class="data-indicator mt-0">
			<div class="data-entries">
				<p>show</p>
				<select id="selection" name="selection" onchange="
						$('#isi-data').load('component/result/ulasan.php?lim=' + $(this).val() + '&&key=' + $('#search').val())">
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
						'component/result/ulasan.php?lim=' + $('#selection').val() + '&&key=' + $(this).val()
					)" />
			</div>
		</div>
		<!-- isi data -->
		<div class="isi-data" id="isi-data">
			<div class="data">
				<ul>
					<?php
					$id = 1;
					foreach ($ulasan as $row):
						$id++;
						?>
						<li>
							<img src="Temp/<?= $row['image'] ?>"
								alt="ini adalah gambar dari buku <?= $row['judul_buku'] ?>" />
							<div class="wrapper-books">
								<div class="row">
									<h2>
										<?= $row['judul_buku'] ?>
									</h2>
									<div class="stars-wrapper">
										<?php
										for ($i = 1; $i <= $row['rating']; $i++) {
											if ($i <= 5) {
												echo '<span class="fa fa-star checked"></span>';
											}
										}
										?>
										<p class="respon">
											<?php
											switch ($row['rating']) {
												case 1:
												case 2:
													echo "tidak di rekomendasikan";
													break;
												case 3:
												case 4:

													echo "cukup baik";
													break;
												case 5:
												default:
													echo "sangat di rekomendasikan";
													break;
											}
											?>
										</p>
									</div>
								</div>
								<button onclick="
								$('#komentar-buku').load('component/result/ulasan.php?bukid=<?= $row['buku_id'] ?>')
							">Komentar user</button>
								<p class="penulis">-
									<?= $row['penulis'] ?>
								</p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
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
						'component/result/result.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())'
					)">
							Next
							<i class="fa-solid fa-angle-right"></i>
						</button>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>