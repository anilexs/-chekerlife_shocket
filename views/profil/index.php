<?php
    require_once "../../model/userModel.php";
    require_once "../../model/catalogModel.php";
    require_once "../../model/collectionModel.php";
    
    $profilInfo = User::profilInfo($_GET['q']);
    if($profilInfo != null){
        $cadreName = explode(".", $profilInfo['cadre_image']);
        $cadreName = $cadreName[0];
    }

    require_once "../inc/header.php";
    $userid = !empty($userInfo["id_user"]) ? $userInfo["id_user"] : null;
?>  
    <link rel="stylesheet" href="<?= $host ?>asset/css/profil.css">
    <script src="<?= $host ?>asset/js/profil.js"></script>
    <title><?= $_GET['q'] ?></title>

    
<?php require_once "../inc/nav.php"; ?>
<!-- <img src="https://wallpapercave.com/wp/wp5567636.jpg" alt=""> -->
<!-- <img src="https://static-cdn.jtvnw.net/jtv_user_pictures/c4bd49ab-edde-42f9-b0c7-cce0b3536d2c-profile_banner-480.png" alt=""> -->
<!-- <img src="https://wallpapercave.com/wp/wp10897345.jpg" alt=""> -->
<!-- <img src="https://abrakadabra.fun/uploads/posts/2022-03/1648222991_1-abrakadabra-fun-p-banneri-anime-2.jpg" alt=""> -->
<?php if(empty($profilInfo)){ ?>
        <h1>aucun conte trouver</h1>
    <?php }else{ 
        if($profilInfo['user_statut'] == 0 && $userid != $profilInfo["id_user"]){ ?>
            <!-- priver -->
            <h1>comte priver</h1>
        <?php }else if($userid == $profilInfo["id_user"]){ ?>
            <!-- user -->
            <div class="header" style="background-image: url(<?= $host ?>asset/img/user/banner/<?=$userInfo['banner_image'] ?>)">
                <div class="profile">
                    <div class="divImgProfil">
                        <img src="<?= $host ?>asset/img/user/cadre/<?=$userInfo['cadre_image'] ?>" alt="" class="<?= $cadreName ?>">
                        <img src="<?= $host ?>asset/img/user/profile/<?=$userInfo['user_image'] ?>" alt="" class="profilImg">
                    </div>
                    <div class="userProfil">
                        <h1 class="profilPseudo"><?= $userInfo['pseudo']; ?></h1>
                        <div class="xp-bar-container">
                            <span id="bar-hover">
                                points d'expérience : <span id="countXp"></span> <br>
                                expérience requis : <span id="levelXp">0</span>
                            </span>
                            
                            <div class="xp-bar" data-xp-user="0" data-xp-level="0"></div> 
                        </div>
                    </div>
    
    
                    
                </div>
            </div>
            <?php }else{ ?>
            <!-- views -->
            <h1>vus</h1>
        <?php } ?>
    <?php } ?>
<?php require_once "../inc/footer.php"; ?>
