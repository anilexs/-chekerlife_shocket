<?php
require_once "database.php";
class AdminCatalog{
    public static function catalogInfoAdmin($catalog_id){
        $db = Database::dbConnect();
        $request = $db->prepare("SELECT * FROM catalog WHERE id_catalogue = ?");

        try{
            $request->execute(array($catalog_id));
            $catalog = $request->fetch(PDO::FETCH_ASSOC);
            return $catalog;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
    
    public static function Cataloglimit($limit, $offset, $parametre) {
        $prepar = "SELECT ";
        if($parametre['allViews']){
            $prepar .= "null as id_brouillon, id_catalogue, image_catalogue, last_img, nom, description, type, saison, publish_date, add_date, likes, brouillon, catalog_actif, 'catalog' as origin FROM catalog UNION ALL SELECT id_brouillon, catalog_id, image_catalogue, last_img, nom, description, type, saison, publish_date, add_date, null, 0, 1, 'brouillon' as origin FROM catalog_brouillon ORDER BY id_catalogue, add_date LIMIT :offset, :limit";
        }else if($parametre['actif'] || $parametre['disable'] || $parametre['brouillon']){
            $prepar .= "null as id_brouillon, id_catalogue, image_catalogue, last_img, nom, description, type, saison, publish_date, add_date, likes, brouillon, catalog_actif, 'catalog' as origin FROM catalog";

            $where = " WHERE ";
            if($parametre['actif']){
                $where .= "catalog_actif=1";
            }
            if($parametre['disable']){
                if ($parametre['actif']) {
                    $where .= " OR ";
                }
                $where .= "catalog_actif=0";
            }
            if($parametre['brouillon']){
                if ($parametre['actif'] || $parametre['disable']) {
                    $where .= " OR ";
                }
                $where .= "brouillon=1";
                $prepar .= $where;
                $prepar .= " UNION ALL SELECT id_brouillon, catalog_id, image_catalogue, last_img, nom, description, type, saison, publish_date, add_date, null, 0, 1, 'brouillon' as origin FROM catalog_brouillon";
            }else{
                $prepar .= $where;
            }
            $prepar .= " ORDER BY id_catalogue, add_date LIMIT :offset, :limit";
        }

        $db = Database::dbConnect();
        $request = $db->prepare($prepar);
        
        try {
            $request->bindParam(':offset', $offset, PDO::PARAM_INT);
            $request->bindParam(':limit', $limit, PDO::PARAM_INT);
            $request->execute();
            $catalog = $request->fetchAll(PDO::FETCH_ASSOC);
            return $catalog;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public static function nbCatalog($parametre) {
        $db = Database::dbConnect();
         $prepar = "SELECT COUNT(*) FROM ( SELECT nom FROM catalog ";
        if($parametre['allViews']){
            
            $prepar .= " UNION ALL SELECT nom FROM catalog_brouillon) AS combined_table";
        }else if($parametre['actif'] || $parametre['disable'] || $parametre['brouillon']){

            $where = " WHERE ";
            if($parametre['actif']){
                $where .= "catalog_actif=1";
            }
            if($parametre['disable']){
                if ($parametre['actif']) {
                    $where .= " OR ";
                }
                $where .= "catalog_actif=0";
            }
            if($parametre['brouillon']){
                if ($parametre['actif'] || $parametre['disable']) {
                    $where .= " OR ";
                }
                $where .= "brouillon=1";
                $prepar .= $where;
                $prepar .= " UNION ALL SELECT nom FROM catalog_brouillon) AS combined_table";
            }else{
                $prepar .= $where . ") AS combined_table";
            }
        }

        $request = $db->prepare($prepar);
        
        try {
            $request->execute();
            $catalog = $request->fetch(PDO::FETCH_ASSOC);
            return $catalog;
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }

    public static function filtreCatalog($filtres, $limit, $offset, $parametre){
        $db = Database::dbConnect();

        $prepar = "SELECT ";
        if($parametre['allViews']){
            $prepar .= "null as id_brouillon, c.id_catalogue, c.image_catalogue, c.last_img, c.nom, c.description, c.type, c.saison, c.publish_date, c.add_date, c.likes, c.brouillon, c.catalog_actif, 'catalog' as origin FROM catalog c LEFT JOIN catalog_alias a ON c.id_catalogue = a.catalog_id WHERE (a.aliasName LIKE CONCAT('%', :filtres, '%') OR c.nom LIKE CONCAT('%', :filtres, '%')) UNION ALL SELECT cb.id_brouillon, cb.catalog_id, cb.image_catalogue, cb.last_img, cb.nom, cb.description, cb.type, cb.saison, cb.publish_date, cb.add_date, null, 0, 1, 'brouillon' as origin FROM catalog_brouillon cb WHERE cb.nom LIKE CONCAT('%', :filtres, '%') ORDER BY id_catalogue, add_date LIMIT :offset, :limit";
        }else if($parametre['actif'] || $parametre['disable'] || $parametre['brouillon']){
            $prepar .= "null as id_brouillon, c.id_catalogue, c.image_catalogue, c.last_img, c.nom, c.description, c.type, c.saison, c.publish_date, c.add_date, c.likes, c.brouillon, c.catalog_actif, 'catalog' as origin FROM catalog c LEFT JOIN catalog_alias a ON c.id_catalogue = a.catalog_id";
            $where = " WHERE (";
            if($parametre['actif']){
                $where .= "catalog_actif=1";
            }
            if($parametre['disable']){
                if ($parametre['actif']) {
                    $where .= " OR ";
                }
                $where .= "catalog_actif=0";
            }
            if($parametre['brouillon']){
                if ($parametre['actif'] || $parametre['disable']) {
                    $where .= " OR ";
                }
                $where .= "brouillon=1";
                $prepar .= $where . " ) AND (a.aliasName LIKE CONCAT('%', :filtres, '%') OR c.nom LIKE CONCAT('%', :filtres, '%'))";
                $prepar .= " UNION ALL SELECT cb.id_brouillon, cb.catalog_id, cb.image_catalogue, cb.last_img, cb.nom, cb.description, cb.type, cb.saison, cb.publish_date, cb.add_date, null, 0, 1, 'brouillon' as origin FROM catalog_brouillon cb WHERE cb.nom LIKE CONCAT('%', :filtres, '%')";
            }else{
                $prepar .= $where . " ) AND (a.aliasName LIKE CONCAT('%', :filtres, '%') OR c.nom LIKE CONCAT('%', :filtres, '%'))";
            }
            $prepar .= " ORDER BY id_catalogue, add_date LIMIT :offset, :limit";
        }


        $request = $db->prepare($prepar);

        $request->bindParam(':offset', $offset, PDO::PARAM_INT);
        $request->bindParam(':limit', $limit, PDO::PARAM_INT);
        $request->bindParam(':filtres', $filtres, PDO::PARAM_STR);

        try {
            $request->execute();
            $filtres = $request->fetchAll(PDO::FETCH_ASSOC);
            return $filtres;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

   
    public static function nbFiltreCatalog($filtres, $parametre){
        $db = Database::dbConnect();

        $prepar = "SELECT COUNT(*) AS nbFiltre FROM ( SELECT nom FROM catalog c LEFT JOIN catalog_alias a ON c.id_catalogue = a.catalog_id WHERE ";
        if($parametre['allViews']){
            
            $prepar .= "(a.aliasName LIKE CONCAT('%', :filtres, '%') OR c.nom LIKE CONCAT('%', :filtres, '%')) UNION ALL SELECT nom FROM catalog_brouillon cb WHERE cb.nom LIKE CONCAT('%', :filtres, '%')) AS combined_table";
        }else if($parametre['actif'] || $parametre['disable'] || $parametre['brouillon']){

            $where = " AND ";
            if($parametre['actif']){
                $where .= "catalog_actif=1";
            }
            if($parametre['disable']){
                if ($parametre['actif']) {
                    $where .= " OR ";
                }
                $where .= "catalog_actif=0";
            }
            if($parametre['brouillon']){
                if ($parametre['actif'] || $parametre['disable']) {
                    $where .= " OR ";
                }
                $where .= "brouillon=1";
                $prepar .= "((a.aliasName LIKE CONCAT('%', :filtres, '%') OR c.nom LIKE CONCAT('%', :filtres, '%'))) " . $where . "  UNION ALL SELECT nom FROM catalog_brouillon cb WHERE cb.nom LIKE CONCAT('%', :filtres, '%')) AS combined_table";
            }else{
                $prepar .= "((a.aliasName LIKE CONCAT('%', :filtres, '%') OR c.nom LIKE CONCAT('%', :filtres, '%')) " . $where . " ) ) AS combined_table;";
            }
        }

        $request = $db->prepare($prepar);

        try{
            $request->bindParam(':filtres', $filtres, PDO::PARAM_STR);
            $request->execute();
            $filtres = $request->fetch(PDO::FETCH_ASSOC);
            return $filtres;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
    public static function catalogInfo($catalog_id){
        $db = Database::dbConnect();
        $request = $db->prepare("SELECT * FROM catalog WHERE id_catalogue = ?");

        try{
            $request->execute(array($catalog_id));
            $catalog = $request->fetch(PDO::FETCH_ASSOC);
            return $catalog;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
    
    public static function brouilloninfo($brouillon_id){
        $db = Database::dbConnect();
        $request = $db->prepare("SELECT * FROM catalog_brouillon WHERE id_brouillon = ?");

        try{
            $request->execute(array($brouillon_id));
            $catalog = $request->fetch(PDO::FETCH_ASSOC);
            return $catalog;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
    
    public static function episodeAll($catalog_id){
        $db = Database::dbConnect();
        $request = $db->prepare("SELECT * FROM (SELECT null as id_episode_brouillon, id_episode, catalog_id, nb_episode, title, dure, description, publish_date, add_date, brouillon, episod_actif, 'catalog' AS origin FROM episode UNION ALL SELECT id_episode_brouillon, null, catalog_id, nb_episode, title, dure, description, publish_date, add_date, null, null, 'brouillon' AS origin FROM episode_brouillon) AS combined_episodes WHERE catalog_id = ? ORDER BY nb_episode, add_date");

        try{
            $request->execute(array($catalog_id));
            $catalog = $request->fetchAll(PDO::FETCH_ASSOC);
            return $catalog;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
    
    public static function disabledEp($episode_id){
        $db = Database::dbConnect();
        $request = $db->prepare("SELECT episod_actif FROM `episode` WHERE id_episode = ?");
        $update = $db->prepare("UPDATE episode SET episod_actif = ? WHERE id_episode = ?");
        
        try{
            $request->execute(array($episode_id));
            $episod = $request->fetch(PDO::FETCH_ASSOC);
            $newEtat = ($episod['episod_actif'] == 1) ? 0 : 1; 
            $update->execute(array($newEtat, $episode_id));
            return $newEtat;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
}
