<?php 
require_once "../../model/adminCatalogModel.php";
require_once "../../model/userModel.php";  

$allViews = isset($_GET['allViews']) ? true : false;
$actif = isset($_GET['actif']) ? false : true;
$disable = isset($_GET['disable']) ? true : false;
$brouillon = isset($_GET['brouillon']) ? true : false;

$parametre = [
    "allViews" => $allViews,
    "actif" => $actif,
    "disable" => $disable,
    "brouillon" => $brouillon,
];

if(isset($_GET['page']) && isset($_GET['titre'])){   
    $page = null;
}else{
    if(isset($_GET['page'])){
        $page = $_GET['page'];
        $page -= 1;
        $page *= 80;
    }else{
        $page = 0;
    }
    $catalog = AdminCatalog::Cataloglimit(80, $page, $parametre);
}

$nbCatalog = AdminCatalog::nbCatalog($parametre);
$nbCatalog = isset($nbCatalog['COUNT(*)']) ? $nbCatalog['COUNT(*)'] : 0;
if(isset($_COOKIE['token'])){
    $userLike = User::userLike($_COOKIE['token']);
}
$titre = null;
if(isset($_GET['titre'])){
    $titre = $_GET['titre'];
}
require_once "../inc/header.php"; 

    if ($userInfo['role'] != "admin" && $userInfo['role'] != "owner") {
        header("Location:" . $host);
        die;
    }

?>
<script src="../asset/js/catalog.js"></script>
<link rel="stylesheet" href="../asset/css/catalog.css">
<title>Catégorie</title>
<?php require_once "../inc/nav.php"; ?>
<div class="contenaireRecherche">
    <input type="text" placeholder="Rechercher..." value="<?= $titre ?>" id="rechercherCategorie" autocomplete="off" maxlength="40">
    <div class="addController">
        <button class="addCatalog" onclick="addCatalog()">ajouter un catalog</button>
    </div>
