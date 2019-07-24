<?php
require_once __DIR__ . '/../config.php';

class DbConnection{
    public function dbConn(){
        // $db = mysqli_connect("localhost", "root","qwnmTANK2006#", "profile") or die("FAILED");
        try{
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=". DB_NAME, DB_USER, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;

        }catch(PDOException $e) {
            die("Error: Connection failed!! " . $e->message());
        }
    }
    
}
    