<?php
if ($_SERVER['REQUEST_URI'] == "/perpus_sekolah/function.php") {
	header("Location: index.php");
}
// koneksi ke databases
$db = mysqli_connect(
	"localhost",
	"root",
	"",
	"perpustakaan_sekolah"
);

function query($query)
{
	global $db;
	$result = mysqli_query($db, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function register($data)
{
	global $db;

	$username = mysqli_real_escape_string($db,strtolower(stripslashes($data["username"])));
	// strtolower() ini fungsi untuk mengubah huruf besar menjadi huruf kecil, stripslashes() ini fungsi untuk menghilangkan karaker unik dalam username
	$pass = mysqli_real_escape_string($db, $data["pass"]);
	// ini fungsi untuk memungkinkan si user untuk memasukkan password ada tanda kutipnya dan akan dimasukkan ke dalam DB secara aman
	$confirmPass = mysqli_real_escape_string($db, $data["confirmPass"]);
	$email = mysqli_real_escape_string($db, $data["email"]);
	$deskripsi = mysqli_real_escape_string($db, $data["deksripsi"]);


	// cek username sudah ada atau belum?
	$nama = mysqli_query($db, "SELECT username FROM loginuser WHERE username = '$username'");
	$mail = mysqli_query($db, "SELECT username FROM loginuser WHERE email = '$email'");
	// cari data username dari tabel user dimana username = variabel username (TRUE)

	if (mysqli_fetch_assoc($nama)) {
		// berarti TRUE dan arraynya ada isinya
		$err = "username sudah ada!";
		$_SESSION["error"] = $err;
		return false;
	}

	if (mysqli_fetch_assoc($mail)) {
		$err = "email sudah digunakan!";
		$_SESSION["error"] = $err;
		return false;
	}
	if (strlen($deskripsi) >= 700) {
		$err = "Terlalu banyak kata!";
		$_SESSION["error"] = $err;
		return false;
	}


	// cek konfirmasi password
	if ($pass !== $confirmPass) {
		$err = "password tidak sama!";
		$_SESSION["error"] = $err;
		return false; //supaya masuk kedalam else
	}

	// Enkripsi password
	// menggunakan fungsi password_hash() untuk mengenkripsi karena jika menggunakan md5 itu adalah versi lama dan mudah untuk diketahui, hanya memasukkan string enkripsinya di google
	$pass = password_hash($pass, PASSWORD_DEFAULT);

	$gambar = upload();

	if (!is_string($gambar)) {
		$err = "ada masalah saat mengupload photo profile!";
		$_SESSION["error"] = $err;
		return false;
	}

	$bergabung = date("H:i d/m/Y");

	// menambah kan ke dalam database
	mysqli_query($db, "INSERT INTO loginuser VALUE(
        NULL, '$username', '$email', '$pass'
        )");

	$user_id = mysqli_insert_id($db);

	mysqli_query($db, "INSERT INTO data_user VALUE(
        $user_id, '$gambar','$deskripsi', '$bergabung'
        )");

	return mysqli_affected_rows($db);
}

function upload()
{
	if ($_FILES["gambar"]["error"] == UPLOAD_ERR_OK) {
		$fileName = $_FILES["gambar"]["name"];
		$fileSize = $_FILES["gambar"]["size"];
		// $fileError = $_FILES["gambar"]["error"];
		$fileTemp = $_FILES["gambar"]["tmp_name"];

		$extensionValid = ["jpg", "jpeg", "png"];
		$extensionFile = explode('.', $fileName);
		$extensionFile = strtolower(end($extensionFile));

		if (!in_array($extensionFile, $extensionValid)) {
			$err = "masukkan ekstensi gambar: \"jpg\",\"jpeg\",\"png\"!";
			$_SESSION['error'] = $err;
			return false;
		}

		if ($fileSize > 10000000) {
			$err = "gambar tidak boleh lebih 10MB";
			$_SESSION['error'] = $err;
			return false;
		}

		$fileGenerateName = uniqid() . "." . $extensionFile;

		move_uploaded_file($fileTemp, __DIR__."/.temp/" . $fileGenerateName);
		return $fileGenerateName;
	}
	return 'default.jpg';
}
function updateData($data)
{
	global $db;

	$id = intval($data["id"]);

	$username = strtolower(stripslashes($data["username"]));
	$pass_lama = mysqli_real_escape_string($db, $data["pass_lama"]);
	$pass = mysqli_real_escape_string($db, $data["pass"]);
	$email = mysqli_real_escape_string($db, $data["email"]);
	$deskripsi = mysqli_real_escape_string($db, $data["deskripsi"]);
	$oldImage = mysqli_real_escape_string($db, $data["oldImage"]);


	$mail = mysqli_query($db, "SELECT email FROM loginuser WHERE id = $id");
	$nama = mysqli_query($db, "SELECT username FROM loginuser WHERE id = $id");
	// cari data username dari tabel user dimana username = variabel username (TRUE)

	if ($username === mysqli_fetch_assoc($nama)) {
		if (mysqli_fetch_assoc($nama)) {
			$err = "username sudah ada!";
			$_SESSION['error'] = $err;
			return false;
		}
	}

	if ($email === mysqli_fetch_assoc($mail)) {
		if (mysqli_fetch_assoc($mail)) {
			$err = "email sudah digunakan!";
			$_SESSION['error'] = $err;
			return false;
		}
	}

	if (strlen($deskripsi) >= 700) {
		$err = "Terlalu banyak kata!";
		$_SESSION['error'] = $err;
		return false;
	}

	$query = '';

	if (!empty(trim($pass_lama)) && !empty(trim($pass))) {
		$password = mysqli_fetch_assoc(mysqli_query($db, "SELECT pass FROM loginuser WHERE id = $id"))['pass'];
		if (password_verify($pass_lama, $password)) {
			$pass = password_hash($pass, PASSWORD_DEFAULT);
			$query = "UPDATE loginuser SET `pass` = '$pass' WHERE id = $id";
			mysqli_query($db, $query);
		} else {
			$err = "password tidak sama!";
			$_SESSION['error'] = $err;
			return false;
		}
	} else {
		$query = "UPDATE loginuser SET `username` = '$username', `email` = '$email' WHERE id = $id";
	}

	$query = "UPDATE loginuser SET `username` = '$username', `email` = '$email' WHERE id = $id";
	mysqli_query($db, $query);

	$image = '';

	if ($_FILES["gambar"]["error"] === 4) {
		$image .= $oldImage;
	} else {
		$image .= upload();
		if (!$image) {
			$image = 'default.jpg';
		}
	}

	$query = "UPDATE data_user SET `gambar` = '$image', `deskripsi` = '$deskripsi' WHERE user_id = $id";
	mysqli_query($db, $query);

	return mysqli_affected_rows($db);
}