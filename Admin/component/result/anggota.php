<?php
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../../index.php");
	exit;
}

$ops = (isset($_GET['ops'])) ? $_GET['ops'] : 'loginuser';

$page = new Pagenation($_GET['lim'], $ops, $_GET['page']);


$key = $_GET['key'];

$member = null;

if ($ops == "loginuser") {
	$kuery = "SELECT loginuser.*, data_user.*
		FROM loginuser
		LEFT JOIN data_user ON data_user.user_id = loginuser.id
		WHERE username LIKE '%$key%' ORDER BY id DESC LIMIT {$page->awalData()},{$page->dataPerhalaman()}";
	$member = mysqli_query($db, $kuery);
} else {
	$member = mysqli_query($db, "SELECT * FROM loginadmin WHERE username LIKE '%$key%' ORDER BY id DESC LIMIT {$page->awalData()},{$page->dataPerhalaman()}");
}

?>

<script src="JS/jquery-3.6.3.min.js"></script>
<div class="isi-data" id="isi-data">
	<div class="data">
		<table width="100%" cols="7">
			<thead width="100%">
				<th>NO</th>
				<?php if ($ops === 'loginuser') { ?>
				<th>PP ANGGOTA</th>
				<?php } else { ?>
				<th>PP ADMIN</th>
				<?php } ?>
				<?php if ($ops === 'loginuser') { ?>
				<th>NICKNAME USER</th>
				<?php } else { ?>
				<th>NICKNAME ADMIN</th>
				<?php } ?>
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
						<?php if ($_GET['ops'] === 'loginuser') { ?>
						<img src="../.temp/<?= $members['gambar'] ?>" alt="Thumbnail" height="70">
						<?php } else { ?>
						<img src="Temp/<?= $members['gambar'] ?>" alt="Thumbnail" height="70">
						<?php } ?>
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
												ops: '<?= $ops ?>',
												username: '<?= $members['username'] ?>'
											});
											Peringatan.sukses('Akun <?= $members['username'] ?> berhasil di HAPUS');
											$('#isi-data').load('component/result/anggota.php?ops=<?= $ops ?>&&lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key=<?= $key ?>')
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
			<?= $id -= 1; ?> of
			<?= $page->dataPerhalaman() ?> entries
		</p>

		<!-- Pagenation -->

		<div class="pagination">
			<?php if ($page->halamanAktif() > 1): ?>
			<button class="left" onclick="
				$('.isi-data').load(
					'component/result/anggota.php?ops=<?= $ops ?>&&lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $key ?>'
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
						'component/result/anggota.php?ops=<?= $ops ?>&&lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $key ?>'
					)">
				Next
				<i class="fa-solid fa-angle-right"></i>
			</button>
			<?php endif ?>
		</div>
	</div>
</div>