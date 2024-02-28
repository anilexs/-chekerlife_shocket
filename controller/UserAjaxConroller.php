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

    if($_POST['action'] == "deconnexion"){
        User::deconnexion();
        
        $response_code = HTTP_OK;
        $responseTab = [
            "response_code" => HTTP_OK,
        ];
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "like" && isset($_POST['catalog_id'])){
        $response_code = HTTP_OK;
        $catalog_id = $_POST['catalog_id'];
        
        if(empty($_COOKIE['token'])){
                $responseTab = [
                    "connect" => false
                ];
        }else{
            $actif = User::user_actif($_COOKIE['token']);
            if($actif['user_actif'] == 1 && $actif['token_active'] == 1){
                $bool = User::like($_COOKIE['token'], $catalog_id);
                $nblike = User::likeCount($_COOKIE['token']);
                $CatalogInfo = Catalog::catalogInfo($_POST['catalog_id']);
                $response_code = HTTP_OK;
                $message = $bool;
                $responseTab = [
                    "response_code" => HTTP_OK,
                    "message" => $message,
                    "nbLike" => $nblike['COUNT(*)'],
                    "CatalogInfo" => $CatalogInfo,
                    "actif" => $actif
                ];
            }else{
                $logout = User::deconnexion();
                $responseTab = [
                    "response_code" => HTTP_OK,
                    "actif" => $actif
                ];
            }
        }
        reponse($response_code, $responseTab);
        
    }else if($_POST['action'] == "views" && isset($_POST['chekboxId'])){
        if(isset($_COOKIE['token'])){
            $response_code = HTTP_OK;
            $actif = User::user_actif($_COOKIE['token']);
            if($actif['user_actif'] == 1 && $actif['token_active'] == 1){
                $catalog = Catalog::episodeInfo($_POST['chekboxId']); 
                $episodeViews = User::episodeUserViews($_COOKIE['token'], $_POST['chekboxId'], $catalog['catalog_id']);
                $nbEpisodeUserViewsActife = User::nbEpisodeUserViewsActife($_COOKIE['token'], $catalog['catalog_id']);
                $responseTab = [
                            "response_code" => HTTP_OK,
                            "connecter" => true,
                            "nbEpisodeUserViewsActife" => $nbEpisodeUserViewsActife['COUNT(*)'],
                            "actif" => $actif,
                        ];
            }else{
                User::deconnexion();
                $responseTab = [
                            "response_code" => HTTP_OK,
                            "connecter" => false,
                        ];
            }
            
        }else{
            $response_code = HTTP_OK;
            $responseTab = [
                            "response_code" => HTTP_OK,
                            "connecter" => false,
                        ];
            }
        reponse($response_code, $responseTab);
    }else if($_POST['action'] == "userXpViews"){
        $userXPProfil = User::userXPProfil($_COOKIE['token']);
        $response_code = HTTP_OK;
        $responseTab = [
            "response_code" => HTTP_OK,
            "xp_actuelle" => $userXPProfil['xp_actuelle'],
            "xp_requis" => 1200,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "returnFriend"){
        $response_code = HTTP_OK;
        $friend = User::returnFriend($_COOKIE['token'], $_POST['pseudo']);
        returnFriend($friend);
    }else if($_POST['action'] == "userOnligne"){
        $response_code = HTTP_OK;
        User::onligne($_COOKIE['token']);
        $responseTab = [
            "response_code" => HTTP_OK,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "allFriend"){
        $response_code = HTTP_OK;
        $friend = User::friend($_COOKIE['token']);
        friendCard($friend);
    }else if($_POST['action'] == "removeFriend"){
        $response_code = HTTP_OK;
        User::removeFriend($_COOKIE['token'], $_POST['pseudo']);
         $responseTab = [
            "response_code" => HTTP_OK,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "requetteFriend"){
        $response_code = HTTP_OK;
        $friend = User::requetteFriend($_COOKIE['token']);
        friendRequette($friend); 
    }else if($_POST['action'] == "addFriend"){
        $response_code = HTTP_OK;
        $friend = User::addFriend($_COOKIE['token'], $_POST['pseudo']);
         $responseTab = [
            "response_code" => HTTP_OK,
            'friend' => $friend,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "friendStatue"){
        $response_code = HTTP_OK;
        User::friendStatue($_POST['update'], $_POST['pseudo'], $_COOKIE['token']);
         $responseTab = [
            "response_code" => HTTP_OK,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "blockFriend"){
        $response_code = HTTP_OK;
        $statue = User::blockFriend($_COOKIE['token'], $_POST['pseudo']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "statue" => $statue,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "friendBloque"){
        $response_code = HTTP_OK;
        $bloque = User::friendBloque($_COOKIE['token']);
        friendBloque($bloque); 
    }else if($_POST['action'] == "unblockedFriend"){
        $response_code = HTTP_OK;
        $bloque = User::unblockedFriend($_COOKIE['token'], $_POST['pseudo']);
        $responseTab = [
            "response_code" => HTTP_OK,
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

function returnFriend($friend){   
    foreach ($friend as $friend) {
        echo '<div class="friendCard">';
        echo '<div class="friendImgContenair">';
        if(!empty($friend['cadre'])){
                    $cadreName = explode(".", $friend['cadre']);
                    echo  '<img src="views/asset/img/user/cadre/'.$friend['cadre'].'" alt="profil img" class="'.$cadreName[0].'">';
                }
                echo '<img src="views/asset/img/user/profile/'.$friend['profil'].'" alt="profil img" class="friendImg">';
            echo '</div>';
            echo '<div class="friendController">';

                echo '<div class="unblockedFriend"><button class="'. $friend['pseudo'] .'" id="addFriendBtn"><i class="fa-solid fa-check"></i></button></div>';
                echo '<div class="messageFriend"><button class="'. $friend['pseudo'] .'" id="messageFriend"><i class="fa-solid fa-message"></i></button></div>';
            
            echo '</div>';
        echo '</div>';
    }
}

function friendCard($friend){   
    foreach ($friend as $friend) {
        echo '<div class="friendCard">';
        echo '<div class="friendImgContenair">';
        if(!empty($friend['cadre_image'])){
                $cadreName = explode(".", $friend['cadre_image']);
                echo '<img src="views/asset/img/user/cadre/'.$friend['cadre_image'].'" alt="profil img" class="'.$cadreName[0].'">';
            }
                echo '<img src="views/asset/img/user/profile/'.$friend['user_image'].'" alt="profil img" class="friendImg">';
            echo '</div>';

            echo '<div class="friendController">';
                echo '<div class="removeFriend"><button class="'. $friend['pseudo'] .'" id="removeFriend"><i class="fa-solid fa-x"></i></button></div>';
                echo '<div class="blockFriend"><button class="'. $friend['pseudo'] .'" id="blockFriend"><i class="fa-solid fa-shield"></i></button></div>';
                echo '<div class="messageFriend"><a href="messagerie/'. $friend['pseudo'] .'"><i class="fa-solid fa-message"></i></a></div>';
            echo '</div>';
            
        echo '</div>';
    }
}

function friendRequette($friend){   
    foreach ($friend as $friend) {
        echo '<div class="friendCard">';
        echo '<div class="friendImgContenair">';
        if(!empty($friend['cadre_image'])){
                $cadreName = explode(".", $friend['cadre_image']);
                echo '<img src="views/asset/img/user/cadre/'.$friend['cadre_image'].'" alt="profil img" class="'.$cadreName[0].'">';
            }
                echo '<img src="views/asset/img/user/profile/'.$friend['user_image'].'" alt="profil img" class="friendImg">';
            echo '</div>';
            echo '<div class="friendController">';

            echo '<div class="Friendtrue"><button class="'. $friend['pseudo'] .'" id="Friendtrue"><i class="fa-solid fa-check"></i></button></div>';
            echo '<div class="FriendFalse"><button class="'. $friend['pseudo'] .'" id="FriendFalse"><i class="fa-solid fa-x"></i></button></div>';
            echo '<div class="blockFriend"><button class="'. $friend['pseudo'] .'" id="blockFriend"><i class="fa-solid fa-shield"></i></button></div>';
            echo '<div class="messageFriend"><a href="messagerie/'. $friend['pseudo'] .'"><i class="fa-solid fa-message"></i></a></div>';

            echo '</div>';
        echo '</div>';
    }
}

function friendBloque($friend){   
    foreach ($friend as $friend) {
        echo '<div class="friendCard">';
        echo '<div class="friendImgContenair">';
        if(!empty($friend['cadre_image'])){
                    $cadreName = explode(".", $friend['cadre_image']);
                    echo  '<img src="views/asset/img/user/cadre/'.$friend['cadre_image'].'" alt="profil img" class="'.$cadreName[0].'">';
                }
                echo '<img src="views/asset/img/user/profile/'.$friend['user_image'].'" alt="profil img" class="friendImg">';
            echo '</div>';
            echo '<div class="friendController">';

                echo '<div class="unblockedFriend"><button class="'. $friend['pseudo'] .'" id="unblockedFriend"><i class="fa-solid fa-x"></i></button></div>';
                echo '<div class="messageFriend"><a href="messagerie/'. $friend['pseudo'] .'"><i class="fa-solid fa-message"></i></a></div>';

            echo '</div>';
        echo '</div>';
    }
}
?>