<?php
$dsn = "mysql:host=localhost;dbname=rotmg_equipments";
$username = "read_write";
$password = "D.ojsQeFn9VU1DhC";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $err){
    echo "Connection failed: " . $err->getMessage();
}