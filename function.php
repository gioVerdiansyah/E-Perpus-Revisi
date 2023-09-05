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

	$username = strtolower(stripslashes($data["username"]));
	// strtolower() ini fungsi untuk mengubah huruf besar menjadi huruf kecil, stripslashes() ini fungsi untuk menghilangkan karaker unik dalam username
	$pass = mysqli_real_escape_string($db, $data["pass"]);
	// ini fungsi untuk memungkinkan si user untuk memasukkan password ada tanda kutipnya dan akan dimasukkan ke dalam DB secara aman
	$confirmPass = mysqli_real_escape_string($db, $data["confirmPass"]);
	$email = mysqli_real_escape_string($db, $data["email"]);
	$deskripsi = mysqli_real_escape_string($db, $data["deksripsi"]);


	// cek username sudah ada atau belum?
	$nama = mysqli_query($db, "SELECT username FROM loginuser WHERE username = '$username'");
	$mail = mysqli_query($db, "SELECT username FROM loginuser WHERE username = '$email'");
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
        '', '$username', '$email', '$pass', '$gambar', '$deskripsi', '$bergabung'
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
			echo "
        <script>
            alert('masukkan ekstensi gambar: \"jpg\",\"jpeg\",\"png\"!');
        </script>";
			return false;
		}

		if ($fileSize > 10000000) {
			echo "
        <script>
            alert('gambar tidak boleh lebih 10MB');
        </script>";
			return false;
		}

		$fileGenerateName = uniqid() . "." . $extensionFile;

		move_uploaded_file($fileTemp, ".temp/" . $fileGenerateName);
		return $fileGenerateName;
	}
	return 'default.jpg';
}