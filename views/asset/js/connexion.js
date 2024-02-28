$(document).ready(function(){
    var host = "http://localhost/!chekerlife/";
    $("#formConnexion").on("submit", function(e) {
        e.preventDefault();
        var errorTab = [];
        var email = $('#email').val();
        var emailPreviews = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        var password = $('#password').val();
        var isBlacklisted = false;
        $("#email, #password").css("border", "3px solid transparent");
        if(email == "" || password == "" || isBlacklisted == true || !(email.match(emailPreviews))){
            if(pseudo == ""){
                // le pseudo choisir n'est pas disponible sur ce site
                $("#pseudo").css("border", "3px solid red");
                var pseudo = "Le pseudo ne peut pas être vide.";
                errorTab.push(pseudo);
            }

            if(email == ""){
                $("#email").css("border", "3px solid red");
                var email = "L'email ne peut pas être vide.";
                errorTab.push(email);
            }else if (!(email.match(emailPreviews))) {
                $("#email").css("border", "3px solid red");
                var email = "L'adresse e-mail n'est pas conforme.";
                errorTab.push(email);
            }
            
            if(password == ""){
                $("#password").css("border", "3px solid red");
                var passwordVid = "Le mot de passe ne peut pas être vide.";
                errorTab.push(passwordVid);
            }

            $('#right').text("");
            $("#right").css({
                "background-image":  "url(none)",
            });

            var error = $('<div>').attr('id', 'error');
            $('#right').append(error);
            
            var gif = $('<div>').attr('id', 'errorGif');
            $('#right').append(gif);


            errorTab.forEach(element => {
                var errorDiv = $('<div>').addClass('error').html('<i class="fa-solid fa-star" style="color: #ff0000;"></i>' + element);
                $('#error').append(errorDiv);
            });
        }else{
            $.ajax({
                url: 'form/UserForm.php',
                type: 'POST',
                data: {
                    action: "connexion",
                    email: email,
                    password: password,
                },
                dataType: 'json',
                success: function (response) {
                    reinitialiser();
                    if(response['error'] !== null){
                        $('#right').text("");
                        $("#right").css({
                            "background-image":  "url(none)",
                        });

                        $("#email, #password").css("border", "3px solid red");

                        var error = $('<div>').attr('id', 'error');
                        $('#right').append(error);

                        var gif = $('<div>').attr('id', 'errorGif');
                        $('#right').append(gif);
                            response['error'].forEach(element => {
                                var errorDiv = $('<div>').addClass('error').html('<i class="fa-solid fa-star" style="color: #ff0000;"></i>' + element);
                                $('#error').append(errorDiv);
                            });
                        }else{
                            if (window.history.length === 1) {
                                window.location.href = host;
                            } else {
                                var urlPagePrecedente = document.referrer;
                                
                                window.location.href = urlPagePrecedente;
                            }
                        }

                    },
                error: function (xhr, status, error) {
                    // console.error('Une erreur s\'est produite lors du chargement du contenu.');
                    console.log(xhr);
                }
            });
        }

    });

    $('#btnReinitialiser').on('click', function() {
        reinitialiser();
    });
});
function reinitialiser(gif = "mikulevitation.gif"){
    $('#right').text("");
    $("#email, #password").css("border", "3px solid transparent");
    
    $("#right").css({
        "background": "url("+host+"views/asset/img/"+ gif +"), url("+host+"views/asset/img/bgSchool.jpg) transparent center no-repeat",
        "background-size": "95%, cover"
    });
}