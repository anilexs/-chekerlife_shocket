<?php 
require_once "../model/catalogModel.php";
$lastCatalog = Catalog::catalogLastAdd();
$catalogTypeNb = Catalog::catalogTypeNb();
$nbCatalog = Catalog::nbCatalog();
$lastTcg = Catalog::tcgLastAdd();

require_once "inc/header.php"; ?>
<link rel="stylesheet" href="asset/css/index.css">
<script src="asset/js/index.js" defer></script>
<title>Accueil</title>
<?php require_once "inc/nav.php"; ?>

    <div class="hdrtxt clearfix">
        <img src="<?= $host ?>asset/img/mikuHomeRever.png" alt="" class="hdrimg">
        <h1>Bienvenue sur ChekerLife</h1>
        <span class="spanHdr" id="animated-text">Bienvenue sur ChekerLife, l'endroit idéal pour les passionnés d'anime, de films et de séries. Explorez un monde où chaque épisode, chaque film et chaque moment d'animation prend vie. Découvrez et partagez vos pépites animées ChekerLife vous offre une plateforme pour marquer et partager vos épisodes d'anime préférés, créer des quiz captivants et échanger avec d'autres fans. Plongez dans un univers où votre passion anime devient une expérience collective. Collectionnez les trésors du monde animé Votre collection de cartes Pokémon, Yu-Gi-Oh! et d'autres trésors de l'animation a enfin trouvé sa place. Documentez chaque carte, chaque édition spéciale et chaque rareté que vous possédez. Connectez-vous avec des fans du monde entier et échangez vos trésors animés. Vivez votre passion, créez votre histoire animée ChekerLife n'est pas seulement un site, c'est une aventure anime. Explorez, partagez, collectionnez et créez des liens avec d'autres passionnés. Rejoignez notre communauté où chaque histoire anime compte. Plongez dans l'univers ChekerLife dès aujourd'hui et donnez vie à votre passion pour l'anime, les films et les séries !</span>
    </div>

    <div class="hdrlastCatalog" >
        <div class="lastCatalogHdrTxt">
            <span>Découvrez nos 10 derniers catalogues animés !</span>
        </div>
        <div class="lastCatalogHdrTxt2">
            ChekerLife est fier de vous présenter nos 10 derniers ajouts à notre collection toujours grandissante d'anime, de films, de séries et bien plus encore ! Plongez dans un océan d'aventures, de suspense et de rires avec nos tout derniers titres soigneusement sélectionnés pour satisfaire tous les goûts.
        </div>
    </div>
    <div class="catalogContenair">
        <div class="lastCatalog">
            <div class="hdrLast">
                <div></div>
                <div><a href="./catalogue.php">En voir plus</a></div>
            </div>
            <div class="catalogDiv">

                <?php foreach ($lastCatalog as $lastCatalog) { 
                    $urlName = str_replace(' ', '+', $lastCatalog["nom"]); ?>
                    <div class="catalogCard">
                        <a href="catalog/<?= $urlName ?>" class="lastA">
                            <div class="catalogLastImgDiv"><img src="asset/img/last_catalog/<?= $lastCatalog["last_img"] ?>" alt="" class="catalogLastImg"></div>
                            <div class="lastCatalogTxtContenaire">
                                <div class="lastCatalogTxt">
                                    <?= $lastCatalog["nom"] ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="lastController">
                <div class="custom-scrollbar"></div>
            </div>
        </div>
    </div>

    <div class="tcg"></div>
    <div class="quiz"></div>
<?php require_once "inc/footer.php"; ?>