<?php
    require_once("db_login.php");

    if (!$_SESSION["usertype"] != 1) {
        header("Location: home.php");
    } 
    if (!isset($_SESSION["usertype"])) {
        header("Location: index.php");
    } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voedselbank Maaskantje Klanten</title>
</head>
<body>

<?php

echo "Voedselbank Maaskantje Klanten";
?>
<br>
<a href='home.php'> Home </a>
<br>
<a href='Voedselpakket.php'> Voedselpakket </a>
<br>
<a href='Medewerker.php'> Medewerkers </a>
<br>
<a href='Voorraad.php'> Voorraad </a>
<br>
<a href='Leverancier.php'> Leveranciers </a>
<br>
<a href='index.php'> Uitloggen </a>

<form method="post" class="flexbox">
        <table>
            <tr>                
                <th>Klant ID</th>
                <th>Naam</th>
                <th>Adres</th>
                <th>Telefoonnummer</th>
                <th>Emailadres</th>
                <th>Aantal volwassenen</th>
                <th>Aantal kinderen</th>
                <th>Aantal baby's</th>
                <th>Wensen</th>            
            </tr>
                <?php 
                    $prevKlant = null;
                    $sql = "SELECT * FROM klant ORDER BY idklant";
                    $result = $conn->query($sql);
                    
                    if ($result) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { 
                            if ($row["idklant"] != $prevKlant) {
                                echo "<tr>";
                                echo "<td>" . $row["idklant"] . "</td>";
                                echo "<td>" . $row["naam"] . "</td>"; 
                                echo "<td>" . $row["adres"] . "</td>"; 
                                echo "<td>" . $row["telefoonnummer"] . "</td>";
                                echo "<td>" . $row["email"] . "</td>";
                                echo "<td>" . $row["aantalvolwassen"] . "</td>";
                                echo "<td>" . $row["aantalkind"] . "</td>";
                                echo "<td>" . $row["aantalbaby"] . "</td>";
                                echo "<td>" . $row["wensen"] . "</td>";
                                echo "</tr>";
                            }
                            $prevKlant = $row["idklant"];
                        }
                    }
                ?>
        </table>
    </form>
</body>
</html>