<?php require_once "inc/header.php"; 
$postFriend = User::postFriend($_COOKIE['token']);
?>
<link rel="stylesheet" href="asset/css/amis.css">
<script src="asset/js/amis.js" defer></script>
<title>friend</title>
<?php require_once "inc/nav.php";?>
<div class="contenaire">
    <div class="leftContenair">
        <div class="leftoption">
            <ul>
                <li><h4><button id="addFriend" disabled>ajoute un amis</button></h4></li>
                <li><h4><button id="online">En ligne</button></h4></li>
                <li><h4><button id="all">Tous</button></h4></li>
                <li><h4><button id="requette">En attente</button></h4></li>
                <li><h4><button id="blocket">Blocket</button></h4></li>
            </ul>
        </div>
    </div>
    <div class="rightContenair">
        <div class="friend">
            <div class="rechercheFriend">
                <input type="text" placeholder="pseudo ?" maxlength="30" id="inputeFriend">
                <button id="parametreFriend"><i class="fa-solid fa-gear"></i></button>
            </div>
            <div class="suggestion">
                <span class="postTxt">Demande d'ami en cours</span>
                <?php foreach ($postFriend as $friend) { ?>
                    <div class="friendCard">
                        <div class="friendImgContenair">
                            <?php if(!empty($friend['cadre'])){
                                $cadreName = explode(".", $friend['cadre']); ?>
                                <img src="views/asset/img/user/cadre/<?= $friend['cadre'] ?>" alt="profil img" class="<?= $cadreName[0] ?>">
                            <?php } ?>
                            <img src="views/asset/img/user/profile/<?= $friend['profil'] ?>" alt="profil img" class="friendImg">
                        </div>
                        <div class="friendController">
                            <div class="removeFriend"><button class="<?= $friend['pseudo'] ?>" id="cancelFriend"><i class="fa-solid fa-x"></i></button></div>
                            <div class="messageFriend"><a href="messagerie/<?= $friend['pseudo'] ?>"><i class="fa-solid fa-message"></i></a></div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php require_once "inc/footer.php"; ?>