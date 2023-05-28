<?php
require "../../../Admin/database/functions.php";
$id = $_GET['bukid'];

$fillIn = query("SELECT * FROM buku WHERE id = $id")[0];
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
                                bukunya: $('#bukunya').val(),
                                kategori: $('#kategori').val(),
                                jumlah_pinjam: $('#jumlah_pinjam').val(),
                                tanggal_pengembalian: $('#tanggal_pengembalian').val()
                            });
                            $('#peminjaman').remove();
                            $('body').removeAttr('height')
                        });
                        Peringatan.sukses('Permintaan meminjam buku <?= $_GET['bukunya'] ?> berhasil dikirim');
                        ">
                <ul>
                    <!-- hidden input -->
                    <input type="hidden" id="bukunya" name="bukunya" value="<?= $_GET['bukunya'] ?>">
                    <input type="hidden" id="kategori" name="kategori" value="<?= $_GET['kategori'] ?>">
                    <div>
                        <li><label for="jumlah_pinjam">Jumlah Pinjam</label>
                            <input type="number" min="1" max="<?= $_GET['jml'] ?>" step="1" name="jumlah_pinjam"
                                id="jumlah_pinjam" required placeholder="max buku adalah <?= $_GET['jml'] ?>" oninput="
                        const maxLength = 2;

                        if (this.value.length > maxLength) {
                          this.value = this.value.slice(0, maxLength);
                        }
                        ">
                        </li>
                        <?php
                        $m = date('m');
                        $max = date('Y-m-d', strtotime('+1 month'));
                        ?>
                        <li><label for="tanggal_pengembalian">Tanggal Penegmbalian</label>
                            <input type="date" min="<?= date('Y-m-d'); ?>" max="<?= $max ?>" name="tanggal_pengembalian"
                                id="tanggal_pengembalian" required>
                        </li>
                    </div>
                    <li class="send">
                        <button type="submit" name="send">Kirim</button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>