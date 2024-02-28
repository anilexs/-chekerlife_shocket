<?php
session_start();
require_once "../model/database.php";
require_once "../model/userModel.php";
require_once "../model/catalogModel.php";

const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400; 
const HTTP_METHOD_NOT_ALLOWED = 405; 

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST'){
    $response_code = HTTP_BAD_REQUEST;
    $message = "il manque le paramétre ACTION";

    if($_POST['action'] == "catalog"){
        $catalog = Catalog::Cataloglimit($_POST['limit'], $_POST['offset']);
        $nbCatalog = Catalog::nbCatalog();
        $nbCatalog = $nbCatalog['COUNT(*)'];
        if(isset($_COOKIE['token'])){
            $userLike = User::userLike($_COOKIE['token']);
        }
        foreach ($catalog as $catalogItem) {
        echo '<div class="card">';

        $isActive = false;
        if (isset($_COOKIE['token'])) {
            foreach ($userLike as $like) {
                if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                    $isActive = true;
                    break;
                }
            }
        }
        card($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
    }
    echo '<script type="text/javascript">pagination('. $nbCatalog .');</script>';

    }else if($_POST['action'] == "filtre"){

        $catalog = Catalog::filtreCatalog($_POST['filtre'], $_POST['limit'], $_POST['offset']);

        $nbCatalog = Catalog::nbFiltreCatalog($_POST['filtre']);
        $nbCatalog = $nbCatalog['nbFiltre'];
        if(isset($_COOKIE['token'])){
            $userLike = User::userLike($_COOKIE['token']);
        }
        foreach ($catalog as $catalogItem) {
            echo '<div class="card">';

            $isActive = false;
            if (isset($_COOKIE['token'])) {
                foreach ($userLike as $like) {
                    if ($like['catalog_id'] == $catalogItem["id_catalogue"] && $like['like_active'] == 1) {
                        $isActive = true;
                        break;
                    }
                }
            }
            card($catalogItem["id_catalogue"], $isActive, $catalogItem["nom"], $catalogItem['likes'], $catalogItem["image_catalogue"], $catalogItem['saison'], $catalogItem['type']);
        }
        echo '<script type="text/javascript">pagination('. $nbCatalog .');</script>';

    }else if($_POST['action'] == "pagination"){

        $nbFiltre = $_POST['nbElement'];
        $elementsParPage = 81;
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
                    echo '<a href="?page=' . ($page - 1) . '"><i class="fa-solid fa-chevron-up fa-rotate-270"></i></a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=' . ($page - 1) . '"><i class="fa-solid fa-chevron-up fa-rotate-270"></i></a>';
                }
            } else {
                echo '<i class="fa-solid fa-chevron-up fa-rotate-270"></i>';
            }
        
            $start = max(1, $page - 3);
            $end = min($nbPages, $start + 6);
        
            if ($page > 4) {
                if($filtre == null){
                    echo '<a href="?page=1">1</a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=1">1</a>';
                }
            }
        
            for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                    if($filtre == null){
                        echo '<span><a href="?page=' . $i . '" class="current">' . $i . '</a></span>';
                    }else{
                        echo '<span><a href="?titre='.$filtre.'&page=' . $i . '" class="current">' . $i . '</a></span>';
                    }
                } else {
                    if($filtre == null){
                        echo '<a href="?page=' . $i . '">' . $i . '</a>';
                    }else{
                        echo '<a href="?titre='.$filtre.'&page=' . $i . '">' . $i . '</a>';
                    }
                }
            }
        
            if ($nbPages - $page > 3 && $nbPages > 7) {
                if($filtre == null){
                    echo '<a href="?page=' . $nbPages . '">' . $nbPages . '</a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=' . $nbPages . '">' . $nbPages . '</a>';
                }
            }
        
            if ($page < $nbPages) {
                if($filtre == null){
                    echo '<a href="?page=' . ($page + 1) . '"><i class="fa-solid fa-chevron-up fa-rotate-90"></i></a>';
                }else{
                    echo '<a href="?titre='.$filtre.'&page=' . ($page + 1) . '"><i class="fa-solid fa-chevron-up fa-rotate-90"></i></a>';
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
    }else if($_POST['action'] == "lastAdd"){
        $response_code = HTTP_OK;
        $lastAdd = Catalog::lastAdd();
        $responseTab = [
            "response_code" => HTTP_OK,
            "lastAdd" => $lastAdd,
        ];
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "cataloginfo"){
        $response_code = HTTP_OK;
        $catalogInfo = Catalog::catalogInfo($_POST['catalog_id']);
        $type = Catalog::catalogType();
        $allType = Catalog::catalogAllType($_POST['catalog_id']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "cataloginfo" => $catalogInfo,
            "type" => $type,
            "allType" => $allType,
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

function card($id_catalogue, $isActive, $nom, $like, $image_catalogue, $saison, $type){
    $urlName = str_replace(' ', '+', $nom);
    echo '<button class="like ' . ($isActive ? 'activeTrue' : 'activeFalse') .  ' ' .'likeCollor'. $id_catalogue . '" id="' . $id_catalogue . ' " onclick="like(' . $id_catalogue . ')">';
    echo '<span class="cataLike ' . $id_catalogue . ' likeId' . $id_catalogue .'" id="likeId' . $id_catalogue .'">' . $like . '</span>';
    echo '<i class="fa-solid fa-heart"></i>';
    echo '</button>';
    echo '<div class="type">'. $type .'</div>';
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