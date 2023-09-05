<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
require 'function.php';

// jika user sudah login
if (isset($_SESSION["login-user"]) || isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) || isset($_COOKIE["UIDprpsMJYNisThroe"])) {
	header("Location: Welcome/");
	exit;
}

$massage = "";
$redirect;

if (isset($_POST["submit"])) {
	global $massage, $redirect;
	if (register($_POST) === 1) {
		$redirect = true;
	} else {
		global $massage;
		$error = $_SESSION["error"];
		$massage = "<p style='color:red;margin:0;'>register gagal karena $error</p>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Login dulu~</title>
	<link rel="stylesheet" href="Welcome/CSS/register.css" />
	<script src="Welcome/JS/script.js"></script>
</head>

<body>
	<?php if (isset($redirect)): ?>
		<div id="redirect">
			<h1>Register Berhasil!!!</h1>
			<div>
				<p>Redirect otomatis dalam <span id="countdown"></span></p>
				<button id="cencel">Cencel</button>
			</div>
			<script>
				document.getElementById("cencel").addEventListener('click', () => {
					clearInterval(x);
					document.getElementById("redirect").remove();
				})
				var countDownDate = new Date().getTime() + 7000;
				var x = setInterval(function () {
					let countdown = document.getElementById("countdown");
					var now = new Date().getTime();

					var seconds = Math.floor((countDownDate - now) / 1000);

					countdown.innerHTML = seconds + "s";

					if (seconds <= 0) {
						clearInterval(x);
						window.location.href = "index.php";
					}
					if (seconds <= 2) {
						countdown.style = "color: red";
					}
				}, 1000);
			</script>
		</div>
	<?php endif ?>
	<div class="image">
		<!-- <img src="Welcome/Assets/bg6-2.svg" alt="bg6-2.svg" /> -->
	</div>
	<main>
		<h1>Selamat Datang user baru!<span id="hello">&#128075</span></h1>
		<form action="" method="post" enctype="multipart/form-data">
			<ul>
				<li>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" placeholder="Masukkan nama" maxlength="64"
						required />
				</li>
				<li>
					<label for="email">Email</label>
					<input type="email" name="email" id="email" placeholder="Masukkan email" maxlength="255" required />
				</li>
				<li>
					<label for="pass">Password</label>
					<input type="password" name="pass" id="pass" maxlength="144" required />
				</li>
				<li>
					<label for="confirmPass">Confirmation Password :</label>
					<input type="password" name="confirmPass" id="confirmPass" required>
				</li>
				<li>
					<label for="gambar">Photo Profile</label>
					<img src="" width="45" id="img">
					<input type="file" name="gambar" id="gambar" onchange="
					let img = document.querySelector('#img');
					let input = document.querySelector('#gambar');
					let reader = new FileReader();
					reader.onload = function(e) {
						img.src = e.target.result;
					}
					reader.readAsDataURL(input.files[0]);
					">

				</li>
				<li>
					<label for="deskripsi">Deksripsi Diri:</label>
					<textarea name="deksripsi" id="deksripsi" cols="50" rows="5"></textarea>
				</li>
				<?php echo $massage ?>
				<li>
					<button type="submit" name="submit">Sign Up</button>
				</li>
			</ul>
		</form>
		<p>Sudah terdaftar menjadi Anggota? <a href="index.php">masuk</a></p>
	</main>
</body>

</html>