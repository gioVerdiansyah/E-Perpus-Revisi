<?php
require "../../../Admin/database/functions.php";
$id = $_GET['bukid'];

$fillIn = query("SELECT * FROM buku WHERE id = $id")[0];

$id = $_GET['pjm_id'];

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
				<tbody>
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
				</tbody>
				<tbody class="thubmnail-book">
					<tr>
						<td class="image-book"><img src="../Admin/Temp/<?= $fillIn['image'] ?>"
								alt="Thumbnail buku <?= $fillIn['judul_buku'] ?>"></td>
					</tr>
				</tbody>
			</table>
			<p class="sinopsis">Sinopsis: </p>
			<p>
				<?= $fillIn["sinopsis"] ?>
			</p>
		</div>
	</div>
</div>

<?php
$id = $_GET['usr'];
$kuery = "SELECT loginuser.*, data_user.*
FROM loginuser
LEFT JOIN data_user ON data_user.user_id = loginuser.id
WHERE loginuser.id = $id
";
$sql = mysqli_query($db, $kuery);
$profile = mysqli_fetch_assoc($sql);
?>

<div id="profile">
	<div class="content" id="add">
		<div class="title">
			<h1>Profile
				<?= ucfirst($profile['username']) ?>
			</h1>
			<button onclick="
				$('#add').attr('id', 'close');
				
				setTimeout(()=>{
				$('#profile').remove();
				$('.popup').attr('hidden', true);
				$('body').removeAttr('height')
				},500);
			">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<div class="data">
			<!-- profile -->
			<div class="profile">
				<form method="post" id="myForm" action="" enctype="multipart/form-data">
					<div class="image">
						<input type="hidden" name="oldImage" value="<?= $profile['gambar'] ?>">
						<img src="../.temp/<?= $profile['gambar'] ?>" id="gambarTampilan"
							alt="Photo profile user <?= $profile['username'] ?>">
						<input type="file" name="gambar" id="image" onchange="
					let img = document.querySelector('#gambarTampilan');
					let input = document.querySelector('#image');
					let reader = new FileReader();
					reader.onload = function(e) {
						img.src = e.target.result;
					}
					reader.readAsDataURL(input.files[0]);
					">
					</div>
					<input type="hidden" name="id" value="<?= $profile['id'] ?>">
					<div class="nama">
						<label for="username">Nama:</label>
						<input type="text" name="username" id="username" value="<?= $profile['username'] ?>">
					</div>
					<div class="nama email">
						<label for="email">Email</label><br>
						<input type="email" name="email" id="email" value="<?= $profile['email'] ?>" required>
					</div>
					<div class="row">
						<div class="col">
							<label for="pass_lama">Password Lama:</label><br>
							<input type="password" name="pass_lama" id="pass_lama" oninput="
								$('#pass').attr('required', true);
							">
						</div>
						<div class="col">
							<label for="pass">Password Baru:</label><br>
							<input type="password" name="pass" id="pass">
						</div>
					</div>
					<div class="col textarea">
						<label for="deskripsi">Deskripsi:</label>
						<textarea name="deskripsi" id="deskripsi" cols="50"
							rows="3"><?= $profile['deskripsi'] ?></textarea>
					</div>
					<div class="row kirim">
						<button type="submit" name="new_data"><i class="fa-solid fa-paper-plane"></i> Perbarui
							data</button>
					</div>
				</form>
				<div class="row">
					<p>Bergabung sejak <br>
						<?= $profile['tanggal_bergabung'] ?>
					</p>
					<button type="button" onclick="
							Peringatan.konfirmasi('Apakah anda yakin ingin menghapus akun anda?', (istrue)=> {
								if(istrue){
								$.get('component/Home.php', {
									hapus_akun: true,
									id: '<?= $profile['id'] ?>'
								})
								$('#profile').remove();
							}
							})
						" class="delete">Hapus Akun!</button>
					<p>Akan otomatis logout!</p>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<div id="peminjaman" class="insert">
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
					if($('#jumlah_pinjam').val() > <?= $_GET['jml'] ?>){
						Peringatan.ditolak('Meminjam buku tidak boleh lebih dari stock buku!!!', 2500);
					}else{
						$.post('component/Buku.php', {
						send: true,
						buku_id: <?= $_GET['bukid'] ?>,
						jumlah_pinjam: $('#jumlah_pinjam').val(),
						tanggal_pengembalian: $('#tanggal_pengembalian').val()
					});
					$('#peminjaman').remove();
					$('body').removeAttr('height')
						Peringatan.sukses('Permintaan meminjam buku <?= $_GET['bukunya'] ?> berhasil dikirim', 2500);
					}
						});
						">
				<ul>
					<div>
						<li>
							<label for="jumlah_pinjam">Jumlah Pinjam</label>
							<?php if (intval(getStock($_GET['bukunya'])) < 1) { ?>
							<input type="text" class="input disabled" placeholder="Stock buku sudah habis!" disabled>
							<?php } else { ?>
							<input type="number" min="1" max="<?= $_GET['jml'] ?>" step="1" name="jumlah_pinjam"
								id="jumlah_pinjam" required placeholder="total buku adalah <?= $_GET['jml'] ?>" oninput="const maxLength = 2;

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
<div id="peminjaman" class="update">
	<div class="content" id="add">
		<div class="title">
			<h1>Pinjam Buku Update</h1>
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
						$.post('component/Peminjaman.php', {
								pinjam_update: true,
								pjm_id: <?= $_GET['pjm_id'] ?>,
								jumlah_pinjam: $('#jumlah_pinjam').val(),
								tanggal_pengembalian: $('#tanggal_pengembalian').val()
							});
							$('#peminjaman').remove();
							$('body').removeAttr('height')
						});
							Peringatan.sukses('Berhasil meupdate buku <?= $_GET['bukunya'] ?>!!', 2500);
						">
				<ul>
					<div>
						<?php
							$pjm_id = $_GET['pjm_id'];
							$kuery = "SELECT peminjam.* FROM peminjam WHERE id = $pjm_id";
							$pinjam_update = mysqli_fetch_assoc(mysqli_query($db, $kuery));
						?>
						<li>
							<label for="jumlah_pinjam">Jumlah Pinjam</label>
							<?php if (intval(getStock($_GET['bukunya'])) < 1) { ?>
							<input type="text" class="input disabled" placeholder="Stock buku sudah habis!" disabled>
							<?php } else { ?>
							<input type="number" min="1" max="<?= $_GET['jml'] ?>" step="1" name="jumlah_pinjam"
								id="jumlah_pinjam" value="<?= $pinjam_update['jumlah_pinjam'] ?>" required
								placeholder="total buku adalah <?= $_GET['jml'] ?>" oninput="const maxLength = 2;

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
								id="tanggal_pengembalian" value="<?= $pinjam_update['tanggal_pengembalian'] ?>"
								required>
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


<div id="input-ulasan">
	<div class="title">
		<h1>Beri Ulasan Buku</h1>
		<button onclick="
				$('#add').attr('id', 'close');
				
				setTimeout(()=>{
				$('#input-ulasan').remove();
				$('.update-komentar').attr('hidden', true);
				$('body').removeAttr('height')
				},500);
			">
			<i class="fa-solid fa-xmark"></i>
		</button>
	</div>
	<?php
	$uls_id = $_GET['uls'];
	$sql = mysqli_query($db, "SELECT ulasan.*,ulasan.id AS uls_id, loginuser.id, buku.id FROM ulasan LEFT JOIN loginuser ON ulasan.user_id = loginuser.id INNER JOIN buku ON ulasan.buku_id = buku.id WHERE ulasan.id = $uls_id");
	$ulasan = mysqli_fetch_assoc($sql);
	?>
	<form action="" method="post">
		<ul>
			<li>
				<p>Beri Rating:</p>
				<div>
					<input type="hidden" id="uls_id" name="uls_id" value="<?= $ulasan['uls_id'] ?>">
					<?php for ($i = 1; $i <= 5; $i++): ?>
					<input type="radio" name="rate" value="<?= $i ?>" class="radio" id="radio<?= $i ?>"
						<?php if ($i == $ulasan['rating']) echo 'checked' ?> required>
					<label for="radio<?= $i ?>" id="radio<?= $i ?>">
						<i class="fa fa-star <?php if ($i <= $ulasan['rating']) echo 'checked' ?>"></i>
					</label>
					<?php endfor; ?>
				</div>

			</li>
			<li class="isi-ulasan">
				<label for="ulasan">Ulasan</label>
				<textarea name="isi_ulasan" id="isi_ulasan" cols="40" rows="5" placeholder="Ketik Ulasan anda"
					required><?= $ulasan['isi_ulasan'] ?></textarea>
				<button type="submit" name="ulasan"><i class="fa-solid fa-paper-plane"></i> kirim</button>
			</li>
		</ul>
	</form>
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
			<div class="update-komentar" hidden></div>
			<ul>
				<?php
				$bukid = $_GET['bukid'];
				$ulasan = mysqli_query($db, "SELECT ulasan.*, AVG(ulasan.rating) AS rating, buku.judul_buku, buku.image, buku.penulis, loginuser.username, data_user.gambar
				FROM ulasan
				INNER JOIN buku ON ulasan.buku_id = buku.id
				INNER JOIN loginuser ON ulasan.user_id = loginuser.id
				INNER JOIN data_user ON loginuser.id = data_user.user_id
				WHERE ulasan.buku_id = $bukid
				GROUP BY ulasan.id, buku.judul_buku, buku.image, buku.penulis, loginuser.username, data_user.gambar ");
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
										for ($i = 1; $i <= 5; $i++): ?>
								<?php if ($i <= $row['rating']) { ?>
								<span class="fa fa-star checked"></span>
								<?php } else { ?>
								<span class="fa fa-star"></span>
								<?php } ?>
								<?php endfor; ?>
								<?php } ?>
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
			<?php if (isset($_GET['usr'])): ?>
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
								user_id: <?= isset($_GET['usr']) ? $_GET['usr'] : NULL ?>,
								buku_id: <?= $_GET['bukid'] ?>
							});
							$('#ulasan').remove();
							Peringatan.sukses('Ulasan berhasil dikirim!', 2500);
							$('body').removeAttr('height')
						});
			">
				<ul>
					<div class="input-ulasan">
						<li>
							<p>Beri Rating:</p>
							<div>
								<?php for ($i = 1; $i <= 5; $i++): ?>
								<input type="radio" name="rate" value="<?= $i ?>" id="radio<?= $i ?>" required>
								<label for="radio<?= $i ?>" id="radio<?= $i ?>">
									<i class="fa fa-star"></i>
								</label>
								<?php endfor; ?>
							</div>
						</li>
						<li class="isi-ulasan">
							<label for="ulasan">Ulasan</label>
							<textarea name="ulasan" id="isi_ulasan" cols="40" rows="5" placeholder="Ketik Ulasan anda"
								required></textarea>
							<button type="submit" name="ulasan"><i class="fa-solid fa-paper-plane"></i> kirim</button>
						</li>
					</div>
				</ul>
			</form>
			<?php endif; ?>
			<p class="kumpulan-komentar">komentar user:</p>
			<hr>
			<?php
			if (isset($row['rating'])) {
				$sql = mysqli_query($db, "SELECT ulasan.*, buku.judul_buku, buku.image, buku.penulis, loginuser.username, data_user.gambar
				FROM ulasan
				INNER JOIN buku ON ulasan.buku_id = buku.id
				INNER JOIN loginuser ON ulasan.user_id = loginuser.id
				INNER JOIN data_user ON loginuser.id = data_user.user_id
				WHERE ulasan.buku_id = {$_GET['bukid']} ORDER BY ulasan.id DESC
				");
				foreach ($sql as $i => $cell):
					++$i;
					?>
			<div class="card komentar">
				<div class="profile">
					<div>
						<img src="../.temp/<?= $cell['gambar'] ?>" alt="photo profile user verdi" width="35" />
						<div>
							<span>
								<?= $cell['tanggal_komentar'] ?>
							</span>
							<p class="nickname-saya">
								<?= $cell['username'] ?>
							</p>
						</div>
					</div>
					<?php
							// Authentikasi Cooyyy
							$name = '';
							$key = $_COOKIE["UIDprpsMJYNisThroe"];
							if ($key === hash("sha512", $cell["username"])) {
								$name = $cell["username"];
							}
							if ($name == $cell["username"]):
								?>
					<div class="opsi">
						<ul id="action-list<?= $i ?>" style="display:none">
							<li class="edit"><button onclick="
								$('.update-komentar').load('component/result/fraction_group.php?bukid=<?= $_GET['bukid'] ?>&&usr=<?= isset($_GET['usr']) ? $_GET['usr'] : NULL ?>&&uls=<?= $cell['id'] ?>&&pjm_id=1 #input-ulasan', ()=>{
									$('.update-komentar').removeAttr('hidden');
								});
							">edit</button></li>
							<li class="delete"><button onclick="
								$.post('component/Buku.php', {
									delete_ulasan:true,
									uls_id: <?= $cell['id'] ?>
								});
								Peringatan.sukses('Komentar berhasil di hapus!');
							">delete</button></li>
							<li class="close"><button onclick="
								$('#action-list<?= $i ?>').fadeOut(500);
							">close</button></li>
						</ul>
						<button class="options" id="options" onclick="
							$('#action-list<?= $i ?>').fadeIn(500);">
							<span></span>
							<span></span>
							<span></span>
						</button>
					</div>
					<?php endif; ?>
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