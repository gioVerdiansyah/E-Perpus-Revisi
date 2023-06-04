// side bar event
$(".side-bar ul .dropdown .master-data").click(function (e) { 
    e.preventDefault();
    $("#list-master-data").toggleClass("list-master-data-onclick");
    $(".side-bar ul li.dropdown .master-data .dropdown-icon").toggleClass("dropdown-icon-onclick");
    $(".side-bar ul .dropdown .master-data").toggleClass("addBg");
});


$("#darkmode").click(function (e) { 
    e.preventDefault();
    $("#dm").attr("href") === "CSS/Home-AdminDM.css" ? $("#dm").removeAttr("href") : $("#dm").attr("href", "CSS/Home-AdminDM.css");

    $("#darkmode i").attr("class") === "fa-solid fa-moon" ? $("#darkmode i").attr("class", "fa-solid fa-sun") : $("#darkmode i").attr("class", "fa-solid fa-moon");
});

$("main .content-wrapper .heading .profile").mouseenter(function() {
    $("main .content-wrapper .heading .profile .dropdown-profile").stop().slideDown();
  });
  
  $("main .content-wrapper .heading .profile").mouseleave(function() {
    $("main .content-wrapper .heading .profile .dropdown-profile").stop().slideUp();
  });
  

  $(document).ready(function() {
    let cliked = false;
    $(".content-wrapper .heading .action button#humberger").click(function (e) { 
        e.preventDefault();
        if(!cliked){
            $(".content-wrapper .heading .action button span:first-child").css({'transform-origin': '0 0','transform': 'rotate(45deg) translate(1px, -1px)'});
            $(".content-wrapper .heading .action button span:last-child").css({'transform-origin': '0 100%','transform': 'rotate(-45deg) translate(1px, 1px)'});
            $(".content-wrapper .heading .action button span:nth-child(2)").css({'opacity': 0,'transform': 'scale(0)'});
            $(".side-bar").slideUp(800);
            cliked = true;
        }else{
            $(".content-wrapper .heading .action button span:first-child").css({'transform-origin': '0 0','transform': 'rotate(0deg) translate(0px, 0px)'});
            $(".content-wrapper .heading .action button span:last-child").css({'transform-origin': '0 0','transform': 'rotate(0deg) translate(0px, 0px)'});
            $(".content-wrapper .heading .action button span:nth-child(2)").css({'opacity': 1,'transform': 'scale(1)'});
            $(".side-bar").slideDown(1000);
            cliked = false;
        }
    });
  });

// AJAX
    $(".content").load('component/Home-Admin.php');
    function onReady (ini, path) {
        $('.content').load(path);
        $('*').removeClass('active');
        $(ini).addClass('active');
    }

    $(".dashboard").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Home-Admin.php');
    });

    $("#penulis h3").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Master-Penulis.php');
    });
    $("#penerbit h3").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Master-Penerbit.php');
    });
    $("#kategori h3").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Master-Kategori.php');
    });
    $("#buku h3").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Master-Buku.php');
    });
    $("#anggota h3").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Master-Anggota.php');
    });

    $("#persetujuan").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Data-Peminjam.php');
    });

    $("#laporan").click(function (e) { 
        e.preventDefault();
        onReady(this, 'component/Data-Laporan.php');
    });