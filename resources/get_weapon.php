<?php
include "db_connect.php";

$id = $_POST["weaponID"];

// $test = array("monTruc" => "La Valeur de mon truc");
// $test["trucNumero2"] = "La c'est le 2e truc";

$weaponQuery = "SELECT * FROM weapons WHERE id = $id";
$shotsQuery = "SELECT * FROM shots WHERE weaponID = $id";
$weaponResult = mysqli_query($connection, $weaponQuery);
$shotsResult = mysqli_query($connection, $shotsQuery);

if (mysqli_num_rows($weaponResult) > 0) {
    $weapon = mysqli_fetch_assoc($weaponResult);
    $shots = array();
    while ($row = mysqli_fetch_assoc($shotsResult)){
        array_push($shots, $row);
    }
    $weapon["shots"] = $shots;
    echo json_encode($weapon);
} else {
    echo "No weapon found";
}