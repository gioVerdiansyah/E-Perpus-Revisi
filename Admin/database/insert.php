<?php
require 'functions.php';
session_name("SSILGNADMINPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login"]) && !isset($_COOKIE["UISADMNLGNISEQLTRE"]) && !isset($_COOKIE["USRADMNLGNISEQLTHROE"])) {
    header("Location: ../login-admin.php");
    exit;
}
if (isset($_POST['submit'])) {
    if (insert($_POST) > 0) {
        echo "<script>
        alert('Data berhasil di tambahkan!');
        document.location.href = '../index.php';
        </script>";
    } else {
        echo "Data gagal di tambahkan" . "<br>" . mysqli_error($db);
    }
}
?>
<link rel="stylesheet" href="../CSS/database.css">
<form action="" method="post" enctype="multipart/form-data">
    <ul style="list-style-type:none;">
        <div class="first">
            <div>
                <li>
                    <label for="judul_buku">Judul Buku</label><br>
                    <input type="text" name="judul_buku" id="judul_buku" maxlength="64" required autofocus>
                </li>

                <li>
                    <label for="kategori">Kategori</label><br>
                    <input type="text" name="kategori" id="kategori" maxlength="144" required>
                </li>

                <li>
                    <label for="penulis">Penulis</label><br>
                    <input type="text" name="penulis" id="penulis" maxlength="144" required>
                </li>
            </div>
            <div>
                <li>
                    <label for="penerbit">Penerbit</label><br>
                    <input type="text" name="penerbit" id="penerbit" maxlength="144" required>
                </li>
                <li>
                    <label for="tahun_terbit">Tahun Terbit</label><br>
                    <input type="date" name="tahun_terbit" id="tahun_terbit" required>
                </li>
                <li>
                    <label for="isbn">ISBN</label><br>
                    <input type="text" step="-any" name="isbn" id="isbn" maxlength="144" required>
                </li>
            </div>
        </div>

        <li>
            <label for="sinopsis">Sinopsis Buku</label><br>
            <textarea name="sinopsis" id="sinopsis" rows="5"></textarea>
        </li>
        <div class="second">
            <li>
                <label for="jumlah_halaman">Jumlah Halaman</label><br>
                <input type="number" name="jumlah_halaman" max="1000000000" id="jumlah_halaman" required>
            </li>
            <li>
                <label for="jumlah_buku">Jumlah Buku</label><br>
                <input type="number" name="jumlah_buku" max="99" id="jumlah_buku" required>
            </li>
        </div>
        <li>
            <label for="image">Thumbnail</label><br>
            <img src="" width="45" id="img" hidden><br>
            <input type="file" name="image" id="gambar" onchange="
                    let img = document.querySelector('#img');
                    let input = document.querySelector('#gambar');
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        img.removeAttribute('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                    ">
        </li>
        <li style="margin-top:5px;">
            <button type="submit" name="submit">Send</button>
            <button type="reset">Reset</button>
        </li>

    </ul>
</form>