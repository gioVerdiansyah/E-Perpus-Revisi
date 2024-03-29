<?php
if ($_SERVER['REQUEST_URI'] == "/perpus_sekolah/Admin/database/functions.php") {
	header("Location: ../index.php");
	exit();
}

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


// INSERT
function insert($data)
{
	$judul_buku = htmlspecialchars($data["judul_buku"]);
	$kode_buku = htmlspecialchars($data["kode_buku"]);
	$kategori = htmlspecialchars($data["kategori"]);
	$penulis = htmlspecialchars($data["penulis"]);
	$penerbit = htmlspecialchars($data["penerbit"]);
	$tahun_terbit = htmlspecialchars($data["tahun_terbit"]);
	$isbn = htmlspecialchars($data["isbn"]);
	$jumlah_halaman = htmlspecialchars($data["jumlah_halaman"]);
	$sinopsis = htmlspecialchars($data["sinopsis"]);
	$jumlah_buku = htmlspecialchars($data["jumlah_buku"]);
	$image = upload();

	if (!$image) {
		return false;
	}

	global $db;
	$query = "INSERT INTO buku VALUES (NULL, '$judul_buku','$kode_buku','$kategori','$penulis','$penerbit','$image','$tahun_terbit','$isbn','$jumlah_halaman', '$jumlah_buku', '$sinopsis')";
	mysqli_query($db, $query);

	return mysqli_affected_rows($db);
}
function upload()
{
	if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
		$fileName = $_FILES["image"]["name"];
		$fileSize = $_FILES["image"]["size"];
		$fileError = $_FILES["image"]["error"];
		$fileTemp = $_FILES["image"]["tmp_name"];

		if ($fileError === 1) {
			echo "<script>alert('Masukkan gambar!');</script>";
			return false;
		}

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

		move_uploaded_file($fileTemp, "../Temp/" . $fileGenerateName);
		return $fileGenerateName;
	}
	return 'default.jpg';
}


// update
function updt($data)
{
	$id = $data["id"];
	$judul_buku = htmlspecialchars($data["judul_buku"]);
	$kode_buku = htmlspecialchars($data["kode_buku"]);
	$kategori = htmlspecialchars($data["kategori"]);
	$penulis = htmlspecialchars($data["penulis"]);
	$tahun_terbit = htmlspecialchars($data["tahun_terbit"]);
	$isbn = htmlspecialchars($data["isbn"]);
	$jumlah_halaman = htmlspecialchars($data["jumlah_halaman"]);
	$sinopsis = htmlspecialchars($data["sinopsis"]);
	$penerbit = htmlspecialchars($data["penerbit"]);
	$jumlah_buku = htmlspecialchars($data["jumlah_buku"]);
	$oldImage = htmlspecialchars($data["oldImage"]);

	if ($_FILES["image"]["error"] === 4) {
		$image = $oldImage;
	} else {
		$image = upload();
	}

	global $db;
	$query = "UPDATE buku SET judul_buku = '$judul_buku', kode_buku = '$kode_buku' , kategori = '$kategori', penulis = '$penulis', penerbit = '$penerbit', `image` = '$image', tahun_terbit = '$tahun_terbit', isbn = '$isbn', jumlah_halaman = '$jumlah_halaman', jumlah_buku = '$jumlah_buku', sinopsis = '$sinopsis' WHERE id = $id";
	mysqli_query($db, $query);

	return mysqli_affected_rows($db);
}




function getStock($buku)
{
	global $db;
	$stock = mysqli_query($db, "SELECT jumlah_buku FROM buku WHERE judul_buku = '$buku'");
	$sisa_buku = mysqli_fetch_assoc($stock);

	return $sisa_buku['jumlah_buku'];
}

function konversiNamaHari($namaHari)
{
	$hari = [
		'Sunday' => 'Minggu',
		'Monday' => 'Senin',
		'Tuesday' => 'Selasa',
		'Wednesday' => 'Rabu',
		'Thursday' => 'Kamis',
		'Friday' => 'Jum\'at',
		'Saturday' => 'Sabtu'
	];

	return $hari[$namaHari];
}
function getDay($val, $tgl_pinjam)
{
	if ($tgl_pinjam) {
		$explode = explode(" ", $val);
		return konversiNamaHari(date("l", strtotime(str_replace('/', '-', end($explode)))));
	} else {
		return konversiNamaHari(date("l", strtotime($val)));
	}
}


class Pagenation
{
	public $awalData, $dataPerhalaman, $jumlahData, $jumlahHalaman, $halamanAktif, $table;

	public function __construct($dataPerhalaman, $table, $halamanAktif)
	{
		$this->dataPerhalaman = intval($dataPerhalaman);
		$this->table = $table;
		$this->halamanAktif = intval($halamanAktif);
	}
	public function dataPerhalaman()
	{
		$this->dataPerhalaman = (isset($this->dataPerhalaman)) ? $this->dataPerhalaman : 10;
		return $this->dataPerhalaman;
	}
	public function jumlahData()
	{
		$this->jumlahData = count(query("SELECT * FROM {$this->table}"));
		return $this->jumlahData;
	}
	public function jumlahHalaman()
	{
		$this->jumlahHalaman = intval(ceil($this->jumlahData() / $this->dataPerhalaman()));
		return $this->jumlahHalaman;
	}
	public function halamanAktif()
	{
		$this->halamanAktif = (isset($this->halamanAktif)) ? $this->halamanAktif : 1;
		return $this->halamanAktif;
	}

	public function awalData()
	{
		$this->awalData = ($this->dataPerhalaman * $this->halamanAktif) - $this->dataPerhalaman;
		return $this->awalData;
	}
}
?>