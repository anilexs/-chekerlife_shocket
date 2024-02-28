<?php 
require_once "../model/catalogModel.php";
require_once "../model/userModel.php";  
if(isset($_GET['page']) && isset($_GET['titre'])){   
    $page = null;
}else{
    if(isset($_GET['page'])){
        $page = $_GET['page'];
        $page -= 1;
        $page *= 81;
    }else{
        $page = 0;
    }
    $catalog = Catalog::Cataloglimit(81, $page);
}

$nbCatalog = Catalog::nbCatalog();
$nbCatalog = $nbCatalog['COUNT(*)'];
if(isset($_COOKIE['token'])){
    $userLike = User::userLike($_COOKIE['token']);
}
$titre = null;
if(isset($_GET['titre'])){
    $titre = $_GET['titre'];
}
require_once "inc/header.php"; 
?>
<script src="asset/js/catalogue.js"></script>
<link rel="stylesheet" href="asset/css/catalogue.css">
<title>Catégorie</title>
<?php require_once "inc/nav.php"; ?>
<div class="contenaireRecherche">
    <input type="text" placeholder="Rechercher..." value="<?= $titre ?>" id="rechercherCategorie" autocomplete="off" maxlength="40">
</div>
<div class="catalog" id="catalog">
    <?php 
        if($page !== null){

            foreach($catalog as $catalogItem){ ?>
                
                <div class="card">
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
                    
                    <?php if($catalogItem['saison'] != null){ ?>
                        <div class="saison">saision <?= $catalogItem['saison'] ?></div>
                    <?php } ?>
                    <a href="catalog/<?= $urlName ?>">
                        <img src="asset/img/catalog/<?= $catalogItem["image_catalogue"] ?>" alt="">
                    </a>
                    <?php
                    echo '<script type="text/javascript">
                        likePosition(' . $catalogItem['id_catalogue'] .');
                    </script>'; ?>
                </div>
            <?php } 
        } ?>
</div>
<div class="page" id="pagination">
    <?php
        if($page !== null){

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
                    echo '<a href="?page=' . ($page - 1) . '"><i class="fa-solid fa-chevron-up fa-rotate-270"></i></a>';
                }else{
                    echo '<i class="fa-solid fa-chevron-up fa-rotate-270"></i>';
                }
            
                $start = max(1, $page - 3);
                $end = min($nbPages, $start + 6);
            
                if ($page > 4) {
                    echo '<a href="?page=1">1</a>';
                }
    
                for ($i = $start; $i <= $end; $i++) {
                    if ($i == $page) {
                        echo '<span><a href="?page=' . $i . '" class="current">' . $i . '</a></span>';
                    } else {
                        echo '<a href="?page=' . $i . '">' . $i . '</a>';
                    }
                }
            
                if ($nbPages - $page > 3 && $nbPages > 7) {
                    echo '<a href="?page=' . $nbPages . '">' . $nbPages . '</a>';
                }
            
                if ($page < $nbPages) {
                    echo '<a href="?page=' . ($page + 1) . '"><i class="fa-solid fa-chevron-up fa-rotate-90"></i></a>';
                }else{
                    echo '<i class="fa-solid fa-chevron-up fa-rotate-90 chevron"></i>';
                }
            
                echo '</div>';
            } 
        }
        ?>
</div>


<?php require_once "inc/footer.php"; ?>