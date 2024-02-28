<?php
session_start();
require_once "../model/database.php";
require_once "../model/userModel.php";
require_once "../model/catalogModel.php";
require_once "../model/adminModel.php";
require_once "../model/adminCatalogModel.php";

const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400; 
const HTTP_METHOD_NOT_ALLOWED = 405; 

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST'){
    $response_code = HTTP_BAD_REQUEST;
    $message = "il manque le paramétre ACTION";

    if($_POST['action'] == "catalog"){
        $catalog = AdminCatalog::Cataloglimit($_POST['limit'], $_POST['offset'], $_POST['parametre']);
        $nbCatalog = AdminCatalog::nbCatalog($_POST['parametre']);
        if(isset($_COOKIE['token'])){
            $userLike = User::userLike($_COOKIE['token']);
        }
        
        $parametre = $_POST['parametre'];
        catalog_parametre($parametre);
        if($catalog != null){
            $nbCatalog = $nbCatalog['COUNT(*)'];

            foreach ($catalog as $catalogItem) {
                if($catalogItem['catalog_actif'] == 0){
                    echo '<div class="cardDisable catalogId'.$catalogItem["id_catalogue"].'">';
            
                    $isActive = false;
                    if (isset($_COOKIE['token'])) {
                        foreach ($userLike as $like) {
                            if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    cardDisable($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }else if($catalogItem['origin'] == "catalog" && $catalogItem['brouillon'] == 0){
                    echo '<div class="card catalogId'.$catalogItem["id_catalogue"].'">';
            
                    $isActive = false;
                    if (isset($_COOKIE['token'])) {
                        foreach ($userLike as $like) {
                            if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    cardCatalog($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }else if($catalogItem['origin'] == "catalog" && $catalogItem['brouillon'] == 1){
                    echo '<div class="cardBrouillonCatalog catalogId'.$catalogItem["id_catalogue"].'">';
            
                    $isActive = false;
                    if (isset($_COOKIE['token'])) {
                        foreach ($userLike as $like) {
                            if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    cardBrouillonCatalog($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
    
                }else{
                    echo '<div class="cardBrouillon brouillonId'.$catalogItem["id_catalogue"].'">';
                    cardBrouillon($catalogItem["id_catalogue"], $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }
                
        }
        echo '<script type="text/javascript">pagination('. $nbCatalog .');</script>';
    }

    }else if($_POST['action'] == "filtre"){

        $catalog = AdminCatalog::filtreCatalog($_POST['filtre'], $_POST['limit'], $_POST['offset'], $_POST['parametre']);

        $nbCatalog = AdminCatalog::nbFiltreCatalog($_POST['filtre'], $_POST['parametre']);
        if(isset($_COOKIE['token'])){
            $userLike = User::userLike($_COOKIE['token']);
        }
        
        catalog_parametre($_POST['parametre']);
        if($nbCatalog != null){
            $nbCatalog = $nbCatalog['nbFiltre'];
            foreach ($catalog as $catalogItem) {
                if($catalogItem['catalog_actif'] == 0){
                    echo '<div class="cardDisable catalogId'.$catalogItem["id_catalogue"].'">';
            
                    $isActive = false;
                    if (isset($_COOKIE['token'])) {
                        foreach ($userLike as $like) {
                            if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    cardDisable($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }else if($catalogItem['origin'] == "catalog" && $catalogItem['brouillon'] == 0){
                    echo '<div class="card catalogId'.$catalogItem["id_catalogue"].'">';
            
                    $isActive = false;
                    if (isset($_COOKIE['token'])) {
                        foreach ($userLike as $like) {
                            if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    cardCatalog($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }else if($catalogItem['origin'] == "catalog" && $catalogItem['brouillon'] == 1){
                    echo '<div class="cardBrouillonCatalog catalogId'.$catalogItem["id_catalogue"].'">';
            
                    $isActive = false;
                    if (isset($_COOKIE['token'])) {
                        foreach ($userLike as $like) {
                            if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                                $isActive = true;
                                break;
                            }
                        }
                    }
                    cardBrouillonCatalog($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }else{
                    echo '<div class="cardBrouillon brouillonId'.$catalogItem["id_catalogue"].'">';
                    cardBrouillon($catalogItem["id_brouillon"], $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
                }
            }
            echo '<script type="text/javascript">pagination('. $nbCatalog .');</script>';
        }

    }else if($_POST['action'] == "pagination"){
        $parametre = $_POST['parametre'];
        $paginationGet = '';

        if ($parametre['allViews']) {   
            $paginationGet .= "allViews=true";
        }else{
            if (!$parametre['actif']) {
                $paginationGet .= ($paginationGet ? "&" : "") . "actif=false";
            }
            
            if ($parametre['disable']) {
                $paginationGet .= ($paginationGet ? "&" : "") . "disable=true";
            }
            
            if ($parametre['brouillon']) {
                $paginationGet .= ($paginationGet ? "&" : "") . "brouillon=true";
            }
        }
        

        $nbFiltre = $_POST['nbElement'];
        $elementsParPage = 80;
        $page = $_POST['page'];
        $filtre = $_POST['filtre'];
        $nbPages = ceil($nbFiltre / $elementsParPage);

        if ($page > $nbPages) {
            $page = 1;
        } else if ($page < 1){
            $page = 1;
        }
    
        if ($nbPages > 1) {
            echo '<div class="pagination">';
        
            if ($page > 1) {
                if($filtre == null){
                    echo '<a href="?page=' . ($page - 1) . ($paginationGet ? "&$paginationGet" : "") .'"><i class="fa-solid fa-chevron-up fa-rotate-270"></i></a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=' . ($page - 1) . ($paginationGet ? "&$paginationGet" : "") .'"><i class="fa-solid fa-chevron-up fa-rotate-270"></i></a>';
                }
            } else {
                echo '<i class="fa-solid fa-chevron-up fa-rotate-270"></i>';
            }
        
            $start = max(1, $page - 3);
            $end = min($nbPages, $start + 6);
        
            if ($page > 4) {
                if($filtre == null){
                    echo '<a href="?page=1'. ($paginationGet ? "&$paginationGet" : "") .'">1</a>';
                }else{
                    echo '<a href="?titre='.$filtre. ($paginationGet ? "&$paginationGet" : "") .'&page=1">1</a>';
                }
            }
        
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                    if($filtre == null){
                        echo '<span><a href="?page=' . $i . ($paginationGet ? "&$paginationGet" : "") .'" class="current">' . $i . '</a></span>';
                    }else{
                        echo '<span><a href="?titre='.$filtre.'&page=' . $i . ($paginationGet ? "&$paginationGet" : "") .'" class="current">' . $i . '</a></span>';
                    }
                } else {
                    if($filtre == null){
                        echo '<a href="?page=' . $i . ($paginationGet ? "&$paginationGet" : "") .'">' . $i . '</a>';
                    }else{
                        echo '<a href="?titre='.$filtre.'&page=' . $i . ($paginationGet ? "&$paginationGet" : "") .'">' . $i . '</a>';
                    }
                }
            }
        
            if ($nbPages - $page > 3 && $nbPages > 7) {
                if($filtre == null){
                    echo '<a href="?page=' . $nbPages . ($paginationGet ? "&$paginationGet" : "") .'">' . $nbPages . '</a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=' . $nbPages . ($paginationGet ? "&$paginationGet" : "") .'">' . $nbPages . '</a>';
                }
            }
        
            if ($page < $nbPages) {
                if($filtre == null){
                    echo '<a href="?page=' . ($page + 1) . ($paginationGet ? "&$paginationGet" : "") .'"><i class="fa-solid fa-chevron-up fa-rotate-90"></i></a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=' . ($page + 1) . ($paginationGet ? "&$paginationGet" : "") .'"><i class="fa-solid fa-chevron-up fa-rotate-90"></i></a>';
                }
            } else {
                echo '<i class="fa-solid fa-chevron-up fa-rotate-90"></i>';
            }
        
            echo '</div>';
        }

    }else if($_POST['action'] == "navFiltre"){
        $catalog = Catalog::navRechercher($_POST['filtreNav']);
        if(isset($_COOKIE['token'])){
            $userLike = User::userLike($_COOKIE['token']);
        }
        if(empty($catalog)){
            echo '<li class="navRechercheCard">';
            echo  '0 résultat n\'a été trouvé';
            echo '</li>';
        }else{
            foreach ($catalog as $catalogItem) {
                echo '<li class="navRechercheCard">';
        
                $isActive = false;
                if (isset($_COOKIE['token'])) {
                    foreach ($userLike as $like) {
                        if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                            $isActive = true;
                            break;
                        }
                    }
                }
                $nbCaracter = strlen($catalogItem["nom"]);
        
                if($nbCaracter >= 22) {
                    $catalogNom = substr($catalogItem["nom"], 0, 19)."...";
                }else{
                    $catalogNom = $catalogItem["nom"];
                }
        
                $urlName = str_replace(' ', '+', $catalogItem["nom"]);
                echo '<button class="likeNavRecherche '. ($isActive ? 'activeTrue' : 'activeFalse') . ' likeCollor'. $catalogItem["id_catalogue"] . '" id="' . $catalogItem["id_catalogue"] . ($isActive ? 'activeTrue' : 'activeFalse') .'" onclick="like(' . $catalogItem["id_catalogue"] . ')">';
                echo '<span class="cataLike ' . $catalogItem["id_catalogue"] . ' likeId' . $catalogItem["id_catalogue"] .'" id="likeId' . $catalogItem["id_catalogue"] .'">' . $catalogItem['likes'] . '</span>';
                echo '<i class="fa-solid fa-heart"></i>';
                echo '</button>';
                echo '<a href="http://localhost/!chekerlife/catalog/' . $urlName . '" class="cardA">';
                echo '<img class="navRechercheImg" src="http://localhost/!chekerlife/views/asset/img/catalog/' . $catalogItem["image_catalogue"] . '" alt="">';
                echo '<h3>'. $catalogNom .'</h3>';
                echo '</a>';
                echo '<script type="text/javascript"> likePosition('. $catalogItem["id_catalogue"]. '); </script>';
                echo '</li>';
            }
        }
    }else if($_POST['action'] == "cataloginfo"){
        $response_code = HTTP_OK;
        $catalogInfo = AdminCatalog::catalogInfo($_POST['catalog_id']);
        $type = Catalog::catalogType();
        $catalogAllType = Catalog::catalogAllType($_POST['catalog_id']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "cataloginfo" => $catalogInfo,
            "type" => $type,
            "allType" => $catalogAllType,
        ];
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "brouilloninfo"){
        $response_code = HTTP_OK;
        $brouilloninfo = AdminCatalog::brouillonInfo($_POST['catalog_id']);
        $type = Catalog::catalogType();
        $catalogAllType = Catalog::catalogAllType($_POST['catalog_id']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "cataloginfo" => $brouilloninfo,
            "type" => $type,
            "allType" => $catalogAllType,
        ];
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "newCatalogActif"){
        $response_code = HTTP_OK;
        $catalogInfo = AdminCatalog::catalogInfoAdmin($_POST['catalog_id']);
        $newEtat = Admin::newCatalog_actif($_POST['catalog_id'], $catalogInfo['catalog_actif']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "newEtat" => $newEtat,
        ];
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "episodeAll"){
        $response_code = HTTP_OK;
        $origin = ($_POST['origin'] == 'catalog') ? "catalogInfo" : "brouilloninfo";
        $catalog = AdminCatalog::$origin($_POST['catalog_id']);
        $episodAll = AdminCatalog::episodeAll($_POST['catalog_id']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "episodAll" => $episodAll,
            "catalog" => $catalog,
        ];
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "disabledEp"){
        $response_code = HTTP_OK;
        $newEtat = AdminCatalog::disabledEp($_POST['episod_id']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "newEtat" => $newEtat,
        ];
        reponse($response_code, $responseTab);
    }

}else {
    $response_code = HTTP_METHOD_NOT_ALLOWED;
    $responseTab = [
        "response_code" => HTTP_METHOD_NOT_ALLOWED,
        "message" => "method not allowed"
    ];
    
    reponse($response_code, $responseTab);
}

function reponse($response_code, $response){
    header('Content-Type: application/json');
    http_response_code($response_code);
    
    echo json_encode($response);
}

function cardCatalog($id_catalogue, $isActive, $nom, $like, $image_catalogue, $saison, $type){
    $urlName = str_replace(' ', '+', $nom);
    echo '<button class="like ' . ($isActive ? 'activeTrue' : 'activeFalse') .  ' ' .'likeCollor'. $id_catalogue . '" id="' . $id_catalogue . ' " onclick="like(' . $id_catalogue . ')">';
    echo '<span class="cataLike ' . $id_catalogue . ' likeId' . $id_catalogue .'" id="likeId' . $id_catalogue .'">' . $like . '</span>';
    echo '<i class="fa-solid fa-heart"></i>';
    echo '</button>';
    echo '<div class="type">'. $type .'</div>';
    echo '<div class="edite"><button onclick="edite(\'catalog\',' . $id_catalogue . ')"><i class="fa-solid fa-pencil"></i></button></div>';
    echo '<div class="addEpisode"><button onclick="addEpisode(' . $id_catalogue . ', \'catalog\')"><i class="fa-solid fa-plus"></i></button></div>';
    if (!empty($saison)) {
        echo '<div class="saison">saison ' . $saison . '</div>';
    }

    echo '<a href="catalog/' . $urlName . '">';
    echo '<img src="http://localhost/!chekerlife/views/asset/img/catalog/' . $image_catalogue . '" alt="">';
    echo '</a>';
    echo '<script type="text/javascript"> likePosition('. $id_catalogue. '); ftrSize();</script>';
    echo '</div>';
}

function cardBrouillonCatalog($id_catalogue, $isActive, $nom, $like, $image_catalogue, $saison, $type){
    $urlName = str_replace(' ', '+', $nom);
    echo '<button class="like ' . ($isActive ? 'activeTrue' : 'activeFalse') .  ' ' .'likeCollor'. $id_catalogue . '" id="' . $id_catalogue . ' " onclick="like(' . $id_catalogue . ')">';
    echo '<span class="cataLike ' . $id_catalogue . ' likeId' . $id_catalogue .'" id="likeId' . $id_catalogue .'">' . $like . '</span>';
    echo '<i class="fa-solid fa-heart"></i>';
    echo '</button>';
    echo '<div class="type">'. $type .'</div>';
    echo '<div class="edite"><button onclick="edite(\'catalog\',' . $id_catalogue . ')"><i class="fa-solid fa-pencil"></i></button></div>';
    echo '<div class="addEpisode"><button onclick="addEpisode(' . $id_catalogue . ', \'catalog\')"><i class="fa-solid fa-plus"></i></button></div>';
    if (!empty($saison)) {
        echo '<div class="saison">saison ' . $saison . '</div>';
    }

    echo '<a href="catalog/' . $urlName . '">';
    echo '<img src="http://localhost/!chekerlife/views/asset/img/catalog/' . $image_catalogue . '" alt="">';
    echo '</a>';
    echo '<script type="text/javascript"> likePosition('. $id_catalogue. '); ftrSize();</script>';
    echo '</div>';
}

function cardBrouillon($id_brouillon, $nom, $like, $image_catalogue, $saison, $type){
    $urlName = str_replace(' ', '+', $nom);
    echo '<div class="type">'. $type .'</div>';
    echo '<div class="edite"><button onclick="edite(\'brouillon\',' . $id_brouillon . ')"><i class="fa-solid fa-pencil"></i></button></div>';
    echo '<div class="addEpisode"><button onclick="addEpisode(' . $id_brouillon . ', \'brouillon\')"><i class="fa-solid fa-plus"></i></button></div>';
    if (!empty($saison)) {
        echo '<div class="saison">saison ' . $saison . '</div>';
    }

    echo '<a href="catalog/' . $urlName . '">';
    echo '<img src="http://localhost/!chekerlife/views/asset/img/catalog/' . $image_catalogue . '" alt="">';
    echo '</a>';
    echo '</div>';
}
function cardDisable($id_catalogue, $isActive, $nom, $like, $image_catalogue, $saison, $type){
    $urlName = str_replace(' ', '+', $nom);
    echo '<button class="like ' . ($isActive ? 'activeTrue' : 'activeFalse') .  ' ' .'likeCollor'. $id_catalogue . '" id="' . $id_catalogue . ' " onclick="like(' . $id_catalogue . ')">';
    echo '<span class="cataLike ' . $id_catalogue . ' likeId' . $id_catalogue .'" id="likeId' . $id_catalogue .'">' . $like . '</span>';
    echo '<i class="fa-solid fa-heart"></i>';
    echo '</button>';
    echo '<div class="type">'. $type .'</div>';
    echo '<div class="edite"><button onclick="edite(\'catalog\',' . $id_catalogue . ')"><i class="fa-solid fa-pencil"></i></button></div>';
    echo '<div class="addEpisode"><button onclick="addEpisode(' . $id_catalogue . ', \'catalog\')"><i class="fa-solid fa-plus"></i></button></div>';
    if (!empty($saison)) {
        echo '<div class="saison">saison ' . $saison . '</div>';
    }

    echo '<a href="catalog/' . $urlName . '">';
    echo '<img src="http://localhost/!chekerlife/views/asset/img/catalog/' . $image_catalogue . '" alt="">';
    echo '</a>';
    echo '<script type="text/javascript"> likePosition('. $id_catalogue. '); ftrSize();</script>';
    echo '</div>';
}


function generateCode($length = 50) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
        
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $code;
}


function catalog_parametre($parametre) {
    echo '<div class="cardNav">';
        echo '<div class="cardNavHdr">menu catalog</div>';
            echo '<div class="cardNavAuto">';
                echo '<span class="cardNavSpan"><input type="checkbox" id="allViews"'. ($parametre['allViews'] ? 'checked' : '') .'><label for="allViews">Tout afficher</label></span>';
                echo '<span class="cardNavSpan"><input type="checkbox" id="actif"'. ($parametre['actif'] || $parametre['allViews'] ? 'checked' : '') .'><label for="actif">Catalogue actif</label></span>';
                echo '<span class="cardNavSpan"><input type="checkbox" id="disable"'. ($parametre['disable'] || $parametre['allViews'] ? 'checked' : '') .'><label for="disable">Catalogue désactivé</label></span>';
                echo '<span class="cardNavSpan"><input type="checkbox" id="brouillon"'. ($parametre['brouillon'] || $parametre['allViews'] ? 'checked' : '') .'><label for="brouillon">Catalogue brouillon</label></span>';
            echo '</div>';
    echo '</div>';
}