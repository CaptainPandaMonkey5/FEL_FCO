<?php



    try{
        $pdo = new PDO ("mysql:host=localhost;dbname=ajcbikeshop_db","root",""); 
        $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        echo "Connection failed " . $e->getMessage();
    }