$(document).ready(function (){
    ftrSize();

    $('.left').animate({
        'left': '0'
    }, 1000);
    $('.right').animate({
        'right': '0'
    }, 1000);
    
    $(".left, .right").on('click', function() {
        var clas = $(this).attr('class');
            clas = clas.split(' ')[0];

        console.log(clas);
        if(clas != 'nullGame'){
            $('.left').animate({
                'left': '-100%'
            }, 1000);
            $('.right').animate({
                'right': '-100%'
            }, 1000);
        }
    });

});