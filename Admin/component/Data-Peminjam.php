<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../index.php");
	exit;
}

// Menangani POST pinjam
if (isset($_POST['jumlah_pinjam'])) {
	$idP = $_POST['idP'];
	$book_name = $_POST['judul_buku'];
	$buku_tersedia = mysqli_query($db, "SELECT jumlah_buku FROM buku WHERE judul_buku = '$book_name'");
	$result = mysqli_fetch_assoc($buku_tersedia);
	$number_buku_tersedia = $result['jumlah_buku'];
	$jumlah_pinjam = $_POST['jumlah_pinjam'];

	// calc
	$jumlah = $number_buku_tersedia - $jumlah_pinjam;
	// update value
	mysqli_query($db, "UPDATE buku SET jumlah_buku = $jumlah WHERE judul_buku = '$book_name'");
	mysqli_query($db, "UPDATE peminjam SET status = '1' WHERE id = $idP");
}

// menangani POST alasan penolakan
if (isset($_POST['isiAlasan'])) {
	$dataAlasan = $_POST['isiAlasan'];
	$idPenolakan = $_POST['idPenolakan'];
	mysqli_query($db, "UPDATE peminjam SET alasan = '$dataAlasan', status = '2' WHERE id = $idPenolakan");
}


// Menangani POST delete
if (isset($_POST['idbor'])) {
	$idBor = $_POST['idbor'];
	mysqli_query($db, "DELETE FROM peminjam WHERE id = $idBor");
}

$pagenation = new Pagenation(10, "peminjam", 1);

$borrower = mysqli_query(
	$db,
	"SELECT
	peminjam.*,
	peminjam.id AS pjm_id,
	buku.judul_buku,
	loginuser.username,
	data_user.gambar
FROM
	peminjam
INNER JOIN
	buku
ON
	peminjam.buku_id = buku.id
INNER JOIN
	loginuser
ON
	peminjam.user_id = loginuser.id
INNER JOIN
	data_user
ON
    loginuser.id = data_user.user_id
WHERE
	peminjam.status = '0'
"
);
?>
<style>
	.side-bar {
		height: 100% !important;
		box-shadow: none !important;
	}

	main {
		height: max-content !important;
	}

	.isi-data .data table tbody tr td.center {
		text-align: center !important;
	}
</style>
<link rel="stylesheet" href="CSS/style-content.css">
<div class="title">
	<h1>Peminjam</h1>
	<hr>
	<h2>Data Peminjam <img src="../Assets/angle-small-right.svg" alt=""></h2>
	<h3>Master-Peminjam</h3>
</div>
<div class="information">
	<h3>
		Informasi:
	</h3>
	<ul>
		<li>
			<p>Data yang sudah disetujui akan otomatis masuk ke dalam laporan dengan status di <strong>setujui</strong>,
				sedangkan yang ditolak akan berstatus <strong>ditolak</strong> </p>
		</li>
		<li>
			<p>Data yang sudah disetujui otomatis stock buku akan berkurang sesuai permintaan peminjam</p>
		</li>
		<li>
			<p>Admin diharap menentukan antara menyetujuinya atau menolaknya, jika menilaknya diharap menulis alasan
				yang jelas agar peminjam tidak kecewa</p>
		</li>
	</ul>
	<button onclick="
		$('.information').slideUp(500);
	"><i class="fa-solid fa-xmark"></i></button>
