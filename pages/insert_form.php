<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../resources/style.css" />
    <link rel="icon" type="image/x-icon" href="../resources/favicon.png">
    <title>Weapon Creator</title>
</head>
<body class="bg-skuld">
    <form action="../resources/insert_weapon.php" method="post" class="form center">
        <fieldset>
            <legend>General</legend>
            <label for="weaponType">Weapon Type</label>
            <select name="weaponType" id="weaponType">
                <option value="bow">Bow/Longbow</option>
                <option value="dagger">Dagger/Blade</option>
                <option value="katana">Katana/Tachi</option>
                <option value="staff">Staff/Spellblade</option>
                <option value="sword">Sword/Flail</option>
                <option value="wand">Wand/Morning Star</option>
            </select>
            <br>
            <label for="weaponName">Weapon Name</label>
            <input required type="text" id="weaponName" name="weaponName">
            <br>
            <label for="weaponTier">Weapon Tier</label>
            <select required name="weaponTier" id="weaponTier">
                <option value="0">T0</option>
                <option value="1">T1</option>
                <option value="2">T2</option>
                <option value="3">T3</option>
                <option value="4">T4</option>
                <option value="5">T5</option>
                <option value="6">T6</option>
                <option value="7">T7</option>
                <option value="8">T8</option>
                <option value="9">T9</option>
                <option value="10">T10</option>
                <option value="11">T11</option>
                <option value="12">T12</option>
                <option value="13">T13</option>
                <option value="14">T14</option>
                <option value="31">UT</option>
                <option value="32">ST</option>
            </select>
        </fieldset>
        <fieldset>
            <legend>Burst</legend>
            <label for="burstCount">Burst Count</label>
            <input type="number" id="burstCount" name="burstCount" value="0">
            <br>
            <label for="minDelay">Min Delay</label>
            <input type="number" step="0.01" id="minDelay" name="minDelay">
            <br>
            <label for="maxDelay">Max Delay</label>
            <input type="number" step="0.01" id="maxDelay" name="maxDelay">
        </fieldset>
        <fieldset>
            <legend>Stats</legend>
            <label for="hp">HP</label>
            <input type="number" id="hp" class="stat-input" name="hp" value="0">
            <label for="¨mp">MP</label>
            <input type="number" id="¨mp" class="stat-input" name="mp" value="0">
            <label for="att">Att</label>
            <input type="number" id="att" class="stat-input" name="att" value="0">
            <label for="def">Def</label>
            <input type="number" id="def" class="stat-input" name="def" value="0">
            <br>
            <label for="spd">Spd</label>
            <input type="number" id="spd" class="stat-input" name="spd" value="0">
            <label for="dex">Dex</label>
            <input type="number" id="dex" class="stat-input" name="dex" value="0">
            <label for="vit">Vit</label>
            <input type="number" id="vit" class="stat-input" name="vit" value="0">
            <label for="wis">Wis</label>
            <input type="number" id="wis" class="stat-input" name="wis" value="0">
        </fieldset>
    <!-- weapon : type name tier (burst-count) (min-delay) (max-delay) (stats)-->
        <fieldset id="shotsField">
            <legend>Shots</legend>
            <fieldset>
                <legend>Shot 1</legend>
                <label for="shotCount0">Shot Count</label>
                <input required type="number" id="shotCount0" name="shotCount0">
                <br>
                <label for="minDmg0">Min Dmg</label>
                <input required type="number" id="minDmg0" name="minDmg0">
                <br>
                <label for="maxDmg0">Max Dmg</label>
                <input required type="number" id="maxDmg0" name="maxDmg0">
                <br>
                <label for="rof0">Rate of Fire</label>
                <input type="number" id="rof0" step="0.01" name="rof0"  value="1">
                <br>
                <label for="pierce">Armor Pierce</label>
                <input type="checkbox" id="pierce0" name="pierce0">
            </fieldset>
        </fieldset>
    <!-- shots : shot-count min-dmg max-dmg (rof) (pierce)-->
        <button type="submit" value="submit">Submit</button>
        <input style="visibility:hidden" value="1" name="shots" id="shots">
    </form>
    <div class="center-btn">
        <button type="button" id="addShot">Add Shot</button>
    </div>

    <script>
        document.getElementById("addShot").addEventListener("click", addShotForm);
        var shotCount = document.getElementById("shots");
        var shotsField = document.getElementById("shotsField");

        function addShotForm(){
            let scv = Number(shotCount.value);
            let temp = elementFromHtml(`<fieldset>
                <legend>Shot ${scv+1}</legend>
                <label for="shotCount${scv}">Shot Count</label>
                <input required type="number" id="shotCount${scv}" name="shotCount${scv}">
                <br>
                <label for="minDmg${scv}">Min Dmg</label>
                <input required type="number" id="minDmg${scv}" name="minDmg${scv}">
                <br>
                <label for="maxDmg${scv}">Max Dmg</label>
                <input required type="number" id="maxDmg${scv}" name="maxDmg${scv}">
                <br>
                <label for="rof${scv}">Rate of Fire</label>
                <input type="number" id="rof${scv}" step="0.01" name="rof${scv}"  value="1">
                <br>
                <label for="pierce">Armor Pierce</label>
                <input type="checkbox" id="pierce${scv}" name="pierce${scv}">
            </fieldset>`);
            
            shotsField.appendChild(temp);

            scv++;
            shotCount.value = scv;
        }

        function elementFromHtml(html){
            const template = document.createElement("template");

            template.innerHTML = html.trim();

            return template.content.firstElementChild;
        }
    </script>
</body>
</html>