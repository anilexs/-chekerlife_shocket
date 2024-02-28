<?php
require_once "database.php";
class Collection{
    public static function collection($id_catalogue){
        $db = Database::dbConnect();
        $request = $db->prepare("SELECT * FROM catalog_collections WHERE collections_name = (SELECT collections_name FROM catalog_collections WHERE catalog_id = ?)");

        try{
            $request->execute(array($id_catalogue));
            $collections = $request->fetchAll(PDO::FETCH_ASSOC);
            return $collections;
        }catch(PDOException $e){
            $e->getMessage();
        }
    }
}