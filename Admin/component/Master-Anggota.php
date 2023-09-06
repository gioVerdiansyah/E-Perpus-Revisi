<?php
require '../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../index.php");
	exit;
}

$ops = (isset($_POST['ops'])) ? $_POST['ops'] : 'loginuser';

if (isset($_POST['id'])) {
	$id = $_POST['id'];
	$usr = $_POST['username'];

	mysqli_query($db, "DELETE FROM $ops WHERE id = $id");
	mysqli_query($db, "DELETE FROM peminjam WHERE username = '$usr'");
}


$pagenation = new Pagenation(10, $ops, 1);
$kuery = "";
if ($ops = 'loginuser') {
	$kuery = "SELECT loginuser.*, data_user.*
	FROM loginuser
	LEFT JOIN data_user ON data_user.user_id = loginuser.id
	ORDER BY id DESC LIMIT {$pagenation->dataPerhalaman()}";
} else {
	$kuery = "SELECT * FROM $ops ORDER BY id DESC LIMIT {$pagenation->dataPerhalaman()}";
}

$member = mysqli_query($db, $kuery);
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
					$('#isi-data').load('component/result/anggota.php?ops=' + $(this).val() + '&&lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
		<option value="loginuser">Anggota</option>
		<option value="loginadmin">Admin</option>
	</select>
	<div class="data-wrapper">
		<div class="data-indicator">
			<div class="data-entries">
				<p>show</p>
				<select id="selection" name="selection"
					onchange="
					$('#isi-data').load('component/result/anggota.php?ops=' + $('#opsi').val() + '&&lim=' + $(this).val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())">
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
								<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin menghapus akun: <?= $members['username'] ?>?', function(isTrue){
										if(isTrue){
											$.post('component/Master-Anggota.php', { 
											   id: '<?= $members['id'] ?>',
											   username: '<?= $members['username'] ?>'
											});
											Peringatan.sukses('Akun <?= $members['username'] ?> berhasil di HAPUS');
											$('#isi-data').load('component/result/anggota.php?ops=' + $('#opsi').val() + '&&lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val())
										}
									});
								"><i class="fa-solid fa-delete-left"></i>
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