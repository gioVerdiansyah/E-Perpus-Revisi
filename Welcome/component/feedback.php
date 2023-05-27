<?php
session_name("SSILGNPERPUSMEJAYAN");
session_start();
if (!isset($_SESSION["login-user"]) && !isset($_COOKIE["UsrLgnMJYNiSeQlThRuE"]) && !isset($_COOKIE["UIDprpsMJYNisThroe"])) {
    header("Location: ../../index.php");
    exit;
}
?>

<link rel="stylesheet" href="CSS/User/Feedback.css">
<div class="heading">
    <h1>Hai User bagaimana pendapat mu?</h1>
    <h2>Tentang:</h2>
    <ul>
        <li>
            <p>Performa Website kami</p>
        </li>
        <li>
            <p>Kepuasan Anda</p>
        </li>
        <li>
            <p>Design Website</p>
        </li>
        <li>
            <p>Pewarnaan Website</p>
        </li>
    </ul>
    <p class="feedback-anda">
        Berikanlah feedback Anda agar kami bisa terus mengembangkan aplikasi perpustakaan ini dengan lebih baik. Setiap
        masukan dan kritik dari pengguna sangat berharga bagi kami. Dengan memberikan feedback, Anda juga turut berperan
        dalam membantu meningkatkan kualitas layanan dan pengalaman pengguna pada aplikasi perpustakaan kami.
    </p>
</div>
<div id="loading"></div>
<div id="alert" style="display:none;">
    <h1>Pesan telah terkirim</h1>
    <p>Terima kasih atas waktu dan perhatiannya dalam memberikan feedback pada aplikasi kami.</p>
    <button onclick="
     document.querySelector('form').reset();
      $('#alert').fadeOut(800);
      ">
        <i class="fa-solid fa-xmark"></i>
    </button>
</div>
<form method="post" name="e_perpus_feedback">
    <input type="hidden" name="username" value="<?= $_GET['usr_nm'] ?>">
    <ul>
        <li>
            <label for="email">Email:</label><br>
            <input type="email" name="email" id="email" placeholder="Ketik email agar kami bisa menghubungi anda"
                autofocus required>
        </li>
        <li>
            <label for="message">Pesan:</label><br>
            <textarea name="message" id="message" placeholder="Ketik pesan yang ingin di sampaikan" required></textarea>
        </li>
        <li class="button">
            <button type="submit" name="submit" id="submit">Send</button>
            <button type="reset" id="hapus">Reset</button>
        </li>
    </ul>
</form>
<script>
$("#loading").hide();
try {
    let hari_ini = new Date();
    let getDate = new Date(hari_ini);
    getDate.setDate(hari_ini.getDate());
    let hariIni = getDate.getDate();



    if (localStorage.getItem("hasSentAMessage") === null) {
        localStorage.setItem("hasSentAMessage", JSON.stringify({
            alredy: 0,
            time: 0
        }));
    }

    let hasSentAMessage = JSON.parse(localStorage.getItem("hasSentAMessage"));


    // system send feedback
    const scriptURL =
        'https://script.google.com/macros/s/AKfycbwGM4sqlWw0KMudR0WPHD4zR3WltEmCP30WMvlcuh4y_ihKhNdgCMU7jkJmkq27tr4k/exec'
    const form = document.forms['e_perpus_feedback']


    form.addEventListener('submit', e => {
        e.preventDefault();
        $("#submit").hide();
        $("#hapus").hide();
        $("#alert").hide();
        $("#loading").html("<img src='../Assets/loading.gif' height='50'>Loading...");

        let value = {
            alredy: hasSentAMessage.alredy,
            time: hariIni
        }

        if (hasSentAMessage.alredy > 2) {
            if (JSON.parse(localStorage.getItem("hasSentAMessage")).time !== hariIni) {
                localStorage.setItem("hasSentAMessage", JSON.stringify({
                    alredy: 0,
                    time: hariIni
                }))
            } else {
                $("#alert").hide();
                $("form ul .button").html(
                    "<p><strong>Ini adalah batas pengiriman!</strong> <br> Terimakasih sudah perhatian kepada kami :) <br> jika anda menemukan bug bisa kirim email langsung kepada <a href='mailto:e01010010or@gmail.com'>kami.</a></p>"
                );
            }
        }


        if (localStorage.getItem("hasSentAMessage") === null) {
            hasSentAMessage.alredy = 1;
            if (hasSentAMessage.time !== 0) {
                value.time = hasSentAMessage.time;
            } else {
                value.time = hariIni;
            }
        } else if (hasSentAMessage.alredy < 4) {
            hasSentAMessage.alredy++;
        } else {
            hasSentAMessage.alredy = hasSentAMessage.alredy;
        }



        if (hasSentAMessage.alredy <= 3 || JSON.parse(localStorage.getItem("hasSentAMessage")).time !==
            hariIni) {
            $("#loading").show();
            fetch(scriptURL, {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => {
                    $("#submit").fadeIn();
                    $("#hapus").fadeIn();
                    $("#loading").fadeOut(500);
                    localStorage.setItem("hasSentAMessage", JSON.stringify(value))
                    if (hasSentAMessage.alredy <= 3 || JSON.parse(localStorage.getItem("hasSentAMessage"))
                        .time !== hariIni) {
                        $("#alert").fadeIn(800);
                    } else {
                        $("#alert").hide();
                    }
                })
                .catch(error => {
                    $("#loading").html("<p>Error! " + error.message + "</p>");
                    console.error('Error!', error.message);
                })
        }
    })
} catch (error) {}
</script>