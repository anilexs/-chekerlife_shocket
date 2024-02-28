urlAjax = "http://localhost/!chekerlife/controller/UserAjaxConroller.php";
var user_xp;
$(document).ready(function () {
    $('img').on('dragstart', function (e) {
        e.preventDefault();
    });
    $('.divImgProfil').on('contextmenu', function (e) {
        e.preventDefault();
    });
});
function userXpViews() {
    $.ajax({
        url: urlAjax,
        type: 'POST',
        data: {
            action: "userXpViews",
        },
        dataType: 'json',
        success: function (response) {
            user_xp = response['xp_actuelle'];
            xp_Level = response['xp_requis'];

            var newPercentage = ((user_xp) / xp_Level) * 100;

        $('.xp-bar').each(function() {
            var xpProgress = $(this).find('.xp-progress');
            xpProgress.stop();
            xpProgress.animate({ width: newPercentage + '%' }, 1000);
            
            
            $('#countXp').text(Math.floor((user_xp)));
            $('#levelXp').text(xp_Level);
            
            
            
            $(this).attr('data-xp-user', user_xp);
            $(this).attr('data-xp-level', xp_Level);
        });
        },
        error: function (xhr, status, error) {
            console.log(xhr);
        }
    });
}

$(document).ready(function() {
    
  $('.xp-bar').each(function() {
    var xpUser = $(this).data('xp-user');
    var xpLevel = $(this).data('xp-level');
    
    var xpProgress = $('<div class="xp-progress"></div>');
    xpProgress.css('width', (xpUser / xpLevel) * 100 + '%');
    
    $(this).append(xpProgress);
  });
  userXpViews();
});