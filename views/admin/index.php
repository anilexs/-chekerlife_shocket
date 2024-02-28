<?php
    require_once "../../model/userModel.php";
    require_once "../../model/catalogModel.php";
    require_once "../../model/collectionModel.php";

    require_once "../inc/header.php";
    
    if ($userInfo['role'] != "admin" && $userInfo['role'] != "owner") {
        header("Location:" . $host);
        die;
    }

?>  
<link rel="stylesheet" href="<?= $host ?>asset/css/dashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
<script src="<?= $host ?>asset/js/dashboard.js"></script>

<title>dashboard</title>
<?php require_once "../inc/nav.php"; ?>
<div class="hdrcontenaire">
    <div class="state">
        
    </div>
    <div class="graphique">
        <div class="hdrGraf">
            <h1 id="grafTXT"></h1>
            <div class="grafController">
                <button id="nbDaymoins24h">-1 jour</button>
                <button id="nbDayplus24h">+1 jour</button>
            </div>
        </div>
        <canvas id="myGraf"></canvas>
        <div class="controllerCanvar">
            <button id="nombre_conte_total">Nombre d'utilisateurs créés au total</button>
            <button id="inscriptions_journalières">Nombre de comptes créés les 24 dernier heur</button>
        </div>
    </div>
</div>
<?php require_once "../inc/footer.php"; ?>
