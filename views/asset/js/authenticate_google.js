

function inscription(name, prenom, email, subHach, picture){
    
    var pseudo = $('#pseudo').val();
        pseudo = pseudo.trim().replace(/\s+/g, ' ');
    if(pseudo == "" || pseudo.length < 5){
        console.log("null");
        console.log(pseudo.length);
    }else{
        $.ajax({
            url: "http://localhost/!chekerlife/form/UserForm.php",
            type: 'POST',
            data: {
                action: "inscriptionGoogle",
                name: name,
                prenom: prenom,
                pseudo: pseudo,
                email: email,
                subHach: subHach,
                picture: picture,
            },
            dataType: 'json',
            success: function (response) {
                if (window.history.length <= 2) {
                    window.location.href = host; 
                } else {
                    window.history.go(-2); 
                }
            },
            error: function (xhr, status, error) {
                console.log(error);
            }
        });
    }
    console.log(pseudo);
}