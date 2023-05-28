function muncul(selector){
    selector.attr('id', 'add');
    setTimeout(() => {
      selector.removeAttr('id')
    }, 500);
  }
  
  function tutup(selector){
    selector.attr('id', 'close');
    setTimeout(() => {
      selector.parent().remove();
      selector.removeAttr('id')
      selector.removeClass('shake');
    }, 500);
  }
  
  function shake(selector){
    $($('#pop-up')).on('click', function(event) {
      if (!$(event.target).closest(selector).length) {
        selector.addClass('shake');
  
      setTimeout(() => {
        selector.removeClass('shake');
      }, 600);
      }
    });
  }
  
  class Peringatan{
    static konfirmasi(pesan, callback){
      $('.popup').removeAttr('hidden')
  
      var div = 
      $('<div>').attr('id', 'pop-up')
    .append($('<div>').addClass('confirm')
      .append($('<h1>').append('<i class="fa-solid fa-triangle-exclamation"></i>'))
      .append($('<h2>').text("Warning!"))
      .append($('<p>').text(pesan))
      .append($('<ul>')
        .append($('<li>')
          .append($('<button>').text('Batal').click(function(){
            tutup($('.confirm'));
            callback(false);
          }))
        )
        .append($('<li>')
          .append($('<button>').text('Iya').click(function (){
            tutup($('.confirm'));
            callback(true);
          }))
        )
      )
    )
    $('.popup').append(div);
    muncul($('.confirm'));
    shake($('.confirm'));
    }
  
    static sukses(pesan){
      $('.popup').removeAttr('hidden');
  
      let div = $('<div>').attr('id', 'pop-up')
      .append($('<div>').addClass('sukses')
      .append($('<h1>').append('<i class="fa-solid fa-circle-check"></i>'))
      .append($('<h2>').text("Success!"))
      .append($('<p>').text(pesan)))
  
      $('.popup').append(div);
      muncul($('.sukses'));
  
      setTimeout(() => {
        tutup($('.sukses'));
      }, 2500);
    }

    static menyetujui(pesan, callback){
        $('.popup').removeAttr('hidden')
  
      var div = 
      $('<div>').attr('id', 'pop-up')
    .append($('<div>').addClass('persetujuan')
        .append($('<button>').addClass('close').click(function (){
            tutup($('.persetujuan'));
        })
            .append('<i class="fa-solid fa-xmark"></i>')
        )
        .append($('<h1>').text("?"))
        .append($('<h2>').text("Ingin menyetujui?"))
        .append($('<p>').text(pesan))
        .append($('<ul>')
          .append($('<li>')
            .append($('<button>').text('Tolak').click(function(){
              tutup($('.persetujuan'));
              callback(false);
            }))
          )
          .append($('<li>')
            .append($('<button>').text('Setujui').click(function (){
              tutup($('.persetujuan'));
              callback(true);
            }))
          )
        )
    )
    $('.popup').append(div);
    muncul($('.persetujuan'));
    shake($('.persetujuan'));
    }


    static ditolak(pesan){
      $('.popup').removeAttr('hidden');
  
      let div = $('<div>').attr('id', 'pop-up')
      .append($('<div>').addClass('ditolak')
      .append($('<h1>').append('<i class="fa-solid fa-circle-xmark"></i>'))
      .append($('<h2>').text("Ditolak!"))
      .append($('<p>').text(pesan)))
  
      $('.popup').append(div);
      muncul($('.ditolak'));
  
      setTimeout(() => {
        tutup($('.ditolak'));
      }, 2500);
    }
  }