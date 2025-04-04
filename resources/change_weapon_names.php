<?php
include "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST["weaponType"];

    $sql = "SELECT id, name FROM weapons WHERE type='$type' ORDER BY tier, name";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)){
            echo '<option value="' . $row["id"] . '">' . $row["name"] .'</option>';
        }
    } else {
        echo "<option>No weapon found</option>";
    }
}
else {
    header("location: ../index.php");
}