</head>
<body>
<?php if(isset($_COOKIE['token'])){ ?>
    <nav>
        <div>
            <a href="<?= $host ?>">
                <img src="<?= $host ?>views/asset/img/logo.png" alt="" class="logo">
            </a>
        </div>
        
        <ul class="ulNav">
            <li class="navRechercher">
                <ul class="rechercherUl" id="contenaireNavRecherche">
                    
                </ul>
                <input type="text" placeholder="Rechercher..." id="navRechercherBar" autocomplete="off" maxlength="30">
            </li>
            <li><a href="<?= $host ?>" class="navAccueil">Accueil</a></li>
            <li><a href="<?= $host ?>catalogue" class="navCatalogue">Catalogue</a></li>
            <li><a href="<?= $host ?>tcg" class="navTcg">Tcg</a></li>
            <li><a href="<?= $host ?>multi-jeux" class="navMulti-jeux">multi-jeux</a></li>
            <li class="navLike">
                <a href="<?= $host ?>likepage">
                    <div class="likePgae">
                        <i class="fa-regular fa-heart iconNavLike" style="color: #fa0000;"></i>
                        <span class="nbLike" id="likeCount"><?= $likeConte['COUNT(*)']; ?></span>
                    </div>
                </a>
            </li>
            <li>
                <button class="profil">
                    <div>
                        <img class="userImg" src="http://localhost/!chekerlife/views/asset/img/user/profile/<?=$userInfo['user_image']; ?>" alt="">
                    </div>
                    <span class="pseudo"><?= $userInfo['pseudo']; ?></span>
                </button>
                <ul class="menu">
                        <li><i class="fa-solid fa-user"></i><a href="<?= $host ?>profil/<?= $userInfo['pseudo'] ?>" class="navProfil">Profile</a></li>
                        <li><i class="fa-solid fa-sack-dollar"></i><a href="<?= $host ?>soutien" class="navSoutien">Nous soutenir</a></li>
                        <li><i class="fa-solid fa-user-group"></i><a href="<?= $host ?>amis" class="navAmi">amis</a></li>
                        <li><i class="fa-solid fa-message"></i></i><a href="<?= $host ?>messagerie" class="navMessagerie">messagerie</a></li>
                        <li><i class="fa-solid fa-gear"></i><a href="<?= $host ?>undefined-page" class="navParametre">Parametre</a></li>
                        <?php if ($userInfo['role'] == "admin" || $userInfo['role'] == "owner") {?>
                            <li class="admin">A D M I N</li>
                            <li><i class="fa-solid fa-screwdriver-wrench"></i><a href="<?= $host ?>admin/" class="navDashboard">Dashboard</a></li>
                            <li><i class="fa-solid fa-book-open"></i><a href="<?= $host ?>admin/catalog" class="navAdminCatalog">Catalog</a></li>
                        <?php } ?>
                        <li><button id="deconnexion">Déconnexion</button></li>
                </ul>
            </li>
        </ul>
    </nav>
    <?php }else{ ?>
        <nav>
            <div>
                <a href="<?= $host ?>">
                    <img src="<?= $host ?>views/asset/img/logo.png" alt="" class="logo">
                </a>
            </div>

            <ul class="ulNav">
                <li class="navRechercher">
                    <ul class="rechercherUl" id="contenaireNavRecherche">
                        
                    </ul>
                    <input type="text" placeholder="Rechercher..." id="navRechercherBar" autocomplete="off" maxlength="30">
                </li>
                <li><a href="<?= $host ?>" class="navAccueil">Accueil</a></li>
                <li><a href="<?= $host ?>catalogue" class="navCatalogue">Catalogue</a></li>
                <li><a href="<?= $host ?>tcg" class="navTcg">Tcg</a></li>
                <li><a href="<?= $host ?>multi-jeux" class="navMulti-jeux">multi-jeux</a></li>
                <li><a href="<?= $host ?>connexion">connexion</a></li>
                <li><a href="<?= $host ?>inscription">inscription</a></li>
            </ul>
        </nav>
    <?php } ?>