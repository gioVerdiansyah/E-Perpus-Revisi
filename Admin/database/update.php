<?php
require 'functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
	header("Location: ../login-admin.php");
	exit;
}

$id = isset($_GET['id']) ? $_GET["id"] : 0;

$books = query("SELECT * FROM buku WHERE id = $id")[0];

if (isset($_POST["submit"])) {
	if (updt($_POST) > 0) {
		$_SESSION['ubah-buku'] = true;
		header("Location: ../index.php");
	} else {
		$_SESSION['ubah-buku'] = false;
		header("Location: ../index.php");
	}
}
?>
<div class="content-popup">
	<link rel="stylesheet" href="CSS/database.css">
	<form action="database/update.php" method="post" enctype="multipart/form-data">
		<div class="title">
			<h1 class="edit">Edit Buku</h1>
			<button class="edit" onclick="
			$('.content-popup').remove();
			
			$('.popup').attr('hidden', true);
			$('body').removeAttr('height')
		">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>
		<ul style="list-style-type:none;">
			<input type="hidden" name="id" value="<?= $books['id'] ?>">
			<input type="hidden" name="oldImage" value="<?= $books['image'] ?>">
			<div class="first">
				<div>
					<li>
						<label for="judul_buku">Judul Buku</label>
						<input type="text" name="judul_buku" id="judul_buku" maxlength="74" required
							value="<?= $books['judul_buku'] ?>">
					</li>
					<li>
						<label for="kode_buku">Kode Buku</label><br>
						<input type="number" name="kode_buku" id="kode_buku" required
							value="<?= $books['kode_buku'] ?>">
					</li>
					<li>
						<label for="kategori">Kategori</label>
						<input type="text" name="kategori" id="kategori" maxlength="144" required
							value="<?= $books['kategori'] ?>">
					</li>
				</div>
				<div>
					<li>
						<label for="penulis">Penulis</label>
						<input type="text" name="penulis" id="penulis" maxlength="144" required
							value="<?= $books["penulis"] ?>">
					</li>
					<li>
						<label for="penerbit">Penerbit</label>
						<input type="text" name="penerbit" id="penerbit" maxlength="144" required
							value="<?= $books["penerbit"] ?>">
					</li>
					<li>
						<label for="tahun_terbit">Tahun Terbit</label>
						<input type="date" name="tahun_terbit" id="tahun_terbit" required
							value="<?= $books["tahun_terbit"] ?>">
					</li>
				</div>
			</div>
			<li class="center">
				<label for="isbn">ISBN</label>
				<input type="text" name="isbn" id="isbn" maxlength="144" required value="<?= $books["isbn"] ?>">
			</li>
			<li>
				<label for="sinopsis">Sinopsis Buku</label>
				<textarea name="sinopsis" id="sinopsis" rows="5"></textarea>
				<script>
					document.querySelector("textarea").value = "<?= $books["sinopsis"] ?>";
				</script>
			</li>
			<div class="second">
				<li>
					<label for="jumlah_halaman">Jumlah Halaman</label>
					<input type="number" name="jumlah_halaman" max="1000000000" id="jumlah_halaman" required
						value="<?= $books["jumlah_halaman"] ?>">
				</li>
				<li>
					<label for="jumlah_buku">Jumlah Buku</label><br>
					<input type="number" name="jumlah_buku" max="99" id="jumlah_buku" required
						value="<?= $books['jumlah_buku'] ?>">
				</li>
			</div>
			<li>
				<label for="image">Thumbnail</label><br>
				<img src="Temp/<?= $books['image'] ?>" id="img" alt="gambar sebelumnya" height="70"><br>
				<input type="file" name="image" id="image" onchange="
					let img = document.querySelector('#img');
					let input = document.querySelector('#image');
					let reader = new FileReader();
					reader.onload = function(e) {
						img.src = e.target.result;
					}
					reader.readAsDataURL(input.files[0]);
					">
			</li>

			<li style="margin-top:5px;">
				<button type="reset">Reset</button>
				<button type="submit" name="submit">Send</button>
			</li>
		</ul>
	</form>
</div>