</div>
<!-- ini.isi -->
<div class="card-wrapper penulis">
	<div class="data-wrapper">
		<div class="data-indicator">
			<div class="data-entries">
				<p>show</p>
				<select id="selection" name="selection"
					onchange="
					let value = $(this).val();
					$('#isi-data').load('component/result/pinjam.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
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
					'component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val()
				)">
			</div>
		</div>
		<!-- isi data -->
		<div class="isi-data" id="isi-data">
			<div class="data">
				<table width="100%">
					<thead width="100%">
						<th>NO</th>
						<th>PEMINJAM</th>
						<th>PP PEMINJAM</th>
						<th>JUDUL <br> BUKU</th>
						<th>JUMLAH <br> PINJAM</th>
						<th>TGL <br> PINJAM</th>
						<th>TGL <br> PENGEMBALIAN</th>
						<th>SETUJUI</th>
					</thead>
					<tbody width="100%" cellspacing="10">
						<?php
						$id = 1;
						foreach ($borrower as $borrowers):
							?>
							<tr cellspacing="10">
								<td>
									<p>
										<?= $id ?>
									</p>
								</td>
								<td>
									<p>
										<?= $borrowers['username'] ?>
									</p>
								</td>
								<td>
									<img src="../.temp/<?= $borrowers['gambar'] ?>" alt="photo profile peminjam"
										height="70">
								</td>
								<td class="limit">
									<p>
										<?= $borrowers['judul_buku'] ?>
										<strong title="Ini adalah jumlah stock buku">
											(
											<?= getStock($borrowers['judul_buku']) ?>)
										</strong>
									</p>
								</td>
								<td class="limit center">
									<p>
										<?= $borrowers['jumlah_pinjam'] ?>
									</p>
								</td>
								<td>
									<p>
										<?= getDay($borrowers["tanggal_pinjam"], true) ?> <br>
										<?= $borrowers['tanggal_pinjam'] ?>
									</p>
								</td>
								<td>
									<p>
										<?= getDay($borrowers["tanggal_pengembalian"], false) ?> <br>
										<?= $borrowers['tanggal_pengembalian'] ?>
									</p>
								</td>
								<td>
									<button class="o" onclick="
											Peringatan.menyetujui(`Apakah anda ingin menyetujui <?= htmlspecialchars($borrowers['username'], ENT_QUOTES, 'UTF-8') ?> meminjam buku <?= $borrowers['judul_buku'] ?>?`, function(isTrue){
												if(isTrue){
													Peringatan.sukses(`Anda telah menyetujui <?= htmlspecialchars($borrowers['username'], ENT_QUOTES, 'UTF-8') ?> meminjam buku <?= $borrowers['judul_buku'] ?>`);

													$.post('component/Data-Peminjam.php', {
														idP: <?= $borrowers['id'] ?>,
														judul_buku: '<?= $borrowers['judul_buku'] ?>',
														jumlah_pinjam: '<?= $borrowers['jumlah_pinjam'] ?>'
													});
													$('#isi-data').load('component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val());
												}else{
													Peringatan.penolakan(`Alasan Anda menolak <?= htmlspecialchars($borrowers['username'], ENT_QUOTES, 'UTF-8') ?> untuk meminjam buku <?= $borrowers['judul_buku'] ?>?`, function(isTrue, data){
														if(isTrue){
															Peringatan.sukses(`Anda telah menolak <?= htmlspecialchars($borrowers['username'], ENT_QUOTES, 'UTF-8') ?> untuk meminjam buku <?= $borrowers['judul_buku'] ?>`);
															$.post('component/Data-Peminjam.php',{
																idPenolakan: <?= $borrowers['id'] ?>,
																isiAlasan: `${data}`
															})
															$('#isi-data').load('component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val());
														}
													})
												}
											});
											">
										<i class="fa-regular fa-clock"></i> Menunggu
									</button>
								</td>
							</tr>
							<?php $id++; endforeach ?>
					</tbody>
				</table>
			</div>
			<div class=" description">
				<p>Showing
					<?= $id -= 1; ?> of 10 entries
				</p>
				<div class="pagination">
					<p class="amount-of-data">1</p>
					<?php if ($pagenation->halamanAktif() < $pagenation->jumlahHalaman()): ?>
						<button
							onclick="
					$('#isi-data').load('component/result/pinjam.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val())">
							Next
							<i class="fa-solid fa-angle-right"></i>
						</button>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
</div>