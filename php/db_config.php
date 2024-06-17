<?php

$host = 'localhost'; 
$dbname = 'drone';
$user = 'postgres';
$password = 'postgre';

try {
    $db = new PDO("pgsql:host=$host; dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage() ."<br>";
}

?>
