<?php
require_once "../model/userModel.php";
if(isset($_COOKIE['token'])) {
    header("Location: index");
}else if (isset($_POST['credential'])) {
    $credential = $_POST['credential'];

    list($header, $payload, $signature) = explode('.', $credential);

    $decodedHeader = base64_decode($header);
    $decodedPayload = base64_decode($payload);
    
    $headerData = json_decode($decodedHeader, true);
    $payloadData = json_decode($decodedPayload, true);
    
    $name = $payloadData['given_name'] ?? '';
    $prenom = $payloadData['family_name'] ?? '';
    $email = $payloadData['email'] ?? '';
    $picture = $payloadData['picture'] ?? '';
    $sub = $payloadData['sub'] ?? '';
    $hashedSub = password_hash($sub, PASSWORD_DEFAULT);

    $user = User::googleAconteVerify($email, $sub);
    if($user[0]){
        echo "il a un compte <br>";
        echo 'id google : '. $sub . '<br>';

        
        echo "Nom: $name<br>";
        echo "Prénom: $prenom<br>";
        echo "Email: $email<br>";
        echo 'id google  hach: '. $hashedSub . '<br>';
        echo '<img src="'.$picture.'" alt="" style="width: 100px; height: 100px"><br>';
        if($user[1]){
            $userInfo = User::loginGoogle($email);
            var_dump($userInfo);
        }else{
            echo "false";
        }
    }
}else{
    header("Location: index");
}
?>
<?php require_once "inc/header.php"; ?>
<link rel="stylesheet" href="asset/css/authenticate_google.css">
<script src="asset/js/authenticate_google.js" defer></script>
<title>authenticate google</title>
<?php require_once "inc/nav.php"; ?>
<?php if(!$user[0]){ ?>
    <div class="googleContenair">
        <div class="logoContenair">
            <div class="gachat">
                <img src="asset/img/gacha/gacha1.png" alt="">
            </div>
            <img src="asset/img/logoGoogle.png" alt="" class="googleLogo">
        </div>
        <div class="form">
            <h2>Merci de choisir un pseudo pour finaliser votre inscription</h2>
            <div id="input">
                <input type="text" id="pseudo" placeholder="speudo">
            </div>
            <button onclick="inscription('<?= $name ?>','<?= $prenom ?>','<?= $email ?>','<?= $hashedSub ?>','<?= $picture ?>')" id="inscription">s'inscrire</button>
        </div>
    </div>
<?php } ?>
<?php require_once "inc/footer.php"; ?>