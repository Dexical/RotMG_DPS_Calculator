<?php
    include "resources/db_connect.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="resources/style.css" />
    <link rel="icon" type="image/x-icon" href="resources/favicon.png">
    <title>DPS Calc</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gradient">
    <div id="test"></div>
    <div class="form">
        <div>
            Att : <input type="number" id="attack">
            Dex : <input type="number" id="dexterity">
        </div>
        <div>
            <select id="weaponType">
                <option value="bow">Bow/Longbow</option>
                <option value="dagger">Dagger/Blade</option>
                <option value="katana">Katana/Tachi</option>
                <option value="staff">Staff/Spellblade</option>
                <option value="sword">Sword/Flail</option>
                <option value="wand">Wand/Morning Star</option>
            </select>
            <select id="weaponName">
                <?php
                    $sql = "SELECT id, name FROM weapons WHERE type='bow' ORDER BY tier, name";
                    $result = mysqli_query($connection, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)){
                            echo '<option value="' . $row["id"] . '">' . $row["name"] .'</option>';
                        }
                    } else {
                        echo "<option>No bow found</option>";
                    }
                ?>
            </select>
        </div>
        <button id="submitStats">Submit</button>
    </div>
    <div class="graph">
        <canvas id="dpsGraph" aria-label="chart" role="img"></canvas>
    </div>
    
    <script src="resources/script.js"></script>
</body>
</html>