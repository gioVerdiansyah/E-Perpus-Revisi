<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
	header("Location: ../../../index.php");
	exit;
}

$page = new Pagenation($_GET['lim'], "buku", $_GET['page']);

$keyword = mysqli_real_escape_string($db,$_GET["key"]);

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
				foreach ($book as $books):
					?>
					<tr cellspacing="10">
						<td>
							<?= $num ?>
						</td>
						<td>
							<img src="../Admin/Temp/<?= $books["image"] ?>" alt="image_of_book" height="70" />
							<!-- rating -->
							<div class="rating">
								<button class="rate" onclick="
											$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?>&&usr=<?= $user_id ?> #ulasan',() => {
												$('#ulasan').removeAttr('hidden');
												$('.popup').fadeIn(500);
											})
											">
									<?php
									$query = "SELECT AVG(ulasan.rating) as rating FROM buku LEFT JOIN ulasan ON ulasan.buku_id = buku.id WHERE ulasan.buku_id = {$books['id']}";
									$rating = mysqli_fetch_assoc(mysqli_query($db, $query))['rating'];
									if ($rating >= 1) {
										for ($i = 1; $i <= 5; $i++): ?>
											<?php if ($i <= $rating) { ?>
												<i class="fa fa-star checked"></i>
											<?php } else { ?>
												<i class="fa fa-star"></i>
											<?php } ?>
										<?php endfor; ?>
									</button>
								<?php } elseif ($rating < 1) { ?>
									<button class="rate" onclick="
											$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?>&&usr=<?= $user_id ?>&&pjm_id=1 #ulasan',() => {
												$('.popup').removeAttr('hidden');
											})
											">No Rating</button>
								<?php } ?>
							</div>
						</td>
						<td>
							<p>
								<?= $books['judul_buku'] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books["kode_buku"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books['kategori'] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books['penulis'] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books['penerbit'] ?>
							</p>
						</td>
						<td id="detail">
							<!-- pinjam -->
							<button onclick="
								$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&kode_buku=<?= $books['kode_buku'] ?>&&kategori=<?= urlencode($books['kategori']) ?>&&jml=<?= $books['jumlah_buku'] ?>&&uls=5&&pjm_id=1&&usr=1 #peminjaman.insert');
								$('.popup').removeAttr('hidden');
								" id="baca-buku">
								Pinjam Buku
							</button>

							<!-- detail -->
							<button onclick="
								$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?>&&uls=5&&pjm_id=1 #pop-up',()=>{
									$('#pop-up').fadeIn(500);
									$('.popup').removeAttr('hidden');
								});s
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