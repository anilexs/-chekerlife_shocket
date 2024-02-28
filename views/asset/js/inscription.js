$(document).ready(function(){
    var host = "http://localhost/!chekerlife/";
    $("#formInscription").on("submit", function(e) {
        e.preventDefault();
        var errorTab = [];
        var pseudo = $('#pseudo').val();
        var emailPreviews = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        var email = $('#email').val();
        var password = $('#password').val();
        var passwordConfirmation = $('#passwordConfirmation').val();
        var Newslatter = $('#Newslatter').is(":checked");
        if(Newslatter === false){
            Newslatter = null;
        }else{
            Newslatter = true;
        }

        pseudo = pseudo.trim().replace(/\s+/g, ' ');
        var pseudoVerify = pseudo.replace(/\s/g, '');
        
        var blackListName = ["pute", "salop", "con", "vendetta"];
        var blackListMots = ["pute", "salop", "con", "vendetta"];
        var isBlacklisted = false;
        var pseudoToCheck = pseudo.toLowerCase();
        for (var i = 0; i < blackListName.length; i++) {
            if (blackListName[i].toLowerCase() === pseudoToCheck) {
                isBlacklisted = true;
                break; // Si le pseudo est trouvé, inutile de continuer à parcourir la liste
            }
        }
        // Vérifier si le pseudo contient des mots de la liste blackListMots
        for (var j = 0; j < blackListMots.length; j++) {
            if (pseudoToCheck.includes(blackListMots[j].toLowerCase())) {
                isBlacklisted = true;
                break;
            }
        }

        $("#pseudo, #email, #password, #passwordConfirmation").css("border", "3px solid transparent");


        if(pseudo == "" || email == "" || password == "" || password.length < 5 || password === pseudo || password === email || isBlacklisted == true || pseudoVerify.length < 5 || pseudoVerify.length > 18 || pseudoVerify.length > 18 || !(email.match(emailPreviews)) || password !== passwordConfirmation){
            
            if(pseudo == ""){
                // le pseudo choisir n'est pas disponible sur ce site
                $("#pseudo").css("border", "3px solid red");
                var pseudo = "Le pseudo ne peut pas être vide.";
                errorTab.push(pseudo);
            }else if(pseudoVerify.length < 5){
                $("#pseudo").css("border", "3px solid red");
                var pseudoError = "Le pseudo ne doit pas contenir moins de 5 caractères.";
                errorTab.push(pseudoError);
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
            }else if(password.length < 5){
                $("#password").css("border", "3px solid red");
                var password5 = "Le mot de passe doit contenir au moins 5 caractères.";
                errorTab.push(password5);
            }else if(password === pseudo || password === email){
                if(password === pseudo){
                    $("#password").css("border", "3px solid red");
                    var passwordPse= "Le mot de passe ne peut pas être le même que le pseudo.";
                    errorTab.push(passwordPse);
                }
                if(password === email){
                    $("#password").css("border", "3px solid red");
                    var passwordEma = "Le mot de passe ne peut pas être le même que l'email.";
                    errorTab.push(passwordEma);
                }
            }
            
            if(passwordConfirmation == ""){
                $("#passwordConfirmation").css("border", "3px solid red");
                var confirmationVid = "La confirmation du mot de passe ne peut pas être vide.";
                errorTab.push(confirmationVid);
            }else if(passwordConfirmation.length < 5){
                $("#passwordConfirmation").css("border", "3px solid red");
                var confirmation5 = "La confirmation du mot de passe doit contenir au moins 5 caractères.";
                errorTab.push(confirmation5);
            }else if(password !== passwordConfirmation){
                $("#passwordConfirmation").css("border", "3px solid red");
                var confirmationNoEgale = "Le mot de passe est différent de la confirmation du mot de passe.";
                errorTab.push(confirmationNoEgale);
            }else if(passwordConfirmation === pseudo || passwordConfirmation === email){
                if(passwordConfirmation === pseudo){
                    $("#passwordConfirmation").css("border", "3px solid red");
                    var passwordPse= "La confirmation du mot de passe ne peut pas être le même que le pseudo.";
                    errorTab.push(passwordPse);
                }
                if(passwordConfirmation === email){
                    $("#passwordConfirmation").css("border", "3px solid red");
                    var passwordEma = "La confirmation du mot de passe ne peut pas être le même que l'email.";
                    errorTab.push(passwordEma);
                }
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
                    action: "inscription",
                    pseudo: pseudo,
                    email: email,
                    password: password,
                    Newslatter: Newslatter,
                },
                dataType: 'json',
                success: function (response) {
                    reinitialiser();
                    if(response['error'] !== null){
                        $('#right').text("");
                        $("#right").css({
                            "background-image":  "url(none)",
                        });
                        if(response['errorBool'][0]){
                            $("#pseudo").css("border", "3px solid red");
                        }
                        if(response['errorBool'][1]){
                            $("#email").css("border", "3px solid red");
                        }
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
function reinitialiser(gif = "mikuInscription.gif"){
    $('#right').text("");
    $("#pseudo, #email, #password, #passwordConfirmation").css("border", "3px solid transparent");
    
    $("#right").css({
        "background": "url("+host+"views/asset/img/"+ gif +"), url("+host+"views/asset/img/bgSchool.jpg) transparent center no-repeat",
        "background-position": "50%",
        "background-size": "cover, cover"
    });
}