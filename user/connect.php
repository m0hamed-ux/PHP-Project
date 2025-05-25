<?php
    try{
        $connexion=new PDO("mysql:host=localhost;dbname=forsa_store;port=3306","root","");
    }
    catch(Exception $e){
        echo $e;
    }
?>