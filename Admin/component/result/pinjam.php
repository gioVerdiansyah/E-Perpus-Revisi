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
	buku.judul_buku,
	loginuser.username,
	data_user.gambar
FROM
	peminjam
    LEFT JOIN buku ON peminjam.buku_id = buku.id
    LEFT JOIN loginuser ON peminjam.user_id = loginuser.id
	LEFT JOIN data_user ON loginuser.id = data_user.user_id
WHERE
	peminjam.status = '0' AND(buku.judul_buku LIKE '%$keyword%' OR loginuser.username LIKE '$keyword%' OR tanggal_pinjam LIKE '$keyword%')
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
			<thead width="100%">
				<th>NO</th>
				<th>PEMINJAM</th>
				<th>PP PEMINJAM</th>
				<th>JUDUL BUKU</th>
				<th>JUMLAH PINJAM</th>
				<th>TGL PINJAM</th>
				<th>TGL PENGEMBALIAN</th>
				<th>SETUJUI</th>
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
						<button class="o" onclick="
											Peringatan.menyetujui('Apakah anda ingin menyetujui <?= $peminjam['username'] ?> meminjam buku <?= $peminjam['judul_buku'] ?>?', function(isTrue){
												if(isTrue){
													Peringatan.sukses('Anda telah menyetujui <?= $peminjam['username'] ?> meminjam buku <?= $peminjam['judul_buku'] ?>');

													$.post('component/Data-Peminjam.php', {
														idP: <?= $peminjam['id'] ?>,
														judul_buku: '<?= $peminjam['judul_buku'] ?>',
														jumlah_pinjam: '<?= $peminjam['jumlah_pinjam'] ?>'
													});
													$('#isi-data').load('component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key=<?= $keyword ?>');
												}else{
													Peringatan.penolakan('Alasan Anda menolak <?= $peminjam['username'] ?> untuk meminjam buku <?= $peminjam['judul_buku'] ?>?', function(isTrue, data){
														if(isTrue){
															Peringatan.sukses('Anda telah menolak <?= $peminjam['username'] ?> untuk meminjam buku <?= $peminjam['judul_buku'] ?>');
															$.post('component/Data-Peminjam.php',{
																idPenolakan: <?= $peminjam['id'] ?>,
																isiAlasan: `${data}`
															})
															$('#isi-data').load('component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key=<?= $keyword ?>');
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
					'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $keyword ?>'
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
						'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $keyword ?>'
					)">
				Next
				<i class="fa-solid fa-angle-right"></i>
			</button>
			<?php endif ?>
		</div>
	</div>
</div>