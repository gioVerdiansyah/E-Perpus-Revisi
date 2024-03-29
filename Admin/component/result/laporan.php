<?php
require '../../database/functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../index.php");
	exit;
}

$page = new Pagenation($_GET['lim'], "peminjam", $_GET['page']);

$keyword = $_GET["key"];

$query = "SELECT
	peminjam.*,
	peminjam.id AS pjm_id,
	buku.judul_buku,
	loginuser.username,
	data_user.gambar
FROM
	peminjam
    LEFT JOIN buku ON peminjam.buku_id = buku.id
    LEFT JOIN loginuser ON peminjam.user_id = loginuser.id
	LEFT JOIN data_user ON loginuser.id = data_user.user_id
WHERE
	peminjam.status IN ('1', '2') AND(buku.judul_buku LIKE '%$keyword%' OR loginuser.username LIKE '$keyword%' OR tanggal_pinjam LIKE '$keyword%')
ORDER BY
    peminjam.id DESC
LIMIT 
	{$page->awalData()},{$page->dataPerhalaman()}
";

$pinjam = mysqli_query($db, $query);
?>
<!-- isi data -->
<script src="JS/jquery-3.6.3.min.js"></script>
<div class="isi-data">
	<div class="data">
		<table width="100%">
			<thead>
				<th>NO</th>
				<th>PEMINJAM</th>
				<th>PP PEMINJAM</th>
				<th>JUDUL <br> BUKU</th>
				<th>JUMLAH <br> PINJAM</th>
				<th>TGL <br> PINJAM</th>
				<th>TGL <br> PENGEMBALIAN</th>
				<th>STATUS</th>
				<th>ACTION</th>
			</thead>
			<tbody width="100%" cellspacing="10">
				<?php
				$id = 1;
				foreach ($pinjam as $peminjam):
					?>
					<tr cellspacing="10">
						<td>
							<p>
								<?= $id ?>
							</p>
						</td>
						<td>
							<p>
								<?= $peminjam['username'] ?>
							</p>
						</td>
						<td>
							<img src="../.temp/<?= $peminjam['gambar'] ?>" alt="photo profile peminjam" height="70">
						</td>
						<td class="limit">
							<p>
								<?= $peminjam['judul_buku'] ?>
								<strong title="Ini adalah jumlah stock buku">
									(
									<?= getStock($peminjam['judul_buku']) ?>)
								</strong>
							</p>
						</td>
						<td class="limit center">
							<p>
								<?= $peminjam['jumlah_pinjam'] ?>
							</p>
						</td>
						<td>
							<p>
								<?= getDay($peminjam["tanggal_pinjam"], true) ?> <br>
								<?= $peminjam['tanggal_pinjam'] ?>
							</p>
						</td>
						<td>
							<p>
								<?= getDay($peminjam["tanggal_pengembalian"], false) ?> <br>
								<?= $peminjam['tanggal_pengembalian'] ?>
							</p>
						</td>
						<td>
							<?php if ($peminjam["status"] === "1") { ?>
								<p class="persetujuan g">
									<i class="fa-solid fa-check"></i> Disetujui
								</p>
							<?php } elseif ($peminjam["status"] === "2") { ?>
								<p class="persetujuan r h" onclick="
										$('.popup').load('../Welcome/component/result/fraction_group.php?bukid=<?= $peminjam['id'] ?>&&pjm_id=<?= $peminjam['pjm_id'] ?>&&usr=5&&uls=1 #ditolak-admin', ()=>{$('.popup').removeAttr('hidden')});
										">
									<i class="fa-regular fa-circle-xmark"></i> Ditolak!
								</p>
							<?php } ?>
						</td>
						<td>
							<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin menghapusnya?', (isTrue)=>{
										if(isTrue){
											$.post('component/Data-Laporan.php',{
												id: <?= $peminjam['pjm_id'] ?>
											});
											$('#isi-data').load('component/result/laporan.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key=' + $('#search').val());
										}
									})
								">
								<i class="fa-solid fa-delete-left"></i>
							</button>
						</td>
					</tr>
					<?php $id++; endforeach ?>
			</tbody>
		</table>
	</div>
	<div class="description">
		<p>Showing
			<?= $id -= 1; ?> of
			<?= $page->dataPerhalaman() ?> entries
		</p>

		<!-- Page -->

		<div class="pagination">
			<?php if ($page->halamanAktif() > 1): ?>
				<button class="left" onclick="
				$('.isi-data').load(
					'component/result/laporan.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $keyword ?>'
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
						'component/result/laporan.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $keyword ?>'
					)">
					Next
					<i class="fa-solid fa-angle-right"></i>
				</button>
			<?php endif ?>
		</div>
	</div>
</div>