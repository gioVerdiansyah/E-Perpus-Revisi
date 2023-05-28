// Animasi
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
    if (!$(event.target).closest('.alert').length) {
      selector.addClass('shake');

    setTimeout(() => {
      selector.removeClass('shake');
    }, 600);
    }
  });
}

class Peringatan{
  static konfirmasi(pesan, callback){
    let div = 
    $('<div>').attr('id', 'pop-up')
    .append($('<div>').addClass('alert')
    .append($('<h1>').append('<i class="fa-solid fa-triangle-exclamation"></i>'))
    .append($('<h2>').text("Warning!"))
    .append($('<p>').text(pesan))
    .append($('<ul>')
      .append($('<li>')
        .append($('<button>').text('Cancel').click(function(){
          tutup($('.alert'));
          callback(false);
        }))
      )
      .append($('<li>')
        .append($('<button>').text('Yes').click(function (){
          callback(true);
        }))
      )
    )
  )
  $('.popup').append(div);
  muncul($('.alert'));
  shake($('.alert'));
  }

  static sukses(pesan){
    let div = $('<div>').attr('id', 'pop-up')
    .append($('<div>').addClass('alert sukses')
    .append($('<h1>').append('<i class="fa-solid fa-circle-check"></i>'))
    .append($('<h2>').text("Success!"))
    .append($('<p>').text(pesan)))

    $('.popup').append(div);
    muncul($('.alert'));

    setTimeout(() => {
      tutup($('.alert'));
    }, 1500);
  }
}