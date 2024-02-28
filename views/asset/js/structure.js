const urlAjax = "http://localhost/!chekerlife/controller/UserAjaxConroller.php";

function ftrSize() {
    var footer = document.getElementById('footer');
    if (document.body.scrollHeight > window.innerHeight) {
        footer.style.position = 'static';
        footer.style.bottom = 'auto';
    } else {
        footer.style.position = 'absolute';
        footer.style.bottom = '0';
    }
}

// function ftrSize() {
//     var footer = document.getElementById('footer');
//     var contentHeight = document.body.scrollHeight;
//     var windowHeight = window.innerHeight;
//     var scrollPosition = window.scrollY || window.pageYOffset;

//     if (scrollPosition + windowHeight >= contentHeight) {
//         footer.style.position = 'static';
//         footer.style.bottom = 'auto';
//     } else {
//         footer.style.position = 'fixed';
//         footer.style.bottom = '0';
//     }
// }

// $(document).ready(function() {
//     var imageCliquable = $('<img>', {
//       id: 'chibi',
//       src: host + 'asset/img/chibi/miku-reverse.png',
//     });
  
//     imageCliquable.click(function() {
//       $(this).effect('explode', {
//         pieces: 100,   
//         easing: 'easeOutQuad', 
//         duration: 1000 
//       });
//     });
  
//     $('body').prepend(imageCliquable);
//     $('#chibi').css({
//       width: '80px',
//       height: '100px',
//       position: 'absolute',
//       top: '0px',
//       zIndex: 1
//     });
  
//     $('#chibi').animate({
//       top: '+=50px',
//     }, 1000); 
// });

$(document).ready(function() {

    function online() {
        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "userOnligne",
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    }
    // online();
    // setInterval(online, 60000);
});
