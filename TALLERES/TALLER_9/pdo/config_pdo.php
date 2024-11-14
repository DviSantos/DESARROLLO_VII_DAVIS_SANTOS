<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','admin');
define('DB_NAME','taller9_db');

try{
    $pdo= new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die("ERROR: no se pudo conectar. ".$e->getMessage());
}
?>