<?php
require "../../../Admin/database/functions.php";
$id = $_GET['bukid'];

$fillIn = query("SELECT * FROM buku WHERE id = $id")[0];

$alasanPenolakan = mysqli_query($db, "SELECT * FROM peminjam WHERE id = $id");
$alasan = mysqli_fetch_assoc($alasanPenolakan);
?>

<div id="pop-up" style="display: none">
	<div class="content" id="add">
		<div class="title">
			<h1>Detail Buku</h1>
			<button onclick="
	  $('#pop-up').fadeOut(500);
	  $('.pop-up').attr('hidden');
	  $('body').removeAttr('height')
	  ">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="data">
			<table>
				<tr>
					<td>Judul Buku</td>
					<td>: <strong>
							<?= $fillIn["judul_buku"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>Kode Buku</td>
					<td>: <strong>
							<?= $fillIn["kode_buku"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>Kategori</td>
					<td>: <strong>
							<?= $fillIn["kategori"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>Penulis</td>
					<td>: <strong>
							<?= $fillIn["penulis"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>Penerbit</td>
					<td>: <strong>
							<?= $fillIn["penerbit"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>tahun Terbit</td>
					<td>: <strong>
							<?= $fillIn["tahun_terbit"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>ISBN</td>
					<td>: <strong>
							<?= $fillIn["isbn"] ?>
						</strong></td>
				</tr>
				<tr>
					<td>Jumlah Halaman</td>
					<td>: <strong>
							<?= $fillIn["jumlah_halaman"] ?> halaman
						</strong></td>
				</tr>
				<tr>
					<td>Jumlah Buku</td>
					<td>: <strong>
							<?= $fillIn["jumlah_buku"] ?> buku
						</strong></td>
				</tr>
			</table>
			<p class="sinopsis">Sinopsis: </p>
			<p>
				<?= $fillIn["sinopsis"] ?>
			</p>
		</div>
	</div>
</div>


<div id="peminjaman">
	<div class="content" id="add">
		<div class="title">
			<h1>Pinjam Buku</h1>
			<button onclick="
				$('#add').attr('id', 'close');
				
				setTimeout(()=>{
				$('#peminjaman').remove();
				$('.popup').attr('hidden', true);
				$('body').removeAttr('height')
				},500);
			">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="data">
			<form method="post" onsubmit="
				event.preventDefault();
				$(document).ready(function() {
						$.post('component/Buku.php', {
								send: true,
								buku_id: <?= $_GET['bukid'] ?>,
								jumlah_pinjam: $('#jumlah_pinjam').val(),
								tanggal_pengembalian: $('#tanggal_pengembalian').val()
							});
							$('#peminjaman').remove();
							$('body').removeAttr('height')
						});
							Peringatan.sukses('Permintaan meminjam buku <?= $_GET['bukunya'] ?> berhasil dikirim', 2500);
						">
				<ul>
					<div>
						<li>
							<label for="jumlah_pinjam">Jumlah Pinjam</label>
							<?php if (intval(getStock($_GET['bukunya'])) < 1) { ?>
								<input type="text" class="input disabled" placeholder="Stock buku sudah habis!" disabled>
							<?php } else { ?>
								<input type="number" min="1" max="<?= $_GET['jml'] ?>" step="1" name="jumlah_pinjam"
									id="jumlah_pinjam" required placeholder="total buku adalah <?= $_GET['jml'] ?>" oninput="
						const maxLength = 2;

						if (this.value.length > maxLength) {
						  this.value = this.value.slice(0, maxLength);
						}
						">
							<?php } ?>
						</li>
						<?php
						$m = date('m');
						$max = date('Y-m-d', strtotime('+1 month'));
						?>
						<li>
							<label for="tanggal_pengembalian">Tanggal Pengembalian</label>
							<?php if (intval(getStock($_GET['bukunya'])) < 1) { ?>
								<input type="date" class="input disabled" min="<?= date('Y-m-d'); ?>" max="<?= $max ?>"
									name="tanggal_pengembalian" id="disabled" disabled>
							<?php } else { ?>
								<input type="date" min="<?= date('Y-m-d'); ?>" max="<?= $max ?>" name="tanggal_pengembalian"
									id="tanggal_pengembalian" required>
							<?php } ?>

						</li>
					</div>
					<li class="send">
						<?php if (intval(getStock($_GET['bukunya'])) < 1) { ?>
							<button id="disabled" disabled="disabled">Kirim</button>
						<?php } else { ?>
							<button type="submit" name="send">Kirim</button>
						<?php } ?>
					</li>
				</ul>
			</form>
		</div>
	</div>
</div>

<div id="ulasan">
	<div class="content" id="add">
		<div class="title">
			<h1>Beri Ulasan Buku</h1>
			<button onclick="
				$('#add').attr('id', 'close');
				
				setTimeout(()=>{
				$('#ulasan').remove();
				$('.popup').attr('hidden', true);
				$('body').removeAttr('height')
				},500);
			">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="data">
			<ul>
				<?php
				$ulasan = mysqli_query($db, "SELECT ulasan.*,buku.judul_buku,buku.image,buku.penulis, loginuser.username, loginuser.gambar FROM ulasan INNER JOIN buku ON ulasan.buku_id = {$_GET['bukid']} INNER JOIN loginuser ON ulasan.user_id = {$_GET['usr']} WHERE ulasan.buku_id = buku.id");
				$row = mysqli_fetch_assoc($ulasan);
				?>
				<?php
				if (isset($row['rating'])) {
					?>
					<li>
						<img src="../Admin/Temp/<?= $row['image'] ?>"
							alt="ini adalah gambar dari buku <?= $row['judul_buku'] ?>" />
						<div class="wrapper-books">
							<div class="row">
								<h2>
									<?= $row['judul_buku'] ?>
								</h2>
								<div class="stars-wrapper">
									<?php
									if (isset($row['rating'])) {
										for ($i = 1; $i <= $row['rating']; $i++) {
											if ($i <= 5) {
												echo '<span class="fa fa-star checked"></span>';
											}
										}
									}
									?>
								</div>
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
							<p class="penulis">-
								<?= $row['penulis'] ?>
							</p>
						</div>
					</li>
				</ul>
			<?php } else { ?>
				<ul>
					<li>
						<?php
						$sql = mysqli_query($db, "SELECT judul_buku,penulis,image FROM buku WHERE buku.id = {$_GET['bukid']}");
						$buku = mysqli_fetch_assoc($sql);
						?>
						<img src="../Admin/Temp/<?= $buku['image'] ?>"
							alt="ini adalah gambar dari buku <?= $buku['judul_buku'] ?>" />
						<div class="wrapper-books">
							<div class="row">
								<h2>
									<?= $buku['judul_buku'] ?>
								</h2>
							</div>
							<p>Belum ada Rating</p>
							<p class="penulis">-
								<?= $buku['penulis'] ?>
							</p>
						<?php } ?>
				</li>
			</ul>
			<hr>
			<p class="beri-ulasan">
				Beri Ulasan:
			</p>
			<form method="post" onsubmit="
				event.preventDefault();
				$(document).ready(function() {
						$.post('component/Buku.php', {
								ulasan: true,
								isi_ulasan: $('#isi_ulasan').val(),
								rating: $('input[name=\'rate\']:checked').val(),
								user_id: <?= $_GET['usr'] ?>,
								buku_id: <?= $_GET['bukid'] ?>
							});
							$('#ulasan').remove();
							$('body').removeAttr('height')
						});
							Peringatan.sukses('Ulasan berhasil dikirim!', 2500);
			">
				<ul>
					<div class="input-ulasan">
						<li>
							<p>Beri Rating:</p>
							<div>
								<?php for ($i = 1; $i <= 5; $i++): ?>
									<input type="radio" name="rate" value="<?= $i ?>" id="radio<?= $i ?>" required>
									<label for="radio<?= $i ?>">
										<i class="fa fa-star"></i>
									</label>
								<?php endfor; ?>
							</div>
						</li>
						<li class="isi-ulasan">
							<label for="ulasan">Ulasan</label>
							<textarea name="ulasan" id="isi_ulasan" cols="40" rows="5" placeholder="Ketik Ulasan anda"
								required></textarea>
							<button type="submit" name="ulasan"><i class="fa-solid fa-paper-plane"></i>kirim</button>
						</li>
					</div>
				</ul>
			</form>
			<?php
			if (isset($row['rating'])) {
				$sql = mysqli_query($db, "SELECT ulasan.*,buku.judul_buku,buku.image,buku.penulis, loginuser.username, loginuser.gambar FROM ulasan INNER JOIN buku ON ulasan.buku_id = buku.id INNER JOIN loginuser ON ulasan.user_id = loginuser.id WHERE ulasan.buku_id = buku.id");
				foreach ($sql as $cell):
					?>
					<div class="card komentar">
						<div class="profile">
							<img src="../.temp/<?= $cell['gambar'] ?>" alt="photo profile user verdi" width="35" />
							<p class="nickname-saya">
								<?= $cell['username'] ?>
							</p>
						</div>
						<div class="komentar">
							<p>
								<?= $cell['isi_ulasan'] ?>
							</p>
						</div>
					</div>
				<?php endforeach;
			} else { ?>
				<p>Tidak Ada ulasan</p>
			<?php } ?>
		</div>
	</div>
</div>


<div id="ditolak">
	<div class="content" id="add">
		<div class="title">
			<h1>Penolakan Buku</h1>
			<button onclick="
			$('#add').attr('id', 'close');
			
			setTimeout(()=>{
			$('#ditolak').remove();
			$('.popup').attr('hidden', true);
			$('body').removeAttr('height')
			},500);
		">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<p class="pesan">Anda ditolak karena <br>
			<strong>
				<?= $alasan['alasan'] ?>
			</strong>
		</p>
		<div class="hubungi-admin">
			<p>Hubungi admin berikut:</p>
			<ul>
				<li>
					<p>Bu Rahayu : </p>
					<i>09876544321</i>
				</li>
				<li>
					<p>Bu Vitri : </p>
					<i>089876765422</i>
				</li>
			</ul>
		</div>
	</div>
</div>

<div id="ditolak-admin">
	<div class="content" id="add">
		<div class="title">
			<h1>Penolakan Buku</h1>
			<button onclick="
			$('#add').attr('id', 'close');
			
			setTimeout(()=>{
			$('#ditolak-admin').remove();
			$('.popup').attr('hidden', true);
			$('body').removeAttr('height')
			},500);
		">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<p class="pesan">Anda menolaknya karena <br>
			<strong>
				<?= $alasan['alasan'] ?>
			</strong>
		</p>
	</div>
</div>