<?php 
  
  $server ="localhost";
  $database="school_db";
  $username ="root";
  $password = "";

try{
  $pdo = new PDO("mysql:host=$server;dbname=$database;",$username,$password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Database connected successfully";
}catch(PDOException $e){
    die("Unable to connect to database".$e->getMessage());
}
?>