<?php
session_start();
if(isset($_COOKIE['token'])) {
    header("Location: index");
}
require_once "inc/header.php"; 
?>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<link rel="stylesheet" href="asset/css/inscription.css">
<script src="asset/js/inscription.js" defer></script>
<title>Document</title>
<?php require_once "inc/nav.php"; ?>
<div class="alignement">
    <div class="contenaire">
        <div class="left">
            <div class="form">
                <h1 class="h1Form">formulaire d'inscription</h1>
                <form method="POST" class="formInscription" id="formInscription">
                    <div class="formPseudo">
                        <label for="pseudo" class="leftLabel">pseudo :</label>
                        <input type="text" maxlength="18" id="pseudo" class="leftInute" placeholder="pseudo">
                    </div>
    
                    <div class="formEmail">
                        <label for="email" class="leftLabel">email :</label>
                        <input type="text" maxlength="100" id="email" class="leftInute" placeholder="email">
                    </div>
    
                    <div class="formPassword">
                        <label for="password" class="leftLabel">mot de passe :</label>
                        <input type="password" maxlength="100" id="password" class="leftInute" placeholder="mot de passe" >
                    </div>
                    <div class="formPasswordConfirmation">
                        <label for="passwordConfirmation" class="leftLabel">confirmation du mot de passe :</label>
                        <input type="password" maxlength="100" id="passwordConfirmation" class="leftInute" placeholder="confirmation du mot de passe">
                    </div>
                    <div class="divNewSlater">
                        <input type="checkbox" class="Newslatter" id="Newslatter">
                        <label for="Newslatter" class="newslatterLabel">Inscrivez-vous à notre newsletter !</label>
                    </div>
                    <div class="btnDiv">
                        <button name="inscription" class="btnInscription">s'inscrire</button>
                        <input type="reset" id="btnReinitialiser">
                    </div>
                </form>
                <!-- co par google a continuet plus tare
                https://www.youtube.com/watch?v=oiCH0HAS_u0&t=1248s&ab_channel=Boris%28%27PrimFX%27%29 -->
                <div id="g_id_onload"
                    data-client_id="224348978546-jki2a29kf80k1q441lp7khc05k67j8kt.apps.googleusercontent.com"
                    data-context="signup"
                    data-ux_mode="popup"
                    data-login_uri="http://localhost/!chekerlife/authenticate_google.php"
                    data-auto_prompt="false">
                </div>

                <div class="g_id_signin"
                    data-type="standard"
                    data-shape="pill"
                    data-theme="filled_blue"
                    data-text="signin_with"
                    data-size="large"
                    data-logo_alignment="left">
                </div>
            </div>
        </div>
        <div id="right">

        </div>

        </div>
</div>
<?php require_once "inc/footer.php"; ?>