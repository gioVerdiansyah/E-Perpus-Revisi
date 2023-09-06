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
$result = mysqli_query($db, "SELECT loginuser.id, loginuser.username, data_user.gambar FROM loginuser LEFT JOIN data_user ON data_user.user_id = loginuser.id WHERE id='$id'");
$row = mysqli_fetch_assoc($result); //ambil
$user_id = $row['id'];
$username = '';
$rowGambar = $row['gambar'];

// cek COOKIE dan username
if ($key === hash("sha512", $row["username"])) {
	$username = $row["username"];
}

$query = "SELECT
buku.*,
IFNULL(ulasan.avg_rating, 0) AS avg_rating
FROM
buku
LEFT JOIN (
SELECT
	buku_id,
	AVG(rating) AS avg_rating
FROM
	ulasan
GROUP BY
	buku_id
) AS ulasan
ON
buku.id = ulasan.buku_id
ORDER BY
buku.id ASC
LIMIT
{$pagenation->dataPerhalaman()}
";

$buku = mysqli_query($db, $query);

date_default_timezone_set('Asia/Jakarta');
$tanggal = date("H:i d/m/Y");

// Menangani data POST dari meminjam buku
if (isset($_POST['send'])) {
	$jumlah_pinjam = $_POST['jumlah_pinjam'];
	$tanggal_pengembalian = $_POST['tanggal_pengembalian'];
	$buku_id = $_POST['buku_id'];

	mysqli_query($db, "INSERT INTO peminjam VALUE('', '$user_id','$buku_id', '$jumlah_pinjam' ,'$tanggal', '$tanggal_pengembalian', '0', 'Tidak ada alasan')");
}

// Menangani data POST ulasan

if (isset($_POST['ulasan'])) {
	$isi_ulasan = $_POST['isi_ulasan'];
	$rating = intval($_POST['rating']);
	$buku_id = intval($_POST['buku_id']);
	$user_id = intval($_POST['user_id']);

	mysqli_query($db, "INSERT INTO ulasan VALUE('', '$isi_ulasan', $rating, $user_id, $buku_id, '$tanggal')");
}

// Menangani data POST ulasan DELETE

if (isset($_POST['delete_ulasan'])) {
	$uls_id = intval($_POST['uls_id']);

	mysqli_query($db, "DELETE FROM ulasan WHERE ulasan.id = $uls_id");
}

?>
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
			<label for="search">Search by:</label>
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
			<table>
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
				<tbody>
					<?php
					$num = 1;
					foreach ($buku as $books):
						?>
					<tr>
						<td>
							<p class="except">
								<?= $num ?>
							</p>
						</td>
						<td>
							<p class="except">
								<img src="../Admin/Temp/<?= $books["image"] ?>"
									alt="image of book: <?= $books['judul_buku'] ?>" height="70" />
							</p>
							<!-- rating -->
							<div class="rating">
								<button class="rate" onclick="
											$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?>&&usr=<?= $row['id'] ?>&&uls=5&&pjm_id=1 #ulasan',() => {
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
											$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?>&&usr=<?= $row['id'] ?> #ulasan',() => {
												$('.popup').removeAttr('hidden');
											})
											">No Rating</button>
								<?php } ?>
							</div>
						</td>
						<td>
							<p>
								<?= $books["judul_buku"] ?>
							</p>
						</td>
						<td>
							<p class="except">
								<?= $books["kode_buku"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books["kategori"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books["penulis"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $books["penerbit"] ?>
							</p>
						</td>
						<td id="detail">
							<!-- pinjam -->
							<button onclick="
								$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?> #peminjaman.insert');
								$('.popup').removeAttr('hidden');
								" id="baca-buku">
								Pinjam Buku
							</button>

							<!-- detail -->
							<button onclick="
								$('.popup').load('component/result/fraction_group.php?bukid=<?= $books['id'] ?>&&bukunya=<?= urlencode($books['judul_buku']) ?>&&jml=<?= $books['jumlah_buku'] ?> #pop-up',()=>{
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