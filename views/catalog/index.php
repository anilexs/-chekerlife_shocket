<?php
    require_once "../../model/userModel.php";  
    require_once "../../model/catalogModel.php";
    require_once "../../model/collectionModel.php";
    
    $get = isset($_GET['q']) ? $_GET['q'] : null;
    $name = str_replace('+', ' ', $get);
    $catalogInfo = Catalog::catalogInfoName($name);
    $episodes = Catalog::episode($catalogInfo['id_catalogue']);
    $nbVus = 0;
    if(isset($_COOKIE['token'])){
        $userLike = User::userLike($_COOKIE['token']);
        $nbEpisodeUserViewsActife = User::nbEpisodeUserViewsActife($_COOKIE['token'], $catalogInfo['id_catalogue']);
        $userViews = User::episodeViewsActifeUser($_COOKIE['token'], $catalogInfo['id_catalogue']);
        $nbVus = $nbEpisodeUserViewsActife['COUNT(*)'];
    }
    
    if($get == null || $catalogInfo === false){
        header("Location:" . $host . "undefined-page.php");
    }
    if(empty($catalogInfo)){
        $catalogInfo = null;
    }else{
        $collection = Collection::collection($catalogInfo['id_catalogue']);
    }
    if (empty($collection)) {
        $collection = null;
    }
    if(isset($_COOKIE['token'])){
        foreach($userLike as $like){
            if($like['catalog_id'] == $catalogInfo["id_catalogue"] && $like['like_active'] == 1){
                $isActive = true;
                break;
            }
        }
    }
    // var_dump($userViews);
    require_once "../inc/header.php";
?>
    <link rel="stylesheet" href="<?= $host ?>asset/css/list.css">
    <script src="<?= $host ?>asset/js/list.js"></script>
    <title><?= $name ?></title>

<?php require_once "../inc/nav.php"; ?>
    <div class="contenaire">
        <div class="contenaireheader">
            <img src="<?= $host ?>views/asset/img/catalog/<?= $catalogInfo['image_catalogue'] ?>" alt="" class="catalogImg">
            <div class="droite">
                <div class="info">
                    <ul class="ul">
                        <li><?= $catalogInfo['nom'] ?></li>
                        <li><?= $catalogInfo['publish_date'] ?></li>
                        <li><?= $catalogInfo['type'] ?></li>
                        <li>
                            <button class="likeList <?php echo $isActive ? 'activeTrue' : 'activeFalse'; ?> likeCollor<?= $catalogInfo["id_catalogue"] ?>" id="<?= $catalogInfo["id_catalogue"] ?>" onclick="likeList(<?= $catalogInfo["id_catalogue"] ?>)">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                            <span class="cataLikeList <?= $catalogInfo["id_catalogue"] ?>" id="likeId<?= $catalogInfo["id_catalogue"] ?>"><?= $catalogInfo['likes'] ?></span>
                        </li>
                    </ul>
                </div>
                <span class="description"><?= $catalogInfo['description'] ?></span>
            </div>
        </div>
    </div>

    <?php if($collection !== null && count($collection) != 1){ ?>
        <div class="btnCollectionDiv">
            <button class="btnCollection"><i class="fa-solid fa-chevron-right fa-xl" id="icon"></i></button>
        </div>
        <div class="collection" style="display: none;">
            <?php foreach($collection as $collection){
                $catalogInfo = Catalog::catalogInfo($collection['catalog_id']);?>
                <div class="collectionCard">
                    <img src="<?= $host ?>asset/img/catalog/<?= $catalogInfo['image_catalogue'] ?>" alt="">
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="episode">
        <?php if(empty($episodes)){ ?>

        <?php }else{ ?>
            <table class="tab">
                <thead>
                    <tr>
                        <td class="th0"></td>
                        <td class="th1"><span id="nbViews"><?= $nbVus ?></span>/<?= count($episodes); ?></td>
                        <td class="th2">numero d'épisode</td>
                        <td class="th3">nom</td>
                        <td class="th4">description</td>
                        <td class="th5">date de publication</td>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 0;
                        foreach ($episodes as $episode) {
                            $i++;
                            $bare = ($i % 2 == 0) ? "paire" : "impair";
                            
                            $isEpisodeActive = false;
                            if(isset($_COOKIE['token'])){
                                foreach ($userViews as $view) {
                                    if ($view['episode_id'] == $episode['id_episode'] && $view['views_active'] == 1) {
                                        $isEpisodeActive = true;
                                        break;
                                    }
                                }
                            } ?>

                            <tr class="<?= $bare ?>">
                                <td class="td0"></td>
                                <td class="td1">
                                    <input type="checkbox" class="chekboxViews" id="<?= $episode['id_episode'] ?>" <?= $isEpisodeActive ? 'checked' : '' ?>>
                                </td>
                                <td class="td2"><?= $episode['nb_episode'] ?></td>
                                <td class="td3"><?= $episode['title'] ?></td>
                                <td class="td4"><div class="td4Description"><?= $episode['description'] ?></div></td>
                                <td class="td5"><?= $episode['publish_date'] ?></td>
                            </tr>
                        <?php } ?>

                </tbody>
            </table>
        <?php } ?>
    </div>

<?php require_once "../inc/footer.php"; ?>