</div>
<div class="catalog" id="catalog">
    <div class="cardNav">
        <div class="cardNavHdr">menu catalog</div>
        <div class="cardNavAuto">
            <span class="cardNavSpan"><input type="checkbox" id="allViews" <?= $allViews ? 'checked' : ''; ?>><label for="allViews">Tout afficher</label></span>
            <span class="cardNavSpan"><input type="checkbox" id="actif" <?= $actif || $allViews ? 'checked' : ''; ?>><label for="actif">Catalogue actif</label></span>
            <span class="cardNavSpan"><input type="checkbox" id="disable" <?= $disable || $allViews ? 'checked' : ''; ?>><label for="disable">Catalogue désactivé</label></span>
            <span class="cardNavSpan"><input type="checkbox" id="brouillon" <?= $brouillon || $allViews  ? 'checked' : ''; ?>><label for="brouillon">Catalogue brouillon</label></span>
        </div>
    </div>
    <?php 
        if($page !== null && $catalog !== null){
            
            foreach($catalog as $catalogItem){ 
                if($catalogItem['catalog_actif'] == 0){ ?>

                    <div class="cardDisable catalogId<?=$catalogItem["id_catalogue"]?>">
                        <?php
                        $isActive = false;
                        if(isset($_COOKIE['token'])){
                            foreach($userLike as $like){
                                if($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1){
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                        $urlName = str_replace(' ', '+', $catalogItem["nom"]);
                        ?>
                        <button class="like <?php echo $isActive ? 'activeTrue' : 'activeFalse'; ?> likeCollor<?= $catalogItem["id_catalogue"] ?>" id="<?= $catalogItem["id_catalogue"] ?> <? echo $isActive ? 'activeTrue' : 'activeFalse'; ?>" onclick="like(<?= $catalogItem["id_catalogue"] ?>)">
                            <span class="cataLike <?= $catalogItem["id_catalogue"] ?> likeId<?= $catalogItem["id_catalogue"] ?>" id="likeId<?= $catalogItem["id_catalogue"] ?>"><?= $catalogItem['likes'] ?></span>
                            <i class="fa-solid fa-heart"></i>
                        </button>
    
                        <div class="type"><?= $catalogItem['type'] ?></div>
                        <div class="edite"><button onclick="edite('catalog', <?= $catalogItem['id_catalogue'] ?>)"><i class="fa-solid fa-pencil"></i></button></div>
                        <div class="addEpisode"><button onclick="addEpisode(<?= $catalogItem['id_catalogue'] ?>, 'catalog')"><i class="fa-solid fa-plus"></i></button></div>
    
                        <?php if($catalogItem['saison'] != null){ ?>
                            <div class="saison">saision <?= $catalogItem['saison'] ?></div>
                        <?php } ?>
                        <a href="../catalog/<?= $urlName ?>">
                            <img src="../asset/img/catalog/<?= $catalogItem["image_catalogue"] ?>" alt="">
                        </a>
                        <?= '<script type="text/javascript">
                            likePosition(' . $catalogItem['id_catalogue'] .');
                        </script>'; ?>
                    </div>
                    
                <?php }else if($catalogItem['origin'] == "catalog" && $catalogItem['brouillon'] == 0){ ?>
                    <div class="card catalogId<?=$catalogItem["id_catalogue"]?>">
                        <?php
                        $isActive = false;
                        if(isset($_COOKIE['token'])){
                            foreach($userLike as $like){
                                if($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1){
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                        $urlName = str_replace(' ', '+', $catalogItem["nom"]);
                        ?>
                        <button class="like <?php echo $isActive ? 'activeTrue' : 'activeFalse'; ?> likeCollor<?= $catalogItem["id_catalogue"] ?>" id="<?= $catalogItem["id_catalogue"] ?> <? echo $isActive ? 'activeTrue' : 'activeFalse'; ?>" onclick="like(<?= $catalogItem["id_catalogue"] ?>)">
                            <span class="cataLike <?= $catalogItem["id_catalogue"] ?> likeId<?= $catalogItem["id_catalogue"] ?>" id="likeId<?= $catalogItem["id_catalogue"] ?>"><?= $catalogItem['likes'] ?></span>
                            <i class="fa-solid fa-heart"></i>
                        </button>
    
                        <div class="type"><?= $catalogItem['type'] ?></div>
                        <div class="edite"><button onclick="edite('catalog', <?= $catalogItem['id_catalogue'] ?>)"><i class="fa-solid fa-pencil"></i></button></div>
                        <div class="addEpisode"><button onclick="addEpisode(<?= $catalogItem['id_catalogue'] ?>, 'catalog')"><i class="fa-solid fa-plus"></i></button></div>
    
                        <?php if($catalogItem['saison'] != null){ ?>
                            <div class="saison">saision <?= $catalogItem['saison'] ?></div>
                        <?php } ?>
                        <a href="../catalog/<?= $urlName ?>">
                            <img src="../asset/img/catalog/<?= $catalogItem["image_catalogue"] ?>" alt="">
                        </a>
                        <?= '<script type="text/javascript">
                            likePosition(' . $catalogItem['id_catalogue'] .');
                        </script>'; ?>
                    </div>
                    
                    <?php }else if($catalogItem['origin'] == "catalog" && $catalogItem['brouillon'] == 1){ ?>
                        <div class="cardBrouillonCatalog catalogId<?=$catalogItem["id_catalogue"]?>">
                            <?php
                            $isActive = false;
                            if(isset($_COOKIE['token'])){
                                foreach($userLike as $like){
                                    if($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1){
                                        $isActive = true;
                                        break;
                                    }
                                }
                            }
                            $urlName = str_replace(' ', '+', $catalogItem["nom"]);
                            ?>
                            <button class="like <?php echo $isActive ? 'activeTrue' : 'activeFalse'; ?> likeCollor<?= $catalogItem["id_catalogue"] ?>" id="<?= $catalogItem["id_catalogue"] ?> <? echo $isActive ? 'activeTrue' : 'activeFalse'; ?>" onclick="like(<?= $catalogItem["id_catalogue"] ?>)">
                                <span class="cataLike <?= $catalogItem["id_catalogue"] ?> likeId<?= $catalogItem["id_catalogue"] ?>" id="likeId<?= $catalogItem["id_catalogue"] ?>"><?= $catalogItem['likes'] ?></span>
                                <i class="fa-solid fa-heart"></i>
                            </button>
        
                            <div class="type"><?= $catalogItem['type'] ?></div>
                            <div class="edite"><button onclick="edite('catalog', <?= $catalogItem['id_catalogue'] ?>)"><i class="fa-solid fa-pencil"></i></button></div>
                            <div class="addEpisode"><button onclick="addEpisode(<?= $catalogItem['id_catalogue'] ?>, 'catalog')"><i class="fa-solid fa-plus"></i></button></div>
        
                            <?php if($catalogItem['saison'] != null){ ?>
                                <div class="saison">saision <?= $catalogItem['saison'] ?></div>
                            <?php } ?>
                            <a href="../catalog/<?= $urlName ?>">
                                <img src="../asset/img/catalog/<?= $catalogItem["image_catalogue"] ?>" alt="">
                            </a>
                            <?= '<script type="text/javascript">
                                likePosition(' . $catalogItem['id_catalogue'] .');
                            </script>'; ?>
                        </div>

                <?php }else{ ?>

                    <div class="cardBrouillon brouillonId<?= $catalogItem['id_brouillon'] ?>">
                        <?php
                            $urlName = str_replace(' ', '+', $catalogItem["nom"]);
                        ?>
                        <div class="type"><?= $catalogItem['type'] ?></div>
                        <div class="edite"><button onclick="edite('brouillon', <?= $catalogItem['id_brouillon'] ?>)"><i class="fa-solid fa-pencil"></i></button></div>
                        <div class="addEpisode"><button onclick="addEpisode(<?= $catalogItem['id_catalogue'] ?>, 'brouillon')"><i class="fa-solid fa-plus"></i></button></div>
    
                        <?php if($catalogItem['saison'] != null){ ?>
                            <div class="saison">saision <?= $catalogItem['saison'] ?></div>
                        <?php } ?>
                        <a href="../catalog/<?= $urlName ?>">
                            <img src="../asset/img/catalog/<?= $catalogItem["image_catalogue"] ?>" alt="">
                        </a>
                    </div>
                <?php }?>
            <?php } 
        } ?>
</div>
<div class="page" id="pagination">
    <?php
        if($page !== null){
            $paginationGet = '';
        
        if ($allViews) {   
            $paginationGet .= "allViews=true";
        }else{
            if (!$actif) {
                $paginationGet .= ($paginationGet ? "&" : "") . "actif=false";
            }
            
            if ($disable) {
                $paginationGet .= ($paginationGet ? "&" : "") . "disable=true";
            }
            
            if ($brouillon) {
                $paginationGet .= ($paginationGet ? "&" : "") . "brouillon=true";
            }
        }
            // echo $paginationGet ? "&" : "?";

            $elementsParPage = 81;
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $nbPages = ceil($nbCatalog / $elementsParPage);
            
            if ($page > $nbPages) {
                $page = 1;
            } else if($page < 1){
                $page = 1;
            }
    
            if ($nbPages > 1) {
                echo '<div class="pagination">';
            
                if ($page > 1) {
                    echo '<a href="?page=' . ($page - 1) . ($paginationGet ? "&$paginationGet" : "").'"><i class="fa-solid fa-chevron-up fa-rotate-270"></i></a>';
                }else{
                    echo '<i class="fa-solid fa-chevron-up fa-rotate-270"></i>';
                }
            
                $start = max(1, $page - 3);
                $end = min($nbPages, $start + 6);
            
                if ($page > 4) {
                    echo '<a href="?page=1'. ($paginationGet ? "&$paginationGet" : "") .'">1</a>';
                }
    
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $page) {
                        echo '<span><a href="?page=' . $i . ($paginationGet ? "&$paginationGet" : "") .'" class="current">' . $i . '</a></span>';
                    } else {
                        echo '<a href="?page=' . $i . ($paginationGet ? "&$paginationGet" : "") .'">' . $i . '</a>';
                    }
                }
            
                if ($nbPages - $page > 3 && $nbPages > 7) {
                    echo '<a href="?page=' . $nbPages . ($paginationGet ? "&$paginationGet" : "") .'">' . $nbPages . '</a>';
                }
            
                if ($page < $nbPages) {
                    echo '<a href="?page=' . ($page + 1) . ($paginationGet ? "&$paginationGet" : "") .'"><i class="fa-solid fa-chevron-up fa-rotate-90"></i></a>';
                }else{
                    echo '<i class="fa-solid fa-chevron-up fa-rotate-90 chevron"></i>';
                }
            
                echo '</div>';
            } 
        }
        ?>
</div>


<?php require_once "../inc/footer.php"; ?>