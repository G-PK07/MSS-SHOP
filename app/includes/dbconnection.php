<?php
// if(session_start() == PHP_SESSION_NONE){
//     session_start();
// }
 
//DB credentials.
define('DB_HOST','mysql-database');
define('DB_USER','root');
define('DB_PASS','');
define('DB_NAME','shop_project');
 
try{
    $dbh= new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
 
}
catch (PDOException $e){
    exit("Error: ".$e->getMessage());
}
?>