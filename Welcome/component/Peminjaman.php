<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require "../../Admin/database/functions.php";

if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
	header("Location: ../../index.php");
	exit;
}

$pagenation = new Pagenation(10, "peminjam", 1);

$id = $_COOKIE["UsrLgnMJYNiSeQlThRuE"];
$key = $_COOKIE["UIDprpsMJYNisThroe"];

// cek username berdasarkan id
$result = mysqli_query($db, "SELECT * FROM loginuser WHERE id='$id'");
$row = mysqli_fetch_assoc($result); //ambil
$username = '';

// cek COOKIE dan username
if ($key === hash("sha512", $row["username"])) {
	$username = $row["username"];
}

// Menangani POST UPDATE peminjaman
if (isset($_POST['pinjam_update'])) {
	$pjm_id = intval($_POST['pjm_id']);
	$jumlah_pinjam = intval($_POST['jumlah_pinjam']);
	$tanggal_pengembalian = $_POST['tanggal_pengembalian'];
	date_default_timezone_set('Asia/Jakarta');
	$tanggal = date("H:i d/m/Y");

	mysqli_query($db, "UPDATE peminjam SET `jumlah_pinjam` = $jumlah_pinjam, `tanggal_pinjam` = '$tanggal', `tanggal_pengembalian` = '$tanggal_pengembalian' WHERE id = $pjm_id");
}

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
";

$result = mysqli_query($db, $query);

$status = mysqli_fetch_assoc($result)['status'];
// Menangani POST method DELETE data 
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	mysqli_query($db, "DELETE FROM peminjam WHERE id = $id");
}

// Enkripsi
$iv = openssl_random_pseudo_bytes(16);
$encryptedData = openssl_encrypt($username, 'AES-256-CBC', '#XXXMr.Verdi_407xxx#', OPENSSL_RAW_DATA, $iv);
$encodedDataUsername = urlencode(base64_encode($encryptedData . $iv));
?>

<div class="title">
	<h2>Data buku yang dipinjam</h2>
	<p>Data di update secara otomatis</p>
</div>
<style>
	.data-wrapper .isi-data .data table tbody tr td p {
		width: 100%;
		text-align: center;
	}
</style>
<!-- ini.isi -->
<div class="data-wrapper">
	<div class="data-indicator">
		<div class="data-entries">
			<p>show</p>
			<select id="selection" name="selection" onchange="
					let value = $(this).val();
					$('#isi-data').load('component/result/pinjam.php?lim=' + value + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $('#search').val() + '&&usr=<?= $username ?>')
					if(value != 10){
						if(window.matchMedia('(max-width: 768px)').matches){
							$('body').css({'height': '100vh'})
						}
					}else{
						$('body').removeAttr('style');
					}
					">
				<option value="10">10</option>
				<option value="5">5</option>
				<option value="2">2</option>
			</select>
			<p>entries</p>
		</div>
		<?php
		if ($status == 1):
			?>
			<div class="unduh-stroke">
				<p>Unduh semua buku yang disetujui:</p>
				<a href="component/result/cetak.php?usr=<?= $encodedDataUsername ?>"> <i
						class="fa-solid fa-cloud-arrow-down"></i>
					Download stroke</a>
			</div>
		<?php endif; ?>
		<div class="data-search">
			<label for="search">Search by:</label>
			<input type="search" name="search" id="search" onkeyup="
			$('#isi-data').load(
					'component/result/pinjam.php?lim=' + $('#selection').val() + '&&page=<?= $pagenation->halamanAktif() ?>&&key=' + $(this).val() + '&&usr=<?= $username ?>'
				)
			" placeholder="judul buku/kode buku/kategori" />
		</div>
	</div>
	<!-- isi data -->
	<div class="isi-data" id="isi-data">
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
					<th>EDIT/HAPUS</th>
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
								<p>
									<?= $peminjam["judul_buku"] ?>
								</p>
							</td>
							<td>
								<p>
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
								<!-- edit -->
								<button onclick="
								$('.popup').load('component/result/fraction_group.php?bukid=<?= $peminjam['bukid'] ?>&&bukunya=<?= urlencode($peminjam['judul_buku']) ?>&&jml=<?= $peminjam['jumlah_buku'] ?>&&pjm_id=<?= $peminjam['pjm_id'] ?> #peminjaman.update');
								$('.popup').removeAttr('hidden');
								" class="edit">
									Edit
								</button>

								<?php if ($peminjam["status"] == 1) { ?>
									<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin menghapus data yang sudah disetujui?', function(isTrue){
										if(isTrue){
											$.post('component/Peminjaman.php', {id: <?= $peminjam['id'] ?>})
											Peringatan.sukses('Data peminjaman berhasil di HAPUS')
											$('#isi-data').load('component/Peminjaman.php #isi-data')
										}
									});
								"><i class="fa-solid fa-delete-left"></i>
									</button>
								<?php } elseif ($peminjam["status"] === "2") { ?>
									<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin menghapus data yang ditolak?', function(isTrue){
										if(isTrue){
											$.post('component/Peminjaman.php', {id: <?= $peminjam['id'] ?>})
											Peringatan.sukses('Data peminjaman berhasil di HAPUS')
											$('#isi-data').load('component/Peminjaman.php #isi-data')
										}
									});
								"><i class="fa-solid fa-delete-left"></i>
									</button>
								<?php } else { ?>
									<button class="delete" onclick="
									Peringatan.konfirmasi('Apakah anda yakin ingin membatalkan meminjam buku <?= $peminjam['judul_buku'] ?>?', function(isTrue){
										if(isTrue){
											$.post('component/Peminjaman.php', {id: <?= $peminjam['id'] ?>})
											Peringatan.sukses('Data peminjaman berhasil di CANCEL')
											$('#isi-data').load('component/Peminjaman.php #isi-data')
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
				<?= $num -= 1 ?> of 10 entries
			</p>
			<div class="pagination">
				<p class="amount-of-data">1</p>
				<?php if ($pagenation->halamanAktif() < $pagenation->jumlahHalaman()): ?>
					<button onclick="
					$('.isi-data').load(
						'component/result/pinjam.php?lim=<?= $pagenation->dataPerhalaman() ?>&&page=<?= $pagenation->halamanAktif() + 1 ?>&&key=' + $('#search').val() + '&&usr=<?= $username ?>')'
					)">
						Next
						<i class="fa-solid fa-angle-right"></i>
					</button>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>