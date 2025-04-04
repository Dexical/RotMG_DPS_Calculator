<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $type = $_POST["weaponType"];
    $name = $_POST["weaponName"];
    $tier = $_POST["weaponTier"];
    $burstCount = $_POST["burstCount"];
    $minDelay = $_POST["minDelay"];
    $maxDelay = $_POST["maxDelay"];
    $hp = $_POST["hp"];
    $mp = $_POST["mp"];
    $att = $_POST["att"];
    $def = $_POST["def"];
    $spd = $_POST["spd"];
    $dex = $_POST["dex"];
    $vit = $_POST["vit"];
    $wis = $_POST["wis"];
    
    $shots = $_POST["shots"];
    $shotCount = array();
    $minDmg = array();
    $maxDmg = array();
    $rof = array();
    $pierce = array();

    for ($i=0; $i < $shots; $i++) { 
        $shotCountStr = "shotCount" . $i;
        $minDmgStr = "minDmg" . $i;
        $maxDmgStr = "maxDmg" . $i;
        $rofStr = "rof" . $i;
        $pierceStr = "pierce" . $i;

        array_push($shotCount, $_POST[$shotCountStr]);
        array_push($minDmg, $_POST[$minDmgStr]);
        array_push($maxDmg, $_POST[$maxDmgStr]);
        array_push($rof, $_POST[$rofStr]);

        if (array_key_exists($pierceStr, $_POST)){
            array_push($pierce, 1);
        } else {
            array_push($pierce, 0);
        }
    }

    
    //$weaponQuery = "INSERT INTO weapons(type, name, tier, burstCount, minDelay, maxDelay, hp, mp, att, def, spd, dex, vit, wis)
    // VALUES ($_POST['weaponType'], $_POST['weaponName'], $_POST['weaponTier'], $_POST['burstCount'], $_POST['minDelay'], $_POST['maxDelay'], $_POST['hp'], $_POST['mp'], $_POST['att'], $_POST['def'], $_POST['spd'], $_POST['dex'], $_POST['vit'], $_POST['wis'])";
    
    

    
    try {
        //Connect to the database
        require_once "db_rw_connect.php";

        //Prepares then executes query to insert weapon data
        $weaponQuery = "INSERT INTO weapons(type, name, tier, burstCount, minDelay, maxDelay, hp, mp, att, def, spd, dex, vit, wis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $statement = $pdo->prepare($weaponQuery);
        $statement->execute([$type, $name, $tier, $burstCount, $minDelay, $maxDelay, $hp, $mp, $att, $def, $spd, $dex, $vit, $wis]);
        $statement = null;
        
        //Prepares then executes query to get weapon id
        $weaponQuery = "SELECT id FROM weapons WHERE name=?;";
        $statement = $pdo->prepare($weaponQuery);
        $statement->execute([$name]);

        //Fetch the id in $result
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement = null;
        
        //Creates query to insert shots data
        $shotsQuery = "INSERT INTO shots(weaponID, shotCount, minDmg, maxDmg, rateOfFire, pierce) VALUES (?, ?, ?, ?, ?, ?);";

        //Checks if we have a result
        if (empty($result)) {
            echo "No Data";
        }
        else {
            //Prepares then executes query to insert shots data
            for ($i=0; $i < $shots; $i++) { 
                $statement = $pdo->prepare($shotsQuery);
                $statement->execute([$result["id"], $shotCount[$i], $minDmg[$i], $maxDmg[$i], $rof[$i], $pierce[$i]]);
                $statement = null;
            }
        }

        $pdo = null;
    } catch (PDOException $err) {
        die("Connection failed: " . $err->getMessage());
    }
    
    header("location:../pages/insert_form.php");
}
else{
    header("location:../pages/insert_form.php");
}