<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
	header("Location: ../../../index.php");
	exit;
}

$page = new Pagenation($_GET['lim'], "peminjam", $_GET['page']);

$keyword = $_GET["key"];
$username = $_GET["usr"];

$query = "SELECT 
peminjam.*,
peminjam.id AS pjm_id,
buku.*,
buku.id AS bukid,
loginuser.username,
data_user.gambar
FROM
peminjam
INNER JOIN
loginuser
ON
peminjam.user_id = loginuser.id
INNER JOIN
buku
ON
peminjam.buku_id = buku.id
INNER JOIN 
    data_user
ON
    loginuser.id = data_user.user_id
WHERE username = '$username' AND (judul_buku LIKE '%$keyword%' OR kode_buku LIKE '$keyword' OR kategori LIKE '$keyword%') ORDER BY peminjam.id DESC LIMIT {$page->awalData()},{$page->dataPerhalaman()}
";

$result = mysqli_query($db, $query);
?>
<!-- isi data -->
<div class="isi-data">
	<div class="data">
		<table width="100%">
			<thead width="100%">
				<th>NO</th>
				<th>JUDUL BUKU</th>
				<th>KODE <br> BUKU</th>
				<th>JUMLAH <br> PINJAM</th>
				<th>TGL PINJAM</th>
				<th>TGL <br> PENGEMBALIAN</th>
				<th>STATUS</th>
				<th>HAPUS/CANCEL</th>
			</thead>
			<tbody>
				<?php
				$num = 1;
				foreach ($result as $peminjam):
					?>
					<tr>
						<td>
							<p>
								<?= $num ?>
							</p>
						</td>
						<td>
							<p class="limit">
								<?= $peminjam["judul_buku"] ?>
							</p>
						</td>
						<td>
							<p class="limit">
								<?= $peminjam["kode_buku"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= $peminjam["jumlah_pinjam"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= getDay($peminjam["tanggal_pinjam"], true) ?>
								<?= $peminjam["tanggal_pinjam"] ?>
							</p>
						</td>
						<td>
							<p>
								<?= getDay($peminjam["tanggal_pengembalian"], false) ?> <br>
								<?= $peminjam["tanggal_pengembalian"] ?>
							</p>
						</td>
						<td>
							<?php if ($peminjam["status"] == 1) { ?>
								<p class="persetujuan g">
									<i class="fa-solid fa-check"></i> Disetujui
								</p>
							<?php } elseif ($peminjam["status"] == "2") { ?>
								<p class="persetujuan r h" onclick="
										$('.popup').load('component/result/fraction_group.php?bukid=<?= $peminjam['id'] ?> #ditolak', ()=>{$('.popup').removeAttr('hidden')});
										">
									<i class="fa-regular fa-circle-xmark"></i> Ditolak!
								</p>
							<?php } else { ?>
								<p class="persetujuan o">
									<i class="fa-regular fa-clock"></i> Belum
								</p>
							<?php } ?>
						</td>
						<td>
							<?php if ($peminjam['status'] == '0'): ?>
									<button onclick="
										$('.popup').load('component/result/fraction_group.php?bukid=<?= $peminjam['bukid'] ?>&&bukunya=<?= urlencode($peminjam['judul_buku']) ?>&&jml=<?= $peminjam['jumlah_buku'] ?>&&usl=5&&pjm_id=<?= $peminjam['pjm_id'] ?>&&usr=1 #peminjaman.update');
										$('.popup').removeAttr('hidden');
										" class="edit">
										Edit
									</button>
								<?php endif; ?>
							<?php if ($peminjam["status"]) { ?>
								<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin menghapus data yang sudah disetujui?', function(isTrue){
										if(isTrue){
											$.post('component/Peminjaman.php', {id: <?= $peminjam['pjm_id'] ?>})
											Peringatan.sukses('Data peminjaman berhasil di HAPUS')
											$('#isi-data').load('component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key= <?= $keyword ?>&&usr=<?= $username ?>')
										}
									});
								"><i class="fa-solid fa-delete-left"></i>
								</button>
							<?php } else { ?>
								<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin membatalkan meminjam buku <?= $peminjam['judul_buku'] ?>?', function(isTrue){
										if(isTrue){
											$.post('component/Peminjaman.php', {id: <?= $peminjam['pjm_id'] ?>})
											Peringatan.sukses('Data peminjaman berhasil di CANCEL')
											$('#isi-data').load('component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() ?>&&key= <?= $keyword ?>&&usr=<?= $username ?>')
										}
									});
								"><i class="fa-solid fa-delete-left"></i>
								</button>
							<?php } ?>
						</td>
					</tr>
					<?php $num++; endforeach;
				?>
			</tbody>
		</table>
	</div>
	<div class="description">
		<p>Showing
			<?= $num -= 1; ?> of
			<?= $page->dataPerhalaman() ?> entries
		</p>

		<!-- Page -->

		<div class="pagination">
			<?php if ($page->halamanAktif() > 1): ?>
				<button class="left" onclick="
				$('.isi-data').load(
					'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() - 1 ?>&&key=<?= $keyword ?>&&usr=<?= $username ?>'
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
						'component/result/pinjam.php?lim=<?= $page->dataPerhalaman() ?>&&page=<?= $page->halamanAktif() + 1 ?>&&key=<?= $keyword ?>&&usr=<?= $username ?>'
					)">
					Next
					<i class="fa-solid fa-angle-right"></i>
				</button>
			<?php endif ?>
		</div>
	</div>
</div>