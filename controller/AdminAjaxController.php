<?php
session_start();
require_once "../model/database.php";
require_once "../model/userModel.php";
require_once "../model/adminModel.php";
require_once "../model/catalogModel.php";

const HTTP_OK = 200;
const HTTP_BAD_REQUEST = 400; 
const HTTP_METHOD_NOT_ALLOWED = 405; 

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtoupper($_SERVER['HTTP_X_REQUESTED_WITH']) == 'XMLHTTPREQUEST'){
    $response_code = HTTP_BAD_REQUEST;
    $message = "il manque le paramétre ACTION";

    if($_POST['action'] == "nombre_dutilisateurs_day"){
        $response_code = HTTP_OK;
        $createsUserDay = Admin::nombre_comptes_créés_last_24h($_POST['date']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "createsUserDay" => $createsUserDay,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "nombre_dutilisateurs_total"){
        $response_code = HTTP_OK;
        $userConte_total = Admin::nombre_dutilisateurs_total();
        $responseTab = [
            "response_code" => HTTP_OK,
            "userConte" => $userConte_total,
        ];
        reponse($response_code, $responseTab); 
    }else if($_POST['action'] == "nombre_conte_jour"){
        $response_code = HTTP_OK;
        $nombre_conte_jour = Admin::nombre_conte_jour($_POST['date']);
        $responseTab = [
            "response_code" => HTTP_OK,
            "nombre_conte_jour" => $nombre_conte_jour,
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


